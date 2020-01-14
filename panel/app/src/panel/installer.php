<?php

namespace Kirby\Panel;

use Dir;
use Folder;

class Installer {

  public function isCompleted() {
    return (site()->users()->count() > 0 && is_writable(kirby()->roots()->accounts()));
  }

  public function problems() {

    $checks   = array('allowed', 'accounts', 'thumbs', 'blueprints', 'content', 'avatars');
    $problems = array();

    foreach($checks as $c) {
      $method = 'check' . $c;

      if(!$this->$method()) {
        $problems[] = l('installation.check.error.' . $c);
      }

    }
    
    return empty($problems) ? false : $problems;

  }

  protected function checkAllowed() {
    return (panel()->isLocal() || kirby()->option('panel.install') === true);
  }

  protected function checkAccounts() {

    $root = kirby()->roots()->accounts();

    // try to create the accounts folder
    dir::make($root);

    return is_writable($root);

  }

  protected function checkThumbs() {

    $root = kirby()->roots()->thumbs();

    // try to create the thumbs folder
    dir::make($root);

    return is_writable($root);

  }

  protected function checkBlueprints() {
    return is_dir(kirby()->roots()->blueprints());
  }

  protected function checkContent() {
    $folder = new Folder(kirby()->roots()->content());
    return $folder->isWritable(true);
  }

  protected function checkAvatars() {

    $root = kirby()->roots()->avatars();

    // try to create the avatars folder
    dir::make($root);

    return is_writable($root);

  }

}