<?php 

namespace Kirby\Panel\Models\Site;

class UI {

  public $site;

  public function __construct($site) {
    $this->site = $site;
  }

  public function create() {
    if($this->site->options()->create() === false) {
      return false;
    } else {
      return $this->site->event('create:ui')->isAllowed();      
    }
  }

  public function update() {
    if($this->site->options()->update() === false) {
      return false;
    } else {
      return $this->site->event('update:ui')->isAllowed();      
    }
  }

  public function upload() {
    if($this->site->options()->upload() === false) {
      return false;
    } else {
      return $this->site->event('upload:ui')->isAllowed();      
    }
  }

  public function files() {
    return $this->site->options()->files();
  }

  public function pages() {
    return $this->site->options()->pages();
  }

}