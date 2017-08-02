<?php

class CheckboxesField extends RadioField {

  public function input() {

    $value = func_get_arg(0);
    $input = parent::input($value);
    $input->replaceClass('radio', 'checkbox');
    $input->attr(array(
      'name'     => $this->name() . '[]',
      'type'     => 'checkbox',
      'value'    => $value,
      'checked'  => ($this->value === 'all') ? true : in_array($value, (array)$this->value()),
      'required' => false,
    ));

    return $input;

  }

  public function value() {

    $value = InputListField::value();

    if(!is_array($value)) {
      $value = str::split($value, ',');
    }

    // Remove items from value array that are not present in the options array
    return array_keys(array_intersect_key(array_flip($value), $this->options()));

  }
  
  public function result() {
    $result = parent::result();
    return is_array($result) ? implode(', ', $result) : '';
  }

  public function item($value, $text) {
    $item = parent::item($value, $text);
    $item->replaceClass('input-with-radio', 'input-with-checkbox');
    return $item;
  }

}
