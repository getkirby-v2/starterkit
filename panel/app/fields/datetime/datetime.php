<?php

use Kirby\Panel\Form;

class DatetimeField extends BaseField {

  public $date = [];
  public $time = [];

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

    if(empty($value) || !isset($value['date'])) {
      return '';
    }

    $date = a::get($value, 'date');
    $time = a::get($value, 'time');

    return empty($time) ? $date : $date . ' ' . $time . ':00';

  } 

  public function content() {

    $value = $this->value();

    if(is_array($value)) {
      $value = $this->result();
    } 

    $ts   = strtotime($value);      
    $date = $this->dateField($value, $ts);
    $time = $this->timeField($value, $ts);

    $grid  = '<div class="field-grid">';
    $grid .= '<div class="field-grid-item field-grid-item-1-2">' . $date->content() . '</div>';
    $grid .= '<div class="field-grid-item field-grid-item-1-2">' . $time->content() . '</div>';
    $grid .= '</div>';

    return $grid;

  }

  public function dateOptions() {

    $options = array_merge([
      'format'   => 'YYYY-MM-DD',
      'required' => $this->required(),
      'default'  => false,
    ], (array)$this->date);

    if($options['required'] && !$options['default']) {
      $options['default'] = 'now';
    }

    return $options;

  }

  public function dateValue($timestamp, $default) {
    return $timestamp ? date('Y-m-d', $timestamp) : $default;
  }

  public function dateField($value, $timestamp) {

    $options = $this->dateOptions();
    $value   = $this->dateValue($timestamp, $options['default']);

    return form::field('date', array(
      'name'     => $this->name() . '[date]',
      'id'       => 'form-field-' . $this->name() . '-date',
      'value'    => $value,
      'format'   => $options['format'],      
      'required' => $options['required'],
      'readonly' => $this->readonly(),
      'disabled' => $this->disabled()
    ));

  }

  public function timeOptions() {

    $options = array_merge([
      'interval' => 60,
      'format'   => 24,
      'required' => null,
      'default'  => false,
    ], (array)$this->time);

    if($this->required() && $options['required'] !== false) {
      $options['required'] = true;
    }

    if($options['required'] && !$options['default']) {
      $options['default'] = date('H:i');
    }

    return $options;

  } 

  public function timeExists($date) {
    return !preg_match('!^[0-9]{4}-[0-9]{2}-[0-9]{2}$!', $date);
  }

  public function timeValue($value, $timestamp, $default) {

    if($this->timeExists($value)) {
      return $timestamp ? date('H:i', $timestamp) : $default;      
    } else {
      return $default;
    }

  }

  public function timeField($value, $timestamp) {

    $options = $this->timeOptions();
    $value   = $this->timeValue($value, $timestamp, $options['default']);

    return form::field('time', array(
      'name'     => $this->name() . '[time]',
      'id'       => 'form-field-' . $this->name() . '-time',
      'value'    => $value,
      'format'   => $options['format'],
      'interval' => $options['interval'],
      'required' => $options['required'],
      'readonly' => $this->readonly(),
      'disabled' => $this->disabled()
    ));

  }

}
