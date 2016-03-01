<?php

class TextareaFieldController extends Kirby\Panel\Controllers\Field {

  public function link() {

    $page = $this->model();
    $form = $this->form('link', array($page, $this->fieldname()));

    return $this->modal('link', compact('form'));

  }

  public function email($textarea = null) {
    
    $page = $this->model();
    $form = $this->form('email', array($page, $this->fieldname()));

    return $this->modal('email', compact('form'));

  }

}