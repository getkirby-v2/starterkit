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
    $errorResponse = new Response('The file could not be found', 'txt', 404);

    // filter out field names that contain directory traversal attacks
    if(preg_match('{[\\\\/]}', urldecode($field))) return $errorResponse;
    if(preg_match('{^[.]+$}', $field))             return $errorResponse;

    // init the form to load all fields
    $form = new Form();

    if($field = kirby()->get('field', $field)) {
      // build the path to the requested file
      $fieldRoot = $field->root() . DS . 'assets';
      $fileRoot  = $fieldRoot . DS . str_replace('/', DS, $path);
      if(!is_file($fileRoot)) return $errorResponse;

      // make sure that we are still in the field's asset dir
      if(!str::startsWith(realpath($fileRoot), realpath($fieldRoot))) return $errorResponse;

      // success, serve the file
      return new Response(f::read($fileRoot), f::extension($fileRoot));
    }

    return $errorResponse;
  }

}
