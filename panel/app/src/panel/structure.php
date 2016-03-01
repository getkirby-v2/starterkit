<?php 

namespace Kirby\Panel;

use A;
use Collection;
use Exception;
use Obj;
use S;
use Str;
use Yaml;

use Kirby\Panel\Structure\Store;
use Kirby\Panel\Models\Page\Blueprint\Fields;

class Structure {

  protected $id;
  protected $model;
  protected $blueprint;
  protected $field;
  protected $data;
  protected $store;

  public function __construct($model, $id) {

    $this->model     = $model;
    $this->id        = 'structure_' . sha1($id);
    $this->blueprint = $this->model->blueprint();

  }

  public function forField($field) {

    if(method_exists($this->model, $field)) {
      throw new Exception('The field name: ' . $field . ' cannot be used as it is reserved');
    }

    $this->field  = $field;
    $this->config = $this->model->getBlueprintFields()->get($this->field);
    $this->source = (array)yaml::decode($this->model->{$this->field}());
    $this->store  = new Store($this, $this->source());

    return $this;

  }

  public function config() {
    return $this->config;
  }

  public function source() {
    return $this->source;
  }

  public function store() {
    return $this->store;
  }

  public function model() {
    return $this->model;
  }

  public function field() {
    return $this->field;
  }

  public function id() {
    return $this->id;    
  }

  public function fields() {

    $fields = $this->config->fields();
    $fields = new Fields($fields, $this->model);
    $fields = $fields->toArray();

    // make sure that no unwanted options or fields 
    // are being included here
    foreach($fields as $name => $field) {

      // remove all structure fields within structures
      if($field['type'] == 'structure') {
        unset($fields[$name]);

      // remove invalid buttons from textareas
      } else if($field['type'] == 'textarea') {
        $buttons = a::get($fields[$name], 'buttons');
        if(is_array($buttons)) {
          foreach($buttons as $index => $value) {
            if(in_array($value, array('link','email'))) {
              unset($fields[$name]['buttons'][$index]);              
            }
          }
        } else if($buttons !== false) {
          $fields[$name]['buttons'] = array('bold', 'italic');
        }
      }

    }

    return $fields;

  }

  public function data() {

    $collection = new Collection($this->store()->data());
    $collection = $collection->map(function($item) {
      return new Obj($item);
    });

    return $collection;

  }

  public function toObject($array) {
    return is_array($array) ? new Obj($array) : false;
  }

  public function find($id) {
    return $this->toObject($this->store()->find($id));
  }

  public function reset() {
  
    if($this->field) {
      return $this->store()->reset();      
    } else {
      foreach(s::get() as $key => $value) {
        if(str::startsWith($key, $this->id)) {
          s::remove($key);
        }
      }      
    }

  }

  public function delete($id = null) {
    return $this->store()->delete($id);
  }

  public function add($data = array()) {
    return $this->store()->add($data);
  }

  public function update($id, $data = array()) {
    return $this->toObject($this->store()->update($id, $data));
  }

  public function sort($ids) {
    return $this->store()->sort($ids);
  }

  public function toArray() {
    return $this->store()->toArray();
  }

  public function toYaml() {
    return $this->store()->toYaml();
  }

}