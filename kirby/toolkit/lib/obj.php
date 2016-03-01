<?php

/**
 * Obj
 *
 * Obj base class
 *
 * @package   Kirby Toolkit
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Obj extends stdClass {

  public function __construct($data = array()) {
    foreach($data as $key => $val) {
      $this->{$key} = $val;
    }
  }

  public function __call($method, $arguments) {
    return isset($this->$method) ? $this->$method : null;
  }

  public function set($key, $value) {
    $this->$key = $value;
  }

  public function get($key, $default = null) {
    return isset($this->$key) ? $this->$key : $default;
  }

  public function toArray() {
    return (array)$this;
  }

}