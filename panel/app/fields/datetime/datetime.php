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

    return empty($time) ? $date : $date . ' ' . $time;

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
      'override' => $this->override(),
      'default'  => a::get($this->default(), 'date', false),
    ], (array)$this->date);

    if(($options['required'] || $options['override']) && !$options['default']) {
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

    return form::field('date', array_merge($options, [
      'name'     => $this->name() . '[date]',
      'id'       => 'form-field-' . $this->name() . '-date',
      'value'    => $value,
      'readonly' => $this->readonly(),
      'disabled' => $this->disabled()
    ]));

  }

  public function timeOptions() {

    $options = array_merge([
      'interval' => 60,
      'format'   => 24,
      'required' => null,
      'override' => $this->override(),
      'default'  => a::get($this->default(), 'time', false),
    ], (array)$this->time);

    if($this->required() && $options['required'] !== false) {
      $options['required'] = true;
    }

    if(($options['required'] || $options['override']) && !$options['default']) {
      $options['default'] = date($this->timeFormat($options['format']));
    }

    return $options;

  } 

  public function timeExists($date) {
    return !preg_match('!^[0-9]{4}-[0-9]{2}-[0-9]{2}$!', $date);
  }

  public function timeFormat($format) {
    return $format == 12 ? 'h:i A' : 'H:i';
  }

  public function timeValue($value, $timestamp, $default, $format) {

    if($this->timeExists($value)) {
      return $timestamp ? date($this->timeFormat($format), $timestamp) : $default;      
    } else {
      return $default;
    }

  }

  public function timeField($value, $timestamp) {

    $options = $this->timeOptions();
    $value   = $this->timeValue($value, $timestamp, $options['default'], $options['format']);
    return form::field('time', array_merge($options, [
      'name'     => $this->name() . '[time]',
      'id'       => 'form-field-' . $this->name() . '-time',
      'value'    => $value,
      'readonly' => $this->readonly(),
      'disabled' => $this->disabled()
    ]));

  }

}
