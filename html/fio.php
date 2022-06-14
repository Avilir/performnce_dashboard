<?php
/*
This module contain the FIO benchmark results related functions
*/

require_once 'main.php';
require_once 'config.php';

use Elasticsearch\ClientBuilder;

$graph_data = array() ; // All data for generation graphs


function diff_res($values, $dir='up')
{
    /*
    This function calculation the percentage difference between 2 numbers
    if $dir is 'up' -> higher is better
    if $dir is 'down' -> lower is better so multiple the result on -1

    Args:
        values (array): array of 2 numbers
        dir (str) : 'up' / 'down' - the direction of calculation

    Returns:
        int : the difference in percentage (%)
    */
    $res = ($values[2] - $values[1]) * 100 / $values[1] ;
    if ($dir == 'up') {
        return  $res ;
    } elseif ($dir == 'down') {
        return  $res * -1 ;
    }
}

function display_diff($vals)
{
    /*
    This function display the difference value in the appropriate color

    Args:
        vals (array): array of 2 numbers to compare between

    */
    $res = diff_res($vals) ;
    $extra = "class='diff_reg'" ;

    // up to +/- 10% the results are the same
    // not improve and not degrade
    if ($res > 10) {
        $extra = "class='diff_pos'" ;
    }
    if ($res < -10) {
        $extra = "class='diff_neg'" ;
    }
    start_td(5, $extra) ;
    echo number_format($res) ;
    close_td() ;
}

function print_test_data_value($tst, $key)
{
    /*
    This function display the FIO test results value

    Args:
        tst (array): array of test results
        key (str): the key to display
    */
    start_td(5, "class='config_td'") ;
    echo $tst[$key] ;
    close_td() ;
}

function start_fio_table()
{
    /*
    This function display the FIO results table header
    */
    global $total_to_compare ;

    $extra1 = "" ;
    if ($total_to_compare > 1) {
        $extra1 = "rowspan=2" ;
    }

    $extra = "" ;
    if ($total_to_compare == 2) {
        $extra = "colspan=3" ;
    }
    if ($total_to_compare > 2) {
        $extra = "colspan=" . $total_to_compare ;
    }

    start_table(3, "fio_tbl") ;

    start_tr(4) ;
    start_th(5, $extra1, false) ;
    echo "BlockSize (K)" ;
    close_th() ;
    start_th(5, $extra1, false) ;
    echo "IO Pattern" ;
    close_th() ;
    start_th(5, $extra, false) ;
    echo "IOPS" ;
    close_th() ;
    start_th(5, $extra, false) ;
    echo "Throughput  (MB/Sec)" ;
    close_th() ;
    close_tr(4) ;

    if ($total_to_compare > 1) {
        start_tr(4) ;
        print_test_legend(2, false, ($total_to_compare == 2));
        close_tr(4) ;
    }
}

function print_fio_data($ind)
{
    /*
    This function display the FIO results data

    Args:
        ind (int): the individual test (in comparison) index at the
                   all data array
    */
    global $all_res_data ;      // the all tests data array
    global $total_to_compare ;  // number of tests for comparison
    global $graph_data ;        // array which will contain data for graphs
                                // generating - will build in this function

    $graph_data = [] ; // init the graph data array
    $vals = array() ;  // array for values to compare

    foreach ($all_res_data[1][1][$ind]['block_sizes'] as &$value) {
        start_tr(4) ;
        start_td(5, "rowspan='2'") ;
        echo print_r($value, true) ;
        close_td() ;

        $index = 0 ;
        foreach ($all_res_data[1][1][$ind]['operations'] as &$op) {
            if (! array_key_exists($op, $graph_data)) {
                $graph_data[$op] = array() ;
            }
            if ($index > 0) {
                start_tr(4) ;
            }
            start_td(5) ;
            echo print_r($op, true) ;
            close_td() ;
            $vals = [] ;
            for ($i = 1 ; $i <= $total_to_compare ; $i ++) {
                $vals[$i] = $all_res_data[$i][1][$ind]['all_results'][$value][$op]['IOPS'] ;
                start_td(5) ;
                echo number_format($vals[$i]) ;
                close_td() ;

                // pushing data into the graph array
                if (! array_key_exists($i, $graph_data[$op])) {
                    $graph_data[$op][$i] = array($vals[$i]) ;
                } else {
                    array_push($graph_data[$op][$i], $vals[$i]) ;
                }
            }
            if ($total_to_compare == 2) {
                display_diff($vals) ;
            }
            $vals = [] ;
            for ($i = 1 ; $i <= $total_to_compare ; $i ++) {
                $vals[$i] = $all_res_data[$i][1][$ind]['all_results'][$value][$op]['Throughput'] ;
                start_td(5) ;
                echo number_format($vals[$i]) ;
                close_td() ;
            }
            $index++ ;
            if ($total_to_compare == 2) {
                display_diff($vals) ;
            }
            close_tr(4) ;
        }
    }
}

function print_fio_cmp_data($ind)
{
    /*
    This function display the FIO results data

    Args:
        ind (int): the individual test (in comparison) index at the
                   all data array
    */
    global $all_res_data ;      // the all tests data array
    global $total_to_compare ;  // number of tests for comparison
    global $graph_data ;        // array which will contain data for graphs
                                // generating - will build in this function

    $vals = array() ;  // array for values to compare

    foreach ($all_res_data[1][1][$ind]['block_sizes'] as &$value) {
        start_tr(4) ;
        start_td(5, "rowspan='2'") ;
        echo print_r($value, true) ;
        close_td() ;

        $index = 0 ;
        foreach ($all_res_data[1][1][$ind]['operations'] as &$op) {
            if (! array_key_exists($op, $graph_data)) {
                $graph_data[$op] = array() ;
            }
            if ($index > 0) {
                start_tr(4) ;
            }
            start_td(5) ;
            echo print_r($op, true) ;
            close_td() ;
            $vals = [] ;
            for ($i = 1 ; $i <= $total_to_compare ; $i ++) {
                $vals[$i] = $all_res_data[$i][1][$ind]['all_results'][$value][$op]['IOPS'] ;
                start_td(5) ;
                echo number_format($vals[$i]) ;
                close_td() ;

                // pushing data into the graph array
                if (! array_key_exists($i, $graph_data[$op])) {
                    $graph_data[$op][$i] = array($vals[$i]) ;
                } else {
                    array_push($graph_data[$op][$i], $vals[$i]) ;
                }
            }
            if ($total_to_compare == 2) {
                display_diff($vals) ;
            }
            $vals = [] ;
            for ($i = 1 ; $i <= $total_to_compare ; $i ++) {
                $vals[$i] = $all_res_data[$i][1][$ind]['all_results'][$value][$op]['Throughput'] ;
                start_td(5) ;
                echo number_format($vals[$i]) ;
                close_td() ;
            }
            $index++ ;
            if ($total_to_compare == 2) {
                display_diff($vals) ;
            }
            close_tr(4) ;
        }
    }
}

function print_fio_test_results()
{
     /* Print the full FIO test results */
    global $all_res_data ;
    global $total_to_compare;

     // compleat set of tests for single configuration (version)
     $data = $all_res_data[1][1] ;

     // loop on all single tests in a test-set (all version(s) have the same test-set)
    for ($tst = 1 ; $tst <= count($data) ; $tst++) {
        $iop = $data[$tst]["io_pattern"] ;
        $vol = explode("-", $data[$tst]["storageclass"]) ;
        $vol = end($vol) ;
        BR(3, 2, true) ;
        H(2, ucfirst($iop) . " I/O on " . ucfirst($vol), 3) ;
        start_table(3, "config_tbl") ;
        start_tr(4) ;
        if ($total_to_compare > 1) {
            print_test_legend() ;
        }
        close_tr(4) ;

        start_tr(4) ;
        start_th(5, "class='config_td'") ;
        echo "Elasticsearch link" ;
        close_th() ;

        for ($i = 1 ; $i <= $total_to_compare ; $i ++) {
            start_td(5, "class='config_td'") ;
            $link = $data[$tst]["es_link"] ;
            anchor("This Link", $link, 0, false) ;
            close_td() ;
        }
        close_tr(4) ;

        start_tr(4) ;
        start_th(5, "class='config_td'") ;
        echo "Test UUID" ;
        close_th() ;

        for ($i = 1 ; $i <= $total_to_compare ; $i ++) {
            print_test_data_value($data[$tst], "uuid");
        }
        close_tr(4) ;
        close_table(3) ;
        BR(3, 2, true) ;
        start_fio_table();
        print_fio_data($tst) ;
        start_tr(4) ;
        Generate_fio_graph($tst, $vol) ;
        close_tr(4) ;
        close_table(3) ;
    }
}

function Generate_fio_graph($ind, $voltype)
{
    /*
    Generating FIO Graph

    Args:
        ind (int) : index of sub-test in the FIO (1 -4)
        voltype (str) : volume type (RBD / CephFS)
    */
    global $all_res_data ;
    global $graph_data ;
    $BS = "&bs=" ;
    foreach ($all_res_data[1][1][$ind]['block_sizes'] as $bs) {
        $BS .= $bs . "," ;
    }
    $op = $all_res_data[1][1][$ind]['operations'][0] ;
    $DS = "" ;
    for ($i = 1 ; $i <= count($graph_data[$op]) ; $i++) {
        $ds = "ds" . $i . "=";
        for ($j = 0 ; $j < count($graph_data[$op][$i]) ; $j++) {
            $ds .= (int)$graph_data[$op][$i][$j] . "," ;
        }
        $DS .= "&" . substr($ds, 0, -1) ;
    }
    $blink = set_graph_url("fio-2_graph.php") ;
    $link = "iotype=" .ucfirst($all_res_data[1][1][$ind]["io_pattern"]) ;
    $link .= "&voltype=" . ucfirst($voltype) ;
    $link .= "&dsc=" . count($graph_data[$op]) ;
    $link .= "&pattern=" . ucfirst($all_res_data[1][1][$ind]['operations'][0]) ;
    if ($GLOBALS['total_to_compare'] == 2) {
        $colspan = $GLOBALS['total_to_compare'] + 2 ;
    } else {
        $colspan = $GLOBALS['total_to_compare'] + 1 ;
    }
    start_td(5, 'colspan=' . $colspan, true) ;
    echo Tab(6) ;
    echo "<iframe src='" . $blink . $link . $BS . $DS . "' scrolling='no'></iframe>" ;
    NL(1) ;
    close_td(5) ;

    $op = $all_res_data[1][1][$ind]['operations'][1] ;
    $DS = "" ;
    for ($i = 1 ; $i <= count($graph_data[$op]) ; $i++) {
        $ds = "ds" . $i . "=";
        for ($j = 0 ; $j < count($graph_data[$op][$i]) ; $j++) {
            $ds .= (int)$graph_data[$op][$i][$j] . "," ;
        }
        $DS .= "&" . substr($ds, 0, -1) ;
    }

    $link = "iotype=" .ucfirst($all_res_data[1][1][$ind]["io_pattern"]) ;
    $link .= "&voltype=" . ucfirst($voltype) ;
    $link .= "&pattern=" . ucfirst($all_res_data[1][1][$ind]['operations'][1]) ;
    $link .= "&dsc=" . count($graph_data[$op]) ;
    start_td(5, 'colspan=' . $colspan, true) ;
    echo Tab(6) ;
    echo "<iframe src='" . $blink . $link . $BS . $DS . "' scrolling='no'></iframe>" ;
    NL(1) ;
    close_td(5) ;
}

function Generate_cmp_fio_graph($ind, $voltype, $bs)
{
    /*
    Generating FIO Graph

    Args:
        ind (int) : index of sub-test in the FIO (1 -4)
        voltype (str) : volume type (RBD / CephFS)
    */
    global $all_res_data ;
    global $graph_data ;
   $op = $all_res_data[1][1][$ind]['operations'][0] ;
    $DS = "" ;
    for ($i = 1 ; $i <= count($graph_data[$op]) ; $i++) {
        $ds = "ds" . $i . "=";
        for ($j = 0 ; $j < count($graph_data[$op][$i]) ; $j++) {
            $ds .= (int)$graph_data[$op][$i][$j] . "," ;
        }
        $DS .= "&" . substr($ds, 0, -1) ;
    }
    $blink = set_graph_url("fio-2_graph.php") ;
    $link = "iotype=" .ucfirst($all_res_data[1][1][$ind]["io_pattern"]) ;
    $link .= "&voltype=" . ucfirst($voltype) ;
    $link .= "&dsc=" . count($graph_data[$op]) ;
    $link .= "&pattern=" . ucfirst($all_res_data[1][1][$ind]['operations'][0]) ;
    if ($GLOBALS['total_to_compare'] == 2) {
        $colspan = $GLOBALS['total_to_compare'] + 2 ;
    } else {
        $colspan = $GLOBALS['total_to_compare'] + 1 ;
    }
    start_td(5, 'colspan=' . $colspan, true) ;
    echo Tab(6) ;
    echo "<iframe src='" . $blink . $link . $bs . $DS . "' scrolling='no'></iframe>" ;
    NL(1) ;
    close_td(5) ;

    $op = $all_res_data[1][1][$ind]['operations'][1] ;
    $DS = "" ;
    for ($i = 1 ; $i <= count($graph_data[$op]) ; $i++) {
        $ds = "ds" . $i . "=";
        for ($j = 0 ; $j < count($graph_data[$op][$i]) ; $j++) {
            $ds .= (int)$graph_data[$op][$i][$j] . "," ;
        }
        $DS .= "&" . substr($ds, 0, -1) ;
    }

    $link = "iotype=" .ucfirst($all_res_data[1][1][$ind]["io_pattern"]) ;
    $link .= "&voltype=" . ucfirst($voltype) ;
    $link .= "&pattern=" . ucfirst($all_res_data[1][1][$ind]['operations'][1]) ;
    $link .= "&dsc=" . count($graph_data[$op]) ;
    start_td(5, 'colspan=' . $colspan, true) ;
    echo Tab(6) ;
    echo "<iframe src='" . $blink . $link . $bs . $DS . "' scrolling='no'></iframe>" ;
    NL(1) ;
    close_td(5) ;
}
