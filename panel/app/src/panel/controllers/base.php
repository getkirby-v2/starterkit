<?php

namespace Kirby\Panel\Controllers;

use Obj;
use R;
use Response;

use Kirby\Panel\Assets;
use Kirby\Panel\Layout;
use Kirby\Panel\View;
use Kirby\Panel\Snippet;
use Kirby\Panel\Topbar;

class Base extends Obj {

  public function redirect($obj = '/', $action = false, $force = false) {    
    return panel()->redirect($obj, $action, $force);
  }

  public function notify($message) {
    panel()->notify($message);
  }

  public function alert($message) {
    panel()->alert($message);
  }

  public function form($id, $data = array(), $submit = null) {
    return panel()->form($id, $data, $submit);
  }

  public function page($id) {
    return panel()->page($id);
  }

  public function user($username = null) {
    return panel()->user($username);
  }

  public function layout($type, $data = array()) {

    $version  = panel()->version();
    $base     = panel()->urls()->index();
    $cssbase  = panel()->urls()->css();
    $jsbase   = panel()->urls()->js();

    $defaults = array(
      'title'     => panel()->site()->title() . ' | Panel',
      'direction' => panel()->direction(),
      'meta'      => $this->snippet('meta'),
      'css'       => css($cssbase . '/panel.min.css?v=' . $version),
      'js'        => js($jsbase . '/dist/panel.min.js?v=' . $version),
      'favicon'   => $this->snippet('favicon', ['url' => panel()->kirby()->option('panel.favicon')]),
      'content'   => '',
      'bodyclass' => '',
    );    

    switch($type) {
      case 'app':
        $defaults['topbar']  = '';
        $defaults['csrf']    = panel()->csrf();
        $defaults['formcss'] = css($cssbase . '/form.min.css?v=' . $version);
        $defaults['formjs']  = js($jsbase   . '/dist/form.min.js?v='  . $version);
        $defaults['appjs']   = js($jsbase   . '/dist/app.min.js?v='   . $version);

        // plugin stuff
        $defaults['pluginscss'] = css($base . '/plugins/css?v='  . $version);
        $defaults['pluginsjs']  = js($base . '/plugins/js?v='  . $version);

        break;
      case 'base':
        break;
    }

    $data = array_merge($defaults, $data);

    if(r::ajax() and $type == 'app') {
      $panel    = panel();
      $user     = $panel->site()->user();
      $response = array(
        'user'      => $user ? $user->username() : false,
        'direction' => $panel->direction(),
        'title'     => $data['title'],
        'content'   => $data['topbar'] . $data['content']
      );
      return response::json($response);
    } else {
      return new Layout($type, $data);      
    }

  }

  public function view($file, $data = array()) {
    return new View($file, $data);
  }

  public function snippet($file, $data = array()) {
    return new Snippet($file, $data);
  }

  public function topbar($view, $input) {
    return new Topbar($view, $input);
  }

  public function screen($view, $topbar = null, $data = array()) {
    return $this->layout('app', array(
      'topbar'  => is_a($topbar, 'Kirby\\Panel\\Topbar') ? $topbar : $this->topbar($view, $topbar),
      'content' => is_a($data,   'Kirby\\Panel\\View')   ? $data   : $this->view($view, $data)
    ));
  }

  public function modal($view, $data = array()) {
    if($view === 'error') $view = 'error/modal';  
    return $this->layout('app', array('content' => $this->view($view, $data)));
  }

  public function json($data = array()) {
    return response::json($data);
  }

}