<?php

namespace Kirby\Panel;

use A;
use Brick;
use Collection;
use Exception;
use Dir;
use F;
use R;
use Str;

use Kirby\Panel\Form\Plugins;

class Form extends Brick {

  static public $root  = array();
  static public $files = null;

  public $tag         = 'form';
  public $fields      = array();
  public $values      = array();
  public $message     = null;
  public $buttons     = null;
  public $centered    = false;
  public $parentField = false;
  public $plugins     = null;

  public function __construct($fields = array(), $values = array(), $parent = false) {

    $this->fields = new Collection;

    // if Form is part of a structureField, set structureField name
    $this->parentField = $parent;

    // initialize all field plugins
    $this->plugins = new Plugins();

    $this->values($values);
    $this->fields($fields);
    $this->buttons();
    $this->attr('method', 'post');
    $this->attr('action', panel()->urls()->current());
    $this->addClass('form');

  }

  public function method($method = null) {
    return $this->attr('method', $method);
  }

  public function action($action = null) {
    return $this->attr('action', $action);
  }

  public function fields($fields = null) {

    if(is_null($fields)) return $this->fields;

    // get the site object
    $site = panel()->site();

    // check if untranslatable fields should be deactivated
    $translated = $site->multilang() && !$site->language()->default();

    foreach($fields as $name => $field) {

      $name = str_replace('-','_', str::lower($name));

      $field['name']    = $name;
      $field['default'] = a::get($field, 'default', null);
      $field['value']   = a::get($this->values(), $name, $field['default']);

      // Pass through parent field name (structureField)
      $field['parentField'] = $this->parentField;

      // Check for untranslatable fields
      if($translated and isset($field['translate']) and $field['translate'] === false) {
        $field['readonly'] = true;
        $field['disabled'] = true;
      }

      $this->fields->append($name, static::field($field['type'], $field));

    }

    return $this;

  }

  public function values($values = null) {
    if(is_null($values)) return array_merge($this->values, r::data());
    $this->values = array_merge($this->values, $values);
    return $this;
  }

  public function value($name) {
    return a::get($this->values(), $name, null);
  }

  public function validate() {

    $site       = panel()->site();
    $translated = $site->multilang() && !$site->language()->default();
    $errors     = array();

    foreach($this->fields() as $field) {

      // don't validate fields, which are not translatable
      if($translated and $field->translate() === false) continue;

      $name  = $field->name();
      $value = $this->value($name);

      if($field->required() and $value == '') {
        $field->error = true;
      } else if($value !== '' and $field->validate() == false) {
        $field->error = true;
      }

    }

  }

  public function isValid() {
    return $this->fields()->filterBy('error', true)->count() == 0;
  }

  public function message($type, $text) {

    $this->message = new Brick('div');
    $this->message->addClass('message');

    if($type == 'error') {
      $this->message->addClass('message-is-alert');      
    } else {
      $this->message->addClass('message-is-notice');
    }

    $this->message->append(function() use($text) {

      $content = new Brick('span');
      $content->addClass('message-content');
      $content->text($text);

      return $content;

    });

    return $this->message;

  }

  public function alert($text) {
    $this->message('error', $text);
  }

  public function notify($text) {
    $this->message('success', $text);
  }

  public function serialize() {

    $data   = array();
    $site   = panel()->site();
    $fields = $this->fields();

    foreach($fields as $field) {
      $result = $field->result();
      if(!is_null($result)) $data[$field->name()] = $result;
    }

    // unset untranslatable fields in all languages but the default lang
    if($site->multilang() and $site->language() != $site->defaultLanguage()) {
      foreach($fields as $field) {
        if($field->translate() === false) {
          $data[$field->name()] = null;
        }
      }
    }

    return $data;

  }

  public function toArray() {
    return $this->serialize();
  }

  public function plugins() {
    return $this->plugins;
  }

  public function style($style) {

    switch($style) {
      case 'centered':
        $this->centered = true;
        $this->buttons->cancel = '';
        break;
      case 'upload':
        $this->centered = true;
        $this->buttons->submit = '';
        $this->attr('enctype', 'multipart/form-data');
        break;
      case 'delete':
        $this->buttons->submit->addClass('btn-negative');
        $this->buttons->submit->attr('autofocus', true);
        $this->buttons->submit->val(l('delete'));
        break;
      case 'editor':

        $kirbytext = kirby()->option('panel.kirbytext', true);

        $this->data('textarea', get('textarea'));    
        $this->data('autosubmit', 'false');
        $this->data('kirbytext', r($kirbytext, 'true', 'false'));
        $this->buttons->submit->val(l('insert'));
        break;
    }

  }

  public function redirect() {
    return get('_redirect');
  }

  public function cancel() {
    if($redirect = $this->redirect()) {
      $this->buttons->cancel->href = purl($redirect);
    } else {    
      $this->buttons->cancel->href = call('purl', func_get_args());
    }
  }

  static public function field($type, $options = array()) {

    $class = $type . 'field';

    if(!class_exists($class)) {
      throw new Exception('The ' . $type . ' field is missing. Please add it to your installed fields or remove it from your blueprint');      
    }

    $field = new $class;

    foreach($options as $key => $value) {
      $field->$key = $value;
    }

    return $field;

  }

  public function buttons() {

    if(!is_null($this->buttons)) return $this->buttons;

    $this->buttons = new Collection();

    $button = new Brick('input', null);
    $button->addClass('btn btn-rounded');

    $cancel = clone $button;
    $cancel->tag('a');
    $cancel->addClass('btn-cancel');
    $cancel->attr('href', '#cancel');
    $cancel->text(l('cancel'));

    $this->buttons->append('cancel', $cancel);

    $submit = clone $button;
    $submit->attr('type', 'submit');
    $submit->addClass('btn-submit');
    $submit->data('saved', l('saved'));
    $submit->val(l('save'));        

    $this->buttons->append('submit', $submit);

    return $this->buttons;

  }

  public function on($action, $callback) {

    // auto-trigger the submit event when the form is being echoed
    if(r::is('post')) {    
      $callback($this);
    } 

    $this->fields->append('csrf', static::field('hidden', array(
      'name'  => 'csrf',
      'value' => panel()->csrf()
    )));

  }

  public function toHTML() {
    
    if($this->message) {
      $this->append($this->message);      
    }
    
    $fieldset = new Brick('fieldset');
    $fieldset->addClass('fieldset field-grid cf');

    foreach($this->fields() as $field) $fieldset->append($field);
  
    // pass the redirect url   
    $redirectField = new Brick('input');
    $redirectField->type  = 'hidden';
    $redirectField->name  = '_redirect';
    $redirectField->value = $this->redirect();
    $fieldset->append($redirectField);

    $this->append($fieldset);

    $buttons = new Brick('fieldset');
    $buttons->addClass('fieldset buttons');

    if($this->centered) {
      $buttons->addClass('buttons-centered');
    }

    foreach($this->buttons() as $button) $buttons->append($button);

    $this->append($buttons);

    return $this;

  }

  public function disable() {

    // disable all form fields
    foreach($this->fields as $field) {
      $field->readonly = true;
    }  

    // hide all the buttons
    $this->centered = true;
    $this->buttons->cancel = '';
    $this->buttons->submit = '';

  }

  public function __toString() {
    
    $this->toHTML();
    return parent::__toString();    

  }

}