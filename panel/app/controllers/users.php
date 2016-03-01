<?php

use Kirby\Panel\Models\User;

class UsersController extends Kirby\Panel\Controllers\Base {

  public function index() {

    $users      = panel()->users()->paginate(20, array('method' => 'query'));
    $admin      = panel()->user()->isAdmin();    
    $pagination = $this->snippet('pagination', array(
      'pagination' => $users->pagination(),
      'nextUrl'    => $users->pagination()->nextPageUrl(),
      'prevUrl'    => $users->pagination()->prevPageUrl(),
    ));

    return $this->screen('users/index', $users, array(
      'users'      => $users,
      'admin'      => $admin,
      'pagination' => $pagination
    ));

  }

  public function add() {

    if(!panel()->user()->isAdmin()) {
      $this->redirect('users');
    }

    $self = $this;
    $form = $this->form('users/user', null, function($form) use($self) {
      
      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      $data = $form->serialize();

      try {
        $user = panel()->users()->create($data);
        $self->notify(':)');
        $self->redirect('users');
      } catch(Exception $e) {
        $self->alert($e->getMessage());
      }

    });

    return $this->screen('users/edit', 'user', array(
      'user'     => null,
      'form'     => $form,
      'writable' => is_writable(kirby()->roots()->accounts()),
      'uploader' => null
    ));

  }

  public function edit($username) {

    $self = $this;
    $user = $this->user($username);

    if(!panel()->user()->isAdmin() and !$user->isCurrent()) {
      $this->redirect('users');
    }

    $form = $user->form('user', function($form) use($user, $self) {
      
      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      $data = $form->serialize();
      
      try {
        $user->update($data);
        $self->notify(':)');
        $self->redirect($user, 'edit');
      } catch(Exception $e) {
        $self->alert($e->getMessage());
      }
        
    });

    return $this->screen('users/edit', $user, array(
      'user'     => $user,
      'form'     => $form,
      'writable' => is_writable(kirby()->roots()->accounts()),
      'uploader' => $this->snippet('uploader', array(
        'url'      => $user->url('avatar'),
        'accept'   => 'image/jpeg,image/png,image/gif',
        'multiple' => false
      ))
    ));

  }

  public function delete($username) {

    $user = $this->user($username);
    $self = $this;

    if(!panel()->user()->isAdmin() and !$user->isCurrent()) {
      return $this->modal('error', array(
        'headline' => l('error'),
        'text'     => l('users.delete.error.rights'),
        'back'     => purl('users')
      ));
    } else {

      $form = $user->form('delete', function($form) use($user, $self) {

        try {
          $user->delete();
          $self->notify(':)');
          $self->redirect('users');
        } catch(Exception $e) {
          $form->alert($e->getMessage());
        }

      });

      return $this->modal('users/delete', compact('form'));

    }

  }

}