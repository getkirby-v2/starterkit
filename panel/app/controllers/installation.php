<?php

use Kirby\Panel\Installer;

class InstallationController extends Kirby\Panel\Controllers\Base {

  public function index() {

    $installer = new Installer();

    if($installer->isCompleted()) {
      $this->redirect();
    } else if($problems = $installer->problems()) {
      return $this->problems($problems);
    } else {
      return $this->signup();
    }

  }

  protected function problems($problems) {
    $form = $this->form('installation/check', array($problems));        
    return $this->modal('index', compact('form'));
  }

  protected function signup() {

    $self = $this;
    $form = $this->form('installation/signup', array(), function($form) use($self) {

      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      try {

        // fetch all the form data
        $data = $form->serialize();

        // make sure that the first user is an admin
        $data['role'] = 'admin';

        // try to create the new user
        $user = site()->users()->create($data);

        // store the new username for the login screen
        s::set('username', $user->username());

        // try to login the user automatically
        if($user->hasPanelAccess()) {
          $user->login($data['password']);
        }

        // redirect to the login
        $self->redirect('login');

      } catch(Exception $e) {
        $form->alert($e->getMessage());
      }

    });

    return $this->modal('index', compact('form'));

  }

  public function modal($view, $data = array()) {
    return $this->layout('base', array(
      'bodyclass' => 'installation',
      'content'   => $this->view('installation/' . $view, $data)
    ));
  }

}