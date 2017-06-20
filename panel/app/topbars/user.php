<?php 

return function($topbar, $user) {

  $topbar->append(purl('users'), l('users'));

  if($user === 'user') {
    $topbar->append(purl('users/add'), l('users.index.add'));    
  } else {
    $topbar->append($user->url(), $user->username());    
  }

};