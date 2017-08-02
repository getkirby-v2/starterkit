<?php 

return function($page, $textarea) {

  $form = new Kirby\Panel\Form(array(
    'url' => array(
      'label' => 'editor.link.url.label',
      'type' => 'text',
      'placeholder' => 'http://',
      'autofocus' => 'true',
      'required' => 'true',
    ),
    'text' => array(
      'label' => 'editor.link.text.label',
      'type' => 'text',
      'help' => 'editor.link.text.help',
    ),
  ));

  $form->data('textarea', 'form-field-' . $textarea);
  $form->style('editor');
  $form->cancel($page, 'show');

  return $form;

};