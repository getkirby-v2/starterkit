<?php

namespace Kirby\Panel;

use Cache;
use Collection;
use Dir;
use Exception;
use Str;

class Search {

  public $query;
  public $pages;
  public $users;
  public $cache;

  public function __construct($query) {

    $this->query = trim($query);
    $this->pages = new Collection;
    $this->users = new Collection;

    // temporary disable the search cache
    $this->cache = cache::setup('mock');

    // try {
    //   $root = kirby()->roots()->cache() . DS . 'search';
    //   dir::make($root);
    //   $this->cache = cache::setup('file', array('root' => $root));
    // } catch(Exception $e) {
    //   $this->cache = cache::setup('session');
    // }

    $this->run();
  
  }

  public function data() {

    $pages = $this->cache->get('pages');
    $users = $this->cache->get('users');

    if(empty($pages)) {
      $pages = array();
      foreach(panel()->site()->index() as $page) {
        $pages[] = array(
          'title' => (string)$page->title(),
          'uri'   => (string)$page->id(),
        );
      }
      $this->cache->set('pages', $pages);
    }

    if(empty($users)) {
      foreach(panel()->users() as $user) {
        $users[] = array(
          'username'    => $user->username(),
          'email'       => $user->email(),
          'firstname'   => $user->firstName(),
          'lastname'    => $user->lastName()
        );
      }
      $this->cache->set('users', $users);
    }

    return compact('pages', 'users');

  }

  public function run() {

    if(empty($this->query) or str::length($this->query) <= 1) {
      return false;
    }

    $data = $this->data();


    foreach($data['pages'] as $page) {
      if(
        str::contains($page['title'], $this->query) or 
        str::contains($page['uri'], $this->query)
      ) {
        $this->pages->append($page['uri'], $page);
      }
    }

    foreach($data['users'] as $user) {
      if(
        str::contains($user['username'], $this->query) or 
        str::contains($user['email'], $this->query) or 
        str::contains($user['firstname'], $this->query) or
        str::contains($user['lastname'], $this->query)
      ) {
        $this->users->append($user['username'], $user);
      }
    }

    $this->pages = $this->pages->limit(5);
    $this->users = $this->users->limit(5);

  }

  public function pages() {
    return $this->pages;
  }

  public function users() {
    return $this->users;
  }

}
