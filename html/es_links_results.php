<?php

require_once 'main.php';
require_once 'results_headers.php';

function get_es_link($tst, $ind){
    global $all_res_data ;

    $link = $all_res_data[$tst][1][$ind]['es_link'] ;
    $link = trim(preg_replace('/\s\s+/', ' ', $link));
    return $link ;
}

function print_ES_link_header(){

    global $total_to_compare;

    start_table(3, $extra="config_tbl") ;
    start_tr(4) ;
    display_multi_col_td("Individual test results can be found at the fallowing ElasticSearch links.") ;
    close_tr(4) ;
    start_tr(4) ;
    if ($total_to_compare > 1) {
        print_test_legend(1, false) ;
        close_tr(4) ;
        start_tr(4) ;
    }
}

function print_ES_links($test_data){
    global $all_res_data ;
    
    print_ES_link_header() ;
     $t = explode(';', $test_data["title"]) ;
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $i = 0 ;
            $title = "" ;
            foreach ($test_data["fields"] as &$val){
                $title .= $all_res_data[1][1][$ind][$val] . $t[$i] ;
                $i += 1 ;
            }
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
}

function print_fio_cmp_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;

    // Display all Individual tests ES link for FIO compress tests
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $io_pattern = $all_res_data[1][1][$ind]["io_pattern"] ;
            $volume_type = $all_res_data[1][1][$ind]["block_sizes"][0] ;
            $title = strtoupper($volume_type)  . "_" . $io_pattern . "_IO";
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
}
