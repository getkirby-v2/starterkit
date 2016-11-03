<?php

use Kirby\Panel\Exceptions\PermissionsException;

class AvatarsController extends Kirby\Panel\Controllers\Base {

  public function upload($username) {

    $user   = $this->user($username);
    $avatar = $user->avatar();

    try {

      if($avatar->exists() && $avatar->ui()->replace() === false) {
        throw new PermissionsException();
      } 

      if(!$avatar->exists() && $avatar->ui()->upload() === false) {
        throw new PermissionsException();
      }

      $avatar->upload();        
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
      throw new Exception(l('users.avatar.missing'));
    }

    if($avatar->ui()->delete() === false) {
      throw new PermissionsException();
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