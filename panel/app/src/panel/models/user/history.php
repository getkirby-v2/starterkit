<?php

namespace Kirby\Panel\Models\User;

use Exception;

class History {

  public $user;

  public function __construct($user) {
    if($user = kirby()->site()->user($user->username())) {
      $this->user = $user;
    } else {
      throw new Exception('The user could not be found');
    }
  }

  public function add($id) {

    if(is_a('Kirby\\Panel\\Models\\Page', $id)) {
      $page = $id;
    } else {
      if(empty($id)) return false;

      try {
        $page = panel()->page($id);
      } catch(Exception $e) {
        return false;
      }      
    }

    $history = $this->get();

    // remove existing entries
    foreach($history as $key => $val) {
      if($val->id() == $page->id()) unset($history[$key]);
    }

    array_unshift($history, $page->id());
    $history = array_slice($history, 0, 5);

    try {
      $this->user->update(array(
        'history' => $history
      ));
    } catch(Exception $e) {

    }

  }

  public function get() {

    $history = $this->user->__get('history');

    if(empty($history) or !is_array($history)) {
      return array();
    }

    $update = false;
    $result = array();

    foreach($history as $item) {

      try {
        $result[] = panel()->page($item);        
      } catch(Exception $e) {
        $update = true;
      }

    }

    if($update) {

      $history = array_map(function($item) {
        return $item->id();
      }, $result);

      try {
        $this->user->update(array(
          'history' => $history
        ));
      } catch(Exception $e) {

      }

    }

    return $result;

  }

}