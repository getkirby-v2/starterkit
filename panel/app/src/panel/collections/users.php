<?php

namespace Kirby\Panel\Collections;

use Exception;
use Kirby\Panel\Event;
use Kirby\Panel\Models\User;

class Users extends \Users {

  public function __construct() {

    parent::__construct();

    $this->map(function($user) {
      return new User($user->username());
    });

  }
  
  public function topbar($topbar) {
    $topbar->append(purl('users'), l('users')); 
  }

  public function create($data) {

    $event = panel()->user()->event('create:action');

    if($data['password'] !== $data['passwordconfirmation']) {
      throw new Exception(l('users.form.error.password.confirm'));
    }

    unset($data['passwordconfirmation']);

    // set the event data
    $event->target->data = $data;

    // check for permissions
    $event->check();

    // create the user
    $user = parent::create($data);
    
    // trigger the create hook
    kirby()->trigger($event, $user);

    // return the new user object
    return new User($user->username());

  }

}