<?php

namespace Kirby\Panel;

class Upload extends \Upload {

  protected function messages() {
    return array(
      static::ERROR_MISSING_FILE        => 'The file is missing',
      static::ERROR_MISSING_TMP_DIR     => 'The /tmp directory is missing on your server',
      static::ERROR_FAILED_UPLOAD       => 'The upload failed',
      static::ERROR_PARTIAL_UPLOAD      => 'The file has been only been partially uploaded',
      static::ERROR_UNALLOWED_OVERWRITE => 'The file exists and cannot be overwritten',
      static::ERROR_MAX_SIZE            => 'The file is too big. The maximum size is ' . f::niceSize($this->maxSize()),
      static::ERROR_MOVE_FAILED         => 'The file could not be moved',
      static::ERROR_UNACCEPTED          => 'The file is not accepted by the server'
    );
  }
  
}