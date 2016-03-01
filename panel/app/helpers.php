<?php

function panel() {
  return Kirby\Panel::instance();
}

function icon($icon, $position = null) {
  return '<i class="icon' . r($position, ' icon-' . $position) . ' fa fa-' . $icon . '"></i>';
}

function i($icon, $position = null) {
  echo icon($icon, $position);
}

function __($var) {
  echo htmlspecialchars($var);
}

function _l($key, $default = null) {
  echo htmlspecialchars(l($key, $default));
}

function _u($obj = '', $action = false) {
  echo purl($obj, $action);
}

function purl($obj = '/', $action = false) {

  if(empty($obj) or is_string($obj)) {
    $base = panel()->urls()->index();
    return ($obj == '/' or empty($obj)) ? $base . '/' : rtrim($base . '/' . $obj, '/');    
  } else if(is_a($obj, 'Kirby\\Panel\\Models\\Site')) {
    return $obj->url(!$action ? 'edit' : $action);  
  } else if(is_a($obj, 'Kirby\\Panel\\Models\\Page')) {
    return $obj->url(!$action ? 'edit' : $action);
  } else if(is_a($obj, 'Kirby\\Panel\\Models\\File')) {
    return $obj->url(!$action ? 'edit' : $action);
  } else if(is_a($obj, 'Kirby\\Panel\\Models\\User')) {
    return $obj->url(!$action ? 'edit' : $action);
  }

}

function slugTable() {
  $table = array();
  foreach(str::$ascii as $key => $value) {
    $key = trim($key, '/');
    foreach(str::split($key, '|') as $needle) {
      $table[$needle] = $value;
    }
  }

  return json_encode($table, JSON_UNESCAPED_UNICODE);
}