<?php

namespace Kirby\Panel\Models;

use Exception;
use Kirby;
use Kirby\Panel\Event;
use Kirby\Panel\Snippet;
use Kirby\Panel\Structure;
use Kirby\Panel\Topbar;
use Kirby\Panel\Collections\Files;
use Kirby\Panel\Collections\Children;
use Kirby\Panel\Collections\Users;
use Kirby\Panel\Models\Page\AddButton;
use Kirby\Panel\Models\Page\Blueprint;
use Kirby\Panel\Models\Page\Changes;
use Kirby\Panel\Models\Page\Sidebar;
use Kirby\Panel\Models\Page\Uploader;
use Kirby\Panel\Models\Site\UI;
use Kirby\Panel\Models\Site\Options;

class Site extends \Site {

  public function __construct(Kirby $kirby) {
    parent::__construct($kirby);
  }

  public function blueprint() {
    if(isset($this->cache['blueprint'])) return $this->cache['blueprint'];
    return $this->cache['blueprint'] = new Blueprint('site');
  }

  public function filterInput($input) {
    return $input;
  }

  public function changes() {
    return new Changes($this);
  }

  public function uri($action = null) {
    if(empty($action)) {
      return parent::uri();
    } else if($action === 'edit') {
      return 'options';
    } else {
      return 'site/' . $action;            
    }
  }

  public function url($action = null) {

    if(empty($action)) {
      return parent::url();
    } else if($action == 'edit') {
      return panel()->urls()->index() . '/options';
    } else if($action == 'preview') {      
      return parent::url();
    } else if($this->multilang() and in_array($action, $this->languages()->codes())) {    
      return parent::url($action);
    } else {
      return panel()->urls()->index() . '/' . $this->uri($action);
    }

  }

  public function form($action, $callback) {    
    return panel()->form('pages/' . $action, $this, $callback);
  }

  public function getFormData() {

    // get the latest content from the text file
    $data = $this->content()->toArray();

    // make sure the title is always there
    $data['title'] = $this->title();

    // add the changes to the content array
    $data = array_merge($data, $this->changes()->get());

    return $data;

  }

  public function getBlueprintFields() {
    return $this->blueprint()->fields($this);
  }

  public function getFormFields() {
    return $this->getBlueprintFields()->toArray();
  }

  public function files() {
    return new Files($this);    
  }

  public function children() {
    return new Children($this);
  }

  public function update($data = array(), $lang = null) {

    // create the update event
    $event = $this->event('update:action', [
      'data' => $data
    ]);

    // check for permissions
    $event->check();

    // keep the old state of the site object
    $old = clone $this;

    $this->changes()->discard();

    parent::update($data, $lang);

    kirby()->trigger($event, [$this, $old]);

  }

  public function sidebar() {
    return new Sidebar($this);    
  }

  public function upload() {
    return new Uploader($this);        
  }

  public function ui() {
    return new UI($this);
  }

  public function options() {
    return new Options($this);
  }

  public function addButton() {
    try {
      return new AddButton($this);
    } catch(Exception $e) {
      return false;
    }
  }

  public function topbar(Topbar $topbar) {

    if($topbar->view == 'options/index') {
      $topbar->append(purl('options'), l('metatags'));
    }

    if($topbar->view == 'subpages/index') {
      $topbar->append($this->url('subpages'), l('subpages'));    
    }
   
    $topbar->html .= new Snippet('languages', array(
      'languages' => $this->languages(),
      'language'  => $this->language(),
    ));

  }

  public function users() {
    return new Users();
  }

  public function user($username = null) {
    if(is_null($username)) return User::current();
    try {
      return new User($username);
    } catch(Exception $e) {
      return null;
    }
  }

  public function delete($force = false) {
    throw new Exception(l('site.delete.error'));
  }

  public function maxSubpages() {
    $max = $this->blueprint()->pages()->max();
    // if max subpages is null, use the biggest 32bit integer
    // will never be reached anyway. Kirby is not made for that scale :)
    return is_null($max) ? 2147483647 : $max;
  }

  public function maxFiles() {
    $max = $this->blueprint()->files()->max();
    // see: maxSubpages
    return is_null($max) ? 2147483647 : $max;    
  }

  public function event($type, $args = []) {

    if(in_array($type, ['create', 'create:ui', 'create:action'])) {
      // rewrite the page create event
      $type = 'panel.page.' . $type;    
    } else if(in_array($type, ['upload', 'upload:ui', 'upload:action'])) {
      // rewrite the upload event
      $type = 'panel.file.' . $type;
    } else {
      $type = 'panel.site.' . $type;
    }

    return new Event($type, array_merge(['site' => $this, 'page' => $this], $args));

  }

  public function structure() {
    return new Structure($this, 'site_' . $this->lang());
  }

  public function lang() {
    return $this->multilang() ? $this->language()->code() : false;
  }

}