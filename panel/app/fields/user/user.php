<?php

class UserField extends SelectField {

  public function __construct() {
    $this->type    = 'text';
    $this->icon    = 'user';
    $this->label   = l::get('fields.user.label', 'User');
  }

  // generating user list here instead of in the constructor because of
  // https://github.com/getkirby/panel/issues/1034
  public function options() {
    $options = null;

    foreach(kirby()->site()->users() as $user) {
      $options[$user->username()] = $user->username();
    }

    return $options;
  }

  public function value() {
    $value = parent::value();

    // default to current user if no default one is defined
    if(empty($value) && !$this->default && $this->default !== false) {
      return site()->user()->username();
    } else {
      return $value;
    }
  }

}
