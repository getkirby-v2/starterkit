<?php

class ToggleField extends RadioField {

  public $text = 'yes/no';

  public function options() {

    switch(strtolower($this->text())) {
      case 'yes/no':
        $true  = l::get('fields.toggle.yes');
        $false = l::get('fields.toggle.no');
        break;
      case 'on/off':
        $true  = l::get('fields.toggle.on');
        $false = l::get('fields.toggle.off');
        break;
    }

    return array(
      'true'  => $true,
      'false' => $false
    );

  }

  public function value() {
    $value = parent::value();

    if(in_array($value, array('yes', 'true', true, 1, 'on'), true)) {
      return 'true';
    } else {
      return 'false';
    }

  }

}
