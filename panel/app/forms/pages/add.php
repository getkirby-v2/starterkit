<?php 

return function($page) {

  $options = [];

  foreach($page->blueprint()->pages()->template() as $template) {
    $options[$template->name()] = $template->title();
  }

  $form = new Kirby\Panel\Form(array(
    'title' => array(
      'label'        => 'pages.add.title.label',
      'type'         => 'title',
      'placeholder'  => 'pages.add.title.placeholder',
      'autocomplete' => false,
      'autofocus'    => true,
      'required'     => true
    ),
    'uid' => array(
      'label'        => 'pages.add.url.label',
      'type'         => 'text',
      'icon'         => 'chain',
      'autocomplete' => false,
      'required'     => true,
    ),
    'template' => array(
      'label'    => 'pages.add.template.label',
      'type'     => 'select',
      'options'  => $options,
      'default'  => key($options),
      'required' => true,
      'readonly' => count($options) == 1 ? true : false,
      'icon'     => count($options) == 1 ? $page->blueprint()->pages()->template()->first()->icon() : 'chevron-down',
    )
  ));

  $form->cancel($page->isSite() ? '/' : $page);

  $form->buttons->submit->val(l('add'));

  return $form;

};