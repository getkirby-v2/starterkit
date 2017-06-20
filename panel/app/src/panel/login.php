<?php

namespace Kirby\Panel;

use A;
use Exception;
use Data;
use Dir;
use Kirby;
use Str;
use Visitor;

class Login {

  protected $kirby;
  protected $username;
  protected $password;
  protected $logfile;
  protected $logdata;
  protected $logexpiry = 3600;
  protected $maxUntrustedAttempts = 10;

  /**
   * Setup the login class with some 
   * basic ingredients
   */
  public function __construct() {

    $this->kirby   = kirby();
    $this->logfile = $this->kirby->roots()->accounts() . DS . '.logins';

    $this->setup();

  }

  /**
   * Setup and check the logfile  
   */
  protected function setup() {

    // make sure the logroot exists
    if(!is_writable(dirname($this->logfile))) {
      throw new Exception(l('users.form.error.permissions.title'));
    }

    // create the logfile if not there yet
    touch($this->logfile);

    // make sure the logroot exists
    if(!is_writable($this->logfile)) {
      throw new Exception(l('login.log.error.permissions'));
    }

  }

  /**
   * Run an attempt to login
   * 
   * @param string $username
   * @param string $password
   */
  public function attempt($username, $password) {

    $this->username = str::lower($username);
    $this->password = $password;

    try {

      if($this->isInvalidUsername() || $this->isInvalidPassword()) {
        throw new Exception(l('login.error'));
      }
      
      $user = $this->user();      
      
      if(!$user->login($this->password)) {
        throw new Exception(l('login.error'));
      }
  
      $this->clearLog($this->visitorId());
      return true;

    } catch(Exception $e) {

      $this->log();
      $this->pause();

      throw $e;

    }

  }

  /**
   * Checks if the login form can be 
   * bypassed, because the user is already
   * authenticated
   * 
   * @return boolean
   */
  public function isAuthenticated() {
    try {
      panel()->user();
      return true;
    } catch(Exception $e) {
      return false;
    }
  }

  /**
   * Checks if a brute force attack has 
   * probably been executed
   * 
   * @return boolean
   */
  public function isBlocked() {
    return $this->attempts() > $this->maxUntrustedAttempts;
  }

  /**
   * Fetch the user for the entered username
   * 
   * @return User
   */
  protected function user() {
    return panel()->user($this->username);
  }

  /**
   * Returns all logdata in an array
   * 
   * @return array
   */
  protected function logdata() {
    if(!is_null($this->logdata)) {
      return $this->logdata;
    } else {

      $data  = (array)data::read($this->logfile, 'json');
      $login = $this;

      // remove old entries
      $data = array_filter($data, function($entry) use($login) { 
        return ($entry['time'] > (time() - $login->logexpiry));
      }); 

      return $this->logdata = $data;
    }
  }

  /**
   * Stores a new login attempt to 
   * make it trackable later
   *
   * The store contains a sha1 hash of the ip
   * 
   * @return boolean
   */
  protected function log() {

    // get the latest logdata
    $data = $this->logdata();

    // store a new attempt
    $data[] = array(
      'time' => time(),
      'id'   => $this->visitorId(),
    );

    // write it to the logfile
    return data::write($this->logfile, $data, 'json');

  }

  /**
   * Return a hashed version of the visitor ip
   * 
   * @return string
   */
  protected function visitorId() {
    return sha1(visitor::ip());
  }

  /**
   * Returns the number of attempts for
   * the current visitor
   * 
   * @return int
   */
  protected function attempts() {

    $data  = $this->logdata();
    $login = $this;
    $data  = array_filter($data, function($entry) use($login) {
      return $login->visitorId() === $entry['id'];
    });

    return count($data);

  }

  /**
   * Checks if an invalid username has been entered
   * 
   * @return boolean
   */
  protected function isInvalidUsername() {
    return !preg_match('!^[a-z0-9._-]{1,}$!', $this->username);
  }

  /**
   * Checks if an invalid password has been entered
   * 
   * @return boolean
   */
  protected function isInvalidPassword() {
    return empty($this->password);
  }

  /**
   * Create a random pause between 0 and 3
   * seconds to make it harder for attackers
   * to execute many sequent attacks
   */
  protected function pause() {
    sleep(rand(1, 3));
  }

  /**
   * Delete log entries by visitor id
   */
  protected function clearLog($id) {   
    
    $data = array_filter($this->logdata(), function($entry) use($id) {
      return $entry['id'] !== $id;
    });

    data::write($this->logfile, $data, 'json');

    // reset the logdata cache
    $this->logdata = null;

    return $this->logdata();

  }

}