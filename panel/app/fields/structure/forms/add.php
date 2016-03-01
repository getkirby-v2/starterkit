<?php 

return function($model, $store) {

  $form = new Kirby\Panel\Form($store->fields());
  $form->cancel($model);
  $form->buttons->submit->value = l('add');

  return $form;

};