<?php

use Kirby\Panel\Form;

class AssetsController extends Kirby\Panel\Controllers\Base {

  public function js() {    
    $form = new Form();
    return new Response($form->plugins()->js(), 'text/javascript');
  }

  public function css() {
    $form = new Form();
    return new Response($form->plugins()->css(), 'text/css');
  }

}