<?php

class DashboardController extends Kirby\Panel\Controllers\Base {

  public function index() {

    return $this->screen('dashboard/index', panel()->site(), array(
      'widgets' => new Kirby\Panel\Widgets()
    ));

  }

}