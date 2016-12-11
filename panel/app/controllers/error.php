<?php

class ErrorController extends Kirby\Panel\Controllers\Base {

  public function index($text = null) {

    if(is_null($text)) {
      $text = l('pages.error.missing');
    }

    if(server::get('HTTP_MODAL')) {
      return $this->modal('error', array(
        'text' => $text, 
        'back' => url::last(),
      ));
    } else {
      return $this->screen('error/index', 'error', array(
        'text' => $text
      ));      
    }

  }

}