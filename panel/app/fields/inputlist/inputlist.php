<?php

class InputListField extends InputField {

  public $columns = 2;

  public function input() {
    $input = parent::input();
    $input->removeClass('input');
    return $input;
  }

  public function options() {
    return fieldoptions::build($this);
  }

  public function item($value, $text) {

    $input = $this->input($value);

    $label = new Brick('label', '<span>' . $this->i18n($text) . '</span>');
    $label->addClass('input');
    $label->attr('data-focus', 'true');
    $label->prepend($input);

    if($this->readonly) {
      $label->addClass('input-is-readonly');
    }

    return $label;

  }

  public function content() {

    $html = '<ul class="input-list field-grid cf">';

    switch($this->columns()) {
      case 2:
        $width = ' field-grid-item-1-2';
        break;
      case 3:
        $width = ' field-grid-item-1-3';
        break;
      case 4:
        $width = ' field-grid-item-1-4';
        break;
      case 5:
        $width = ' field-grid-item-1-5';
        break;
      default:
        $width = '';
        break;
    }

    foreach($this->options() as $key => $value) {
      $html .= '<li class="input-list-item field-grid-item' . $width . '">';
      $html .= $this->item($key, $value);
      $html .= '</li>';
    }

    $html .= '</ul>';

    $content = new Brick('div');
    $content->addClass('field-content');
    $content->append($html);

    return $content;

  }

  public function validate() {
    if(is_array($this->value())) {
      foreach($this->value() as $v) {
        if(!array_key_exists($v, $this->options())) return false;
      }
      return true;
    } else {
      return array_key_exists($this->value(), $this->options());
    }
  }

}
