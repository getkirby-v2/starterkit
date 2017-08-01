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

  public function other($field, $path) {
    // init the form to load all fields
    $form = new Form();
    
    if($field = kirby()->get('field', $field)) {
      $root = $field->root() . DS . 'assets' . DS . $path;
      
      $file = new Media($root);
      if($file->exists()) {
        return new Response(f::read($root), f::extension($root));
      }
    }
    
    return new Response('The file could not be found', f::extension($path), 404);
  }

}
