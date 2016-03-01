<?php

use Kirby\Panel\Login;

class AuthController extends Kirby\Panel\Controllers\Base {

  public function login() {

    $login = new Login();

    if($login->isAuthenticated()) {
      $this->redirect();
    }

    if($login->isBlocked()) {
      return $this->layout('base', array(
        'content' => $this->view('auth/block')
      ));
    }

    $self = $this;
    $form = $this->form('auth/login', null, function($form) use($self, $login) {

      $data = $form->serialize();      

      try {
        $login->attempt($data['username'], $data['password']);
        $self->redirect();
      } catch(Exception $e) {
        $form->alert(l('login.error'));
        $form->fields->username->error = true;
        $form->fields->password->error = true;
      }

    });

    return $this->layout('base', array(
      'bodyclass' => 'login',
      'content'   => $this->view('auth/login', compact('form'))
    ));

  }

  public function logout() {

    if($user = panel()->user()) {
      $user->logout();
    }

    $this->redirect('login');

  }

}