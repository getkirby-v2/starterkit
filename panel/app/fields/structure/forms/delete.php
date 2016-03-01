<?php 

return function($model) {

  $form = new Kirby\Panel\Form(array(
    'entry' => array(
      'label' => 'fields.structure.delete.label',
      'type'  => 'info',
    )
  ));

  $form->style('delete');
  $form->cancel($model);

  return $form;

};