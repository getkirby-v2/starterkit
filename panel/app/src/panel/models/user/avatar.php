<?php

namespace Kirby\Panel\Models\User;

use Media;
use Exception;
use Error;
use Thumb;

use Kirby\Panel\Upload;
use Kirby\Panel\Models\User;

class Avatar extends Media {

  public $user;

  public function __construct(User $user, $avatar) {

    $this->user = $user;

    if($avatar) {
      parent::__construct($avatar->root(), $avatar->url());
    } else {
      parent::__construct($this->user->avatarRoot('{safeExtension}'));
    }

  }

  public function form($action, $callback) {
    return panel()->form('avatars/' . $action, $this, $callback);
  }

  public function url() {
    return $this->exists() ? parent::url() . '?' . $this->modified() : purl('assets/images/avatar.png');
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

    thumb::$defaults['root'] = dirname($upload->file()->root());

    $thumb = new Thumb($upload->file(), array(
      'filename'  => $upload->file()->filename(),
      'overwrite' => true,
      'width'     => 256,
      'height'    => 256,
      'crop'      => true
    ));

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