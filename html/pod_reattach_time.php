<?php

require_once 'main.php';
require_once 'es_links_results.php';
require_once 'results_headers.php';

function print_pvc_attach_time_results(){
    global $all_res_data ;

    BR(3, 1, true) ;
    print_ES_links_pvc_attach() ;
    display_test_results_header("pvc_attach") ;
    BR(4,1,1);

    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        start_tr(4) ;
        TD(5, '', get_volume_type($all_res_data[1][1][$ind]["storageclass"])) ;
        TD(5, '', $all_res_data[1][1][$ind]["samples_number"]) ;
        number_td($all_res_data[1][1][$ind]['pvc_size'], 0 );
        number_td($all_res_data[1][1][$ind]['attach_time_stdev_percent'], 2 );
        display_data_section($ind, "attach_time_average", 'down') ;
        close_tr(4) ;
    }
    close_table(3) ;
}

function print_pod_reattach_time_results(){
    global $all_res_data ;

    BR(3, 1, true) ;
    print_ES_links_pod_reattach() ;
    display_test_results_header("pod_reattach") ;
    BR(4,1,1);
 
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        start_tr(4) ;
        TD(5, '', get_volume_type($all_res_data[1][1][$ind]["storageclass"])) ;
        TD(5, '', count($all_res_data[1][1][$ind]["pod_reattach_time"])) ;
        number_td($all_res_data[1][1][$ind]['files_number_average'], 0 );
        TD(5, '', $all_res_data[1][1][$ind]['data_average']);
        display_data_section($ind, "pod_reattach_time_average", 'down') ;
        close_tr(4) ;
    }
    close_table(3) ;
}

function print_bulk_pod_attach_time_results(){
    global $all_res_data ;

    BR(3, 1, true) ;
    print_ES_links_bulk_attach() ;
    display_test_results_header("bulk_pod_attach") ;
    BR(4,1,1);

    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["storageclass"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["bulk_size"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        display_data_section($ind, "pod_bulk_attach_time", 'down') ;
        close_tr(4) ;
    }
    close_table(3) ;
}
