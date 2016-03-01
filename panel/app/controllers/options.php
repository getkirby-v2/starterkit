<?php

class OptionsController extends Kirby\Panel\Controllers\Base {

  public function index() {

    $self    = $this;
    $site    = panel()->site();
    $sidebar = $site->sidebar();
    $form    = $site->form('edit', function($form) use($site, $self) {
      
      // validate all fields
      $form->validate();

      // stop at invalid fields
      if(!$form->isValid()) {
        return $self->alert(l('pages.show.error.form'));
      }

      try {
        $site->update($form->serialize());
        $self->notify(':)');
        return $self->redirect('options');
      } catch(Exception $e) {
        return $self->alert($e->getMessage());
      }

    });

    return $this->screen('options/index', $site, array(
      'site'     => $site,
      'form'     => $form,
      'files'    => $sidebar->files(),
      'license'  => panel()->license(),
      'uploader' => $this->snippet('uploader', array('url' => $site->url('upload')))
    ));

  }

}