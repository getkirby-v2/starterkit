<?php

class TelField extends TextField {

  public function __construct() {
    $this->type  = 'tel';
    $this->icon  = 'phone';
    $this->label = l::get('fields.tel.label', 'Phone');
  }

}