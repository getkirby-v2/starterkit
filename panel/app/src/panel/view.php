<?php

namespace Kirby\Panel;

use Exception;
use Tpl;

use Kirby\Panel;

class View {

  public $_root = null;
  public $_file = null;
  public $_data = array();

  public function __construct($file, $data = array()) {
    $this->_root = panel::instance()->roots()->views();
    $this->_file = $file;
    $this->_data = $data;
  }

  public function __set($key, $value) {
    $this->_data[$key] = $value;
  }

  public function render() {
    $file = $this->_root . DS . str_replace('.', DS, $this->_file) . '.php';
    if(!file_exists($file)) throw new Exception(l('view.error.invalid') . $file);
    return tpl::load($file, $this->_data);
  }

  public function __toString() {
    try {
      return (string)$this->render();
    } catch(Exception $e) {
      return $e->getMessage();
    }
  }

}