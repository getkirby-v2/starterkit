<?php 

return function($model, $structure) {

  $form = new Kirby\Panel\Form($structure->fields(), array(), $structure->field());
  $form->cancel($model);
  $form->buttons->submit->value = l('add');

  return $form;

};