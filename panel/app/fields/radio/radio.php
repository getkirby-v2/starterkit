<?php

class RadioField extends InputListField {

  public function value() {
    $value = parent::value();
    if(empty($value)) {
      // get the first key of options
      $options = $this->options();
      if(is_array($options)) {
        reset($options);
        $value = key($options);        
      }
    }
    return $value;
  }

  public function input() {

    $val   = func_get_arg(0);
    $input = parent::input();
    $input->addClass('radio');
    $input->attr('type', 'radio');
    $input->val($val);

    if($this->readonly) {
      $input->attr('disabled', true);      
    }

    $input->attr('checked', $val == $this->value());
    return $input;

  }

  public function item($value, $text) {
    $item = parent::item($value, $text);
    $item->addClass('input-with-radio');
    return $item;
  }

}
