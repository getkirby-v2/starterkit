<?php

class AutocompleteController extends Kirby\Panel\Controllers\Base {

  public function index($method) {
    
    try {
      $auto   = new Kirby\Panel\Autocomplete(panel(), $method, get());
      $result = $auto->result();
    } catch(Exception $e) {
      $result = array();
    }

    return $this->json(array(
      'data' => $result
    ));

  }

}