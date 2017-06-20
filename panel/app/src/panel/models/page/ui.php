<?php 

namespace Kirby\Panel\Models\Page;

class UI {

  public $page;

  public function __construct($page) {
    $this->page = $page;
  }

  public function show() {
    return $this->page->options()->show() && $this->read();
  }

  public function read() {
    return $this->page->event('read:ui')->isAllowed();      
  }

  public function create() {
    if($this->page->options()->create() === false) {
      return false;
    } else {
      return $this->page->event('create:ui')->isAllowed();      
    }
  }

  public function update() {
    if($this->page->options()->update() === false) {
      return false;
    } else {
      return $this->page->event('update:ui')->isAllowed();      
    }
  }

  public function delete() {
    if($this->page->options()->delete() === false) {
      return false;
    } else {
      return $this->page->event('delete:ui')->isAllowed();      
    }
  }

  public function url() {
    if($this->page->options()->url() === false) {
      return false;
    } else {
      return $this->page->event('url:ui')->isAllowed();      
    }
  }

  public function template() {
    if($this->page->options()->template() === false) {
      return false;
    } else {
      return $this->page->event('template:ui')->isAllowed();      
    }
  }

  public function visibility() {
    if($this->page->options()->visibility() === false) {
      return false;
    } else {
      return $this->page->event('visibility:ui')->isAllowed();      
    }
  }

  public function pages() {
    return $this->page->options()->pages();
  }

  public function files() {
    return $this->page->options()->files();
  }

  public function upload() {
    if($this->page->options()->upload() === false) {
      return false;
    } else {
      return $this->page->event('upload:ui')->isAllowed();      
    }
  }

}