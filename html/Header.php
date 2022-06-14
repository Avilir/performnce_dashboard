<?php
/**
 * This module have the PHP header function which display the main Banner of the page
 * 
 */

 require_once 'php_main/htmltags.php';

function display_header()
{
    /**
     * Display the Performance dashboard header,
     * the HTML head and the Beginning of the body
    */
    start_html("lang=en") ;

    start_head() ;
    title('QPas Dashboard') ;

    // Adding all css files
    add_css("CSS/main.css") ;
    add_css("CSS/header.css") ;
    add_css("CSS/tests.css") ;
    add_css("CSS/tabs.css") ;

    //  Adding Javascript files
    Jscript("js/main.js") ;

    echo Tab(2) ;
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' ;
    NL(1) ;
    close_head() ;

    start_body() ;
    start_div(2, "id='header'") ;

    start_span(3, "class='logo_span'") ;
    start_anchor('index.php', 4, false) ;
    IMG("img/logo.png") ;
    close_anchor() ;
    close_span(3) ;

    start_span(3, "class='head_span'") ;
    H(1, "The QPAS Performance Dashboard", 4) ;
    close_span(3) ;

    close_div(2) ;  // The header div
    NL() ;
    br(2, 2, true) ;
    NL() ;
}
