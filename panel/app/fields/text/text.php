<?php

class TextField extends InputField {

  public $type     = 'text';
  public $validate = array(
    'minLength' => 0,
    'maxLength' => null
  );

  static public $assets = array(
    'js' => array(
      'counter.js'
    )
  );

  public function minLength() {
    return isset($this->validate['minLength']) ? $this->validate['minLength'] : false;
  }

  public function maxLength() {
    return isset($this->validate['maxLength']) ? $this->validate['maxLength'] : false;
  }

  public function input() {

    $input = parent::input();

    if(!$this->readonly() && ($this->minLength() || $this->maxLength())) {
      $input->data('max', $this->maxLength())->data('min', $this->minLength());
    }

    return $input;

  }

  public function outsideRange($length) {

    if($this->minLength() && $length < $this->minLength()) return true;
    if($this->maxLength() && $length > $this->maxLength()) return true;

    return false;

  }

  public function counter() {

    if(!$this->minLength() && !$this->maxLength() || $this->readonly()) return null;

    $counter = new Brick('div');
    $counter->addClass('field-counter marginalia text');

    $length = str::length(trim($this->value()));

    if($this->outsideRange($length)) {
      $counter->addClass('outside-range');
    }

    $counter->data('field', 'counter');
    $counter->html($length . ($this->maxLength() ? '/' . $this->maxLength() : ''));

    return $counter;

  }

  public function template() {

    return $this->element()
      ->append($this->label())
      ->append($this->content())
      ->append($this->counter())
      ->append($this->help());

  }

}
