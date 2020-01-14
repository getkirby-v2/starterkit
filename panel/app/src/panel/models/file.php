<?php

namespace Kirby\Panel\Models;

use C;
use F;
use Kirby\Panel\Event;
use Kirby\Panel\Structure;
use Kirby\Panel\Models\File\Menu;
use Kirby\Panel\Models\File\UI;
use Kirby\Panel\Models\Page\Uploader;

class File extends \File {

  public function uri($action = null) {
    if(empty($action)) {
      return parent::uri();
    } else {
      return $this->page()->uri('file') . '/' . $this->encodedFilename() . '/' . $action;
    }
  }

  public function encodedFilename() {
    if(php_sapi_name() == 'cli-server') {
      $filename = str_replace('.', '․', $this->filename());
    } else {
      $filename = $this->filename();
    }
    return rawurlencode($filename);
  }

  public static function decodeFilename($filename) {
    $filename = rawurldecode($filename);
    if(php_sapi_name() == 'cli-server') {
      $filename = str_replace('․', '.', $filename);
    }
    return $filename;
  }

  public function url($action = null) {
    if(empty($action)) {
      return parent::url();
    } else if($action == 'preview') {
      return parent::url() . '?' . $this->modified();    
    } else {
      return panel()->urls()->index() . '/' . $this->uri($action);
    }
  }

  public function menu() {
    return new Menu($this);    
  }

  public function ui() {
    return new UI($this);
  }

  public function form($action, $callback) {    
    return panel()->form('files/' . $action, $this, $callback);
  }

  public function filterInput($input) {
    return $input;
  }

  public function getBlueprintFields() {
    return $this->blueprint()->files()->fields($this);
  }

  public function getFormFields() {
    return $this->getBlueprintFields()->toArray();
  }

  public function getFormData() {
    return $this->meta()->toArray();    
  }

  public function isWebImage() {
    $images = array('image/jpeg', 'image/gif', 'image/png');
    return in_array($this->mime(), $images);
  }

  public function rename($name, $safeName = true) {

    // keep the old state of the file object
    $old   = clone $this;
    $event = $this->event('rename:action', [
      'name'     => $name,
      'safeName' => $safeName
    ]);

    // don't do anything if it's the same name
    if($name == $this->name()) return true;

    // check for permissions
    $event->check();

    // check if the name should be sanitized
    $safeName = $this->page()->blueprint()->files()->sanitize();

    // rename and get the new filename          
    $filename = parent::rename($name, $safeName);

    // clean the thumbs
    // we don't rename them as there may be totally different thumb sizes
    // for this new filename; re-generating for this single image isn't much work
    $old->removeThumbs();

    // trigger the rename hook
    kirby()->trigger($event, array($this, $old));          

  }

  public function update($data = array(), $sort = null, $trigger = true) {

    // keep the old state of the file object
    $old = clone $this;

    if($data == 'sort') {

      // create the sorting event
      $event = $this->event('sort:action', ['sort' => $sort]);

      // check for permissions
      $event->check();

      parent::update(['sort' => $sort]);

      kirby()->trigger($event, [$this, $old]);

      return true;

    }

    // rename the file if necessary
    if(!empty($data['_name'])) {
      $filename = $this->rename($data['_name']);      
    }

    // remove the name url and info
    unset($data['_name']);
    unset($data['_info']);
    unset($data['_link']);

    // don't do anything on missing data
    if(empty($data)) return true;

    // check if the form has been allowed to be submitted
    if($this->event('update:ui')->isDenied()) {
      return true;
    }

    // create the update event
    $event = $this->event('update:action', ['data' => $data]);
    
    // check for update permissions
    $event->check();

    parent::update($data);          

    if($trigger) {
      kirby()->trigger($event, [$this, $old]);
    }

  }

  public function replace() {
    new Uploader($this->page, $this);    
  }

  public function delete($force = false) {

    // create the delete event
    $event = $this->event('delete:action');

    // check for permissions
    if(!$force) $event->check();

    // remove all thumbs
    $this->removeThumbs();

    // delete the file
    parent::delete();

    kirby()->trigger($event, $this);    

  }

  public function icon($position = 'left') {

    switch($this->type()) {
      case 'image':
        return icon('file-image-o', $position);
        break;
      case 'document':
        switch($this->extension()) {
          case 'pdf':
            return icon('file-pdf-o', $position);
            break;
          case 'doc':
          case 'docx':
            return icon('file-word-o', $position);
            break;
          case 'xls':
            return icon('file-excel-o', $position);
            break;
          default:
            return icon('file-text-o', $position);
            break;
        }
        break;
      case 'code':
        return icon('file-code-o', $position);
        break;
      case 'audio':
        return icon('file-audio-o', $position);
        break;
      case 'video':
        return icon('file-video-o', $position);
        break;
      default:
        return icon('file-archive-o', $position);
        break;
    }

  }

  public function dragText() {
    if(kirby()->option('panel.kirbytext') === false) {
      switch($this->type()) {
        case 'image':
          return '![' . $this->name() . '](' . parent::url() . ')';
          break;
        default:
          return '[' . $this->filename() . '](' . parent::url() . ')';
          break;
      }    
    } else {
      switch($this->type()) {
        case 'image':
          return '(image: ' . $this->filename() . ')';
          break;
        default:
          return '(file: ' . $this->filename() . ')';
          break;
      }
    }
  }

  public function topbar($topbar) {

    $this->files()->topbar($topbar);

    $topbar->append($this->url('edit'), $this->filename());
   
  }

  public function createMeta($triggerUpdateHook = true) {

    // save default meta 
    $meta = array();

    foreach($this->page()->blueprint()->files()->fields($this) as $field) {
      $meta[$field->name()] = $field->default();
    }

    $this->update($meta, null, $triggerUpdateHook);

    return $this;

  }

  public function blueprint() {
    return $this->page->blueprint();
  }

  public function structure() {
    return new Structure($this, 'file_' . $this->page()->id() . '_' . $this->filename() . '_' . $this->site()->lang());
  }

  public function event($type, $args = []) {  
    return new Event('panel.file.' . $type, array_merge([
      'page' => $this->page(),
      'file' => $this
    ], $args));
  }

  /**
   * Remove all thumbs of the file
   */
  public function removeThumbs() {

    $pattern = $this->kirby->roots()->thumbs() . '/' . $this->page()->id() . '/' . $this->name() . '-*.' . $this->extension();

    if(!empty($pattern)) {
      foreach(glob($pattern) as $thumb) {
        f::remove($thumb);
      }      
    }

  }

}