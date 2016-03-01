<?php

class TagsField extends TextField {

  public function __construct() {

    $this->icon      = 'tag';
    $this->label     = l::get('fields.tags.label', 'Tags');
    $this->index     = 'siblings';
    $this->separator = ',';
    $this->lower     = false;

  }

  public function input() {

    $input = parent::input();
    $input->addClass('input-with-tags');
    $input->data(array(
      'field'     => 'tags',
      'lowercase' => $this->lower ? 'true' : false,
      'separator' => $this->separator,
    ));

    if(isset($this->data)) {

      $input->data('url', html(json_encode($this->data), false));

    } else if($page = $this->page()) {

      empty($this->field) ? $field = $this->name() : $field = $this->field;

      $query = array(
        'uri'       => $page->id(),
        'index'     => $this->index(),
        'field'     => $field,
        'yaml'      => $this->parentField,
        'separator' => $this->separator(),
        '_csrf'     => panel()->csrf(),
      );

      $input->data('url', panel()->urls()->api() . '/autocomplete/field?' . http_build_query($query));

    }

    return $input;

  }

}
