<?php 

$user = panel()->user();

return array(
  'title' => array(
    'text'   => l('dashboard.index.account.title'),
    'link'   => $user->url('edit'),
  ),
  'options' => array(
    array(
      'text' => l('dashboard.index.account.edit'),
      'icon' => 'pencil',
      'link' => $user->url('edit')
    )
  ),
  'html'  => function() use($user) {
    return tpl::load(__DIR__ . DS . 'account.html.php', array(
      'user' => $user
    ));
  }
);