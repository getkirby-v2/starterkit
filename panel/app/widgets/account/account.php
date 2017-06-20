<?php 

$user = panel()->user();
$read = $user->ui()->read();

if($read) {
  $options = [
    [
      'text' => l('dashboard.index.account.edit'),
      'icon' => 'pencil',
      'link' => $user->url('edit')
    ]
  ];
} else {
  $options = [];
}

return [
  'title' => [
    'text'   => l('dashboard.index.account.title'),
    'link'   => $read ? $user->url('edit') : false,
  ],
  'options' => $options,
  'html'    => function() use($user, $read) {
    return tpl::load(__DIR__ . DS . 'account.html.php', array(
      'user' => $user,
      'read' => $read
    ));
  }
];