<?php

namespace Kirby\Panel\Models\User;

use A;
use Data;
use F;
use Obj;

use Kirby\Panel\Models\Page\Blueprint\Fields;

class Blueprint extends Obj {

  static public $root = null;

  public $user = null;
  public $name = null;
  public $file = null;
  public $yaml = array();

  public function __construct($user) {

    // store the user object
    $this->user = $user;

    // load from yaml file
    $this->load();

  }

  public function load() {

    // get the user role and load the 
    // correspondant blueprint if available
    $this->name = basename(strtolower($this->user->role()));

    // try to find a user blueprint
    $file = f::resolve(static::$root . DS . $this->name, array('yml', 'php', 'yaml'));

    if($file) {
      $this->file = $file;
      $this->yaml = data::read($this->file, 'yaml');

      // remove the broken first line
      unset($this->yaml[0]);
    } 

  }

  public function fields() {
    $fields = (array)a::get($this->yaml, 'fields', array());
    return new Fields($fields, $this->user);
  }

  public function __toString() {
    return $this->name;
  }

}
