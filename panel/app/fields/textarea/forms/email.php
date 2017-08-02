<?php 

return function($page, $textarea) {

  $form = new Kirby\Panel\Form(array(
    'address' => array(
      'label'       => 'editor.email.address.label',
      'type'        => 'email',
      'placeholder' => 'editor.email.address.placeholder',
      'autofocus'   => 'true',
      'required'    => 'true',
    ),
    'text' => array(
      'label' => 'editor.email.text.label',
      'type'  => 'text',
      'help'  => 'editor.email.text.help',
      'icon'  => 'font'
    )
  ));

  $form->data('textarea', 'form-field-' . $textarea);
  $form->style('editor');
  $form->cancel($page);

  return $form;

};