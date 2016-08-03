<?php

namespace Kirby\Panel\Models;

use C;
use Kirby\Panel\Structure;
use Kirby\Panel\Models\File\Menu;
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

  public function canHavePreview() {
    return $this->isWebImage() or $this->extension() == 'svg';    
  }  

  public function isWebImage() {
    $images = array('image/jpeg', 'image/gif', 'image/png');
    return in_array($this->mime(), $images);
  }

  public function canHaveThumb() {
    if(!$this->isWebImage()) {
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

  public function rename($name, $safeName = true) {

    // keep the old state of the file object
    $old = clone $this;

    if($name == $this->name()) return true;

    // check if the name should be sanitized
    $safeName = $this->page()->blueprint()->files()->sanitize();

    // rename and get the new filename          
    $filename = parent::rename($name, $safeName);

    // clean the thumbs folder
    $this->page()->removeThumbs();

    // trigger the rename hook
    kirby()->trigger('panel.file.rename', array($this, $old));          

  }

  public function update($data = array(), $sort = null, $trigger = true) {

    // keep the old state of the file object
    $old = clone $this;

    if($data == 'sort') {
      parent::update(array('sort' => $sort));
      kirby()->trigger('panel.file.sort', array($this, $old));
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

    if(!empty($data)) {
      parent::update($data);          
    }

    if($trigger) {
      kirby()->trigger('panel.file.update', array($this, $old));
    }

  }

  public function replace() {
    new Uploader($this->page, $this);    
  }

  public function delete() {

    parent::delete();

    // clean the thumbs folder
    $this->page()->removeThumbs();

    kirby()->trigger('panel.file.delete', $this);    

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

}