<?php

namespace Kirby\Panel\Models\Page;

use A;
use Data;
use Dir;
use Exception;
use F;
use Obj;

use Kirby\Panel\Models\Page\Blueprint\Pages;
use Kirby\Panel\Models\Page\Blueprint\Files;
use Kirby\Panel\Models\Page\Blueprint\Fields;
use Kirby\Panel\Models\Page\Blueprint\Options;

class Blueprint extends Obj {

  static public $cache = array();
  static public $root  = null;

  public $name      = null;
  public $file      = null;
  public $yaml      = array();
  public $title     = null;
  public $preview   = 'page';
  public $pages     = null;
  public $files     = null;
  public $hide      = false;
  public $deletable = true;
  public $icon      = 'file-o';
  public $fields    = array();
  public $options   = null;

  public function __construct($name) {

    // load from yaml file
    $this->load($name);

    $this->title     = a::get($this->yaml, 'title', 'Page');
    $this->preview   = a::get($this->yaml, 'preview', 'page');
    $this->deletable = a::get($this->yaml, 'deletable', true);
    $this->icon      = a::get($this->yaml, 'icon', 'file-o');
    $this->hide      = a::get($this->yaml, 'hide', false);
    $this->type      = a::get($this->yaml, 'type', 'page');
    $this->pages     = new Pages(a::get($this->yaml, 'pages', true));
    $this->files     = new Files(a::get($this->yaml, 'files', true));
    $this->options   = new Options(a::get($this->yaml, 'options', []));

  }

  public function load($name) {

    // make sure there's no path included in the name
    $name = basename(strtolower($name));

    if(isset(static::$cache[$name])) {
      $this->file = static::$cache[$name]['file'];
      $this->name = static::$cache[$name]['name'];
      $this->yaml = static::$cache[$name]['yaml'];
      return true;
    }

    // find the matching blueprint file
    $file = kirby()->get('blueprint', $name);

    if($file) {

      $this->file = $file;
      $this->name = $name;
      $this->yaml = data::read($this->file, 'yaml');

      // remove the broken first line
      unset($this->yaml[0]);

      static::$cache[$name] = array(
        'file' => $this->file,
        'name' => $this->name,
        'yaml' => $this->yaml
      );

      return true;

    } else if($name == 'default') {
      throw new Exception(l('blueprints.error.default.missing'));
    } else {
      return $this->load('default');
    }

  }

  public function fields($model) {
    $fields = a::get($this->yaml, 'fields', array());
    return new Fields($fields, $model);
  }

  static public function exists($name) {
    return kirby()->get('blueprint', $name) ? true : false;
  }

  static public function all() {

    $files  = dir::read(static::$root);
    $result = array_keys(kirby()->get('blueprint'));
    $home   = kirby()->option('home', 'home');
    $error  = kirby()->option('error', 'error');

    foreach($files as $file) {

      $name = f::name($file);

      if($name != 'site' and $name != $home and $name != $error) {
        $result[] = $name;
      }

    }

    return $result;

  }

  public function __toString() {
    return $this->name;
  }

}
