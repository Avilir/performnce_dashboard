<?php
/*
This is a module which contain the database related variables and functions

it based on the `mysql` database.
*/

$conn = '' ;             // the connection handler

function make_db_connection()
{
    /* This function open a connection to the database */
    try {
        $GLOBALS['conn'] = new mysqli(
            $GLOBALS['host'],
            $GLOBALS['user'],
            $GLOBALS['pass'],
            $GLOBALS['db']
        );
        if ($GLOBALS['conn']->connect_error) {
            die('Connection failed: ' . $GLOBALS['conn']->connect_error);
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

function get_select_values($table_name, $sort = false)
{
    /*
    This function run a select of data from specific table in the database

    Args:
        table_name (str): the table name to get data from
        sort (bool): return the data as sorted ? - default : false

    Returns:
        array : array of data from the table [id, name]
    */
    
    if ($sort == true) {
        if ($table_name == 'versions') {
            $extra = " ORDER by INET_ATON(SUBSTRING_INDEX(CONCAT(name,'.0.0'),'.',3));" ;
        } else {
            $extra = ' ORDER by name' ;
        }
    } else {
        $extra = '' ;
    }
    $sql = 'SELECT id, name FROM ' . $table_name . $extra ;

    $data = $GLOBALS['conn']->query($sql) ;
    return $data ;
}

function get_name_by_id($table_name, $id)
{
    /* 
    This function return a name by its ID 

    Args:
        table_name (str): the name of the table
        id (int): the id of the value in the table

    Returns:
        string : the name of the id in the table   
    */

    $sql = 'SELECT name FROM ' . $table_name . ' WHERE id = ' . $id;
    $data = $GLOBALS['conn']->query($sql) ;
    foreach ($data as $op_data) {
        return $op_data['name'] ;
    }
}

function get_last_version()
{
    /* 
    This function return the lates version in the DB 
    
    Returns:
        array(string, int) : the name of the version , the id of the version
    */

    $data = get_select_values('versions', $sort = true) ;
    foreach ($data as $op_data) {
        $n = $op_data['name'] ;
        $i = $op_data['id'] ;
    }
    return [$n, $i] ;
}

function get_builds($version)
{
    /*
    This function return the list of builds for a particular version

    Args:
        version (int): the version id

    Returns:
        array : array of builds [id, name]
    */

    $sql = 'SELECT `id`, `name` FROM `builds` WHERE `version` = ' . $version  ;
    $data = $GLOBALS['conn']->query($sql) ;
    return $data ;
}

function get_latest_build($version)
{
    /*
    This function return the last build information for a specific version

    Args:
        version (int): the version id

    Returns:
        array(string, int) : the name of the build , the id of the build
    */
    $sql = 'SELECT id, name FROM `builds` WHERE `version` = ' . $version . ' ORDER by name';
    $data = $GLOBALS['conn']->query($sql) ;
    foreach ($data as $op_data) {
        $n = $op_data['name'] ;
        $i = $op_data['id'] ;
    }
    return [$n, $i] ;
}

function get_test_name($id)
{
    /*
    This function return the name of test by it is ID

    Args:
        id (int): the test name id

    Returns:
        str : the test name
    */
    $sql = 'SELECT name FROM `tests` WHERE `id` = ' . $id ;
    $data = $GLOBALS['conn']->query($sql)->fetch_assoc()['name'] ;
    return $data ;
}

function get_test_count()
{
    /*
    This function return the number of tests in the DB

    Returns:
        int : the number of tests in tests table
    */
    $sql = 'SELECT * FROM tests' ;
    $result = $GLOBALS['conn']->query($sql);
    $num_rows = mysqli_num_rows($result);
    return $num_rows ;
}

function get_tests_list()
{
    /*
    This function return list of tests id's in the DB

    Returns:
        list : all tests id's
    */
    $sql = 'SELECT id FROM tests' ;
    $data = $GLOBALS['conn']->query($sql);
    $r = [] ;
    foreach ($data as $op_data) {
        array_push($r, $op_data['id']) ;
    }
    return $r ;
}
