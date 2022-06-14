<?php

function print_pvc_cd_test_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_ES_links() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("pvc_cd") ;
        }

        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        display_data_section($ind, "creation-time", 'down') ;
        display_data_section($ind, "deletion-time", 'down') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_bulk_cd_test_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_ES_links_bulk_cd() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("pvc_bulk_cd") ;
        }

        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["bulk_size"]) ;
        display_data_section($ind, "bulk_pvc_creation_time", 'down') ;
        display_data_section($ind, "bulk_pvc_deletion_time", 'down') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_multiple_delete_results(){
    global $all_res_data ;

    BR(3, 1, true) ;
    print_ES_links_multi_delete() ;
    display_test_results_header("pvc_multi_delete") ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["storageclass"]) ;
        start_tr(4) ;
        TD(5, '', $volume_type) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["bulk_size"]) ;
        display_data_section($ind, "bulk_deletion_time", 'down') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_bulk_clonec_create_results(){
    global $all_res_data ;

    BR(3, 1, true) ;
    print_BClone_ES_links() ;
    display_test_results_header("bulk_clone") ;
    BR(3, 1, true) ;
  
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        start_tr(4) ;
        TD(5, '', $volume_type) ;
        TD(5, '', $all_res_data[1][1][$ind]["clone_size"]) ;
        TD(5, '', number_format($all_res_data[1][1][$ind]["data_size(MB)"], 0) ) ;
        TD(5, '', $all_res_data[1][1][$ind]["bulk_size"]) ;
        display_data_section($ind, "bulk_creation_time", 'down') ;
        display_data_section($ind, "speed", 'up') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_bulk_cad_test_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_CAD_ES_links() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("pvc_bulk_cad") ;
        }

        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        TD(5, '', $all_res_data[1][1][$ind]["number_of_pvcs"]) ;
        display_data_section($ind, "creation_after_deletion_time", 'down') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_snapshot_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_Snap_ES_links() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("pvc_snap") ;
        }
 
        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        display_data_section($ind, "avg_snap_creation_time_insecs", 'down') ;
        display_data_section($ind, "avg_snap_restore_time_insecs", 'down') ;
        display_data_section($ind, "avg_snap_restore_speed", 'up') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_snapshot_multi_files_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_Snap_MF_ES_links() ;
    
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("multi_snap_with_files") ;
        }

        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["file_size_inKB"]) ;
        TD(5, '', format_numbers($all_res_data[1][1][$ind]["all_results"]['total_files'])) ;
        TD(5, '', $all_res_data[1][1][$ind]["all_results"]['total_dataset']) ;
        display_data_section($ind, "avg_snapshot_creation_time_insecs", 'down') ;
        close_tr(4) ;       
    }
    close_table(3) ;
}

function print_pvc_clone_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_Clone_ES_links() ;
     
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("pvc_clone") ;
         }

        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        display_data_section($ind, "average_clone_creation_time", 'down') ;
        display_data_section($ind, "average_clone_creation_speed", 'up') ;
        display_data_section($ind, "average_clone_deletion_time", 'down') ;
        close_tr(4) ;        
    }
    close_table(3) ;
}

function print_pvc_clone_mf_results(){
    global $all_res_data ;
    $storageclass = array() ;
    $start = false ;

    BR(3, 1, true) ;
    print_Clone_ES_links() ;
     
    for ($ind = 1 ; $ind <= count($all_res_data[1][1]) ; $ind++) {
        $volume_type = get_volume_type($all_res_data[1][1][$ind]["interface"]) ;
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
            display_test_results_header("pvc_clone_mf") ;
         }

        start_tr(4) ;
        TD(5, '', $all_res_data[1][1][$ind]["pvc_size"]) ;
        TD(5, '', format_numbers($all_res_data[1][1][$ind]['files_number'])) ;
        display_data_section($ind, "average_clone_creation_time", 'down') ;
        display_data_section($ind, "average_clone_deletion_time", 'down') ;
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
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
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
        if(! in_array($volume_type, $storageclass)) {
            array_push($storageclass, $volume_type) ;
            if ($start) { close_table(3) ; }
            else { $start = true ; }
            H(2, "Results for " . $volume_type, 3) ;
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
                //start_tr(4) ;
                //for ($i = 1 ; $i <= count($graph_data[$op]) ; $i++) {
                    //$ds = "ds" . $i . "=";
                    //for ($j = 0 ; $j < count($graph_data[$op][$i]) ; $j++) {
                    //    $ds .= (int)$graph_data[$op][$i][$j] . "," ;
                    //}
                    //$DS .= "&" . substr($ds, 0, -1) ;
                //}
                //close_tr(4) ;
 
                start_tr(4) ;
            
                Generate_cmp_fio_graph(($ind-1), 'rbd', $BS) ;
                //print_r($graph_data);
                //Generate_fio_cmp_graph($ind, 'rbd') ;
                //start_td(5) ;
                //echo $op1 . " Graph</br>" . $BS . $DS ;
                //close_td(5) ;
    
                //start_td(5) ;
                //echo $op2 . " Graph" ;
                //close_td(5) ;
    
                //close_tr(4) ;
           
                close_table(3) ; 
                $DS = "" ;
                $op1 = $all_res_data[1][1][$ind]['operations'][0] ;
                $op2 = $all_res_data[1][1][$ind]['operations'][1] ;
                //echo "=====> " . $ind . " <=======";    
                //echo "====</br>" .$BS . "^^^^^</br>" ;   
                $BS = "&bs=" ;
   
            }
            else { $start = true ; }
            H(2, "Results for " . $io_pattern . " I/O on RBD", 3) ;
            start_fio_table();
            //echo "All BlockSizes are : " . $BS . " Operation = " . $op1;
            //echo " The results are : " . $DS ;
            $graph_data = [] ;

        }

        print_fio_cmp_data($ind) ;
        //Generate_fio_graph($ind, 'rbd') ;

        $bs = $data[$ind]['block_sizes'][0] ;
        $BS .= $bs . "," ;
        $DS .= "&ds" . $ind . "=" .$data[$ind]['all_results'][$bs][$op1]['IOPS'] ; 
        //echo "A ====</br>" .$BS . "^^^^^</br>" ;   
        //echo "====</br>" ; print_r($data[$ind]['all_results'][$bs]) ; echo "^^^^^</br>" ;
    }
    start_tr(4) ;
    Generate_cmp_fio_graph(($ind-1), 'rbd', $BS) ;

    //Generate_fio_cmp_graph(2, 'rbd') ;
    //start_td(5) ;
    //echo $op1 . " Graph" ;
    //close_td(5) ;

    //start_td(5) ;
    //echo $op2 . " Graph" ;
    //close_td(5) ;

    //close_tr(4) ;

    close_table(3) ;
}
