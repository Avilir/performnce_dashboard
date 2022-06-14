<?php
/**
 * This module contain the HTML Select tag functions
 */

function start_select($tabs, $nl = true, $extra = '')
{
    /**
     * Opening of select option section
     *
     * Args:
     *     $tabs (int): number of tabs for indentation
     *     $nl (bool): print NewLine in the end ? default is 'true'.
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('select', Tab($tabs), $nl, $extra) ;
}

function close_select($tabs, $nl = true)
{
    /**
     * Closing the select option section
     *
     * Args:
     *     $tabs (int): number of tabs for indentation
     *     $nl (bool): print NewLine in the end ? default is 'true'.
    */
    close_tag('select', Tab($tabs), $nl) ;
}

function select_option($title, $extra, $tabs = 0)
{
    /**
     * Adding an option to select section
     *
     * Args:
     *     $title (str): the option title'.
     *     $extra (str): extra data in the tag - used for adding class / ID
     *                   and the option value.
     *     $tabs (int): number of tabs for indentation
    */
    start_tag('option', Tab($tabs), false, $extra) ;
    echo $title ;
    close_tag('option', '', true) ;
}
