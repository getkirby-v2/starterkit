<?php 

return function($problems) {

  $form = new Kirby\Panel\Form(array(
    'info' => array(
      'type' => 'info'
    )
  ));

  if(count($problems) > 1) {
    $info = new Brick('ol');
    foreach($problems as $problem) {
      $info->append('<li>' . $problem . '</li>');        
    }
  } else {
    $info = new Brick('p');
    foreach($problems as $problem) {
      $info->append($problem);        
    }    
  }

  // add the list of problems to the info field
  $form->fields->info->text = (string)$info;

  // setup the retry button
  $form->buttons->submit->value     = l('installation.check.retry');
  $form->buttons->submit->autofocus = true;

  $form->style('centered');
  $form->alert(l('installation.check.text'));

  return $form;

};