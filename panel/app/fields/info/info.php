<?php

class InfoField extends BaseField {

  public $text;

  public function result() {
    return null;
  }

  public function element() {
    $element = parent::element();
    $element->addClass('field-with-icon');
    return $element;
  }

  public function input() {
    return '<div class="text">' . kirbytext($this->i18n($this->text())) . '</div>';
  }

}