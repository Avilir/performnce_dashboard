<?php

// content="text/plain; charset=utf-8"
require_once('jpgraph/jpgraph.php');
require_once('jpgraph/jpgraph_bar.php');
require_once 'main.php';

//header("Content-Type: image/png");

function creation_fio_graph()
{
    $pcolors = array("#FBFB06","#06FB58","#1111cc","#cc1111");

    // Create the graph. These two calls are always required
    $graph = new Graph(520, 300, 'auto');
    $graph->SetScale("textlin");

    $theme_class=new UniversalTheme();
    $graph->SetTheme($theme_class);

    $graph->SetBox(false);

    $graph->ygrid->SetFill(false);
    $xtick = explode(",", $_GET['bs']) ;
    $graph->xaxis->SetTickLabels($xtick);
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false, false);
    $graph->yaxis->SetLabelFormatCallback('format_numbers') ;

    // Create the bar plots
    $bplots = array() ;
    for ($i = 1 ; $i <= $_GET['dsc'] ; $i++) {
        $ind = "ds" . $i ;
        $data = explode(',', $_GET[$ind]) ;
        $bplots[$i-1] = new BarPlot($data) ;
    }

    // Create the grouped bar plot
    $gbplot = new GroupBarPlot($bplots);

    // ...and add it to the graPH
    $graph->Add($gbplot);

    for ($i = 0 ; $i < $_GET['dsc'] ; $i++) {
        $ind=$i+1;
        $bplots[$i]->SetColor("white");
        $bplots[$i]->SetFillColor($pcolors[$i]) ;
        $bplots[$i]->SetLegend('Test' . $ind) ;
    }

    $title = $_GET['pattern'] . " IOPS on " . $_GET['voltype'] ;
    $graph->title->Set($title);
    $graph->title->SetFont(FF_FONT2, FS_BOLD);

    $graph->legend->SetColor('blue');
    $graph->legend->SetColumns($_GET['dsc']);
    $graph->legend->Pos(0.53, 0.99, 'center', 'bottom');
    $graph->legend->SetFillColor('white');
    $graph->legend->SetLayout(LEGEND_HOR);

    // Display the graph
    $graph->Stroke();
}
creation_fio_graph();
