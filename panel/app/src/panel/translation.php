<?php

namespace Kirby\Panel;

use Data;
use Exception;
use L;
use Obj;

class Translation {

  public $panel;
  public $code;
  public $root;
  public $info = null;

  public $map = array(
    'es_la' => 'es_419',
    'no_nb' => 'nb',
    'cz'    => 'cs'
  );

  public function __construct($panel, $code) {

    $this->panel = $panel;
    $this->code  = basename($code);

    // convert old codes
    if(isset($this->map[$this->code])) {
      $this->code = $this->map[$this->code];
    }

    // set the root for the translation directory
    $this->root = $this->panel->roots()->translations() . DS . $this->code;

    if(!is_dir($this->root)) {
      throw new Exception('The translation does not exist: ' . $this->code);
    }

    if(!is_file($this->root . DS . 'package.json')) {
      throw new Exception('The package.json is missing for the translation with code: ' . $this->code);
    }

    if(!is_file($this->root . DS . 'core.json')) {
      throw new Exception('The core.json is missing for the translation with code: ' . $this->code);
    }

  }

  public function code() {
    return $this->code;
  }

  public function root() {
    return $this->root;
  }

  public function load() {
    return l::$data = data::read($this->root . DS . 'core.json');
  }

  public function info() {
    if(!is_null($this->info)) return $this->info;
    return $this->info = new Obj(data::read($this->root . DS . 'package.json'));
  }

  public function direction() {
    $direction = $this->info()->direction();
    return $direction ? $direction : 'ltr';
  }

  public function __call($method, $args) {
    return $this->info()->{$method}();
  }

  public function __toString() {
    return $this->code;
  }

  public function __debuginfo() {
    return [
      'title'     => $this->title(),
      'code'      => $this->code(),
      'root'      => $this->root(),
      'direction' => $this->direction(),
    ];
  }

}
