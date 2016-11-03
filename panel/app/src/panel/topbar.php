<?php

namespace Kirby\Panel;

use A;
use Brick;
use Exception;
use S;
use Str;
use Url;

class Topbar {

  public $view       = null;
  public $breadcrumb = array();
  public $html       = null;

  public function __construct($view, $input) {

    $this->view = $view;

    if(is_object($input) and method_exists($input, 'topbar')) {
      $input->topbar($this);
    } else {

      $class = is_object($input) ? str_replace('model', '', strtolower(get_class($input))) : (string)$input;
      $file  = panel()->roots()->topbars() . DS . str::lower($class) . '.php';

      if(file_exists($file)) {

        $callback = require($file);
        $callback($this, $input);

      } else {
        throw new Exception(l('topbar.error.class.definition') . $class);
      }

    }

  }

  public function append($url, $title) {

    $this->breadcrumb[] = array(
      'title' => $title,
      'url'   => $url
    );

  }

  public function menu() {
    return new Snippet('menu');
  }

  public function breadcrumb() {
    return new Snippet('breadcrumb', array(
      'items' => $this->breadcrumb
    ));
  }

  public function message() {

    if($message = s::get('kirby_panel_message') and is_array($message)) {

      $text = a::get($message, 'text');
      $type = a::get($message, 'type', 'notification');

      $element = new Brick('div');
      $element->addClass('message');

      if($type == 'error') {
        $element->addClass('message-is-alert');      
      } else {
        $element->addClass('message-is-notice');
      }

      $element->append(function() use($text) {
        $content = new Brick('span');
        $content->addClass('message-content');
        $content->text($text);
        return $content;
      });

      $element->append(function() {
        $toggle = new Brick('a');
        $toggle->attr('href', url::current());
        $toggle->addClass('message-toggle');
        $toggle->html('<i>&times;</i>');
        return $toggle;
      });

      s::remove('kirby_panel_message');

      return $element;

    }

  }

  public function render() {

    $element = new Brick('header', '', array('class' => 'topbar'));
    $element->append($this->menu());
    $element->append($this->breadcrumb());
    $element->append($this->html);
    $element->append(new Snippet('search'));
    $element->append($this->message());

    return $element;

  }

  public function __toString() {
    return (string)$this->render();
  }

}