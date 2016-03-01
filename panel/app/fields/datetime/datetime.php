<?php

use Kirby\Panel\Form;

class DatetimeField extends BaseField {

  public function __construct() {

    $this->date = array(
      'format'   => 'YYYY-MM-DD'
    );

    $this->time = array(
      'interval' => 60,
      'format'   => 24
    );

  }

  public function validate() {
    
    $result = $this->result();

    if(empty($result)) {
      return !$this->required();
    } else {
      return v::date($result);      
    }
  
  }

  public function result() {

    $value = array_filter($this->value());

    if(empty($value) or !isset($value['date']) or !isset($value['time'])) {
      return '';
    }

    return a::get($value, 'date') . ' ' . a::get($value, 'time') . ':00';

  } 

  public function content() {

    if(is_array($this->value())) {
      $timestamp = strtotime($this->result());      
    } else {
      $timestamp = strtotime($this->value());      
    }

    $date = form::field('date', array(
      'name'     => $this->name() . '[date]',
      'value'    => $timestamp ? date('Y-m-d', $timestamp) : null,      
      'format'   => a::get($this->date, 'format', 'YYYY-MM-DD'),      
      'id'       => 'form-field-' . $this->name() . '-date',
      'required' => $this->required(),
      'readonly' => $this->readonly(),
    ));

    $time = form::field('time', array(
      'name'     => $this->name() . '[time]',
      'value'    => $timestamp ? date('H:i', $timestamp) : ($this->required() ? 'now' : false),
      'format'   => a::get($this->time, 'format', 24),
      'interval' => a::get($this->time, 'interval', 60),
      'id'       => 'form-field-' . $this->name() . '-time',
      'required' => $this->required(),
      'readonly' => $this->readonly(),
    ));

    $grid  = '<div class="field-grid">';
    $grid .= '<div class="field-grid-item field-grid-item-1-2">' . $date->content() . '</div>';
    $grid .= '<div class="field-grid-item field-grid-item-1-2">' . $time->content() . '</div>';
    $grid .= '</div>';

    return $grid;

  }

}