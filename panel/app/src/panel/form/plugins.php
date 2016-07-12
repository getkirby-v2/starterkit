<?php

namespace Kirby\Panel\Form;

use Dir;
use Exception;
use F;
use Kirby\Panel\Form;

class Plugins {

  public function __construct() {
    $this->find();
    $this->load();
  }

  public function find() {

    $kirby = kirby();

    // store all fields coming from plugins and load 
    // them between the default fields and the custom fields
    $pluginfields = $kirby->get('field');

    // load the default panel fields first, because they can be overwritten
    foreach(dir::read(form::$root['default']) as $name) {
      $kirby->set('field', $name, form::$root['default'] . DS . $name, true);
    }

    // load the plugin fields again. A bit hacky, but works
    foreach($pluginfields as $name => $field) {
      $kirby->set('field', $name, $field->root());
    }

    // load all custom fields, which can overwrite all the others
    foreach(dir::read(form::$root['custom']) as $name) {
      $kirby->set('field', $name, form::$root['custom'] . DS . $name, true);
    }

  }

  public function load() {

    $fields  = kirby()->get('field');
    $classes = [];

    foreach($fields as $name => $field) {
      $classes[$field->class()] = $field->file();
    }

    // start the autoloader
    load($classes);

    foreach($fields as $name => $field) {

      $classname = $field->class();

      if(!class_exists($classname)) {
        throw new Exception('The field class is missing for: ' . $classname);
      }

      if(method_exists($classname, 'setup')) {
        call(array($classname, 'setup'));
      }

    }

  }

  public function assets($type) {

    $output      = [];
    $defaultRoot = panel()->roots()->fields();

    foreach(kirby()->get('field') as $name => $field) {

      $root = $field->root();
      $base = dirname($root);

      // only fetch assets for custom fields
      if($base == $defaultRoot) {
        continue;
      }

      $classname = $field->class();

      if(!class_exists($classname)) {
        throw new Exception('The field class is missing for: ' . $classname);
      }

      if(!isset($classname::$assets) || !isset($classname::$assets[$type])) {
        continue;
      }

      foreach($classname::$assets[$type] as $filename) {
        $output[] = f::read($field->root() . DS . 'assets' . DS . $type . DS . $filename);
      }

    }

    return implode(PHP_EOL . PHP_EOL, $output);

  }

  public function css() {
    return $this->assets('css');
  }

  public function js() {
    return $this->assets('js');
  }

}
