<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use A;

class Options {

  protected $preview  = true;
  protected $status   = true;
  protected $template = true;
  protected $url      = true;
  protected $delete   = true;

  /**
   * @param array $options
   */
  public function __construct($options) {
    $this->preview  = a::get($options, 'preview', true);
    $this->status   = a::get($options, 'status', true);
    $this->template = a::get($options, 'template', true);
    $this->url      = a::get($options, 'url', true);
    $this->delete   = a::get($options, 'delete', true);
  }

  public function preview() {
    return $this->preview;
  }

  public function status() {
    return $this->status;
  }

  public function template() {
    return $this->template;
  }

  public function url() {
    return $this->url;
  }

  public function delete() {
    return $this->delete;
  }

}