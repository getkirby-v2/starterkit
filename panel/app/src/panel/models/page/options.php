<?php 

namespace Kirby\Panel\Models\Page;

/**
 * Delegate to check for available 
 * page options depending on blueprint
 * settings and page attributes
 */
class Options {

  public $page;

  public function __construct($page) {
    $this->page = $page;
  }

  /**
   * Option to list the current page
   */
  public function show() {
    return $this->page->blueprint()->hide() !== true;
  }

  /**
   * Option to create new pages 
   * within this page
   */
  public function create() {

    if($this->pages() === false) {
      return false;
    } else if($this->page->children()->count() >= $this->page->maxSubpages()) {
      return false;
    } else {
      return $this->page->blueprint()->pages()->add() !== false;    
    }

  }

  /**
   * Option to update the page
   */
  public function update() {
    return $this->page->blueprint()->options()->update();    
  }

  /**
   * Option to show the page preview
   */
  public function preview() {
    return $this->page->blueprint()->options()->preview();
  }

  /**
   * Option to change the visibility 
   * of this page
   */
  public function visibility() {

    if($this->page->isErrorPage()) {
      return false;
    } else if($this->page->blueprint()->options()->visibility() === false) {
      return false;
    } else {
      return true;
    }

  }

  /**
   * Option to change the URL of this page
   */
  public function url() {

    if($this->page->isHomePage()) {
      return false;
    } else if($this->page->isErrorPage()) {
      return false;
    } else if($this->page->blueprint()->options()->url() === false) {
      return false;
    } else {
      return true;
    }

  }

  /**
   * Option to change the template 
   * of this page
   */
  public function template() {

    if($this->page->isHomePage()) {
      return false;
    } else if($this->page->isErrorPage()) {
      return false;
    } else if($this->page->blueprint()->options()->template() === false) {
      return false;
    } else {
      return $this
        ->page
        ->parent()
        ->blueprint()
        ->pages()
        ->template()
        ->count() > 1;
    }

  }

  /**
   * Option to upload files for this page
   */
  public function upload() {

    if($this->files() === false) {
      return false;
    } else if($this->page->files()->count() >= $this->page->maxFiles()) {
      return false;
    } else {
      return $this->page->blueprint()->files()->add() !== false;    
    }    

  }

  /**
   * Option to have files
   */
  public function files() {
    return $this->page->maxFiles() !== 0;
  }

  /**
   * Option to have subpages
   */
  public function pages() {
    return $this->page->maxSubpages() !== 0;
  }

  /**
   * Option to sort this page
   */
  public function sort() {  
    if($this->page->isErrorPage()) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * Option to delete the page
   */
  public function delete() {

    if($this->page->isHomePage()) {
      return false;
    } else if($this->page->isErrorPage()) {
      return false;
    } else if($this->page->hasChildren()) {
      return false;
    } else if($this->page->blueprint()->deletable() === false) {
      return false;
    } else if($this->page->blueprint()->options()->delete() === false) {
      return false;
    } else {
      return true;
    }

  }

}