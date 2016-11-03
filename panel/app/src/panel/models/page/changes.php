<?php

namespace Kirby\Panel\Models\Page;

use A;
use S;

use Kirby\Panel\Form;

class Changes {

  public $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function data() {
    return s::get('kirby_panel_changes', array());
  }

  public function id() {
    $site = panel()->site();    
    if($site->multilang()) {
      return $site->language()->code() . '-' . sha1($this->model->id());
    } else {
      return sha1($this->model->id());      
    }
  }

  public function keep() {

    $blueprint = $this->model->blueprint();
    $fields    = $blueprint->fields($this->model);
    $form      = new Form($fields->toArray());
    $data      = $this->model->filterInput($form->serialize());
    $old       = $this->model->content()->toArray();

    if($data != $old) {
      $this->update($data);      
    }
  
  }

  public function discard($field = null) {

    $store = $this->data();

    if(is_null($field)) {
      unset($store[$this->id()]);          
    } else {
      unset($store[$this->id()][$field]);          
    }

    s::set('kirby_panel_changes', $store);

    // remove all structures from the session as well
    $this->model->structure()->reset();

    return $store;

  }

  public function differ() {

    $data    = $this->get();
    $changes = false;

    foreach($data as $field => $value) {    

      $object = $this->model->{$field}();

      if(!method_exists($object, '__toString')) {
        continue;
      }

      if((string)$object !== $value) {
        $changes = true;
      }
    
    }

    return $changes;

  }

  public function get($field = null) {

    $data = (array)a::get($this->data(), $this->id());      

    if(!is_null($field)) {
      return a::get($data, $field);
    } else {
      return $data;
    }

  }

  public function update($field, $data = null) {

    if(is_null($data) and is_array($field)) {
      $store = $this->data();
      $store[$this->id()] = $field;
    } else if(is_string($field)) {
      $store = $this->data();
      if(!isset($store[$this->id()]) or !is_array($store[$this->id()])) {
        $store[$this->id()] = array();
      }
      $store[$this->id()][$field] = $data;      
    }

    s::set('kirby_panel_changes', $store);
    return $store;

  }

  public function flush() {
    s::set('kirby_panel_changes', array());
  }

}