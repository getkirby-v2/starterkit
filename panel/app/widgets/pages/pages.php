<?php 

$site    = panel()->site();
$options = array();

if($site->canHaveSubpages()) {
  $options[] = array(
    'text' => l('dashboard.index.pages.edit'),
    'icon' => 'pencil',
    'link' => $site->url('subpages')
  );
}

if($addbutton = $site->addButton()) {
  $options[] = array(
    'text'  => l('dashboard.index.pages.add'),
    'icon'  => 'plus-circle',
    'link'  => $addbutton->url(),
    'modal' => $addbutton->modal(),
    'key'   => '+',
  );
}

return array(
  'title' => array(
    'text'       => l('dashboard.index.pages.title'),
    'link'       => $site->url('subpages'),
    'compressed' => true
  ),
  'options' => $options,
  'html'  => function() use($site) {
    return tpl::load(__DIR__ . DS . 'pages.html.php', array(
      'pages' => $site->children()->paginated('sidebar')
    ));
  }
);