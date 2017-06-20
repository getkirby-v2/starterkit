<?php

use Kirby\Panel\Search;

class SearchController extends Kirby\Panel\Controllers\Base {

  public function results() {

    $search = new Search(get('q'));

    return $this->view('search/results', array(
      'pages' => $search->pages(), 
      'users' => $search->users(),
    ));

  }

}