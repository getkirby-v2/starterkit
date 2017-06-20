<?php

define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// set the timezone for all date functions
date_default_timezone_set('UTC');

// compatibility with both PHPUnit < 6 and >= 6
if(class_exists('\PHPUnit\Framework\TestCase') && !class_exists('\PHPUnit_Framework_TestCase')) {
  class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

// include the kirby testcase file
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'testcase.php');
