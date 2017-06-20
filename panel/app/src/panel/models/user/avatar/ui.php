<?php 

namespace Kirby\Panel\Models\User\Avatar;

class UI {

  public $avatar;

  public function __construct($avatar) {
    $this->avatar = $avatar;
  }

  public function active() {
    if($this->avatar->exists()) {
      return $this->replace() || $this->delete();
    } else {
      return $this->upload();
    }
  }

  public function upload() {
    return $this->avatar->event('upload:ui')->isAllowed();
  }

  public function replace() {
    return $this->avatar->event('replace:ui')->isAllowed();
  }

  public function delete() {
    return $this->avatar->event('delete:ui')->isAllowed();
  }

}