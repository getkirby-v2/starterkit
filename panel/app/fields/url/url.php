<?php

class UrlField extends TextField {

  static public $assets = array(
    'js' => array(
      'url.js'
    )
  );

  public function __construct() {

    $this->type        = 'url';
    $this->icon        = 'chain';
    $this->label       = l::get('fields.url.label', 'URL');
    $this->placeholder = 'http://';

  }

  public function validate() {
    return v::url($this->value());
  }

  public function input() {
    $input = parent::input();
    $input->data('field', 'urlfield');
    return $input;
  }

}