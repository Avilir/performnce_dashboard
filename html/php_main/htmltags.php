<?php
/**
 * This module it intend to have HTML tag strings function
 */

require_once 'strings.php';
require_once 'table_tags.php';
require_once 'select_tags.php';

function tag($tag_name, $op, $pref = '', $nl = false, $extra = '')
{
    /**
     * This function print an HTML tag.
     *
     * Args:
     *   $tag_name (str): the tag name
     *   $op (str): which kind of tag - open / close
     *   $pref (str): prefix to print before the tag - for indentation
     *   $nl (bool): print NewLine at the end ? - default : false (no)
     *   $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    echo $pref . '<' ;
    if ($op == 'close') {
        echo '/' ;
    }
    echo $tag_name ;
    if ($extra != '') {
        echo ' ' . $extra ;
    }
    echo '>' ;
    if ($nl == true) {
        NL() ;
    }
}

function start_tag($tag, $pref, $nl, $extra = '')
{
    /**
     * This function is generic `start` tag.
     *
     * Args:
     *     $tag (str): the tag name
     *     $pref (str): prefix to print before the tag - for indentation
     *     $nl (bool): print NewLine at the end ? - default : false (no)
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    tag($tag, 'start', $pref, $nl, $extra) ;
}

function close_tag($tag, $pref, $nl)
{
    /**
     * This function is generic `close` tag.
     *
     * Args:
     *     $tag (str): the tag name
     *     $pref (str): prefix to print before the tag - for indentation
     *     $nl (bool): print NewLine at the end ? - default : false (no)
    */
    tag($tag, 'close', $pref, $nl) ;
}

function doctype($html_ver)
{
    /**
     * Print the doctype content for the browser.
     *
     * Args:
     *     $html_ver (str): html version - 4 / 5 / 1.1 (for xhtml)
    */
    if ($html_ver == '5') {
        $public = 'html' ;
        $link = '' ;
    }
    if ($html_ver == '4') {
        $public = 'HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" ' ;
        $link = '"http://www.w3.org/TR/html4/loose.dtd"' ;
    }
    if ($html_ver == '1.1') {
        $public = 'html PUBLIC "-//W3C//DTD XHTML 1.1//EN" ' ;
        $link = '"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"' ;
    }
    echo '<!DOCTYPE ' . $public . $link . '>' ;
    NL() ;
}
/** ============================= HTML Tags ================================ */
function start_html($extra = '')
{
    /** Starting the HTML document */
    start_tag('html', '', true, $extra) ;
}

function close_html()
{
    /** Closing the HTML document */
    close_tag('html', '', true) ;
}
/** ============================= Head Tags ================================ */
function start_head($extra = '')
{
    /**
     * Starting the HTML head section
     *
     * Args:
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('head', Tab(1), true, $extra) ;
}

function close_head()
{
    /** Closing the HTML head section */
    close_tag('head', Tab(1), true) ;
}
/** ============================= JavaScript Tags ========================== */
function Jscript($script_name, $tabs = 2)
{
    /**
     * Add a javascript file into the html file.
     *
     * Args:
     *     $script_name (str): the script file name
     *     $tabs (int): number of tabs for indentation, default is 2
    */
    $extra = 'type="text/javascript" src="' . $script_name . '"' ;
    start_tag('script', Tab($tabs), false, $extra) ;
    close_tag('script', '', true) ;
}
/** ============================= Title Tag ================================ */
function title($title, $tabs = 2)
{
    /**
     * Add a title into the html file.
     *
     * Args:
     *     $title (str): the title to add
     *     $tabs (int): number of tabs for indentation, default is 2
    */
    start_tag('title', Tab($tabs), false) ;
    echo $title ;
    close_tag('title', '', true) ;
}
/** ============================= CSS Tags ================================= */
function add_css($css, $tabs = 2)
{
    /**
     * Add a css file into the html file.
     *
     * Args:
     *     $css (str): the css file to add
     *     $tabs (int): number of tabs for indentation, default is 2
    */
    Tab($tabs) ;
    echo "<link rel='stylesheet' href='" . $css . "' />" ;
    NL();
}
/** ============================= Body Tags ================================ */
function start_body($tabs = 1, $extra = '')
{
    /**
     * Starting the body of the html file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 2
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('body', Tab($tabs), true, $extra) ;
}

function close_body($tabs = 1)
{
    /**
     * Closing the body of the html file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 2
    */
    close_tag('body', Tab($tabs), true) ;
}
/** ============================= Div Tags ================================= */
function start_div($tabs = 0, $extra = '')
{
    /**
     * Starting DIV section in the HTML file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('div', Tab($tabs), true, $extra) ;
}

function close_div($tabs = 0)
{
    /**
     * Closing DIV section in the HTML file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
    */
    close_tag('div', Tab($tabs), true) ;
}
/** ============================= Span Tags ================================ */
function start_span($tabs = 0, $extra = '')
{
    /**
     * Starting SPAN section in the HTML file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('span', Tab($tabs), true, $extra) ;
}

function close_span($tabs = 0)
{
    /**
     * Closing SPAN section in the HTML file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
    */
    close_tag('span', Tab($tabs), true) ;
}
/** ============================= Form Tags ================================ */
function start_form($tabs = 0, $extra = '')
{
    /**
     * Starting Form section in the HTML file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    start_tag('form', Tab($tabs), true, $extra) ;
}

function close_form($tabs = 0)
{
    /**
     * Closing Form section in the HTML file.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
    */
    close_tag('form', Tab($tabs), true) ;
}

function submit($name, $value, $tabs)
{
    /**
     * Adding Submit button to a form.
     *
     * Args:
     *     $name (str): the name for the button
     *     $value (str): the value to submit
     *     $tabs (int): number of tabs for indentation, no default
    */
    Tab($tabs) ;
    echo "<input type='submit' name='" . $name . "' value='" . $value . "' />" ;
    NL() ;
}

function reset_b($name, $value, $tabs)
{
    /**
     * Adding Reset button to a form.
     *
     * Args:
     *     $name (str): the name for the button
     *     $value (str): the value to submit
     *     $tabs (int): number of tabs for indentation, no default
    */
    Tab($tabs) ;
    echo "<input type='reset' name='" . $name . "' value='" . $value . "' />" ;
    NL() ;
}
/** ============================= Anchor Tags ============================== */
function start_anchor($link, $tabs = 0, $nl = false)
{
    /**
     * Starting Anchor tag.
     *
     * Args:
     *     $link (int): the link of the Anchor
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $nl (bool): print NewLine in the end ? default is 'false'.
    */
    start_tag('a', Tab($tabs), $nl, "href='" . $link . "'") ;
}

function close_anchor($tabs = 0, $nl = true)
{
    /**
     * Closing Anchor tag.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $nl (bool): print NewLine in the end ? default is 'true'.
    */
    close_tag('a', Tab($tabs), $nl) ;
}

function anchor($title, $link, $tabs = 0, $nl = true)
{
    /**
     * Full Anchor tag.
     *
     * Args:
     *     $title (str) title to display between the tags
     *     $link (int): the link of the Anchor
     *     $tabs (int): number of tabs for indentation, default is 0
     *     $nl (bool): print NewLine in the end ? default is 'true'.
    */
    start_anchor($link, $tabs, false) ;
    echo $title ;
    close_anchor($tabs = 0, $nl) ;
}
/** ============================= Image Tag ================================ */
function IMG($img, $extra = '')
{
    /**
     * An Image tag.
     *
     * Args:
     *     $img (str): the image source
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    echo "<img src='" . $img . "'" ;
    if ($extra != '') {
        echo ' ' . $extra ;
    }
    echo '>' ;
}
/** ============================= BR Tag =================================== */
function BR($pref, $count, $nl)
{
    /**
     * BR tag - this is an HTML new line.
     *
     * Args:
     *     $pref (int): number of tabs for indentation, no default
     *     $count (int): number of tags to print
     *     $nl (bool): print NewLine in the end ?
    */
    Tab($pref) ;
    echo multi_chars('</br>', $count) ;
    if ($nl == true) {
        NL() ;
    }
}
/** ============================= BR Tag =================================== */
function HR($tabs)
{
    /**
     * HR tag - this is an HTML horizontal line.
     *
     * Args:
     *     $tabs (int): number of tabs for indentation, no default
    */
    start_tag('hr /', Tab($tabs), true) ;
}
/** ============================= NBSP Tag ================================= */
function nbsp($count = 1)
{
    /**
     * NBSP tag - this is an HTML space.
     *
     * Args:
     *     $count (int): number of spaces to display, default is 1.
    */
    echo multi_chars('&nbsp;', $count) ;
}
/** ============================= H Tag ==================================== */
function H($num = 1, $title = '', $pref = 0, $extra = '')
{
    /**
     * H tag - this is an HTML Document Header line.
     *
     * Args:
     *     $num (int): the header number (1-6) default is 1
     *     $title (str): the text in the header line
     *     $pref (int): number of tabs for indentation, default is 0
     *     $extra (str): extra data in the tag - used for adding class / ID etc.
    */
    Tab($pref) ;
    echo '<h' . $num ;
    if ($extra != '') {
        echo ' ' . $extra ;
    }
    echo '>' . $title ;
    close_tag('h' . $num, '', true) ;
}
