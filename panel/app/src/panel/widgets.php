<?php

namespace Kirby\Panel;

use Collection;
use Dir;

class Widgets extends Collection {

  public $order    = array();
  public $unsorted = array();

  public function __construct() {

    $this->order = kirby()->option('panel.widgets');

    $this->defaults();
    $this->custom();    
    $this->sort();

  }

  public function load($name, $file) {

    if(!file_exists($file)) {
      return false;
    }

    $widget = require($file);

    if(!is_array($widget)) {
      return false;
    }

    $this->unsorted[$name] = $widget;

  }

  public function defaults() {

    $root = panel()->roots()->widgets();

    foreach(dir::read($root) as $dir) {

      // add missing widgets to the order array
      if(!array_key_exists($dir, $this->order)) {
        $this->order[$dir] = true;
      }

      $this->load($dir, $root . DS . $dir . DS . $dir . '.php');

    }

  }

  public function custom() {

    $root = kirby()->roots()->widgets();

    foreach(dir::read($root) as $dir) {

      // add missing widgets to the order array
      if(!array_key_exists($dir, $this->order)) {
        $this->order[$dir] = true;
      }

      $this->load($dir, $root . DS . $dir . DS . $dir . '.php');
    }

  }

  public function sort() {

    // the license warning must always be included
    $this->order['license'] = true;

    foreach($this->order as $name => $add) {
      if($add and isset($this->unsorted[$name])) {
        $this->append($name, $this->unsorted[$name]);        
      }
    }

  }

}