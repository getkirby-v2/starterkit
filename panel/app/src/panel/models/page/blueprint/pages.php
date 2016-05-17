<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use A;
use Collection;
use Obj;

use Kirby\Panel\Models\Page\Blueprint;

class Pages extends Obj {

  public $template = array();
  public $sort     = null;
  public $limit    = 20;
  public $num      = null;
  public $max      = null;
  public $sortable = true;
  public $hide     = false;
  public $build    = array();

  public function __construct($params = array()) {

    if($params === true) {
      $this->template = blueprint::all();
    } else if($params === false) {
      $this->limit    = 0;
      $this->max      = 0;
      $this->sortable = false;
      $this->hide     = true;
    } else if(is_array($params)) {
      $template = a::get($params, 'template');
      if($template == false) {
        $this->template = blueprint::all();
      } else if(is_array($template)) {
        $this->template = $template;
      } else {
        $this->template = array($template);
      }
      $this->sort     = a::get($params, 'sort', $this->sort);
      $this->sortable = a::get($params, 'sortable', $this->sortable);
      $this->limit    = a::get($params, 'limit', $this->limit);
      $this->num      = a::get($params, 'num', $this->num);
      $this->max      = a::get($params, 'max', $this->max);
      $this->hide     = a::get($params, 'hide', $this->hide);
      $this->build    = a::get($params, 'build', $this->build);
    } else if(is_string($params)) {
      $this->template = array($params);
    }

  }

  public function template() {
    $result = array();
    foreach($this->template as $t) {
      $result[$t] = new Blueprint($t);
    }
    return new Collection($result);
  }

  public function num() {

    $obj = new Obj();

    $obj->mode    = 'default';
    $obj->field   = null;
    $obj->format  = null;
    $obj->display = null;

    if(is_array($this->num)) {
      foreach($this->num as $k => $v) $obj->$k = $v;
    } else if(!empty($this->num)) {
      $obj->mode = $this->num;
    }

    switch($obj->mode) {
      case 'field':
        isset($obj->field) or $obj->field = 'num';
        break;
      case 'date':
        // switch the default date format by configured handler
        $defaultDateFormat    = kirby()->option('date.handler') == 'strftime' ? '%Y%m%d' : 'Ymd';
        $defaultDisplayFormat = kirby()->option('date.handler') == 'strftime' ? '%Y/%m/%d' : 'Y/m/d';

        // set the defaults
        isset($obj->field)   or $obj->field   = 'date';
        isset($obj->format)  or $obj->format  = $defaultDateFormat;
        isset($obj->display) or $obj->display = $defaultDisplayFormat;
        break;
    }

    return $obj;

  }

}