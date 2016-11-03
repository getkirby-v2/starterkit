<?php 

use Kirby\Panel\Form;
use Kirby\Panel\Event;

return function($page) {

  // create the form
  $form = new Form($page->getFormFields(), $page->getFormData());

  // add the blueprint name as css class
  $form->addClass('form-blueprint-' . $page->blueprint()->name());

  // center the submit button
  $form->centered = true;

  // set the keep api    
  $form->data('keep', $page->url('keep'));

  // set the autofocus on the title field
  $form->fields->title->autofocus = true;

  // add the changes alert
  if($page->changes()->differ()) {

    // display unsaved changes
    $alert = new Brick('div');
    $alert->addClass('text');
    $alert->append('<span>' . l('pages.show.changes.text') . '</span>');

    $form->buttons->prepend('changes', $alert);
    $form->buttons->cancel->attr('href', $page->url('discard'));
    $form->buttons->cancel->html(l('pages.show.changes.button'));

    // add wide buttons
    $form->buttons->cancel->addClass('btn-wide');
    $form->buttons->submit->addClass('btn-wide');

  } else {
    // remove the cancel button
    $form->buttons->cancel = '';    
  }

  // check for update permissions
  if(!$page->ui()->update()) {
    $form->disable();
  }

  return $form;

};