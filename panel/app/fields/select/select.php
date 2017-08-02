<?php

class SelectField extends BaseField {

  public function __construct() {

    $this->type    = 'select';
    $this->options = array();
    $this->icon    = 'chevron-down';

  }

  public function options() {
    return FieldOptions::build($this);
  }

  public function option($value, $text, $selected = false) {
    return new Brick('option', $this->i18n($text), array(
      'value'    => $value,
      'selected' => $selected
    ));
  }

  public function input() {

    $select = new Brick('select');
    $select->addClass('selectbox');
    $select->attr(array(
      'name'         => $this->name(),
      'id'           => $this->id(),
      'required'     => $this->required(),
      'autocomplete' => $this->autocomplete(),
      'autofocus'    => $this->autofocus(),
      'readonly'     => $this->readonly(),
      'disabled'     => $this->disabled(),
    ));

    $default = $this->default();

    if(!$this->required()) {
      $select->append($this->option('', '', $this->value() == ''));
    }

    if($this->readonly()) {
      $select->attr('tabindex', '-1');
    }

    foreach($this->options() as $value => $text) {
      $select->append($this->option($value, $text, $this->value() == $value));
    }

    $inner = new Brick('div');
    $inner->addClass('selectbox-wrapper');
    $inner->append($select);

    $wrapper = new Brick('div');
    $wrapper->addClass('input input-with-selectbox');
    $wrapper->append($inner);

    if($this->readonly()) {
      $wrapper->addClass('input-is-readonly');
    } else {
      $wrapper->attr('data-focus', 'true');
    }

    return $wrapper;

  }

  public function validate() {
    return array_key_exists($this->value(), $this->options());
  }

}
