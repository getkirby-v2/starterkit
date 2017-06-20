<?php 

namespace Kirby\Panel\Models\User;

class UI {

  public $user;

  public function __construct($user) {
    $this->user = $user;
  }

  public function read() {
    return $this->user->event('read:ui')->isAllowed();
  }

  public function create() {
    return $this->user->event('create:ui')->isAllowed();
  }

  public function update() {
    return $this->user->event('update:ui')->isAllowed();
  }

  public function delete() {
    return $this->user->event('delete:ui')->isAllowed();
  }

}