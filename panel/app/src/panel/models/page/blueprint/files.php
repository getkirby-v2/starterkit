<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use A;
use Obj;

class Files extends Obj {

  public $fields    = array();
  public $type      = array();
  public $max       = null;
  public $size      = false;
  public $width     = false;
  public $height    = false;
  public $hide      = false;
  public $sort      = null;
  public $sortable  = false;
  public $sanitize  = true;

  public function __construct($params = array()) {

    // start the fields collection
    $this->params = $params;

    if($params === false) {
      $this->fields     = array();
      $this->type       = array();
      $this->size       = false;
      $this->width      = false;
      $this->height     = false;
      $this->max        = 0;
      $this->hide       = true;
      $this->sortable   = false;

    } else if(is_array($params)) {
      $this->fields     = a::get($params, 'fields', $this->fields);
      $this->type       = a::get($params, 'type', $this->type);
      if (!is_array($this->type))
        $this->type     = array($this->type);
      $this->size       = a::get($params, 'size', $this->size);
      $this->width      = a::get($params, 'width', $this->width);
      $this->height     = a::get($params, 'height', $this->height);
      $this->max        = a::get($params, 'max', $this->max);
      $this->hide       = a::get($params, 'hide', $this->hide);
      $this->sort       = a::get($params, 'sort', $this->sort);
      $this->sortable   = a::get($params, 'sortable', $this->sortable);
      $this->sanitize   = a::get($params, 'sanitize', true);
    }

  }

  public function fields($file) {
    return new Fields($this->fields, $file);
  }

}
