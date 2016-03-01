<?php

class AvatarsController extends Kirby\Panel\Controllers\Base {

  public function upload($username) {

    $user = $this->user($username);

    try {
      $user->avatar()->upload();        
      $this->notify(':)');
    } catch(Exception $e) {
      $this->alert($e->getMessage());
    }

    $this->redirect($user);        

  }

  public function delete($username) {

    $self   = $this;
    $user   = $this->user($username);
    $avatar = $user->avatar();

    if(!$avatar->exists()) {
      return $this->modal('error', array(
        'text' => l('users.avatar.missing'),
        'back' => $user->url()
      ));
    }

    $form = $avatar->form('delete', function($form) use($user, $avatar, $self) {

      try {
        $avatar->delete();        
        $self->notify(':)');
        $self->redirect($user);
      } catch(Exception $e) {
        $form->alert($e->getMessage());
      }

    });

    return $this->modal('avatars/delete', compact('form'));

  }

}