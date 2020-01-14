<?php 

use Kirby\Panel\Snippet;

$site    = panel()->site();
$options = array();
$pages   = $site->ui()->pages();

if($pages) {
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
    'link'       => $pages ? $site->url('subpages') : false,
    'compressed' => true
  ),
  'options' => $options,
  'html'  => function() use($site) {
    $pages = $site->children()->paginated('sidebar');

    $pagination = new Snippet('pagination', array(
      'pagination' => $pages->pagination(),
      'nextUrl'    => $pages->pagination()->nextPageUrl(),
      'prevUrl'    => $pages->pagination()->prevPageUrl(),
    ));

    return tpl::load(__DIR__ . DS . 'pages.html.php', compact('pages', 'pagination'));
  }
);