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

  public function __construct($params = array(), $model) {

    if(!empty($params['extends'])) {
      $params = $this->_extend($params);
    }

    if(a::get($params, 'name') == 'title') {
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
    $params['default'] = $this->_default(a::get($params, 'default'));

    parent::__construct($params);

  }


  public function _extend($params) {

    $extends = $params['extends'];
    $file = kirby()->get('blueprint', 'fields/' . $extends);

    if(empty($file) || !is_file($file)) {
      throw new Exception(l('fields.error.extended'));
    }

    $yaml   = data::read($file, 'yaml');
    $params = a::merge($yaml, $params);

    return $params;

  }

  public function _default($default) {

    if($default === true) {
      return 'true';
    } else if($default === false) {
      return 'false';
    } else if(empty($default) and strlen($default) == 0) {
      return '';
    } else if(is_string($default)) {
      return $default;
    } else {
      $type = a::get($default, 'type');

      switch($type) {
        case 'date':
          $format = a::get($default, 'format', 'Y-m-d');
          return date($format);
          break;
        case 'datetime':
          $format = a::get($default, 'format', 'Y-m-d H:i:s');
          return date($format);
          break;
        case 'user':
          $user = isset($default['user']) ? site()->users()->find($default['user']) : site()->user();
          if(!$user) return '';
          return (isset($default['field']) and $default['field'] != 'password') ? $user->{$default['field']}() : $user->username();
          break;
        case 'structure':
          return "\n" . \data::encode(array($default), 'yaml') . "\n";
          break;
        default:
          return $default;
          break;
      }

    }

  }

}
