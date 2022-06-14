<?php

require_once 'main.php';

$headers_data = array(
    "pvc_cd" => array(
        "1" => array("title" => "PV Size", "compare" => false) ,
        "2" => array("title" => "Creation Time (Sec.)", "compare" => true) ,
        "3" => array("title" => "Deletion Time (Sec.)", "compare" => true) ,
    ),
    "pvc_bulk_cd" => array(
        "1" => array("title" => "PV Size", "compare" => false) ,
        "2" => array("title" => "Bulk Size", "compare" => false) ,
        "3" => array("title" => "Creation Time (Sec.)", "compare" => true) ,
        "4" => array("title" => "Deletion Time (Sec.)", "compare" => true) ,
    ),
    "pvc_bulk_cad" => array(
        "1" => array("title" => "PV Size", "compare" => false) ,
        "2" => array("title" => "Bulk Size</br>(To delete & recreate)", "compare" => false) ,
        "3" => array("title" => "Creation After Deletion Time (Sec.)", "compare" => true) ,
    ),
    "pvc_snap" => array(
        "1" => array("title" => "PV Size", "compare" => false) ,
        "2" => array("title" => "Creation Time (Sec.)", "compare" => true) ,
        "3" => array("title" => "Restore Time (Sec.)", "compare" => true) ,
        "4" => array("title" => "Restore Speed (MiB/Sec.)", "compare" => true) ,
    ),
    "pvc_multi_delete" => array(
        "1" => array("title" => "Volume Type", "compare" => false) ,
        "2" => array("title" => "PVC Size", "compare" => false) ,
        "3" => array("title" => "PVC's Count", "compare" => false) ,
        "4" => array("title" => "Deletion Time (Sec.)", "compare" => true) ,   
   ),
    "bulk_clone" => array(
        "1" => array("title" => "Interface", "compare" => false) ,
        "2" => array("title" => "PVC Size", "compare" => false) ,
        "3" => array("title" => "Data Size (MiB)", "compare" => false) ,
        "4" => array("title" => "Bulk Size", "compare" => false) ,   
        "5" => array("title" => "Creation Time (Sec.)", "compare" => true) ,
        "6" => array("title" => "Creation Speed (MiB/Sec.)", "compare" => true) ,   
    ),
    "pvc_multi_clone" => array(
        "1" => array("title" => "Clone Size (GiB)", "compare" => false) ,
        "2" => array("title" => "Clone Numbers", "compare" => false) ,
        "3" => array("title" => "Avg. Clone Time (Sec.)", "compare" => true) ,
        "4" => array("title" => "Avg. Clone Speed (MiB/Sec.)", "compare" => true) ,   
    ),
    "pvc_clone" => array(
        "1" => array("title" => "PVC Size (GiB)", "compare" => false) ,
        "2" => array("title" => "Creation Time (Sec.)", "compare" => true) ,
        "3" => array("title" => "Creation Speed (MiB/Sec.)", "compare" => true) ,
        "4" => array("title" => "Deletion Time (Sec.)", "compare" => true) ,   
    ),
    "pvc_clone_mf" => array(
        "1" => array("title" => "PVC Size (GiB)", "compare" => false) ,
        "2" => array("title" => "Number of files", "compare" => false) ,
        "3" => array("title" => "Creation Time (Sec.)", "compare" => true) ,
        "4" => array("title" => "Deletion Time (Sec.)", "compare" => true) ,   
    ),
    "multi_snap" => array(
        "1" => array("title" => "PVC Size (GiB)", "compare" => false) ,
        "2" => array("title" => "Snapshots Number", "compare" => false) ,
        "3" => array("title" => "Avg. Creation Time (Sec.)", "compare" => true) ,
        "4" => array("title" => "Avg. Creation Speed (MiB/Sec.)", "compare" => true) ,   
    ),
    "multi_snap_with_files" => array(
        "1" => array("title" => "Files size (KiB)", "compare" => false) ,
        "2" => array("title" => "Number of files", "compare" => false) ,
        "3" => array("title" => "Total Dataset (GiB)", "compare" => false) ,
        "4" => array("title" => "Avg. Creation Time (Sec.)", "compare" => true) ,   
    ),
    "bulk_pod_attach" => array(
        "1" => array("title" => "Volume Type", "compare" => false) ,
        "2" => array("title" => "Bulk size (POD's)", "compare" => false) ,
        "3" => array("title" => "PV Size", "compare" => false) ,
        "4" => array("title" => "Attach Time (Sec.)", "compare" => true) ,   
    ),
    "pod_reattach" => array(
        "1" => array("title" => "Volume Type", "compare" => false) ,
        "2" => array("title" => "Number of samples", "compare" => false) ,
        "3" => array("title" => "Total files", "compare" => false) ,
        "4" => array("title" => "Total Data (GiB)", "compare" => false) ,
        "5" => array("title" => "ReAttach Time (Sec.)", "compare" => true) ,   
    ),
    "pvc_attach" => array(
        "1" => array("title" => "Volume Type", "compare" => false) ,
        "2" => array("title" => "Number of samples", "compare" => false) ,
        "3" => array("title" => "PVC Size (GiB)", "compare" => false) ,
        "4" => array("title" => "STD Deviation (%)", "compare" => false) ,
        "5" => array("title" => "Attach Time (Sec.)", "compare" => true) ,
    ),
);

function display_multi_line_td($title){
    global $rspan ;
    TD(5, "rowspan=" . $rspan, $title) ;
}

function display_multi_col_td($title){
    global $cspan ;
    TD(5, "colspan=" . $cspan, $title) ;
}

function display_second_line_header($times){

    global $all_res_data ;
    global $total_to_compare;

    if ($total_to_compare > 1) {
        start_tr(4) ;
        for ($i = 1 ; $i <= $times ; $i++){
            for ($ind = 1 ; $ind <= $total_to_compare ; $ind++) {
                $build = explode('-', $all_res_data[$ind][1][1]['ocs_build'])[0] ;
                $platform = explode('-', $all_res_data[$ind][1][1]['platform'])[0] ;                
                $classid = "class='test" . $ind ."'" ;
                $title = "Test " . $ind . " (" . $build . ")" ;
                start_td(5, $classid) ;
                echo $title ;
                BR(0, 1, 0) ;
                echo $platform ;
                close_td() ;
            }
            if ($total_to_compare == 2) {
                TD(5, "", "Diff (%) ") ;
            }
        }
        close_tr(4) ;
    }

}

function display_data_section ($ind, $key, $diff_dir) {
    global $all_res_data ;

    $vals = [] ;
    for ($tst = 1 ; $tst <= count($all_res_data) ; $tst++) {
        $vals[$tst] = $all_res_data[$tst][1][$ind][$key] ;
        number_td($vals[$tst], 3) ;
    }
    display_dif(1, $vals, $diff_dir) ;

}

function display_test_results_header($test_name){

    global $headers_data ;
    $compers = 0 ;
    $data = $headers_data[$test_name] ;

    start_table(3, "fio_tbl") ;

    // Display the first line in the table
    start_tr(4) ;
    for ($c = 1 ; $c <= count($data) ; $c++) {
        if ($data[$c]['compare']) {
            $compers++ ;
            display_multi_col_td($data[$c]['title']) ;
        } else {
            display_multi_line_td($data[$c]['title']) ;
        }
    }
    close_tr(4) ;        

    // Display the second line of the table (if needed)
    display_second_line_header($compers) ;
}
