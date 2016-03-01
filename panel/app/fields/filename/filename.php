<?php

class FilenameField extends TextField {

  public $extension = null;
  public $icon      = true;

  public function icon() {

    $icon = new Brick('div');
    $icon->addClass('field-icon');
    $icon->append('<span>.' . $this->extension . '</span>');

    return $icon;

  }

}
