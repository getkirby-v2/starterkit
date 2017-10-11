<?php 

namespace Kirby\Panel\Models\Page;

use Error;
use Exception;
use F;
use Str;
use Kirby\Panel\Event;
use Kirby\Panel\Upload;

class Uploader {

  public $kirby;
  public $page;
  public $file;
  public $blueprint;
  public $filename;

  public function __construct($page, $file = null) {

    $this->page      = $page;
    $this->file      = $file;
    $this->blueprint = $page->blueprint();
    $this->filename  = $this->blueprint->files()->sanitize() ? '{safeFilename}' : '{filename}';

    if($this->file) {
      $this->replace();
    } else {
      $this->upload();      
    }

  }

  public function upload() {

    $upload = new Upload($this->page->root() . DS . $this->filename, array(
      'overwrite' => true,
      'accept'    => function($file) {

        $callback = kirby()->option('panel.upload.accept');

        if(is_callable($callback)) {
          return call($callback, $file);
        } else {
          return true;
        }

      }
    ));

    $event = $this->page->event('upload:action');
    $file  = $this->move($upload, $event);

    // create the initial meta file
    // without triggering the update hook
    try {
      $file->createMeta(false);      
    } catch(Exception $e) {
      // don't react on meta errors
      // the meta file can still be generated later
    }

    // make sure that the file is being marked as updated
    touch($file->root());

    kirby()->trigger($event, $file);          

  }

  public function replace() {

    $file   = $this->file;    
    $upload = new Upload($file->root(), array(
      'overwrite' => true,
      'accept' => function($upload) use($file) {
        if($upload->mime() != $file->mime()) {
          throw new Error(l('files.replace.error.type'));
        }
      }
    ));

    // keep the old state of the file object
    $old   = clone $file;
    $event = $file->event('replace:action');
    $file  = $this->move($upload, $event);

    // make sure that the file is being marked as updated
    touch($file->root());

    // clean all thumbs of the file
    $file->removeThumbs();

    kirby()->trigger($event, [$file, $old]);

  }

  public function move($upload, $event) {

    // flush all cached files
    $this->page->reset();

    // get the file object from the upload
    $uploaded = $upload->file();

    // check if the upload worked
    if(!$uploaded) {
      throw new Exception($upload->error()->getMessage());
    }

    // check if the page has such a file
    $file = $this->page->file($uploaded->filename());

    // delete the upload if something went wrong
    if(!$file) {
      $uploaded->delete();
      throw new Exception(l('files.error.missing.file'));
    }

    try {
      // add the uploaded file to the event target
      $event->target->upload = $file;
      // and check for permissions
      $event->check();
      // run additional file checks
      $this->checkUpload($file);
      return $file;
    } catch(Exception $e) {
      $file->delete(true);
      throw $e;
    }

  }

  public function checkUpload($file) {

    $filesettings        = $this->blueprint->files();
    $forbiddenExtensions = array('php', 'html', 'htm', 'exe', kirby()->option('content.file.extension', 'txt'));
    $forbiddenMimes      = array_merge(f::$mimes['php'], array('text/html', 'application/x-msdownload'));
    $extension           = strtolower($file->extension());

    // files without extension are not allowed
    if(empty($extension)) {
      throw new Exception(l('files.add.error.extension.missing'));
    }

    // block forbidden extensions
    if(in_array($extension, $forbiddenExtensions)) {
      throw new Exception(l('files.add.error.extension.forbidden'));
    }

    // especially block any connection that contains php
    if(str::contains($extension, 'php')) {
      throw new Exception(l('files.add.error.extension.forbidden'));
    }

    // block forbidden mimes
    if(in_array(strtolower($file->mime()), $forbiddenMimes)) {
      throw new Exception(l('files.add.error.mime.forbidden'));
    }

    // Block htaccess files
    if(strtolower($file->filename()) == '.htaccess') {
      throw new Exception(l('files.add.error.htaccess'));
    }

    // Block invisible files
    if(str::startsWith($file->filename(), '.')) {
      throw new Exception(l('files.add.error.invisible'));
    }

    // Files blueprint option 'type'
    if(count($filesettings->type()) > 0 and !in_array($file->type(), $filesettings->type())) {
      throw new Exception(l('files.add.blueprint.type.error') . ' ' . implode(', ', $filesettings->type()));
    }

    // Files blueprint option 'size'
    if($filesettings->size() and f::size($file->root()) > $filesettings->size()) {
      throw new Exception(l('files.add.blueprint.size.error') . ' ' . f::niceSize($filesettings->size()));
    }

    // Files blueprint option 'width'
    if($file->type() == 'image' and $filesettings->width() and $file->width() > $filesettings->width()) {
      throw new Exception('Page only allows image width of ' . $filesettings->width().'px');
    }

    // Files blueprint option 'height'
    if($file->type() == 'image' and $filesettings->height() and $file->height() > $filesettings->height()) {
      throw new Exception('Page only allows image height of ' . $filesettings->height().'px');
    } 

  }

}