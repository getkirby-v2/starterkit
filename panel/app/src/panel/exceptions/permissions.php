<?php

namespace Kirby\Panel\Exceptions;

use Exception;

class PermissionsException extends Exception {

  public function __construct($message = null, $code = 0, Exception $previous = null) {

    if($message === null) {
      $message = l('permissions.error');
    }

    parent::__construct($message, $code, $previous);

  }

}