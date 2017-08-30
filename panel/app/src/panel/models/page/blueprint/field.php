<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use A;
use Data;
use Exception;
use F;
use Obj;

class Field extends Obj {

  public $name      = null;
  public $label     = null;
  public $default   = null;
  public $type      = null;
  public $value     = null;
  public $required  = false;
  public $translate = true;

  public function __construct($params = array(), $model, $formtype = 'default') {

    if(!empty($params['extends'])) {
      $params = $this->_extend($params);
    }

    if($formtype === 'default' && a::get($params, 'name') == 'title') {
      $params['type'] = 'title';

      if(!isset($params['required'])) {
        $params['required'] = true;
      }
    }

    if(empty($params['type'])) {
      $params['type'] = 'text';
    }

    // lowercase the type
    $params['type'] = strtolower($params['type']);

    // register the parent model
    $params['model'] = $model;

    // try to fetch the parent page from the model
    if(is_a($model, 'Page')) {
      $params['page'] = $model;
    } else if(is_a($model, 'File')) {
      $params['page'] = $model->page();
    }

    // create the default value
    $params['default'] = $this->_default($params, a::get($params, 'default'));

    parent::__construct($params);

  }


  public function _extend($params) {

    $extends = $params['extends'];
    $file = kirby()->get('blueprint', 'fields/' . $extends);

    if(empty($file) || !is_file($file)) {
      throw new Exception(l('fields.error.extended') . ' "' . $extends . '"');
    }

    $yaml   = data::read($file, 'yaml');
    $params = a::merge($yaml, $params);

    return $params;

  }

  public function _default($params, $default) {

    if($default === true) {
      return 'true';
    } else if($default === false) {
      return 'false';
    } else if(empty($default) and strlen($default) == 0) {
      return '';
    } else if(is_string($default)) {
      return $default;
    } else {
      switch(a::get($params, 'type')) {
        case 'structure':
          return "\n" . \data::encode($default, 'yaml') . "\n";
          break;
        default:
          return $default;
          break;
      }

    }

  }

}
