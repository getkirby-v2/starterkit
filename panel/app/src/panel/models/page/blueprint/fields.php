<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use Collection;
use Str;

class Fields extends Collection {

  public function __construct($fields = array(), $model) {

    if(empty($fields) or !is_array($fields)) $fields = array();

    foreach($fields as $name => $field) {

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
      $field = new Field($field, $model);

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