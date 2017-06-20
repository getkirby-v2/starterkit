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

      $input->data('url', json_encode($this->data));

    } else if($page = $this->page()) {

      $field = empty($this->field) ? $this->name() : $this->field;
      $model = is_a($this->model, 'File') ? 'file' : 'page';

      $query = array(
        'uri'       => $page->id(),
        'index'     => $this->index(),
        'field'     => $field,
        'yaml'      => $this->parentField,
        'model'     => $model,
        'separator' => $this->separator(),
        '_csrf'     => panel()->csrf(),
      );

      $input->data('url', panel()->urls()->api() . '/autocomplete/field?' . http_build_query($query));

    }

    return $input;

  }

}
