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

    if(is_array($value)) {
      return $value;
    } else {
      return str::split($value, ',');
    }

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
