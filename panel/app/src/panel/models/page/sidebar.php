<?php 

namespace Kirby\Panel\Models\Page;

use Exception;
use Kirby\Panel\Snippet;

class Sidebar {

  public $page;
  public $blueprint;

  public function __construct($page) {
    $this->page      = $page;
    $this->blueprint = $page->blueprint();
  }

  public function menu() {

    $menu = $this->page->menu('sidebar');
    $html = $menu->html();

    // check if the menu has items
    if($menu->isEmpty()) return '';

    return new Snippet('pages/sidebar/menu', array('menu' => $html));

  }

  public function subpages() {

    if(!$this->page->canShowSubpages()) {
      return null;
    }

    // fetch all subpages in the right order
    $children = $this->page->children()->paginated('sidebar');

    // create the pagination snippet
    $pagination = new Snippet('pagination', array(
      'pagination' => $children->pagination(),
      'nextUrl'    => $children->pagination()->nextPageUrl(),
      'prevUrl'    => $children->pagination()->prevPageUrl(),
    ));

    // create the snippet and fill it with all data
    return new Snippet('pages/sidebar/subpages', array(
      'title'      => l('pages.show.subpages.title'),
      'page'       => $this->page,
      'subpages'   => $children,
      'addbutton'  => $this->page->addButton(),
      'pagination' => $pagination,
    ));

  }

  public function files() {

    if(!$this->page->canShowFiles()) {
      return null;
    }

    return new Snippet('pages/sidebar/files', array(
      'page'  => $this->page,
      'files' => $this->page->files(),
    ));

  }

  public function render() {

    // create the monster sidebar
    return new Snippet('pages/sidebar', array(
      'page'      => $this->page,
      'menu'      => $this->menu(),
      'subpages'  => $this->subpages(),
      'files'     => $this->files(),
    ));

  }

  public function __toString() {
    try {
      return (string)$this->render();
    } catch(Exception $e) {
      return $e->getMessage();
    }
  }

}