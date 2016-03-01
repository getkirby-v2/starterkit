<?php 

return function($file) {

  $form = new Kirby\Panel\Form(array(
    'file' => array(
      'label'    => 'files.delete.headline',
      'type'     => 'text',
      'readonly' => true,
      'icon'     => false,
      'default'  => $file->filename()
    )
  ));

  $form->style('delete');
  $form->cancel($file);

  return $form;

};