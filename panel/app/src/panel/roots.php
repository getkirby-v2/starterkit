<?php

namespace Kirby\Panel;

use Obj;

class Roots extends Obj {

  public $panel;

  public function __construct($panel, $root) {

    $this->panel       = $panel;
    $this->index       = $root;
    $this->app         = $root . DS . 'app';
    $this->assets      = $root . DS . 'assets';

    $this->config       = $this->app . DS . 'config';
    $this->controllers  = $this->app . DS . 'controllers';
    $this->collections  = $this->app . DS . 'collections';
    $this->models       = $this->app . DS . 'models';
    $this->fields       = $this->app . DS . 'fields';
    $this->forms        = $this->app . DS . 'forms';
    $this->translations = $this->app . DS . 'translations';
    $this->widgets      = $this->app . DS . 'widgets';
    $this->layouts      = $this->app . DS . 'layouts';
    $this->lib          = $this->app . DS . 'lib';
    $this->topbars      = $this->app . DS . 'topbars';
    $this->snippets     = $this->app . DS . 'snippets';
    $this->views        = $this->app . DS . 'views';

  }

  public function __debuginfo() {

    // get the obj debuginfo
    $data = parent::__debuginfo();

    // remove the recursion
    unset($data['panel']);

    return $data;
  
  }  

}