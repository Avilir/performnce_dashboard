<?php

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

function display_test_results_header($data){

    $compers = 0 ;

    start_table(3, "fio_tbl") ;

    // Display the first line in the table
    start_tr(4) ;
    for ($c = 0 ; $c < count($data) ; $c++) {
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
