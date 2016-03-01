<?php

class TextField extends InputField {

  public $type     = 'text';
  public $validate = array(
    'min' => 0,
    'max' => null
  );

  static public $assets = array(
    'js' => array(
      'counter.js'
    )
  );

  public function min() {
    return isset($this->validate['min']) ? $this->validate['min'] : false;
  }

  public function max() {
    return isset($this->validate['max']) ? $this->validate['max'] : false;
  }

  public function input() {

    $input = parent::input();

    if(!$this->readonly() && ($this->min() || $this->max())) {
      $input->data('max', $this->max())->data('min', $this->min());
    }

    return $input;

  }

  public function outsideRange($length) {

    if($this->min() && $length < $this->min()) return true;
    if($this->max() && $length > $this->max()) return true;

    return false;

  }

  public function counter() {

    if(!$this->min() && !$this->max() || $this->readonly()) return null;

    $counter = new Brick('div');
    $counter->addClass('field-counter marginalia text');

    $length = str::length($this->value());

    if($this->outsideRange($length)) {
      $counter->addClass('outside-range');
    }

    $counter->data('field', 'counter');
    $counter->html($length . ($this->max() ? '/' . $this->max() : ''));

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
