<?php

namespace Kirby\Panel;

use Obj;

class Urls extends Obj {

  public function __construct($panel, $root) {

    $this->panel = $panel;

    // base url
    $this->index  = rtrim($this->panel->kirby()->urls()->index(), '/') . '/' . basename($root);

    // assets
    $this->assets = $this->index . '/assets';
    $this->css    = $this->assets . '/css';
    $this->js     = $this->assets . '/js';
    $this->images = $this->assets . '/images';

    // enable urls without rewriting
    if(kirby()->option('rewrite') === false) {
      $this->index .= '/index.php';
    }

    // shortcuts
    $this->api    = $this->index . '/api';
    $this->login  = $this->index . '/login';
    $this->logout = $this->index . '/logout';
    $this->error  = $this->index . '/error';

  }

}