<?php

namespace Kirby\Panel\Models\Page\Blueprint;

use A;

class Options {

  protected $update     = true;
  protected $delete     = true;
  protected $preview    = true;
  protected $visibility = true;
  protected $template   = true;
  protected $url        = true;

  /**
   * @param array $options
   */
  public function __construct($options) {
    $this->create     = a::get($options, 'create', true);
    $this->update     = a::get($options, 'update', true);
    $this->delete     = a::get($options, 'delete', true);
    $this->preview    = a::get($options, 'preview', true);
    $this->visibility = a::get($options, 'visibility', a::get($options, 'status', true));
    $this->template   = a::get($options, 'template', true);
    $this->url        = a::get($options, 'url', true);
    $this->upload     = a::get($options, 'upload', true);
  }

  public function update() {
    return $this->update;
  }

  public function delete() {
    return $this->delete;
  }

  public function preview() {
    return $this->preview;
  }

  public function visibility() {
    return $this->visibility;
  }

  /**
   * Deprecated alternative  
   * @see static::visibility()
   */
  public function status() {
    return $this->visibility;
  }

  public function template() {
    return $this->template;
  }

  public function url() {
    return $this->url;
  }

}