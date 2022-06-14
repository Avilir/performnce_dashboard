<?php

/*
This module include function which display the configuration data of tests

*/

require_once 'main.php';

function print_test_legend($count = 1, $full = true, $diff = false)
{
    /*
    This function display the tests legend, when the report is for more than
    one test (comparison report)
    For each test it also display the test version (OCS)

    Args:
        count (int): how meany times to display the legend, for multiple
                     results column
        full (bool): do we need the "Test Legend" title ?
    */
    global $total_to_compare ;
    global $all_res_data ;

    debug("Total tests to compare : $total_to_compare, and the count is $count") ;
    if ($full) {
        start_th(5) ;
        echo 'Test Legend' ;
        close_th() ;
    }
    for ($i = 1 ; $i <= $count ; $i++) {
        for ($ind = 1 ; $ind <= $total_to_compare ; $ind++) {
            $data = $all_res_data[$ind][1][1] ;
            $build = explode('-', $data['ocs_build'])[0] ;
            $platform = explode('-', $data['platform'])[0] ;
            start_td(5, "class='test" . $ind . "'") ;
            echo 'Test ' . $ind . ' (' . $build . ')';
            echo BR(0, 1, false) ;
            echo $platform ;
            close_td() ;
        }
        if ($diff && $total_to_compare == 2) {
            start_td(5)  ;
            echo 'Diff (%) ' ;
            close_td() ;
        }
    }
}

function print_config_data($all_data, $key, $format = false)
{
    /*
    This function print the configuration data of the test(s)

    Args:
        all_data (array): array with all test(s) data
        key (str): the that we want to display the data of
        format (bool): does the data is number ? default : false
    */
    global $total_to_compare ;
    for ($ind = 1 ; $ind <= $total_to_compare ; $ind++) {
        start_td(5) ;
        // since all sub-tests have the same configuration, taking data
        // from the 1st one
        $data = $all_data[$ind][1][1] ;
        if (isset($data[$key])) {
            $data = $data[$key] ;
            // for ceph version display only the firs part
            if ($key == 'ceph_version') {
                $data = explode(' ', $data)[0] ;
            }
            if ($format) {
                if ($data > (1024 ** 2)) {  // This number is in TiB
                    echo number_format($data / (1024 ** 2)) ;
                } else {  // This number is in GiB
                    echo number_format($data, 2) ;
                }
            } elseif ($key == '') {
                echo nbsp() ;
            } else {
                echo $data ;
            }
        } else {
            echo 'N/A' ;
        }
        close_td() ;
    }
}

function print_config_line($title, $data, $key, $format = false)
{
    /* Print full configuration line - Title and data

    Args :
        title (sting) : The title to display
        $data (array): array with all test(s) data
        key (str): the that we want to display the data of
        format (bool): does the data is number ? default : false
    */
    start_tr(4) ;
    start_th(5) ;
    echo $title ;
    close_th() ;
    print_config_data($data, $key, $format) ;
    close_tr(4) ;
}

function print_hw_config($all_data)
{
    /*
    This function print the Hardware configuration table,
    titles and data of the test(s)

    Args:
        all_data (array): array with all test(s) data
    */
    global $total_to_compare ;
    $extra = '' ;
    H(2, 'Hardware Configuration', 3) ;
    if ($total_to_compare > 1) {
        // for multiple tests expand the columns
        $extra = 'colspan=' . $total_to_compare ;
    }
    start_table(3, 'config_tbl') ;
    // Table header line
    start_tr(4) ;
    start_th(5) ;
    echo nbsp() ;
    close_th() ;
    start_th(5, $extra) ;
    echo 'RAM' ;
    close_th() ;
    start_th(5, $extra) ;
    echo 'vCPU' ;
    close_th() ;
    start_th(5, $extra) ;
    echo 'Num of Nodes' ;
    close_th() ;
    close_tr(4) ;

    if ($total_to_compare > 1) {
        start_tr(4) ;
        print_test_legend(3) ;
        close_tr(4) ;
    }

    // Master nodes data
    start_tr(4) ;
    start_td(5) ;
    echo 'Master Nodes' ;
    close_td() ;
    print_config_data($all_data, 'master_nodes_memory', true) ;
    print_config_data($all_data, 'master_nodes_cpu_num') ;
    print_config_data($all_data, 'master_nodes_num') ;
    close_tr(4) ;

    // Worker nodes data
    start_tr(4) ;
    start_td(5) ;
    echo 'Worker Nodes' ;
    close_td() ;
    print_config_data($all_data, 'worker_nodes_memory', true) ;
    print_config_data($all_data, 'worker_nodes_cpu_num') ;
    print_config_data($all_data, 'worker_nodes_num') ;
    close_tr(4) ;
    close_table(3) ;
}

function print_cl_config($all_data)
{
    /*
    This function print the Cluster configuration table,
    titles and data of the test(s)

    Args:
        all_data (array): array with all test(s) data
    */
    global $total_to_compare ;
    H(2, 'Cluster Configuration', 3) ;
    start_table(3, 'config_tbl') ;
    if ($total_to_compare > 1) {
        start_tr(4) ;
        print_test_legend() ;
        close_tr(4) ;
    }
    print_config_line('HW Platform', $all_data, 'platform') ;
    print_config_line('Number of ODF nodes', $all_data, 'ocs_nodes_num') ;
    print_config_line('Number of total OSDs', $all_data, 'osd_num') ;
    print_config_line('OSD Size (TiB)', $all_data, 'osd_size', true) ;
    print_config_line('Total available storage (GiB)', $all_data, 'total_capacity', true) ;
    print_config_line('OCP Version', $all_data, 'ocp_build') ;
    print_config_line('ODF Version', $all_data, 'ocs_build') ;
    print_config_line('Ceph Version', $all_data, 'ceph_version') ;
    print_config_line('Cluster name', $all_data, 'clustername') ;
    print_config_line('Cluster ID', $all_data, 'clusterID') ;
    print_config_line('Full version list', $all_data, '') ;
    close_table(3) ;
}

function print_test_configuration($all_data)
{
    /*
    This function print all configuration tables (HW & Cluster),
    titles and data of the test(s)

    Args:
        all_data (array): array with all test(s) data
    */
    print_hw_config($all_data) ;
    print_cl_config($all_data) ;
}

function set_graph_url($benchmark)
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = 'https://';
    } else {
        $url = 'http://';
    }
    $url .= $_SERVER['HTTP_HOST'];

    $url .= '/' . $benchmark . '?';
    return $url ;
}
