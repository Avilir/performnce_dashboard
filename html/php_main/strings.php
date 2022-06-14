<?php
/**
 * This module it intend to have strings functions
*/

function multi_chars($ch, $count=1)
{
    /**
     * This function return string with the same character multiple times
     *
     * Args:
     *     $ch (char): the character to multiply
     *     $count (int): number of time to multiply the $chr - default is 1
     * Returns:
     *     string : multiple character string
    */
    $res = "" ;
    for ($x = 1; $x <= $count; $x++) {
        $res .= $ch ;
    }
    return $res ;
}

function NL($count=1)
{
    /**
     * This function return string with multiple (1 by default) of NewLine
     * 
     * Args:
     *     $count (int): number of times to multiply the NewLine - default is 1
    */
    echo multi_chars("\n", $count) ;
}

function Tab($count=1)
{
    /**
     * This function return string with multiple (1 by default) of Tab
     * 
     * Args:
     *     $count (int): number of times to multiply the Tab - default is 1
    */
    echo  multi_chars("\t", $count) ;
}

function Space($count=1)
{
    /**
     * This function return string with multiple (1 by default) of Space
     *   
     * Args:
     *     $count (int): number of times to multiply the Space - default is 1
    */
    echo multi_chars(" ", $count) ;
}
