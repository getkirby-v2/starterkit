<?php 

return array(
  'title' => array(
    'text'   => l('dashboard.index.history.title'),
    'link'   => false,
  ),
  'html' => function() {
    return tpl::load(__DIR__ . DS . 'history.html.php', array(
      'history' => panel()->user()->history()->get()
    ));
  }
);