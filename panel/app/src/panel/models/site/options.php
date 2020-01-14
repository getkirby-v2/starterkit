<?php 

namespace Kirby\Panel\Models\Site;

/**
 * Delegate to check for available 
 * site options depending on blueprint
 * settings and site attributes
 */
class Options {

  public $site;

  public function __construct($site) {
    $this->site = $site;
  }

  /**
   * Option to create new pages 
   * within this page
   */
  public function create() {

    if($this->site->children()->count() >= $this->site->maxSubpages()) {
      return false;
    } else {
      return $this->site->blueprint()->pages()->add() !== false;    
    }

  }

  /**
   * Option to update the site info
   */
  public function update() {
    return $this->site->blueprint()->options()->update();    
  }

  /**
   * Option to upload files for the site
   */
  public function upload() {

    if($this->files() === false) {
      return false;
    } else if($this->site->files()->count() >= $this->site->maxFiles()) {
      return false;
    } else {
      return $this->site->blueprint()->files()->add() !== false;    
    }    

  }

  /**
   * Option to have files
   */
  public function files() {
    return $this->site->maxFiles() !== 0;
  }

  /**
   * Option to sort files
   */
  public function sortFiles() {
    return $this->site->blueprint()->files()->sortable();
  }

  /**
   * Option to have pages
   */
  public function pages() {
    return $this->site->maxSubpages() !== 0;
  }

}