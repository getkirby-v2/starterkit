<?php

class BaseField {

  static public $assets = array('js' => array(), 'css' => array());

  public $id;
  public $name;
  public $input;
  public $label;
  public $icon;
  public $type;
  public $help;
  public $value;
  public $text;
  public $autofocus;
  public $placeholder;
  public $options;
  public $content;
  public $readonly;
  public $disabled;
  public $required;
  public $validate;
  public $width;
  public $default;
  public $error = false;
  public $parentField = false;
  public $page;
  public $model;
  
  public function root() {
    $obj = new ReflectionClass($this);
    return dirname($obj->getFileName());
  }

  public function validate() {

    try {

      if(!$this->validate) {
        return true;
      } else if(is_array($this->validate)) {
        foreach($this->validate as $validator => $options) {
          if(!is_null($options)) {
             if(is_numeric($validator)) {
              $result = call('v::' . $options, $this->value());
            } else {
              $result = call('v::' . $validator, array($this->value(), $options));
            }
            if(!$result) return false;
          }
        }
        return true;
      } else {
        return call('v::' . $this->validate, $this->value());
      }

    } catch(Exception $e) {
      return true;
    }

  }

  public function result() {
    return get($this->name());
  }

  public function __call($name, $args) {
    return isset($this->{$name}) ? $this->{$name} : null;
  }

  public function id() {
    if(!is_null($this->id)) return $this->id;
    return 'form-field-' . $this->name;
  }

  public function label() {

    if(!$this->label) return null;

    $label = new Brick('label', $this->i18n($this->label));
    $label->addClass('label');
    $label->attr('for', $this->id());

    if($this->required()) {
      $label->append(new Brick('abbr', '*', array('title' => l::get('required', 'Required'))));
    }

    return $label;

  }

  public function i18n($value) {

    if(empty($value)) {
      return null;
    } else if(is_array($value)) {
      $translation = a::get($value, panel()->translation()->code());

      if(empty($translation)) {
        // try to fallback to the default language at least
        $translation = a::get($value, kirby()->option('panel.language'), $this->name());
      }

      return $translation;
    } else if(is_string($value) and $translation = l::get($value)) {
      return $translation;
    } else {
      return $value;
    }

  }

  public function icon() {

    if(empty($this->icon)) {
      return null;
    } else if($this->readonly() and empty($this->icon)) {
      $this->icon = 'lock';
    }

    $i = new Brick('i');
    $i->addClass('icon fa fa-' . $this->icon);

    $icon = new Brick('div');
    $icon->addClass('field-icon');
    $icon->append($i);

    return $icon;

  }

  public function help() {

    if(!$this->help) return null;

    $help = new Brick('div');
    $help->addClass('field-help marginalia text');
    $help->html($this->i18n($this->help));
    return $help;

  }

  public function input() {
    return $this->input;
  }

  public function content() {

    $content = new Brick('div');
    $content->addClass('field-content');
    $content->append($this->input());
    $content->append($this->icon());
    return $content;

  }

  public function element() {

    $element = new Brick('div');

    $element->addClass('field');
    $element->addClass('field-grid-item');

    if($this->error) {
      $element->addClass('field-with-error');
    }

    if($this->width) {
      $element->addClass('field-grid-item-' . str_replace('/', '-', $this->width));
    }

    if($this->readonly) {
      $element->addClass('field-is-readonly');
    }

    if($this->disabled) {
      $element->addClass('field-is-disabled');
    }

    if($this->icon) {
      $element->addClass('field-with-icon');
    }

    return $element;

  }

  public function template() {

    return $this->element()
      ->append($this->label())
      ->append($this->content())
      ->append($this->help());

  }

  public function __toString() {
    try {
      return (string)$this->template();
    } catch(Exception $e) {
      die($e);
    }
  }

}