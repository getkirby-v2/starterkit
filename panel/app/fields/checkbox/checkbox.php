<?php

class CheckboxField extends InputField {

  public function input() {

    $input = new Brick('input', null);
    $input->addClass('checkbox');
    $input->attr(array(
      'id'           => $this->id(),
      'name'         => $this->name(),
      'required'     => $this->required(),
      'autofocus'    => $this->autofocus(),
      'autocomplete' => $this->autocomplete(),
      'readonly'     => $this->readonly(),
      'type'         => 'checkbox',
      'checked'      => v::accepted($this->value()),
    ));

    $wrapper = parent::input();
    $wrapper->tag('label');
    $wrapper->text($this->i18n($this->text()));
    $wrapper->attr('for', $this->id());
    $wrapper->removeAttr('id');
    $wrapper->addClass('input-with-checkbox');
    $wrapper->prepend($input);

    return $wrapper;

  }

  public function value() {
    $value = parent::value();
    return empty($value) ? '0' : $value;
  }

  public function result() {
    $result = parent::result();
    return v::accepted($result) ? '1' : '0';
  }

  public function validate() {
    return v::accepted($this->value()) or v::denied($this->value());
  }

}