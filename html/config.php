<?php

/**
 * This file contain the general configuration variables
 */

// Setting for development mode, in production change to false
$dev_mode = true ;

 // Maximum tests to compare
$Compare_tests = 4 ;

// SQL quarry for each compared test [size = $Compare_tests]
$All_sql = [] ;

// all compared tests results data  [max size = $Compare_tests]
$test_results = [] ;
$all_res_data = [] ;

$OK_to_display = true ;

// Total tests to be compared
$total_to_compare = 0 ;

// Total number of tests
$tests_count = 0 ;

// Database variables

// mysql server name
$host = "db-server";

// username to connect the database
$user = 'ocsqe';

// the database name
$db = 'app1';
