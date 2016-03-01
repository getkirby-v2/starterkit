<?php 

return function($page) {

  // label option
  $option = new Brick('a', icon('magic', 'left') . l('pages.url.uid.label.option'), array(
    'class'      => 'btn btn-icon label-option',
    'href'       => '#',
    'data-title' => str::slug($page->title())
  ));

  // url preview
  $preview = new Brick('div', '', array('class' => 'uid-preview'));
  $preview->append(ltrim($page->parent()->uri() . '/', '/'));
  $preview->append('<span>' . $page->slug() . '</span>');

  // create the form
  $form = new Kirby\Panel\Form(array(
    'uid' => array(
      'label'     => l('pages.url.uid.label') . $option,
      'type'      => 'text',
      'icon'      => 'chain',
      'autofocus' => true,
      'help'      => $preview,
      'default'   => $page->slug()
    )
  ));

  $form->buttons->submit->val(l('change'));
  $form->cancel($page);

  return $form;

};