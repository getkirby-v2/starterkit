<?php 

$license = panel()->license();

if($license->type() == 'trial' and !$license->local()) {

  return array(
    'title' => array(
      'text'       => l('dashboard.index.license.title'),
      'link'       => false,
      'compressed' => false
    ),
    'html' => function() {
      return tpl::load(__DIR__ . DS . 'license.html.php', array(
        'text' => kirbytext(str::template(l('dashboard.index.license.text'), array(
          'buy'  => 'http://getkirby.com/buy',
          'docs' => 'http://getkirby.com/docs/installation/license-code'
        )))
      ));
    }
  );

} else {
  return false;
}