<?php

class LineField extends BaseField {

  public function result() {
    return null;
  }

  public function content() {
    return '<hr>';
  }

  public function element() {
    $element = parent::element();
    $element->addClass('field-with-line');
    return $element;
  }

}