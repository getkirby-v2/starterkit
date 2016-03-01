<?php

use Kirby\Panel\Form;

class TimeField extends SelectField {

  public $override = false;

  public function __construct() {
    $this->icon     = 'clock-o';
    $this->interval = 60;
    $this->format   = 24;
  }

  public function interval() {
    if($this->interval <= 0) {
      $this->interval = 60;
    } 
    return $this->interval;    
  }

  public function value() {

    if($this->override()) {
      $value = $this->default();
    } else {
      $value = parent::value();
    }

    if(!empty($value)) {

      if($value == 'now') {
        $value = date($this->format(), time());
      }

      $time  = round((strtotime($value) - strtotime('00:00')) / ($this->interval() * 60)) * ($this->interval() * 60) + strtotime('00:00');
      $value = date($this->format(), $time);

    }

    return $value;

  }

  public function format() {
    return $this->format == 12 ? 'h:i A' : 'H:i';
  }

  public function options() {

    $time    = strtotime('00:00');
    $end     = strtotime('23:59');
    $options = array();
    $format  = $this->format();

    while($time < $end) {

      $now    = date($format, $time);
      $time  += 60 * $this->interval();

      $options[$now] = $now;

    }

    return $options;

  }

}
