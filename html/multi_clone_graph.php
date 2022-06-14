<?php

require_once('jpgraph/jpgraph.php');
require_once('jpgraph/jpgraph_line.php');
require_once 'main.php';

function creation_multi_clone_graph()
{
    $pcolors = array("#FBFB06","#06FB58","#1111cc","#cc1111");

    // Create the graph. These two calls are always required
    $graph = new Graph(650, 450, 'auto');
    $graph->SetScale("textlin");

    $theme_class=new UniversalTheme();
    $graph->SetTheme($theme_class);

    $graph->SetBox(false);

    $graph->SetScale("intlin",0,0,0,512);

    $title = "Clones creation time on " . $_GET['interface']  ;
    $graph->title->Set($title);
    $graph->title->SetFont(FF_FONT2, FS_BOLD);

    $graph->ygrid->SetFill(false);
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false, false);
    $graph->yaxis->SetLabelFormatCallback('format_numbers') ;
    $graph->yaxis->title->Set("Time (Sec.)");     
    $graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD); 

    $graph->xgrid->SetLineStyle("solid");
    $graph->xaxis->HideLine(false);
    $graph->xaxis->HideTicks(false, false);
    $graph->xaxis->title->Set("Clone Number"); 
    $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD); 

    // Create the line plots
    $bplots = array() ;
    for ($i = 1 ; $i <= $_GET['cnt'] ; $i++) {
        $ind = "ds" . $i ;
        $data = explode(',', $_GET[$ind]) ;
        $bplots[$i-1] = new LinePlot($data) ;
        $bplots[$i-1]->SetLegend('Test' . $i) ;
    }

    // ...and add it to the graPH
    $graph->Add($bplots);

    $graph->legend->SetColor('blue');
    $graph->legend->SetColumns($_GET['ds1']);
    $graph->legend->Pos(0.53, 0.99, 'center', 'bottom');
    $graph->legend->SetFillColor('white');
    $graph->legend->SetLayout(LEGEND_HOR);

    // Display the graph
    $graph->Stroke();
}

creation_multi_clone_graph();
