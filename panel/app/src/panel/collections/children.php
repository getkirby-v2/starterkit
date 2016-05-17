<?php

namespace Kirby\Panel\Collections;

use A;
use S;
use Str;
use Exception;

use Kirby\Panel\Models\Page;
use Kirby\Panel\Models\Page\Blueprint;

class Children extends \Children {

  public function __construct($page) {

    parent::__construct($page);

    $page->reset();

    $inventory = $page->inventory();

    foreach($inventory['children'] as $dirname) {
      $child = new Page($page, $dirname);
      $this->data[$child->id()] = $child;        
    }

    $sort = $page->blueprint()->pages()->sort();

    switch($sort) {
      case 'flip':
        $cloned = $this->flip();
        $this->data = $cloned->data;
        break;
      default;
        $parts = str::split($sort, ' ');
        if(count($parts) > 0) {
          $cloned = call(array($this, 'sortBy'), $parts);
          $this->data = $cloned->data;
        }
        break;
    }

  }

  public function create($uid, $template, $content = array()) {

    if(empty($template)) {
      throw new Exception(l('pages.add.error.template'));
    }

    $uid       = empty($uid) ? str::random(32) : $uid;
    $blueprint = new Blueprint($template);
    $data      = array();

    foreach($blueprint->fields(null) as $key => $field) {
      $data[$key] = $field->default();
    }

    $data = array_merge($data, $content);

    // create the new page and convert it to a page model
    $page = new Page($this->page, parent::create($uid, $template, $data)->dirname());

    if(!$page) {
      throw new Exception(l('pages.add.error.create'));
    }

    kirby()->trigger('panel.page.create', $page);

    // subpage builder
    foreach((array)$page->blueprint()->pages()->build() as $build) {
      $missing = a::missing($build, array('title', 'template', 'uid'));
      if(!empty($missing)) continue;
      $subpage = $page->children()->create($build['uid'], $build['template'], array('title' => $build['title']));
      if(isset($build['num'])) $subpage->sort($build['num']);
    }

    return $page;

  }

  public function paginated($mode = 'sidebar') {

    if($limit = $this->page->blueprint()->pages()->limit()) {

      $hash = sha1($this->page->id());

      switch($mode) {
        case 'sidebar':
          $id  = 'pages.' . $hash;
          $var = 'page';
          break;
        case 'subpages/visible':
          $id  = 'subpages.visible.' . $hash;
          $var = 'visible';
          break;
        case 'subpages/invisible':
          $id  = 'subpages.invisible.' . $hash;
          $var = 'invisible';
          break;
      }

      // filter out hidden pages
      $children = $this->filter(function($child) {
        return $child->blueprint()->hide() === false;
      });

      $children = $children->paginate($limit, array(
        'page'          => get($var, s::get($id)), 
        'omitFirstPage' => false, 
        'variable'      => $var,
        'method'        => 'query',
        'redirect'      => false
      ));

      // store the last page
      s::set($id, $children->pagination()->page());

      return $children;

    } else {
      return $this;
    }

  }


}
