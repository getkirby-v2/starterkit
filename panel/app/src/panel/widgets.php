<?php

namespace Kirby\Panel;

use Collection;
use Dir;

class Widgets extends Collection {

  public $order     = array();
  public $available = array();

  public function __construct() {

    $this->order = kirby()->option('panel.widgets');

    $this->defaults();
    $this->custom();    
    $this->sort();
    $this->permissions();

  }

  public function load($name) {

    if(!isset($this->available[$name])) {
      return false;
    }

    $dir  = $this->available[$name];    
    $file = $dir . DS . $name . '.php';

    if(!file_exists($file)) {
      return false;
    }

    $widget = require($file);

    if(is_array($widget)) {
      $this->append($name, $widget);
      return $widget;
    } else {
      return false;
    }

  }

  public function defaults() {

    $kirby = kirby();    
    $root  = panel()->roots()->widgets();

    foreach(dir::read($root) as $dir) {
      $kirby->registry->set('widget', $dir, $root . DS . $dir, true);
    }

  }

  public function custom() {

    $kirby = kirby();    
    $root  = $kirby->roots()->widgets();

    foreach(dir::read($root) as $dir) {
      $kirby->registry->set('widget', $dir, $root . DS . $dir, true);
    }

  }

  public function sort() {

    // load all widgets from the registry
    $this->available = kirby()->registry()->get('widget');

    // the license warning must always be included
    $this->order['license'] = true;

    // append ordered widgets
    foreach($this->order as $name => $add) {
      if($add) {
        $this->load($name);
      }
      unset($this->available[$name]);
    }

    // append the unsorted widgets 
    foreach($this->available as $name => $dir) {
      $this->load($name);
    }

  }

  public function permissions() {

    foreach($this->data as $key => $widget) {
      
      $event = new Event('panel.widget.' . $key);

      if($event->isDenied() && $key !== 'license') {
        unset($this->data[$key]);        
      }
      
    }

  }

}