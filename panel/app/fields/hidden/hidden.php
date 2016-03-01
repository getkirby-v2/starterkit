<?php

class HiddenField extends BaseField {

  public function template() {
    return new Brick('input', null, array(
      'type'  => 'hidden',
      'name'  => $this->name(),
      'value' => $this->value()
    ));
  }

}