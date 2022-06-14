<?php
// content="text/plain; charset=utf-8"
require_once 'jpgraph/jpgraph.php';
require_once 'jpgraph/jpgraph_bar.php';
require_once 'main.php';

function creation_smallfiles_graph()
{
    $pcolors = ['#FBFB06', '#06FB58', '#1111cc', '#cc1111'];

    // Create the graph. These two calls are always required
    $graph = new Graph(520, 300, 'auto') ;
    $graph->SetScale('textint') ;

    $theme_class = new UniversalTheme;
    $graph->SetTheme($theme_class) ;

    $graph->SetBox(false) ;

    $graph->ygrid->SetFill(false) ;

    $xtick = explode(',', $_GET['op']) ;
    $graph->xaxis->SetTickLabels($xtick) ;
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false, false) ;
    $graph->yaxis->SetLabelFormatCallback('format_numbers') ;

    // Create the bar plots
    $bplots = [] ;
    for ($i = 1 ; $i <= $_GET['dsc'] ; $i++) {
        $ind = 'ds' . $i ;
        $data = explode(',', $_GET[$ind]) ;
        $bplots[$i - 1] = new BarPlot($data) ;
    }

    // Create the grouped bar plot
    $gbplot = new GroupBarPlot($bplots);

    // ...and add it to the graph
    $graph->Add($gbplot);

    for ($i = 0 ; $i < $_GET['dsc'] ; $i++) {
        $ind = $i + 1 ;
        $bplots[$i]->SetColor('white') ;
        $bplots[$i]->SetFillColor($pcolors[$i]) ;
        $bplots[$i]->SetLegend('Test' . $ind) ;
    }

    $title = 'SmallFiles Files/Second on ' . $_GET['voltype'] . ' with' ;
    $subtitle = explode('_', $_GET['key']) ;
    $subtitle = $subtitle[1] . 'K Blocksize & ' . $subtitle[2] . ' Files.' ;
    $graph->title->Set($title) ;
    $graph->title->SetFont(FF_FONT2, FS_BOLD) ;
    $graph->subtitle->Set($subtitle) ;
    $graph->subtitle->SetFont(FF_FONT2, FS_BOLD) ;

    $graph->legend->SetColor('blue') ;
    $graph->legend->SetColumns($_GET['dsc']) ;
    $graph->legend->Pos(0.53, 0.99, 'center', 'bottom') ;
    $graph->legend->SetFillColor('white') ;
    $graph->legend->SetLayout(LEGEND_HOR) ;

    // Display the graph
    $graph->Stroke();
}

creation_smallfiles_graph();
