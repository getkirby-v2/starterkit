<?php

namespace Kirby\Panel;

use A;
use Exception;

class Autocomplete {

  public $panel;
  public $site;
  public $method;
  public $params;

  public function __construct($panel, $method, $params = array()) {
    $this->panel  = $panel;
    $this->site   = $panel->site();
    $this->method = $method;
    $this->params = $params;
  }

  public function result() {

    $method = 'autocomplete' . $this->method;

    if(!method_exists($this, $method)) {
      throw new Exception(l('autocomplete.method.error'));
    }

    $result = array_values((array)$this->$method($this->params));

    // sort results alphabetically
    sort($result);

    return $result;

  }

  public function autocompleteUsernames() {
    return $this->panel->users()->map(function($user) {
      return $user->username();
    })->toArray();
  }

  public function autocompleteEmails() {
    return $this->panel->users()->map(function($user) {
      return $user->email();
    })->toArray();
  }

  public function autocompleteUris() {
    return $this->site->index()->map(function($page) {
      return $page->id();
    })->toArray();
  }

  public function autocompleteField($params = array()) {

    $defaults = array(
      'index'     => 'siblings',
      'uri'       => '/',
      'field'     => 'tags',
      'yaml'      => false,
      'separator' => true
    );

    $options = array_merge($defaults, $params);
    $page    = $this->panel->page($options['uri']);
    $pages   = $this->pages($page, $options['index'], $options);
    $yaml    = $options['yaml'];

    if($yaml) {
      $result = array();
      foreach($pages as $p) {
        $values = $p->$yaml()->toStructure()->pluck($options['field'], $options['separator'], true);
        $result = array_merge($result, $values);
      }
      $result = array_unique($result);
    } else {
      $result = $pages->pluck($options['field'], $options['separator'], true);
    }

    return $result;

  }

  public function pages($page, $index, $params = array()) {

    switch($index) {
      case 'siblings':
      case 'children':
        return $page->$index();
        break;
      case 'template':
        $template = a::get($params, 'template', $page->template());
        return $this->site->index()->filterBy('template', $template);
        break;
      case 'pages':
      case 'all':
        return $this->site->index();
        break;
      default:
        return $page->children();
        break;
    }

  }

}