<?php 

return function($avatar) {

  $form = new Kirby\Panel\Form(array(
    'image' => array(
      'type' => 'info'
    )
  ));

  $form->fields->image->text = '(image: ' . $avatar->url() . ' class: avatar avatar-full avatar-centered)';
  $form->centered = true;
  $form->style('delete');

  return $form;

};


