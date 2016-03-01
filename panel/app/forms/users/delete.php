<?php

return function($user) {

  $form = new Kirby\Panel\Form(array(
    'username' => array(
      'label'    => 'users.delete.headline',
      'type'     => 'text',
      'readonly' => true,
      'icon'     => false,
      'default'  => $user->username(),
      'help'     => $user->email(),
    )
  ));

  $form->style('delete');
  $form->cancel($user, 'edit');

  return $form;

};

