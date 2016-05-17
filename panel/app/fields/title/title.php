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
        $this->help  = $this->i18n($this->help);
        $this->help .= '<br />';
      }

      // build a readable version of the page slug
      $slug = ltrim($this->page->parent()->slug() . '/', '/') . $this->page->slug();

      // TODO: move this to the css file
      $style = 'padding-left: .5rem; color: #777; border:none';

      if($this->page->canChangeUrl()) {
        $this->help .= '&rarr;<a style="' . $style . '" data-modal title="' . $this->page->url('preview') . '" href="' . $this->page->url('url') . '">' . $slug . '</a>';      
      } else {
        $this->help .= '&rarr;<span style="' . $style . '" title="' . $this->page->url('preview') . '">' . $slug . '</span>';      
      }

    }

    return parent::help();

  }

}
