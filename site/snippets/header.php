<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8" />

  <title><?php echo html($site->title()) ?> | <?php echo html($page->title()) ?></title>
  <meta name="description" content="<?php echo html($site->description()) ?>" />

  <?php

  echo css(array(
    'http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,400italic',
    'assets/css/main.css',
  ));

  ?>

</head>
<body>

  <header class="cf" role="banner">
    <a class="branding" href="<?php echo url() ?>"><img src="<?php echo url('assets/images/logo.png') ?>" width="115" height="41" alt="<?php echo html($site->title()) ?>" /></a>
    <?php snippet('menu') ?>
  </header>