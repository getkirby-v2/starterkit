<?php

namespace Kirby\Panel;

use ReflectionClass;
use Error;
use Exception;
use Str;
use Kirby\Event as KirbyEvent;

class Event extends KirbyEvent {

  public $panel       = null;
  public $site        = null;
  public $user        = null;
  public $language    = null;
  public $translation = null;
  public $state;

  public function __construct($type, $target = [], $state = null) {

    // if the event type contains a state
    if(str::contains($type, ':')) {
      list($type, $state) = str::split($type, ':');
    }

    parent::__construct($type, $target);

    $this->state = $state;

  }

  public function panel() {
    if($this->panel !== null) {
      return $this->panel;
    } else {
      return $this->panel = panel();
    }
  }

  public function site() {
    if($this->site !== null) {
      return $this->site;
    } else {
      return $this->site = $this->panel()->site();
    }
  }

  public function user() {
    if($this->user !== null) {
      return $this->user;
    } else {
      return $this->user = $this->site()->user();
    }
  }

  public function translation() {
    if($this->translation !== null) {
      return $this->translation;
    } else {
      return $this->panel()->translation();
    }
  }

  public function state($state = null) {
    if($state !== null) {
      $this->state = $state;
      return $this;
    } else {
      return $this->state;      
    }
  }

  /**
   * Checks if the current user has permission for the event
   */
  public function check() {

    $user = $this->user();

    if(!$user) {
      throw new Error('No user is logged in, cannot check permissions.');
    }

    $result = $user->permission($this);

    // set default error message if no custom one is set
    $message = $result->message();

    if(!$message) {
      $message = l('permissions.error');
    }

    if(!$result->status()) {
      throw new Error($message);
    }
    
    return true;

  }

  /**
   * Checks if an event is allowed
   * 
   * @return boolean
   */
  public function isAllowed() {
    try {
      $this->check();
      return true;
    } catch(Exception $e) {
      return false;
    } catch(Error $e) {
      return false;
    } 
  }

  /**
   * Checks if an event is denied
   * 
   * @return boolean
   */
  public function isDenied() {
    return !$this->isAllowed();
  }

}