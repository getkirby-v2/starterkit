<?php

use Kirby\Panel\Models\File;
use Kirby\Panel\Exceptions\PermissionsException;

class FilesController extends Kirby\Panel\Controllers\Base {

  public function index($id) {

    $page  = $this->page($id);
    $files = $page->files();

    // don't create the view if the page is not allowed to have files
    if($page->ui()->files() === false) {
      throw new PermissionsException();
    }

    // sort action
    $this->sort($page);

    return $this->screen('files/index', $files, array(
      'page'     => $page,
      'files'    => $files,
      'back'     => $page->url('edit'),
      'sortable' => $page->canSortFiles(),
      'uploader' => $this->snippet('uploader', array('url' => $page->url('upload')))
    ));

  }

  public function edit($id, $filename) {

    $self = $this;
    $page = $this->page($id);

    try {
      $file = $this->file($page, $filename);
    } catch(Exception $e) {
      $this->alert(l('files.error.missing.file'));
      $this->redirect($page);
    }

    // setup the form and form action
    $form = $file->form('edit', function($form) use($file, $page, $self) {

      try {

        $form->validate();

        if(!$form->isValid()) {
          throw new Exception(l('files.show.error.form'));
        }

        $file->update($form->serialize());
        $self->notify(':)');
        $self->redirect($file);
      } catch(Exception $e) {
        $self->alert($e->getMessage());
      }

    });

    return $this->screen('files/edit', $file, array(
      'form'     => $form,
      'page'     => $page,
      'file'     => $file,
      'returnTo' => url::last() == $page->url('files') ? $page->uri('files') : $page->uri('edit'),
      'uploader' => $this->snippet('uploader', array(
        'url'      => $file->url('replace'), 
        'accept'   => $file->mime(),
        'multiple' => false
      ))
    ));

  }

  public function upload($id) {

    $page = $this->page($id);

    // check if files can be uploaded for the page
    if($page->ui()->upload() === false) {
      throw new PermissionsException();
    }

    try {
      $page->upload();        
      $this->notify(':)');
    } catch(Exception $e) {
      $this->alert($e->getMessage());
    }

    $this->redirect($page);

  }

  public function replace($id, $filename) {

    $page = $this->page($id);
    $file = $this->file($page, $filename);

    // check if files can be replaced
    if($file->ui()->replace() === false) {
      throw new PermissionsException();
    }

    try {
      $file->replace();        
      $this->notify(':)');
    } catch(Exception $e) {
      $this->alert($e->getMessage());
    }

    $this->redirect($file);

  }

  public function context($id, $filename) {

    $page = $this->page($id);
    $file = $this->file($page, $filename);

    return $file->menu();

  }

  public function delete($id, $filename) {

    $self = $this;
    $page = $this->page($id);
    $file = $this->file($page, $filename);

    if($file->ui()->delete() === false) {
      throw new PermissionsException();
    }

    $form = $this->form('files/delete', $file, function($form) use($file, $page, $self) {

      try {
        $file->delete();
        $self->notify(':)');
        $self->redirect($page, 'edit');
      } catch(Exception $e) {
        $form->alert($e->getMessage());
      }

    });

    return $this->modal('files/delete', compact('form'));

  }

  protected function file($page, $filename) {

    $file = $page->file(File::decodeFilename($filename));    

    if(!$file) {
      throw new Exception(l('files.error.missing.file'));
    }

    return $file;

  }

  protected function sort($page) {

    if(!r::is('post') or get('action') != 'sort') return;

    $filenames = get('filenames');
    $counter   = 0;

    $files = $page->files()->find($filenames);

    foreach ($files as $file) {
      $counter++;
      try {
        $file->update('sort', $counter);
      } catch(Exception $e) {
      }
    }

    $this->redirect($page, 'files');

  }

}
