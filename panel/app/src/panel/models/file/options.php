<?php 

namespace Kirby\Panel\Models\File;

/**
 * Delegate to check for available 
 * file options depending on blueprint
 * settings and file attributes
 */
class Options {

  public $file;
  public $page;

  public function __construct($file) {
    $this->file = $file;
    $this->page = $file->page();
  }

  public function preview() {
    
    if($this->file->isWebImage()) {
      return true;
    } else if($this->file->extension() === 'svg') {
      return true;
    } else {
      return false;
    }

  }

  public function thumb() {

    if($this->file->isWebImage() === false) {
      return false;
    } else if(kirby()->option('thumbs.driver') == 'gd') {
      if($this->width() > 2048 or $this->height() > 2048) {
        return false;
      } else {
        return true;
      }
    } else {
      return true;      
    }

  }

}