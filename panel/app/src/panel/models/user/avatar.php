<?php

namespace Kirby\Panel\Models\User;

use Media;
use Exception;
use Error;
use Thumb;

use Kirby\Panel\Upload;
use Kirby\Panel\Models\User;

class Avatar extends \Avatar {

  public function __construct(User $user) {

    parent::__construct($user);

    if(!$this->exists()) {
      $this->root = $this->user->avatarRoot('{safeExtension}');
      $this->url  = purl('assets/images/avatar.png');
    }

  }

  public function form($action, $callback) {
    return panel()->form('avatars/' . $action, $this, $callback);
  }

  public function upload() {

    if(!panel()->user()->isAdmin() and !$this->user->isCurrent()) {
      throw new Exception(l('users.avatar.error.permission'));
    }

    $root = $this->exists() ? $this->root() : $this->user->avatarRoot('{safeExtension}');

    $upload = new Upload($root, array(
      'accept' => function($upload) {
        if($upload->type() != 'image') {
          throw new Error(l('users.avatar.error.type'));
        }
      }
    ));

    if(!$upload->file()) {
      throw $upload->error();
    }

    // flush the cache in case if the user data is 
    // used somewhere on the site (i.e. for profiles)
    kirby()->cache()->flush();

    kirby()->trigger('panel.avatar.upload', $this);

  }

  public function delete() {

    if(!panel()->user()->isAdmin() and !$this->user->isCurrent()) {
      throw new Exception(l('users.avatar.delete.error.permission'));
    } else if(!$this->exists()) {
      return true;
    }

    if(!parent::delete()) {
      throw new Exception(l('users.avatar.delete.error'));
    } 

    // flush the cache in case if the user data is 
    // used somewhere on the site (i.e. for profiles)
    kirby()->cache()->flush();

    kirby()->trigger('panel.avatar.delete', $this);

  }

}