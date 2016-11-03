<?php 

namespace Kirby\Panel\Models\File;

class UI {

  public $file;

  public function __construct($file) {
    $this->file = $file;
  }

  public function update() {
    return $this->file->event('update:ui')->isAllowed();
  }

  public function rename() {
    return $this->file->event('rename:ui')->isAllowed();   
  }

  public function replace() {
    return $this->file->event('replace:ui')->isAllowed();
  }

  public function delete() {
    return $this->file->event('delete:ui')->isAllowed();
  }

}