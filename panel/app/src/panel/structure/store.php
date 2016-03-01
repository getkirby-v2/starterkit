<?php 

namespace Kirby\Panel\Structure;

use A;
use Collection;
use S;
use Str;
use Yaml;

class Store {

  public $id = null;
  public $structure;
  public $source = array();
  public $data = array();

  public function __construct($structure, $source) {
    $this->structure = $structure;  
    $this->source    = $source;
    $this->id        = $structure->id() . '_' . $structure->field();

    $this->init();
  } 

  public function init() {

    $data = s::get($this->id());

    if(!is_array($data)) {
      $raw = (array)$this->source;        
    } else {
      $raw = (array)s::get($this->id(), array());      
    }

    $data = array();

    foreach($raw as $row) {
      if(!isset($row['id'])) {
        $row['id'] = str::random(32);
      }
      $data[$row['id']] = $row;
    }

    $this->data = $data;
    s::set($this->id, $this->data);

  }

  public function id() {
    return $this->id;
  }

  public function data() {
    return $this->data;
  }  

  public function find($id) {
    return a::get($this->data, $id);
  }

  public function add($data) {

    $data['id'] = str::random(32);
    
    $this->data[ $data['id'] ] = $data;
    $this->save();

    return $data['id'];

  }

  public function update($id, $data) {

    if($entry = a::get($this->data, $id)) {
  
      foreach($data as $key => $value) {
        $entry[$key] = $value;
      }
  
      $this->data[$id] = $entry;
      $this->save();

      return $entry;

    } else {
      return false;
    }

  }

  public function delete($id) {

    if(is_null($id)) {
      $this->data = array();
    } else {
      unset($this->data[$id]);      
    }

    $this->save();

    return $this->data;

  }

  public function sort($ids) {

    $data = array();

    foreach($ids as $id) {
      if($item = $this->find($id)) {
        $data[$id] = $item;        
      }
    }

    $this->data = $data;
    $this->save();

    return $this->data;

  }

  public function toArray() {
    $array = array_values($this->data);
    $array = array_map(function($item) {
      unset($item['id']);
      return $item;
    }, $array);
    return $array;
  }  

  public function toYaml() {
    return trim(yaml::encode($this->toArray()));
  }

  public function save() {

    s::set($this->id, $this->data);

    // keep the changes for the page
    if(is_a($this->structure->model(), 'page')) {
      $this->structure->model()->changes()->update(
        $this->structure->field(), 
        $this->toYaml()
      );      
    }

  }

  public function reset() {
    return s::remove($this->id);
  }


}