<?php

class ImageField extends SelectField {

  public $extension;

  public function __construct() {
    $this->icon = 'image';
  }

  public function element() {
    $element = parent::element();
    $element->addClass('field-with-image');
    $element->data('field', 'imagefield');
    return $element;
  }

  public function image() {
    return $this->page->image($this->value());
  }

  public function preview() {

    $figure = new Brick('figure');

    if($image = $this->image()) {
      $figure->attr('style', 'background-image: url(' . $image->crop(75)->url() . ')');      
      $url = $image->url('edit');
    } else {
      $figure->attr('style', 'background-image: url(' . $this->value() . ')');      
      $url = '';
    }

    return '<a href="' . $url . '" class="input-preview">' . $figure . '</a>';

  }

  public function input() {
    return $this->preview() . parent::input();
  }

  public function option($filename, $image, $selected = false) {

    if($image == '') {
      return new Brick('option', '', array(
        'value'    => '',
        'selected' => $selected
      ));
    } else {      
      return new Brick('option', $image->filename(), array(
        'value'      => $filename,
        'selected'   => $selected,
        'data-url'   => $image->url('edit'),
        'data-thumb' => $image->crop(75)->url()
      ));
    }

  }

  public function options() {

    $options = [];

    foreach($this->images() as $image) {
      $options[$image->filename()] = $image;
    }

    return $options;

  }

  public function images() {

    $images = $this->page->images();

    if(!empty($this->extension)) {

      if(!is_array($this->extension)) {
        $extensions = [$this->extension];
      } else {
        $extensions = $this->extension;
      }
      
      $images = $images->filter(function($image) use($extensions) {
        return in_array(strtolower($image->extension()), $extensions);
      });        
    
    }

    return $images;

  }

}
