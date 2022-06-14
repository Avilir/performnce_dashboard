<?php

require_once 'main.php';
require_once 'config.php';

use Elasticsearch\ClientBuilder ;

function read_rt($test, $op)
{
    /*
        Read all response time results from elastic search server

        Args:
            $test (dict) : the test results array - to retrieve the UUID from
            $op (str): the test operation to read data about.

        Returns:
            int : the average of all 99 percentile results in micro seconds
    */

    global $client ;

    $results = [] ;

    $params = [
        'index' => 'ripsaw-smallfile-rsptimes',
        'type' => '_doc',
        'body' => [
            'query' => [
                'bool' => [
                    'must' => [
                        ['match' => ['uuid' => '"' . $test['uuid'] . '"']],
                        ['match' => ['optype' => $op]]
                    ]
                ]
            ]
        ]
    ];

    $response = $client->search($params);
    $response = $response['hits']['hits'] ;
    for ($i = 0 ; $i < count($response) ; $i++) {
        array_push($results, $response[$i]['_source']['99%']) ;
    }

    // calculate the RT avarage in micro secounds
    $avg = array_sum($results) / count($results) * 1000000;
    return $avg ;
}

function total_files($test)
{
    /*
        This function calculate the total number of files in a particular test.

        Args:
            $test (dict): dictionary of single test result

        Returns:
            str : the number of files as formatted string (e.g. 3.62M / 840K)
    */
    $files = $test['global_options']['files'] * $test['threads'] * $test['clients'];

    return format_numbers($files) ;
}

function print_smalfiles_results_header()
{
    global $total_to_compare;
    start_table(2, 'fio_tbl') ;

    // First line header
    start_tr(3) ;

    $rspan = 1 ;
    $cspan = $total_to_compare ;

    if ($total_to_compare > 1) {
        $rspan = 2 ;
    }

    if ($total_to_compare == 2) {
        $cspan = 3 ;
    }

    TD(4, 'rowspan=' . $rspan, 'File Size (K)') ;
    TD(4, 'rowspan=' . $rspan, 'Op.') ;
    TD(4, 'rowspan=' . $rspan, 'Total Files') ;
    TD(4, 'colspan=' . $cspan, 'Files per second') ;
    TD(4, 'colspan=' . $cspan, '99% Response Time (μs)') ;

    close_tr(3) ;

    // Second line in case of comparison
    if ($total_to_compare > 1) {
        start_tr(3) ;
        print_test_legend(2, false, true) ;
        close_tr(3) ;
    }
}

function print_smallfiles_test_results()
{
    global $all_res_data ;
    global $total_to_compare;
    global $graph_data ;

    $graph_data = [] ;
    BR(2, 1, true) ;

    // Elasticsearch data table
    start_table(2, $extra = 'config_tbl') ;
    start_tr(3) ;
    start_td(4, 'colspan=' . ($total_to_compare + 1)) ;
    echo 'Individual test results can be found at the fallowing ElasticSearch links.' ;
    close_td() ;
    close_tr(3) ;
    start_tr(3) ;

    if ($total_to_compare > 1) {
        print_test_legend(1, false) ;
        close_tr(3) ;
        start_tr(3) ;
    }

    // Display the ES link for individual test result
    // running for each test to compare (1 - $total_to_compare)
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        $data = $all_res_data[$tst][1] ;
        start_td(4, '', true) ;

        // running for each individual sub-test within a test
        for ($ind = 1 ; $ind <= count($data) ; $ind++) {
            // Getting the PVC (volume) type - RBD / CephFS
            $vol = explode('-', $data[$ind]['global_options']['storageclass']);
            $vol = end($vol);

            // Getting the Block size of the files (file size) in KB
            $bs = $data[$ind]['global_options']['file_size'] ;

            // Calculate the number of files : files per Thread * threads * clients
            $files = total_files($data[$ind]) ;
            $gkey = $vol . '_' . $bs . '_' . $files ; // the key for the graphs
            if (!array_key_exists($gkey, $graph_data)) {
                $graph_data[$gkey] = [] ;
            }
            $link = $data[$ind]['es_link'] ;
            $link = trim(preg_replace('/\s\s+/', ' ', $link));
            $title = strtoupper($vol) . ' - ' . $files . ' Files - ' . $bs . 'K FileSize' ;

            anchor($title, $link, 5, true) ;
            BR(5, 1, 1) ;
        }
        close_td(4) ;
    }
    close_tr(3) ;
    close_table(2) ;

    // Display the tests results
    $lastvol = '' ;

    // compleat set of tests for single configuration (version)
    $data = $all_res_data[1][1] ;

    // loop on all single tests in a test-set (all version(s) have the same test-set)
    for ($tst = 1 ; $tst <= count($data) ; $tst++) {
        $vol = explode('-', $data[$tst]['global_options']['storageclass']) ;
        $vol = end($vol);

        $bs = $data[$tst]['global_options']['file_size'] ;

        $files = total_files($data[$tst]) ;
        $gkey = $vol . '_' . $bs . '_' . $files ; // the key for the graphs
        if ($vol != $lastvol) {
            if ($lastvol != '') {
                close_table(2) ;
                Generate_smallfiles_graph($ind, $lastvol, $bs, $files) ;
            }
            BR(2, 2, true) ;
            H(2, ucfirst($vol) . ' Test results', 2) ;
            $lastvol = $vol ;
            print_smalfiles_results_header() ;
        }

        start_tr(3) ;
        TD(4, 'rowspan=' . (count($data[$tst]['operations']) - 1), $bs) ;

        // display the operation(s) title - one line for each operation
        for ($i = 1 ; $i < count($data[$tst]['operations']) ; $i++) {
            $OP = $data[$tst]['operations'][$i] ;
            if ($i > 1) {
                start_tr(3) ;
            }
            TD(4, '', $OP) ;
            if ($i == 1) {
                TD(4, 'rowspan=' . (count($data[$tst]['operations']) - 1), $files) ;
            }
            $vals = [] ; // files-per-sec values
            $vals2 = [] ; // The response time values

             for ($j = 1 ; $j <= count($all_res_data) ; $j++) {
                 if (isset($all_res_data[$j][1][$tst]['full-res'][$OP]['filesPerSec'])) {
                     $TP = $all_res_data[$j][1][$tst]['full-res'][$OP]['filesPerSec']  ;
                     $vals[$j] = $TP ;
                 } else {
                     $TP = 0 ;
                 }
                 TD(4, '', number_format($TP)) ;

                 if (!array_key_exists($OP, $graph_data[$gkey])) {
                     $graph_data[$gkey][$OP] = [$vals[$j]] ;
                 } else {
                     array_push($graph_data[$gkey][$OP], $vals[$j]) ;
                 }
             }
            display_dif($TP, $vals, 'up') ;
            for ($j = 1 ; $j <= count($all_res_data) ; $j++) {
                $RT = read_rt($all_res_data[$j][1][$tst], $OP) ;
                 $vals2[$j] = $RT ;
                TD(4, '', number_format($RT)) ;
            }
           display_dif($RT, $vals2, 'down') ;
        }
    }

    close_table(2) ;
    Generate_smallfiles_graph($ind, $vol, $bs, $files) ;
}

function set_sfg_url()
{
    global $input_data ;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = 'https://';
    } else {
        $url = 'http://';
    }
    $url .= $_SERVER['HTTP_HOST'];

    $url .= '/smallfiles_graph.php?' ;
    return $url ;
}

function Generate_smallfiles_graph($ind, $voltype, $filesize, $files)
{
    global $all_res_data ;
    global $graph_data ;

    $blink = set_sfg_url() ;
    $link = 'voltype=' . ucfirst($voltype) ;
    start_table(2, $extra = 'config_tbl') ;
    start_tr(3) ;
    foreach (array_keys($graph_data) as $key) {
        $vol = explode('_', $key)[0] ;
        if ($vol == $voltype) {
            $link .= '&dsc=' . count($graph_data[$key]['create']) . '&key=' . $key;
            start_td(4) ;
            $DS = '' ;
            $OP = '&op=' ;
            $ind = 1 ;
            for ($t = 0 ; $t < count($graph_data[$key]['create']) ; $t++) {
                $DS .= '&ds' . $ind . '=' ;
                $ind++ ;
                foreach (array_keys($graph_data[$key]) as $op) {
                    $OP .= $op . ',' ;
                    $DS .= $graph_data[$key][$op][$t] . ',' ;
                }
                $DS = substr($DS, 0, -1) ;
            }
            echo "<iframe src='" . $blink . $link . $OP . $DS . "' scrolling='no'></iframe>" ;

            close_td() ;
        }
    }
    close_tr(3) ;
    close_table(2) ;
    return ;
}
