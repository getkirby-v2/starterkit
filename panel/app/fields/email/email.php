<?php

class EmailField extends TextField {

  public function __construct() {

    $this->type         = 'email';
    $this->icon         = 'envelope';
    $this->label        = l::get('fields.email.label', 'Email');
    $this->placeholder  = l::get('fields.email.placeholder', 'mail@example.com');
    $this->autocomplete = true;

  }

  public function input() {

    $input = parent::input();

    if($this->autocomplete) {
      $input->attr('autocomplete', 'off');
      $input->data(array(
        'field' => 'autocomplete',
        'url'   => panel()->urls()->api() . '/autocomplete/emails?_csrf=' . panel()->csrf()
      ));
    }

    return $input;

  }

  public function validate() {
    return v::email($this->result());
  }

}