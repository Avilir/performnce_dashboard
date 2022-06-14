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

function print_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;

    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
            $bs = $all_res_data[1][1][$ind]["pvc_size"] ;
            $title = strtoupper($volume_type) . "_with_volume_size_of-" . $bs ;
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

function print_ES_links_multi_delete(){
    global $all_res_data ;
    
    print_ES_link_header() ;

    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
            $bs = $all_res_data[1][1][$ind]["bulk_size"] ;
            $title = strtoupper($volume_type) . "_with_bulk_size_of-" . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_ES_links_bulk_cd(){
    global $all_res_data ;
    
    print_ES_link_header() ;

    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
            $bs = $all_res_data[1][1][$ind]["bulk_size"] ;
            $title = strtoupper($volume_type) . "_with_bulk_size_of-" . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_ES_links_pod_reattach(){
    global $all_res_data ;

    print_ES_link_header() ;

    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        $data = $all_res_data[$tst] ;
        start_td(5, '', true) ;
        for ($ind = 1 ; $ind <= count($data[1]) ; $ind++) {
            $volume_type = get_volume_type($data[1][$ind]["storageclass"]) ;
            $copies_number = $data[1][$ind]['copies_number'] ;            
            $title = strtoupper($volume_type) . '_' . $copies_number . '_Copies_of_files' ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
}

function print_ES_links_pvc_attach(){
    global $all_res_data ;
    global $total_to_compare;

    print_ES_link_header() ;

    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        $data = $all_res_data[$tst] ;
        start_td(5, '', true) ;
        for ($ind = 1 ; $ind <= count($data[1]) ; $ind++) {
            $volume_type = get_volume_type($data[1][$ind]["storageclass"]) ;
            $copies_number = $data[1][$ind]['pvc_size'] ;
            $title = strtoupper($volume_type) . '_' . $copies_number . 'G_PVC' ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
}

function print_ES_links_bulk_attach(){
    global $all_res_data ;

    print_ES_link_header() ;

    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        $data = $all_res_data[$tst] ;
        start_td(5, '', true) ;
        for ($ind = 1 ; $ind <= count($data[1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
            $bulk_size = $data[1][$ind]['bulk_size'] ;            
            $bs = $data[1][$ind]['pvc_size'] ;
            $title = strtoupper($volume_type) . '_' . $bulk_size . '_PVC_of-' . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
}

function print_Clone_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;
    
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
            $bs = $all_res_data[1][1][$ind]["pvc_size"] ;
            $title = strtoupper($volume_type) . "_with_volume_size_of-" . $bs . "_GiB";
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_CAD_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;
    
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
            $bs = $all_res_data[1][1][$ind]["pvc_size"] ;
            $title = strtoupper($volume_type) . "_with_volume_size_of-" . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_Snap_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;
    
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
            $bs = $all_res_data[1][1][$ind]["pvc_size"] ;
            $title = strtoupper($volume_type) . "_with_volume_size_of-" . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_Snap_MF_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;
    
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
            $bs = format_numbers($all_res_data[1][1][$ind]['all_results']["total_files"]) ;
            $title = strtoupper($volume_type) . "_with_" . $bs . "_of_files" ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_BClone_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;
    
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
            $bs = $all_res_data[1][1][$ind]["clone_size"] ;
            $title = strtoupper($volume_type) . "_with_volume_size_of-" . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}

function print_multi_snap_ES_links(){
    global $all_res_data ;
    
    print_ES_link_header() ;
    
    // Display all Individual tests ES link
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        start_td(5, "", true) ;
        for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
            $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
            $bs = $all_res_data[1][1][$ind]["pvc_size"] ;
            $title = strtoupper($volume_type) . "_with_volume_size_of-" . $bs ;
            anchor($title, get_es_link($tst, $ind), 6, true) ;
            BR(6, 1, 1) ;
        }
        close_td(5) ;
    }
    close_tr(4) ;
    close_table(3) ;
    
}
