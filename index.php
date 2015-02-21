<?php

define('DS', DIRECTORY_SEPARATOR);

// load kirby
require(__DIR__ . DS . 'kirby' . DS . 'bootstrap.php');

// check for a custom site.php
if(file_exists(__DIR__ . DS . 'site.php')) {
  require(__DIR__ . DS . 'site.php');
} else {
  $kirby = kirby();
}

// compress kirby output
function compress_output($comprs){
return preg_replace('!\s+!', ' ',str_replace(array("\n","\r","\t"),'',$comprs));
}
ob_start("compress_output");
// render
echo $kirby->launch();
ob_end_flush();
