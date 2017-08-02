<?php 

return array(
  'title' => array(
    'text'   => l('dashboard.index.site.title'),
    'link'   => url(),
    'target' => '_blank',
  ),
  'html'  => function() {
    return tpl::load(__DIR__ . DS . 'site.html.php');
  }
);