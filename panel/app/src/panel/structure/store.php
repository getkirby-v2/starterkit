<?php 

namespace Kirby\Panel\Structure;

use A;
use Collection;
use F;
use S;
use Str;
use Yaml;

class Store {

  public $id = null;
  public $structure;
  public $source = array();
  public $data   = array();
  public $age    = null;

  public function __construct($structure, $source) {
    $this->structure = $structure;  
    $this->source    = $source;
    $this->id        = $structure->id() . '_' . $structure->field();
    $this->age       = time();

    $this->sync();
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

      if(is_string($row)) {
        continue;
      }

      if(!isset($row['id'])) {
        $row['id'] = str::random(32);
      }

      $data[$row['id']] = $row;

    }

    $this->data = $data;
    s::set($this->id, $this->data);
    s::set($this->id . '_age', $this->age);

  }

  /**
   * Resets store if necessary to stay in sync with content file
   */
  public function sync() {

    // get the age of the currently stored content file
    $file     = $this->structure->model()->textfile();
    $ageModel = f::exists($file) ? f::modified($file) : 0;

    // same for the default language in multilang
    if(site()->multilang()) {
      $fileDefL     = $this->structure->model()->textfile(null, site()->languages()->findDefault()->code());
      $ageModelDefL = f::exists($fileDefL) ? f::modified($fileDefL) : 0;
    } else {
      $ageModelDefL = $ageModel; // there's only one content file to check
    }

    // get the age of the current version in store
    $ageStore = s::get($this->id() . '_age');

    if($ageStore < $ageModel || $ageStore < $ageModelDefL) {
      $this->reset();
      $this->age = $ageModel;
    } else {
      $this->age = $ageStore;
    }

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