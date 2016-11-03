<?php

define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// set the timezone for all date functions
date_default_timezone_set('UTC');

// include the kirby testcase file
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'testcase.php');