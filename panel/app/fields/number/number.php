<?php

class NumberField extends InputField {

  public function __construct() {

    $this->type        = 'number';
    $this->label       = l::get('fields.number.label', 'Number');
    $this->placeholder = l::get('fields.number.placeholder', '#');
    $this->step        = 1;
    $this->min         = false;
    $this->max         = false;

  }

  public function input() {
    $input = parent::input();
    $input->attr('step', $this->step);
    $input->attr('min', $this->min);
    $input->attr('max', $this->max);
    return $input;
  }

  public function validate() {

    if(!v::num($this->result())) return false;

    if($this->validate and is_array($this->validate)) {
      return parent::validate();
    } else {
      if(is_numeric($this->min) and !v::min($this->result(), $this->min)) return false;
      if(is_numeric($this->max) and !v::max($this->result(), $this->max)) return false;
    }

    return true;

  }

}
