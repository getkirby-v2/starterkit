<?php 

return function($page) {

  $parent    = $page->parent();
  $blueprint = $parent->blueprint();
  $siblings  = $parent->children()->visible();

  // sorting needed
  if($blueprint->pages()->num()->mode() == 'default' and $siblings->count() > 0) {

    $options = array('' => l('pages.toggle.invisible'), '-' => '-');
    $n       = 1;

    foreach($siblings as $sibling) {
      $options[$n] = $n;
      $n++;
    }

    if($page->isInvisible()) {
      $options[$n] = $n;      
    }    

    $form = new Kirby\Panel\Form(array(
      'position' => array(
        'label'    => l('pages.toggle.position'),
        'type'     => 'select', 
        'required' => true,
        'default'  => $page->num(),
        'options'  => $options
      )
    ));

  } else {

    $form = new Kirby\Panel\Form(array(
      'confirmation' => array(
        'type' => 'info', 
        'text' => $page->isVisible() ? l('pages.toggle.hide') : l('pages.toggle.publish')
      )
    ));

  }

  $form->buttons->submit->value     = l('change');
  $form->buttons->submit->autofocus = true;

  $form->cancel($page);

  return $form;

};