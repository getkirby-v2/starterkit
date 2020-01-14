<?php 

return function($page) {

  $fields = [
    'page' => array(
      'label'    => 'pages.delete.headline',
      'type'     => 'text',
      'readonly' => true,
      'icon'     => false,
      'default'  => $page->title(),
      'help'     => $page->id(),
    )
  ];

  if($page->children()->count()) {
    $fields['check'] = [
      'label'    => 'pages.delete.children.headline',
      'type'     => 'checkbox',
      'text'     => 'pages.delete.children.text',
      'help'     => 'pages.delete.children.help',
      'required' => true
    ];
  }

  $form = new Kirby\Panel\Form($fields);
  $form->style('delete');
  $form->cancel($page);

  return $form;

};