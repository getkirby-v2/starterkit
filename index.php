<?php

define('DS', DIRECTORY_SEPARATOR);

define('ROOT',         __DIR__);
define('ROOT_CONTENT', __DIR__ . DS . 'content');
define('ROOT_SITE',    __DIR__ . DS . 'site');

// detect the base path of the code files
$path = (is_dir(__DIR__ . DS . 'vendor' . DS . 'kirby'))? __DIR__ . DS . 'vendor' . DS . 'kirby' : __DIR__;

// detect if the request points to the Panel
if(strpos($_SERVER['REQUEST_URI'], '/panel') !== false) {
  // load the panel bootstrapper
  if(is_file($path . DS . 'panel' . DS . 'index.php')) {
    include($path . DS . 'panel' . DS . 'index.php');
    die();
  }
  
  // if the program still runs, the panel is not installed -> load CMS
}

// load the cms bootstrapper
include($path . DS . 'kirby' . DS . 'bootstrap.php');

// start the cms
echo kirby::start(array(
  'root'         => ROOT,
  'root.content' => ROOT_CONTENT,
  'root.site'    => ROOT_SITE
));