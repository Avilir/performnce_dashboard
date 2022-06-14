<?php
/**
 * This module it intend to have Tables related tag string
*/
/** ============================= Table Tags =============================== */
function start_table($tabs, $class = '')
{
    /**
     * Start a Table
     *
     * Args:
     *     $tabs (int): number of tabs for indentation
     *     $class (str): The class of this tag.
    */
    if ($class != '') {
        $title = "class='" . $class . "'" ;
    } else {
        $title = 'table' ;
    }
    start_tag('table', Tab($tabs), true, $title) ;
}

function close_table($tabs)
{
    /**
     * Closing a Table
     *
     * Args:
     *     $tabs (int): number of tabs for indentation
    */
    close_tag('table', Tab($tabs), true) ;
}
/** ============================= TR Tags ================================== */
function start_tr($tabs = 0, $extra = '')
{
    /**
     * Opening of TR tag (Table row)
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('tr', Tab($tabs), true, $extra) ;
}

function close_tr($tabs = 0)
{
    /**
     * Closing of TR tag (Table row)
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
    */
    close_tag('tr', Tab($tabs), true) ;
}
/** ============================= TH Tags ================================== */
function start_th($tabs = 0, $extra = '', $nl = false)
{
    /**
    * Opening of TH tag (Table header row)
    *
    * Args:
    *     $tabs (int): number of tabs for indentation default is 0
    *     $extra (str): extra data in the tag - used for adding class / ID etc.
    *     $nl (bool): print NewLine in the end ? default is 'false'.
    */
    start_tag('th', Tab($tabs), $nl, $extra) ;
}

function close_th($tabs = 0)
{
    /**
     * Closing of TH tag (Table header row)
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
    */
    close_tag('th', Tab($tabs), true) ;
}
/** ============================= TD Tags ================================== */
function start_td($tabs = 0, $extra = '', $nl = false)
{
    /**
     * Opening of TD tag (Table cell)
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
     *     $nl (bool): print NewLine in the end ? default is 'false'.
    */
    start_tag('td', Tab($tabs), $nl, $extra) ;
}

function close_td($tabs = 0)
{
    /**
     * Closing of TD tag (Table cell)
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
    */
    close_tag('td', Tab($tabs), true) ;
}
