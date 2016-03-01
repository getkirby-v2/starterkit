<?php

namespace Kirby\Panel\Form;

use Dir;
use F;
use Kirby\Panel\Form;

class Plugins {

  public $default = array();
  public $custom  = array();
  public $classes = array();
  public $css     = '';
  public $js      = '';

  public function __construct() {
    $this->find('custom');
    $this->find('default');
    $this->load();
  }

  public function find($type) {

    $root = form::$root[$type];
    $dirs = dir::read($root);

    foreach($dirs as $dir) {

      $name = strtolower($dir);
      $file = $root . DS . $name . DS . $name . '.php';

      if(file_exists($file)) {
        $this->{$type}[$name . 'field'] = $file;
      }

    }

  }

  public function load() {

    $this->classes = array_merge($this->default, $this->custom);

    load($this->classes);

    foreach($this->classes as $classname => $root) {

      if(method_exists($classname, 'setup')) {
        call(array($classname, 'setup'));
      }

    }

    foreach($this->custom as $classname => $root) {

      if(!isset($classname::$assets)) continue;

      if(isset($classname::$assets['css'])) {
        $this->assets('css', $root, $classname::$assets['css']);
      }

      if(isset($classname::$assets['js'])) {
        $this->assets('js', $root, $classname::$assets['js']);
      }

    }

  }

  public function assets($type, $root, $files) {

    $output = array();

    foreach($files as $filename) {
      $output[] = f::read(dirname($root) . DS . 'assets' . DS . $type . DS . $filename);
    }

    $this->{$type} .= implode(PHP_EOL . PHP_EOL, $output);

  }

  public function css() {
    return $this->css;
  }

  public function js() {
    return $this->js;
  }

}
