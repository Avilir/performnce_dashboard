<?php

/*
    This file contain the main project common functions
*/

//require_once 'config.php';
//require_once 'php_main/htmltags.php';

function TD($tabs, $extra, $title){
    start_td($tabs, $extra) ;
    echo $title ;
    close_td() ;
}

function number_td($data, $dpoint){
    start_td(5) ;
    echo number_format($data, $dpoint) ;
    close_td() ;
}

function debug($msg){
    if ($GLOBALS['dev_mode']) {
        echo "$msg</br>\n" ;
    }
}

function read_quary_string(){
    /*
        Reading the URL Query string into a global variable
    */

    global $input_data ;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input_data = $_POST ;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $input_data = $_GET ;
    }
}

function format_numbers($number){
    /*
        Format Numbers in Thousands and Millions

        Args:
            $number (int): the number to format

        Returns:
            str : The formatted number with (K) or (M)
    */

    if ($number >= 1000000) {
        $number = $number / 1000000 . 'M' ;
    } elseif ($number >= 1000) {
        $number = $number / 1000 . 'K' ;
    }
    return $number ;
}

function display_dif($result, $values, $direction)
{
    global $total_to_compare ;

    if ($total_to_compare == 2) {
        if ($result > 0) {
            $res = diff_res($values, $direction) ;
        } else {
            $res = 0 ;
        }
        $extra = "class='diff_reg'" ;
        if ($res > 10) {
            $extra = "class='diff_pos'" ;
        }
        if ($res < -10) {
            $extra = "class='diff_neg'" ;
        }
        TD(4, $extra, number_format($res, 2)) ;
    }
}

function get_volume_type($input_data) {
    $vol = explode("-", $input_data) ;
    $vol = end($vol);
    if (strtoupper($vol) == "THICK") { $vol = "RBD-THICK" ; }
    if ($vol == "CephBlockPool") { $vol = "RBD" ; }
    if ($vol == "CephFileSystem") { $vol = "CephFS" ; }
    return $vol ;

}

function set_row_span($num_of_tests){
    if ($num_of_tests > 1) {
        return  2 ;
    } else {
        return 1 ;
    }
}

function set_col_span($num_of_tests){
    if ($num_of_tests == 2) {
        return  3 ;
    } else {
        return $num_of_tests ;
    }
}
