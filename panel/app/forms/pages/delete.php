<?php 

return function($page) {

  $form = new Kirby\Panel\Form(array(
    'page' => array(
      'label'    => 'pages.delete.headline',
      'type'     => 'text',
      'readonly' => true,
      'icon'     => false,
      'default'  => $page->title(),
      'help'     => $page->id(),
    )
  ));

  $form->style('delete');
  $form->cancel($page);

  return $form;

};