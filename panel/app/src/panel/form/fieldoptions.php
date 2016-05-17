<?php

namespace Kirby\Panel\Form;

use Collection;
use Str;
use Remote;
use V;

class FieldOptions {

  public $field;
  public $options = array();

  static public function build($field) {
    $obj = new static($field);
    return $obj->toArray();
  }

  public function __construct($field) {

    $this->field = $field;

    if(is_array($this->field->options)) {
      $this->options = $this->field->options;
    } else if($this->isUrl($this->field->options)) {
      $this->options = $this->optionsFromApi($this->field->options);
    } else if($this->field->options == 'query') {
      $this->options = $this->optionsFromQuery($this->field->query);
    } else if($this->field->options == 'field') {
      $this->options = $this->optionsFromField($this->field->field);
    } else {
      $this->options = $this->optionsFromPageMethod($this->field->page, $this->field->options);
    }

    // sorting
    $this->options = $this->sort($this->options, !empty($this->field->sort) ? $this->field->sort : null);

  }

  public function optionsFromPageMethod($page, $method) {

    if($page && $items = $this->items($page, $method)) {
      $options = array();
      foreach($items as $item) {
        if(is_a($item, 'Page')) {
          $options[$item->uid()] = (string)$item->title();
        } else if(is_a($item, 'File')) {
          $options[$item->filename()] = (string)$item->filename();
        }
      }
      return $options;
    } else {
      return array();
    }

  }

  public function optionsFromApi($url) {
    $response = remote::get($url);
    $options  = @json_decode($response->content(), true);
    return is_array($options) ? $options : array();
  }

  public function optionsFromField($field) {

    // default field parameters
    $defaults = array(
      'page'     => $this->field->page ? ($this->field->page->isSite() ? '/' : $this->field->page->id()) : '',
      'name'      => 'tags',
      'separator' => ',',
    );

    // sanitize the query
    if(!is_array($field)) {
      $field = array();
    }

    // merge the default parameters with the actual query
    $field = array_merge($defaults, $field);

    // dynamic page option
    // ../
    // ../../ etc.
    $page    = $this->page($field['page']);
    $items   = $page->{$field['name']}()->split($field['separator']);
    $options = array();

    foreach($items as $item) {
      $options[$item] = $item;
    }

    return $options;

  }

  public function optionsFromQuery($query) {

    // default query parameters
    $defaults = array(
      'page'     => $this->field->page ? ($this->field->page->isSite() ? '/' : $this->field->page->id()) : '',
      'fetch'    => 'children',
      'value'    => '{{uid}}',
      'text'     => '{{title}}',
      'flip'     => false,
      'template' => false
    );

    // sanitize the query
    if(!is_array($query)) {
      $query = array();
    }

    // merge the default parameters with the actual query
    $query = array_merge($defaults, $query);

    // dynamic page option
    // ../
    // ../../ etc.
    $page    = $this->page($query['page']);
    $items   = $this->items($page, $query['fetch']);
    $options = array();

    if($query['template']) {
      $items = $items->filter(function($item) use($query) {
        return in_array(str::lower($item->intendedTemplate()), array_map('str::lower', (array)$query['template']));
      });
    }

    if($query['flip']) {
      $items = $items->flip();
    }

    foreach($items as $item) {
      $value = $this->tpl($query['value'], $item);
      $text  = $this->tpl($query['text'], $item);

      $options[$value] = $text;
    }

    return $options;

  }

  public function page($uri) {

    if(str::startsWith($uri, '../')) {
      if($currentPage = $this->field->page) {
        $path = $uri;
        while(str::startsWith($path, '../')) {
          if($parent = $currentPage->parent()) {
            $currentPage = $parent;
          } else {
            $currentPage = site();
          }
          $path = str::substr($path, 3);
        }
        if(!empty($path)) {
          $currentPage = $currentPage->find($path);
        }
        $page = $currentPage;
      } else {
        $page = null;
      }
    } else if($uri == '/') {
      $page = site();
    } else {
      $page = page($uri);
    }

    return $page;

  }

  public function sort($options, $sort) {

    if(empty($sort)) return $options;

    switch(strtolower($sort)) {
      case 'asc':
        asort($options);
        break;
      case 'desc':
        arsort($options);
        break;
    }

    return $options;

  }

  public function tpl($string, $obj) {
    return preg_replace_callback('!\{\{(.*?)\}\}!', function($item) use($obj) {
      return (string)$obj->{$item[1]}();
    }, $string);
  }

  public function isUrl($url) {
    return
      v::url($url) or
      str::contains($url, '://localhost') or
      str::contains($url, '://127.0.0.1');
  }

  public function items($page, $method) {

    if(!$page) return new Collection();

    switch($method) {
      case 'visibleChildren':
        $items = $page->children()->visible();
        break;
      case 'invisibleChildren':
        $items = $page->children()->invisible();
        break;
      case 'siblings':
        $items = $page->siblings()->not($page);
        break;
      case 'visibleSiblings':
        $items = $page->siblings()->not($page)->visible();
        break;
      case 'invisibleSiblings':
        $items = $page->siblings()->not($page)->invisible();
        break;
      case 'pages':
        $items = site()->index();
        $items = $items->sortBy('title', 'asc');
        break;
      case 'index':
        $items = $page->index();
        $items = $items->sortBy('title', 'asc');
        break;
      case 'children':
      case 'grandchildren':
      case 'files':
      case 'images':
      case 'documents':
      case 'videos':
      case 'audio':
      case 'code':
      case 'archives':
        $items = $page->{$method}();
        break;
      default:
        $items = new Collection();
    }

    return $items;

  }

  public function toArray() {
    return $this->options;
  }

}
