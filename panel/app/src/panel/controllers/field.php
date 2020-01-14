<?php

namespace Kirby\Panel\Controllers;

use Kirby\Panel\View;
use Kirby\Panel\Snippet;

class Field extends Base {

  public function __construct($model, $field) {
    $this->model     = $model;
    $this->field     = $field;
    $this->fieldname = $field->name();
  }

  public function form($id, $data = array(), $submit = null) {
    $file = $this->field->root() . DS . 'forms' . DS . $id . '.php';
    return panel()->form($file, $data, $submit);
  }

  public function view($file, $data = array()) {

    $view = new View($file, $data);
    $root = $this->field->root() . DS . 'views';

    if(file_exists($root . DS . $file . '.php')) {
      $view->_root = $root;
    }

    return $view;

  }

  public function snippet($file, $data = array()) {

    $snippet = new Snippet($file, $data);
    $root    = $this->field->root() . DS . 'snippets';

    if(file_exists($root . DS . $file . '.php')) {
      $snippet->_root = $root;
    }

    return $snippet;

  }

}