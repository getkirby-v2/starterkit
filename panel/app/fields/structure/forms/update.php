<?php 

return function($model, $structure, $entry) {
  
  $form = new Kirby\Panel\Form($structure->fields(), $entry->toArray(), $structure->field());

  $form->cancel($model);
  $form->buttons->submit->value = l('ok');

  return $form;

};