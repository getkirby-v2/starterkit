<?php

class TextareaField extends TextField {

  static public $assets = array(
    'js' => array(
      'editor.js'
    )
  );

  public function __construct() {
    $this->label   = l::get('fields.textarea.label', 'Text');
    $this->buttons = true;
  }

  public function routes() {
    return array(
      array(
        'pattern' => 'link',
        'action'  => 'link',
        'method'  => 'get|post'
      ),
      array(
        'pattern' => 'email',
        'action'  => 'email',
        'method'  => 'get|post'
      ),
    );
  }

  public function input() {

    $input = parent::input();
    $input->tag('textarea');
    $input->removeAttr('type');
    $input->removeAttr('value');
    $input->html($this->value() ? htmlentities($this->value(), ENT_NOQUOTES, 'UTF-8') : false);
    $input->data('field', 'editor');

    return $input;

  }

  public function result() {
    // Convert all line-endings to UNIX format
    return str_replace(array("\r\n", "\r"), "\n", parent::result());
  }

  public function element() {

    $element = parent::element();
    $element->addClass('field-with-textarea');

    if($this->buttons and !$this->readonly) {
      $element->addClass('field-with-buttons');
    }

    return $element;

  }

  public function content() {

    $content = parent::content();

    if($this->buttons and !$this->readonly) {
      $content->append($this->buttons());
    }

    return $content;

  }

  public function buttons() {
    require_once(__DIR__ . DS . 'buttons.php');
    return new Buttons($this, $this->buttons);
  }

}
