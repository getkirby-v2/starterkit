<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use Collection;
use Str;

class Fields extends Collection {

  protected $model;
  protected $formtype;

  public function __construct($fields = array(), $model, $formtype = 'default') {

    if(empty($fields) or !is_array($fields)) $fields = array();

    $this->model    = $model;
    $this->formtype = $formtype;

    foreach($fields as $name => $field) {
      $this->createField($name, $field);
    }

  }

  public function createField($name, $field) {

    // sanitize the name
    $name = str_replace('-','_', str::lower($name));

    // import a field by name
    if(is_string($field)) {
      $field = array(
        'name'    => $name,
        'extends' => $field
      );
    }    

    // add the name to the field
    $field['name'] = $name;
        
    // create the field object
    $field = new Field($field, $this->model, $this->formtype);

    if($field->type === 'group') {
      
      foreach($field->fields as $name => $subfield) {
        $this->createField($name, $subfield);
      }

      return;
    } else {
      // append it to the collection
      $this->append($name, $field);      
    }


  }

  public function toArray($callback = null) {
    $result = array();
    foreach($this->data as $field) {
      $result[$field->name()] = $field->toArray();
    }
    return $result;
  }

}