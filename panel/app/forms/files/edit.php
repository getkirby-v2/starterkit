<?php 

use Kirby\Panel\Event;

return function($file) {

  // file info display
  $info = array();

  $info[] = $file->type();
  $info[] = $file->niceSize();

  if((string)$file->dimensions() != '0 x 0') {
    $info[] = $file->dimensions();      
  }

  $renameEvent = $file->event('rename:ui');
  $updateEvent = $file->event('update:ui');

  // setup the default fields
  $fields = array(
    '_name' => array(
      'label'     => 'files.show.name.label',
      'type'      => 'filename',
      'extension' => $file->extension(), 
      'required'  => true,
      'default'   => $file->name(),
      'readonly'  => $renameEvent->isDenied()
    ),
    '_info' => array(
      'label'    => 'files.show.info.label',
      'type'     => 'text',
      'readonly' => true,
      'icon'     => 'info',
      'default'  => implode(' / ', $info),
    ),
    '_link' => array(
      'label'    => 'files.show.link.label',
      'type'     => 'text',
      'readonly' => true,
      'icon'     => 'chain',
      'default'  => $file->url()
    )
  );

  $form = new Kirby\Panel\Form(array_merge($fields, $file->getFormFields()), $file->getFormData());

  $form->centered = true;
  $form->buttons->cancel = '';

  // disable custom fields
  if($updateEvent->isDenied()) {
    foreach($file->getFormFields() as $key => $field) {
      $form->fields->$key->readonly = true;
    }
  }

  // if there are readonly fields only, disable the entire form
  if($form->fields()->count() === $form->fields->filterBy('readonly', true)->count()) {
    $form->disable();
  }

  return $form;

};