<?php

use Kirby\Panel\Event;

return function($user) {

  $mode         = $user ? 'edit' : 'add';
  $content      = $user ? $user->data() : array();
  $translations = array();
  $roles        = array();

  // make sure the password is never shown in the form
  unset($content['password']);

  // add all languages
  foreach(panel()->translations() as $code => $translation) {
    $translations[$code] = $translation->title();
  }

  if($user) {
    // get the actual role from the user data
    // important because of the role fallbacks in the User class
    $content['role'] = $user->role()->id();

    // make sure that there's an empty role option if the user's role is invalid
    // enforces that the user can only be updated with a valid role
    if($user->role()->id() === 'nobody') $roles[null] = '';
  }

  // add all roles
  foreach(site()->roles() as $role) {
    // don't offer the fallback "nobody" role (only for internal use)
    if($role->id() === 'nobody') continue;

    $roles[$role->id()] = $role->name();
  }

  // the default set of fields
  $fields = array();

  if($user && !$user->password()) {
    // Warning that the user does not have a password and can't login
    $fields['noPasswordHelp'] = array(
      'type' => 'info',
      'text' => 'users.form.password.none.info'
    );
  }

  $fields['username'] = array(
    'label'     => 'users.form.username.label',
    'type'      => 'text',
    'icon'      => 'user',
    'autofocus' => $mode != 'edit',
    'required'  => true,
    'help'      => $mode == 'edit' ? 'users.form.username.readonly' : 'users.form.username.help',
    'readonly'  => $mode == 'edit',
  );

  $fields['firstName'] = array(
    'label'     => 'users.form.firstname.label',
    'autofocus' => $mode == 'edit',
    'type'      => 'text',
    'width'     => '1/2',
  );

  $fields['lastName'] = array(
    'label' => 'users.form.lastname.label',
    'type'  => 'text',
    'width' => '1/2',
  );

  $fields['email'] = array(
    'label'        => 'users.form.email.label',
    'type'         => 'email',
    'autocomplete' => false
  );
  
  $fields['password'] = array(
    'label'      => $mode == 'edit' && $user->password() ? 'users.form.password.new.label' : 'users.form.password.label',
    'type'       => 'password',
    'width'      => '1/2',
    'suggestion' => true,
  );

  $fields['passwordConfirmation'] = array(
    'label'    => $mode == 'edit' && $user->password() ? 'users.form.password.new.confirm.label' : 'users.form.password.confirm.label',
    'type'     => 'password',
    'width'    => '1/2',
  );

  $fields['language'] = array(
    'label'    => 'users.form.language.label',
    'type'     => 'select',
    'required' => true,
    'width'    => '1/2',
    'default'  => kirby()->option('panel.language', 'en'),
    'options'  => $translations
  );
    
  $fields['role'] = array(
    'label'    => 'users.form.role.label',
    'type'     => 'select',
    'required' => true,
    'width'    => '1/2',
    'default'  => site()->roles()->findDefault()->id(),
    'options'  => $roles,
    'readonly' => (!panel()->user()->isAdmin() or ($user and $user->isLastAdmin()))
  );

  if($user) {

    // add all custom fields
    foreach($user->blueprint()->fields()->toArray() as $name => $field) {

      if(array_key_exists($name, $fields)) {
        continue;
      }

      $fields[$name] = $field;

    }

  }

  // setup the form with all fields
  $form = new Kirby\Panel\Form($fields, $content);

  // setup the url for the cancel button
  $form->cancel('users');

  if($mode == 'add') {
    $form->buttons->submit->value = l('add');
  }

  if($user) {
    $event = $user->event('update:ui');
  } else {
    $event = panel()->user()->event('create:ui');
  }

  if($event->isDenied()) {
    $form->disable();
    $form->centered = true;
    $form->buttons->submit = '';
    $form->buttons->cancel = '';
  }

  return $form;

};

