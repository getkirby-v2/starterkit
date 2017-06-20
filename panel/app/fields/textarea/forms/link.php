<?php 

return function($page, $textarea) {

  $form = new Kirby\Panel\Form(array(
    'url' => array(
      'label'       => 'editor.link.url.label',
      'type'        => 'text',
      'placeholder' => 'http://',
      'autofocus'   => 'true',
      'required'    => 'true',
      'icon'        => 'chain'
    ),
    'text' => array(
      'label' => 'editor.link.text.label',
      'type'  => 'text',
      'help'  => 'editor.link.text.help',
      'icon'  => 'font'
    ),
  ));

  $form->data('textarea', 'form-field-' . $textarea);
  $form->style('editor');
  $form->cancel($page);

  return $form;

};