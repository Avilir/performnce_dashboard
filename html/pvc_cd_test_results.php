<?php

function storgeclass_header($vol_type, $storageclass, $start){
    $display_header = false ;
    if(! in_array($vol_type, $storageclass)) {
        array_push($storageclass, $vol_type) ;
        if ($start) { close_table(3) ; }
        else { $start = true ; }
        H(2, "Results for " . $vol_type, 3) ;
        $display_header = true ;
     }
     return [$start, $storageclass, $display_header] ;
}

function print_test_results($test_name){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    $test_id = get_id_by_name("tests", $test_name) ;
    $test_data = get_test_data($test_id) ;
    $test_data = json_decode($test_data, true) ;
    
    BR(3, 1, true) ;
    print_ES_links($test_data["eslink_data"]) ;

    if ($test_data["heder"] == 1) {
        display_test_results_header($test_data["head_title"]) ;
    }

    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        if ($test_data["heder"] == 2) {
            if (array_key_exists("storageclass", $all_res_data[1][1][$ind])) {
                $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
            } else {
                $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;               
            }
            list($start, $storageclass, $disp_head) = storgeclass_header($volume_type, $storageclass, $start) ;
            if ($disp_head) { display_test_results_header($test_data["head_title"]) ; }
        }
        start_tr(4) ;
        foreach ($test_data["data"] as &$col) {
            if ($col["type"] == 1) {
                TD(5, '', $all_res_data[1][1][$ind][$col["name"]]) ;
            }
            if ($col["type"] == 2) {
                display_data_section($ind, $col["name"], 'down') ;
            }
            if ($col["type"] == 3) {
                display_data_section($ind, $col["name"], 'up') ;
            }
            if ($col["type"] == 4) {
                TD(5, '', count($all_res_data[1][1][$ind][$col["name"]])) ;
            }
            if ($col["type"] == 5) {
                number_td($all_res_data[1][1][$ind][$col["name"]], 0 );
            }
        }
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_multi_clone_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_BClone_ES_links() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        list($start, $storageclass, $disp_head) = storgeclass_header($volume_type, $storageclass, $start) ;
        if ($disp_head) {
            display_test_results_header("pvc_multi_clone") ;
        }
        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["clone_size"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["clones_num"]) ;
        display_data_section($ind, "multi_clone_creation_time_average", 'down') ;
        display_data_section($ind, "multi_clone_creation_speed_average", 'up') ;
        close_tr(4) ;
        
        start_tr(4) ;
        Generate_multi_clone_graph($ind) ;
        close_tr(4) ;
    }
    close_table(3) ;
}

function print_pvc_multi_snap_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_multi_snap_ES_links() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
        list($start, $storageclass, $disp_head) = storgeclass_header($volume_type, $storageclass, $start) ;
        if ($disp_head) {
            display_test_results_header("multi_snap") ;
        }
        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["snapshot_num"]) ;
        display_data_section($ind, "avg_creation_time", 'down') ;
        display_data_section($ind, "avg_creation_speed", 'up') ;
        close_tr(4) ;
        
        start_tr(4) ;
        Generate_multi_snap_graph($ind) ;
        close_tr(4) ;
    }
    close_table(3) ;
}

function Generate_multi_clone_graph($ind){
    /*
    Generating FIO Graph

    Args:
        ind (int) : index of sub-test in the FIO (1 -4)
        voltype (str) : volume type (RBD / CephFS)
    */
    global $all_res_data ;
    global $graph_data ;

    $DS="" ;
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        for ($i = 0 ; $i < count($all_res_data[$tst][1][$ind]["multi_clone_creation_time"]) ; $i++) {
            $n = $all_res_data[$tst][1][$ind]["multi_clone_creation_time"][$i] ;
            if ($n < 0) { $n = 0 ; }
            $all_res_data[$tst][1][$ind]["multi_clone_creation_time"][$i] = number_format($n, 2, '.', '') ;
        }
           $DS .= "&ds" . $tst . "=" .  implode(",", $all_res_data[$tst][1][$ind]["multi_clone_creation_time"]) ;
    }
    $blink = set_graph_url("multi_clone_graph.php") ;
    $link = "interface=" .ucfirst($all_res_data[1][1][$ind]["interface"]) ;
    $link .= "&cnt=" . count($all_res_data) ;

    if ($GLOBALS['total_to_compare'] == 2) {
        $colspan = $GLOBALS['total_to_compare'] + 6 ;
    } else {
        $colspan = $GLOBALS['total_to_compare'] + 3 ;
    }
    start_td(5, 'colspan=' . $colspan, true) ;
    echo Tab(6) ;
    echo "<iframe class='lineg' src='" . $blink . $link . $DS . "' scrolling='no'></iframe>" ;
    NL(1) ;
    close_td(5) ;
}

function Generate_multi_snap_graph($ind){
    /*
    Generating FIO Graph

    Args:
        ind (int) : index of sub-test in the FIO (1 -4)
        voltype (str) : volume type (RBD / CephFS)
    */
    global $all_res_data ;
    global $graph_data ;

    $DS="" ;
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        $ds_data=array() ;
        for ($i = 0 ; $i < count($all_res_data[$tst][1][$ind]["all_results"]) ; $i++) {
            $n = $all_res_data[$tst][1][$ind]["all_results"][$i]['time'] ;
            if ($n < 0) { $n = 0 ; }
            $ds_data[$i] = number_format($n, 2, '.', '') ;
        }
           $DS .= "&ds" . $tst . "=" .  implode(",", $ds_data) ;
    }
    $blink = set_graph_url("multi_snap_graph.php") ;
    $link = "interface=" .ucfirst($all_res_data[1][1][$ind]["storageclass"]) ;
    $link .= "&cnt=" . count($all_res_data) ;
    if ($GLOBALS['total_to_compare'] == 2) {
        $colspan = $GLOBALS['total_to_compare'] + 6 ;
    } else {
        $colspan = $GLOBALS['total_to_compare'] + 3 ;
    }
    start_td(5, 'colspan=' . $colspan, true) ;
    echo Tab(6) ;
    echo "<iframe class='lineg' src='" . $blink . $link . $DS . "' scrolling='no'></iframe>" ;
    NL(1) ;
    close_td(5) ;
}

function print_fio_cmp_graph_data() {

}

function print_compress_test_results(){
    global $all_res_data ;
    global $total_to_compare;
    global $graph_data ;
    $storageclass = array() ;
    $start = false ;
    $BS = "&bs=" ;

    $data = $all_res_data[1][1] ;
    BR(3, 1, true) ;
    print_fio_cmp_ES_links() ;
    
     $DS = "" ;

    for ($ind = 1 ; $ind <= count($data) ; $ind++) {
        $io_pattern = $data[$ind]["io_pattern"] ;
        if(! in_array($io_pattern, $storageclass)) {
            $op1 = $all_res_data[1][1][$ind]['operations'][0] ;
            $op2 = $all_res_data[1][1][$ind]['operations'][1] ;
            array_push($storageclass, $io_pattern) ;
            if ($start) {
 
                start_tr(4) ;
            
                Generate_cmp_fio_graph(($ind-1), 'rbd', $BS) ;
           
                close_table(3) ; 
                $DS = "" ;
                $op1 = $all_res_data[1][1][$ind]['operations'][0] ;
                $op2 = $all_res_data[1][1][$ind]['operations'][1] ;
                $BS = "&bs=" ;
   
            }
            else { $start = true ; }
            H(2, "Results for " . $io_pattern . " I/O on RBD", 3) ;
            start_fio_table();
            $graph_data = [] ;

        }

        print_fio_cmp_data($ind) ;
        //Generate_fio_graph($ind, 'rbd') ;

        $bs = $data[$ind]['block_sizes'][0] ;
        $BS .= $bs . "," ;
        $DS .= "&ds" . $ind . "=" .$data[$ind]['all_results'][$bs][$op1]['IOPS'] ; 
    }
    start_tr(4) ;
    Generate_cmp_fio_graph(($ind-1), 'rbd', $BS) ;

    close_table(3) ;
}
