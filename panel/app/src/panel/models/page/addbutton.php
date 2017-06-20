<?php

namespace Kirby\Panel\Models\Page;

use Exception;
use Obj;

class AddButton extends Obj {

  public function __construct($page) {

    $this->page  = $page;
    $this->modal = true;
    $this->url   = $this->page->url('add');

    if(!$this->page->ui()->create()) {
      throw new Exception(l('subpages.add.error.more'));
    }

  }  

}