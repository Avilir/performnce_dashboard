<?php
/*
    This file contain functions which use to display the main site form(s).

        * selecting the type of report :
            - Comparison report -> new comparison form
            - Full report on the Last version -> platform -> topology
            - Comparison report between runs on Last Build of last Version
                -> platform -> topology

        * for comparison report, user can select the environment to compare :
            Version , build , platform , topology , test(s)
            up to 4 different test(s)
*/

function print_select_option($title, $name, $values)
{
    /*
        This function display a 'select' field for a particular table in the DB

        Args:
            $title (str): which title to display in the select field as firs option
                          can not be selected.
            $name (str): the name for the field in the URL - for reselect the value
            $values (str): the table name in the DB
    */
    global $input_data;

    // Check if data ned to be re-select
    $prev = 0;
    if (!empty($input_data[$name])) {
        $prev = $input_data[$name];
    }

    // Reading all relevant data from the DB
    $data = get_select_values($values);

    $extra = 'name=' . $name;
    if ($name == 'test_name') {
        $extra .= '[] multiple="multiple" size="6"';
    }
    start_select(7, $nl = true, $extra);
    select_option('Choose ' . $title, "disabled selected value=''", $tabs = 8);
    if ($data->num_rows > 0) {
        while ($row = $data->fetch_array()) {
            if ($name == 'test_name' && $row['name'] == "PVC Multiple-Delete") { 
                continue ; 
            }
            $extra = 'value=' . $row['id'];
            if ($row['id'] == $prev) {
                $extra .= " selected='selected'";
            }
            select_option($row['name'], $extra, $tabs = 8);
        }
    }
    close_select(7);
}

function print_version_select($line)
{
    /*
        This function display the version select field.

        Args:
            $line (int): the number of test (in the comparison form) to display
    */
    global $All_Vers;
    $name = 'version' . $line;
    $extra = 'name=' . $name . ' onchange="ReloadFormValues()"';
    start_select(7, $nl = true, $extra);
    select_option('Select ODF Version', "value=''", $tabs = 8);

    // Reading all relevant data from the DB
    $res = get_select_values('versions', $sort = true);
    foreach ($res as $op_data) {
        if ($op_data['id'] == $All_Vers[$line]) {
            select_option($op_data['name'], "selected value='$op_data[id]'", $tabs = 8);
        } else {
            select_option($op_data['name'], "value='$op_data[id]'", $tabs = 8);
        }
    }
    close_select(7);
}

function print_build_select($line)
{
    /*
        This function display the build select field correlating to the version selected.

        Args:
            $line (int): the number of test (in the comparison form) to display
    */
    global $All_Vers;
    global $input_data;
    $prev = 0;
    $key = 'build' . $line;
    if (!empty($input_data[$key])) {
        $prev = $input_data[$key];
    }

    // Reading all relevant data from the DB
    if ($All_Vers[$line]) {
        $data = get_builds($All_Vers[$line]);
    }
    start_select(7, $nl = true, $extra = "name='" . $key . "'");
    select_option('Select ODF Build', "value=''", $tabs = 8);
    if (!$data) {
        close_select(7);
        return;
    }
    if ($data->num_rows > 0) {
        foreach ($data as $op_data) {
            $extra = 'value=' . $op_data['id'];
            if ($op_data['id'] == $prev) {
                $extra .= " selected='selected'";
            }
            select_option($op_data['name'], $extra, $tabs = 8);
        }
    }
    close_select(7) ;
}

function display_select_form_line($line)
{
    /*
        This function display a test selection line in the comparison form

        Args:
            $line (int) : the number of the test to compare
    */
    start_tr(5, "class='select_tr'");
    start_td(6, "class='select_td'");
    echo 'Test number ' . $line;
    if ($line == 1) {
        echo ' (Base)';
    }
    echo ' : ';
    NL(1);

    print_version_select($line);
    print_build_select($line);

    print_select_option('HW Platform', 'platform' . $line, 'platform');
    print_select_option('AZ Topology', 'az_topology' . $line, 'az_topology');
    close_td(6);
    if ($line == 1) {
        start_td(6, "class='select_td' rowspan='" . $GLOBALS['Compare_tests'] . "'", true);
        print_select_option('Test Name', 'test_name', 'tests');
        close_td(6);
    }
    close_tr(5);
}

function display_select_form()
{
    /*
        This function display the main comparison selection form of the
        results to display / compare
    */
    global $input_data;

    start_div(2, 'class=selection_form');
    start_form(3, "id='main_form' action='' method='get'");
    start_table(4, 'select_tbl');
    for ($line = 1 ; $line <= $GLOBALS['Compare_tests'] ; $line++) {
        display_select_form_line($line);
    }
    close_table(4);
    submit('submit', 'Choose options', 4);
    close_form(3);
    close_div(2);
    HR(2) ;
    // if this is the first time, remove the submit from the URL
    if (isset($input_data['compare'])) {
        unset($input_data['submit']);
    }
}

function select_build_runs($line)
{
    /*
        This function display the select platform for last build / version test(s) results

        Args:
            $line (int): 0 - last build / 1 - last version
    */
    global $All_Vers;
    global $input_data;
    $prev = 0;
    $key = 'platform' . $line;
    $extra = 'name=' . $key;
    if ($line == 0) {
        $name = 'build_run';
        $extra .= ' onchange="LastBuildRun()"';
    } elseif ($line == 1) {
        $name = 'ver_repo' ;
        $extra .= ' onchange="LastVerReport()"';
    }
    if (!empty($input_data[$name])) {
        $prev = $input_data[$name];
    }
    start_select(7, $nl = true, $extra);
    select_option('Select HW Platform', "value=''", $tabs = 8);
    $res = get_select_values('platform', $sort = true);
    foreach ($res as $op_data) {
        if ($op_data['id'] == $prev) {
            select_option($op_data['name'], "selected value='$op_data[id]'", $tabs = 8);
        } else {
            select_option($op_data['name'], "value='$op_data[id]'", $tabs = 8);
        }
    }
    close_select(7);
}

function select_topology()
{
    /*
        This function display the select topology drop box in the initial form
    */
    global $All_Vers;
    $name = 'az_topology1';
    $extra = 'name=' . $name;
    $extra .= ' onchange="full_report()"';
    start_select(7, $nl = true, $extra);
    select_option('Select AZ Topology', "value=''", $tabs = 8);
    $res = get_select_values('az_topology', $sort = true);
    foreach ($res as $op_data) {
        if ($op_data['id'] == $All_Vers) {
            select_option($op_data['name'], "selected value='$op_data[id]'", $tabs = 8);
        } else {
            select_option($op_data['name'], "value='$op_data[id]'", $tabs = 8);
        }
    }
    close_select(7);
}

function display_initial_form()
{
    /* This function display the initial form  */
    global $input_data;
    display_select_form();
    return;
    if (isset($input_data['submit']) || isset($input_data['version1'])) {
        if (isset($input_data['compare']) || isset($input_data['version1'])) {
            display_select_form();
        }
    } else {
        start_div(2, 'class=selection_form');
        start_form(3, "id='init_form' action='' method='get'");
        start_table(4, 'select_tbl');
        start_tr(5, "class='select_tr'");

        start_td(6, "class='select_td'", true);
        Tab(7);
        echo 'Comparison report :';
        NL(1);
        Tab(7);
        echo '<input type="radio" name="compare" id="compare" value="compare" onchange="Compare()" />';
        NL(1);
        Tab(7);
        nbsp(3);
        echo '/';
        nbsp(3);
        NL(1);
        close_td(6);

        start_td(6, "class='select_td'");
        echo 'Last Version report :';
        NL(1);
        select_build_runs(1);
        Tab(7);
        nbsp(3);
        echo '/';
        nbsp(3);
        NL(1) ;
        close_td(6);

        start_td(6, "class='select_td'");
        echo 'Last build run(s) :';
        NL(1);
        select_build_runs(0);
        close_td(6);
        NL(1);

        close_tr(5);

        if (isset($input_data['build_run']) || isset($input_data['ver_repo'])) {
            start_tr(5, "class='select_tr'");
            start_td(6, "class='select_td'");
            nbsp();
            close_td();

            start_td(6, "class='select_tbl' colspan=2", true);
            echo 'Report Topology :';
            NL(1);
            select_topology();
            close_td();

            close_tr(5);
        }
        close_table(4);
        close_form(3);
        close_div(2);
    }
}
