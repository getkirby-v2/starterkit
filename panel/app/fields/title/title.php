<?php

class TitleField extends TextField {

  public function __construct() {

    $this->label    = l::get('fields.title.label', 'Title');
    $this->icon     = 'font';
    $this->required = true;

  }

  public function help() {

    if($this->page and !$this->page->isSite()) {
      
      if(!empty($this->help)) {
        $this->help .= '<br />';
      }

      $this->help .= '&rarr;&nbsp;&nbsp;<a style="color: #777; border:none" data-modal title="' . $this->page->previewUrl() . '" href="' . $this->page->url('url') . '">' . ltrim($this->page->parent()->slug() . '/', '/') . $this->page->slug() . '</a>';      

    }

    return parent::help();

  }

}
