<?php 

namespace Kirby\Panel\Models\Page;

class Sorter {

  public $page;
  public $parent;
  public $siblings;
  public $params;
  public $to;

  public function __construct($page) {

    $this->page     = $page;
    $this->parent   = $page->parent();
    $this->params   = $this->parent->blueprint()->pages()->num();
    $this->siblings = $this->parent->children()->visible();

  }

  protected function execute() {

    switch($this->params->mode()) {
      case 'date':
        $this->date();
        break;
      case 'zero':
        $this->zero();
        break;
      default:
        $this->num();
        break;
    }

  }

  protected function zero() {
    foreach($this->siblings as $sibling) {
      $sibling->_sort(0);
    }
  }

  protected function date() {

    foreach($this->siblings as $sibling) {

      // get the date
      $date = $sibling->date($this->params->format(), $this->params->field());

      // take the current date if the 
      if(!$date) {
        $date = date($this->params->format());
      }

      $sibling->_sort($date);

    }

  }

  protected function num() {

    // make sure the siblings are sorted correctly
    $this->siblings = $this->siblings->not($this->page)->sortBy('num', 'asc');

    // special keywords and sanitization
    if($this->to == 'last') {
      $this->to = $this->siblings->count() + 1;
    } else if($this->to == 'first') {
      $this->to = 1;
    } else if($this->to === false) {
      $this->to = false;
    } else if($this->to < 1) {
      $this->to = 1; 
    }

    // start the index
    $n = 0;

    if($this->to === false) {
      foreach($this->siblings as $sibling) {
        $n++; $sibling->_sort($n);
      }
    } else {

      // go through all items before the selected page
      foreach($this->siblings->slice(0, $this->to - 1) as $sibling) {
        $n++; $sibling->_sort($n);
      }

      // add the selected page
      $n++; $this->page->_sort($n);

      // go through all the items after the selected page
      foreach($this->siblings->slice($this->to - 1) as $sibling) {
        $n++; $sibling->_sort($n);
      }

    }

  }

  public function to($to) {
    $this->siblings->data[$this->page->id()] = $this->page;      
    $this->to = $to;
    $this->execute();
  }

  public function delete() {
    $this->siblings = $this->siblings->not($this->page);
    $this->to       = false;
    $this->execute();
  }

  public function hide() {
    $this->siblings = $this->siblings->not($this->page);
    $this->to       = false;
    $this->execute();
  }

}