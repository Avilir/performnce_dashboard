<?php

require_once 'vendor/autoload.php';
use Elastic\Elasticsearch\ClientBuilder ;

require_once 'config.php';
require_once 'secret.php';
require_once 'main.php';

require_once 'php_main/htmltags.php';
require_once 'db.php';
require_once 'main_form.php';
require_once 'display_config.php';
require_once 'fio.php';
require_once 'smallfiles.php';
require_once 'pvc_cd_test_results.php';

function display_header()
{
    /*
        Display the Performance dashboard header,
        the HTML head and the Beginning of the body
    */

    start_html('lang=en') ;

    start_head() ;
    title('QPas Dashboard') ;

    // Adding all css files
    add_css('CSS/main.css') ;
    add_css('CSS/header.css') ;
    add_css('CSS/tests.css') ;
    add_css('CSS/tabs.css') ;

    //  Adding Javascript files
    Jscript('js/main.js') ;

    echo Tab(2) ;
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' ;
    NL(1) ;
    close_head() ;

    start_body() ;
    start_div(2, "id='header'") ;

    start_span(3, "class='logo_span'") ;
    start_anchor('index.php', 4, false) ;
    IMG('img/logo.png') ;
    close_anchor() ;
    close_span(3) ;

    $Title = 'The QPAS Performance Dashboard' ; 
    if ($GLOBALS['dev_mode']) {
        $Title = $Title . " - Development" ;
    }

    start_span(3, "class='head_span'") ;
    H(1, $Title, 4) ;
    if ($GLOBALS['dev_mode']) {
        H(4, 'Connecting to DB on : ' . $GLOBALS['host'], 4) ;
    }
    close_span(3) ;

    close_div(2) ;  // The header div
    NL() ;
    BR(2, 2, true) ;
    NL() ;
}

function no_test_found()
{
    /*
        This function display en Error message, when there are no results to display
        or when there are no results to compare (only partial results exists)
    */

    BR(3, 2, true) ;
    H(2, 'No results are available for this configuration, or for comparison !', 3) ;
}

function validate_input($name, $key, $line = 0)
{
    /*
        This function validate that a specific key have input data

        Args:
            $name (str): The name of the variable to verify - use for display only
            $key (str):  The name of the field in the form (and in query string)
            $line (int): the number of the tets (in comparison mode)
                        default is 0 for one test display (no comparison)

                        Return:
            bool : true if data is valid, otherwise false
    */

    global $All_sql ;        // array of all comparison tests [size = $Compare_tests]
    global $input_data ;     // the URL query string
    global $OK_to_display ;  // is it ok to display the results

    // The field 'test_name' is uniq in the form and not individual for a single line
    if ($key != 'test_name') {
        $thekey = $key . $line ;
    } else {
        $thekey = $key ;
    }

    if (!empty($input_data[$thekey])) {
        if ($key != 'test_name') {
            $data = $input_data[$thekey];
            $s = ' `' . $key . '` = ' . $data ;
            $All_sql[$line] .= $s ;
        }
        return true ;
    } else {
        if ($line > 1 and $name == 'Version') {
            return false ;
        } else {
            $OK_to_display = false ;
            echo Tab(2) ;
            echo 'Please select a ' . $name . ' for test number ' . $line . '.' ;
            BR(0, 1, true) ;
        }
    }
}

// no need to change anything in this function
function test_version_select()
{
    /*
        This function return array with all comparison tests version num.
    */
    $all_vers = [] ;
    for ($ind = 1 ; $ind <= $GLOBALS['Compare_tests'] ; $ind++) {
        @$all_vers[$ind] = $_GET['version' . $ind] ;
        if ($all_vers[$ind] && strlen($all_vers[$ind])) {
            if ((strlen($all_vers[$ind]) > 0) and !is_numeric($all_vers[$ind])) {
                echo 'Data Error' ;
                exit() ;
            }
    
        }
    }
    return $all_vers ;
}

function get_result_data($sql)
{
    /*
    This function return test results from thr ES server

    Args:
        $sql (str): the sql query to run
    */
    global $total_to_compare;
    global $client;

    $my_test_res = $GLOBALS['conn']->query($sql);

    $results = []; // The main results array
    if ($my_test_res->num_rows > 0) {
        // Initializing the test results array as array os arrays
        for ($i = 1 ; $i <= $my_test_res->num_rows ; $i++) {
            $results[$i] = [];
        }

        $i = 0 ;
        // Running for each sample results for the same test
        while ($row = $my_test_res->fetch_array()) {
            $i++;

            $es_indexs = explode(',', $row['es_link']);
            $ind = 0 ;
            foreach ($es_indexs as $esq) {
                $ind++;
                $pieces = explode('/', $esq);
                $es_index = $pieces[3];
                $es_uuid = explode(':', $pieces[4]);
                $es_uuid = $es_uuid[1] ;
                $hosts = [$pieces[2]] ;
                $client = ClientBuilder::create()->setHosts($hosts)->build();

                $params = ['index' => $es_index, 'type' => '_doc', 'body' => ['query' => ['match' => ['uuid' => $es_uuid]]]];

                $response = $client->search($params);
                if (count($response['hits']['hits']) > 0) {
                    $results[$i][$ind] = $response['hits']['hits'][0]['_source'] ;
                    $results[$i][$ind]['es_link'] = $esq ;
                    $results[$i][$ind]['log_link'] = $row['log_file'] ;
                } else {
                    $results[$i][$ind] = [] ;
                }
            }
        }
    }
    return $results ;
}

function verify_all_inputs($ind)
{
    /*
    Verify all the selected data are validate and generate
    sql query for each comparison tests.
    all queries will be in the Global variable : $All_sql

    Args:
        $ind (int): the number of the test to verify (1 - total_to_compare)
    */

    global $All_sql;
    // initiate the sql quarry
    $All_sql[$ind] = 'SELECT log_file,es_link FROM `results` WHERE';

    if (validate_input('Version', 'version', $ind) == true) {
        $All_sql[$ind] .= ' AND ';
        validate_input('Build', 'build', $ind);
        $All_sql[$ind] .= ' AND ';
        validate_input('Platform', 'platform', $ind);
        $All_sql[$ind] .= ' AND ';
        validate_input('Availability-Zone topology', 'az_topology', $ind);
        $All_sql[$ind] .= ' AND ';
        validate_input('Test name', 'test_name', $ind);
    }
}

function read_full_test_results($testid)
{
    global $input_data;
    global $test_results;
    global $All_sql;
    global $conn;
    global $total_to_compare;
    global $all_res_data;
    $OK = true;
    $total_to_compare = 0;

    for ($ind = 1 ; $ind <= $GLOBALS['Compare_tests'] ; $ind++) {
        $sql = '';
        if (substr($All_sql[$ind], -1) == ' ') {
            $sql = $All_sql[$ind] . 'test_name = ' . $testid ;
            if (!(isset($input_data['build_run']) && $input_data['build_run'] != 'null')) {
                $sql .= ' AND sample = 0';
            }
        } else {
            $sql = $All_sql[$ind] ;
        }
        if ($sql != '') {
            try {
                $test_results[$ind] = $conn->query($sql);
            } catch (Exception $ex) {
                $test_results[$ind] = null ;
            }
           if ($test_results[$ind] != null) {
                $num_of_res = $test_results[$ind]->num_rows ;
                if ($ind == 1 and $num_of_res > 1 and $GLOBALS['Compare_tests'] > 1) {
                    if (!(isset($input_data['build_run']) && $input_data['build_run'] != 'null')) {
                        H(2, 'There are more then one test results [' . $num_of_res . '] - Can not make comparison') ;
                        $OK = false ;
                    }
                }
                if ($num_of_res == 0) {
                    $OK = false ;
                }
                $total_to_compare++ ;
                $all_res_data[$ind] = get_result_data($sql) ;
                for ($i = 1 ; $i <= count($all_res_data[$ind]) ; $i++) {
                    if (count($all_res_data[$ind][$i]) < 1) {
                        $OK = false ;
                    }
                }
            }
        }
    }
    return $OK ;
}

function display_tests_tabs($tests)
{
    // Build the tests result tabs
    start_div(2, 'class="tab"') ;
    for ($i = 0 ; $i < count($tests) ; $i++) {
        $test_name = get_test_name($tests[$i]) ;
        echo Tab(3) ;
        echo '<button class="tablinks" onclick="openTest(event, \'' . $test_name . '\')">' . $test_name . "</button>\n" ;
    }
    close_div(2) ;
}

function create_version_report()
{
    global $input_data ;
    global $All_sql ;

    $platform = get_name_by_id('platform', $input_data['ver_repo']) ;
    $last_ver = get_last_version() ;
    $last_build = get_latest_build($last_ver[1]);
    $topology = get_name_by_id('az_topology', $input_data['az_topology']) ;
    H(3, 'Latest version [' . $last_ver[0] . '] report on : ' . $platform . ' (' . $topology . ')') ;
    $all_tests = get_tests_list() ;
    $input_data['version1'] = $last_ver[1] ;
    $input_data['build1'] = $last_build[1];
    $input_data['platform1'] = $input_data['ver_repo'];
    $input_data['az_topology1'] = $input_data['az_topology'];
    $input_data['test_name'] = $all_tests;
    return;
}

function create_build_report()
{
    global $input_data ;
    global $All_sql ;

    $platform = get_name_by_id('platform', $input_data['build_run']) ;
    $last_ver = get_last_version() ;
    $last_build = get_latest_build($last_ver[1]);
    $topology = get_name_by_id('az_topology', $input_data['az_topology']) ;
    $full_ver = $last_ver[0] . '-' . $last_build[0] ;
    H(3, 'The last Build [' . $full_ver . '] run(s) on : ' . $platform . ' (' . $topology . ')') ;
    $all_tests = get_tests_list() ;
    $input_data['version1'] = $last_ver[1] ;
    $input_data['build1'] = $last_build[1];
    $input_data['platform1'] = $input_data['build_run'];
    $input_data['az_topology1'] = $input_data['az_topology'];
    $input_data['test_name'] = $all_tests;
    return ;
}

// Read the URL quarry_string into a variable - $input_data
read_quary_string() ;

// open connection to the MySQL Database
make_db_connection() ;

$tests_count = get_test_count() ;
$All_Vers = test_version_select() ;

// Global variables for rowspan / colspan
$rspan = 1 ;
$cspan = 1 ;


// Initialize the array of sql statement accordingly to the compare_tests number
$All_sql = [] ;
for ($ind = 0 ; $ind <= $GLOBALS['Compare_tests'] ; $ind++) {
    $All_sql[$ind] = ' ' ;
}

doctype('5') ;
display_header() ;

debug('Running in debug mode') ;
display_initial_form();

if (isset($input_data['submit'])) {
    $OK_to_display = true ;
    if (isset($input_data['build_run']) && $input_data['build_run'] != 'null') {
        debug('running the last build report (same samples - same build)') ;
        create_build_report() ;
    }
    if (isset($input_data['ver_repo']) && $input_data['ver_repo'] != 'null') {
        debug('running the last version report (one run only)') ;
        create_version_report() ;
    }

    // Verify that all variables (for individual / comparison) are defined
    for ($ind = 1 ; $ind <= $GLOBALS['Compare_tests'] ; $ind++) {
        verify_all_inputs($ind) ;
    }

    if ($OK_to_display) {

        display_tests_tabs($input_data['test_name']) ;
        for ($i = 0 ; $i < count($input_data['test_name']) ; $i++) {
            if (strtolower(get_test_name($input_data['test_name'][$i])) != 'pvc multiple-delete') {
                $test_name = get_test_name($input_data['test_name'][$i]) ;

                start_div(2, 'id="' . $test_name . '" class="tabcontent"') ;
                H(2, $test_name . ' performance report', 3) ;

                if (read_full_test_results($input_data['test_name'][$i])) {
                    $rspan = set_row_span($total_to_compare) ;
                    $cspan = set_col_span($total_to_compare) ;
                
                    debug('Generating comparisone report for ' . count($all_res_data) . ' tests') ;
                    print_test_configuration($all_res_data) ;
                     if (strtolower($test_name) == 'fio') {
                        // TODO: Need to be more generic
                        print_fio_test_results() ;
                    }
                    elseif (strtolower($test_name) == 'fio-compress') {
                         // TODO: Need to be more generic
                        print_compress_test_results() ;
                    }
                    elseif (strtolower($test_name) == 'smallfiles') {
                         // TODO: Need to be more generic
                        print_smallfiles_test_results() ;
                    }
                    elseif (strtolower($test_name) == 'pvc snapshot - multiple files') {
                        // TODO: The test need to be fix, and then this can be deleted.
                        // print_pvc_snapshot_multi_files_results() ;
                    }
                     else {
                        print_test_results($test_name) ; 
                    }
                } else {
                    no_test_found() ;
                }
                
            }

            close_div(2) ;

            // make the first test tab available (Active) and show it to the user
            if ($i == 0) {
                NL(1) ;
                echo Tab(2) . "<script>openFirstTest('" . $test_name . "')</script>";
                NL(2) ;
            }
        }
    }
}

$conn->close();
close_body() ;
close_html() ;
