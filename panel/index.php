<?php

use Kirby\Panel;

define('DS', DIRECTORY_SEPARATOR);

// fetch the site's index directory
$index = dirname(__DIR__);

// load the kirby bootstrapper
require($index . DS . 'kirby' . DS . 'bootstrap.php');

// load the panel bootstrapper
require(__DIR__ . DS . 'app' . DS . 'bootstrap.php');

// check for a custom site.php
if(file_exists($index . DS . 'site.php')) {
  // load the custom config
  require($index . DS . 'site.php');
} else {
  // create a new kirby object
  $kirby = kirby();
}

// fix the base url for the kirby installation
// we always need to call the index() method because
// it needs to detect the URL for the check below to work
$indexUrl = $kirby->urls()->index();
if($kirby->urls()->indexDetected === true) {
  $kirby->urls->index = dirname($indexUrl);
}

// the default index directory
if(!isset($kirby->roots->index)) {
  $kirby->roots->index = $index;
}

// the default avatar directory
if(!isset($kirby->roots->avatars)) {
  $kirby->roots->avatars = $index . DS . 'assets' . DS . 'avatars';
}

// the default thumbs directory
if(!isset($kirby->roots->thumbs)) {
  $kirby->roots->thumbs = $index . DS . 'thumbs';
}

try {

  // create the panel object
  $panel = new Panel($kirby, __DIR__);  

  // launch the panel
  echo $panel->launch();

} catch(Exception $e) {
  echo Panel::fatal($e, __DIR__);
}
