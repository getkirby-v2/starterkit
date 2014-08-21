<?php snippet('header') ?>

  <main class="main" role="main">

    <h1><?php echo html($page->title()) ?></h1>
    <?php echo kirbytext($page->text()) ?>

    <hr>

    <?php snippet('projects') ?>

  </main>

<?php snippet('footer') ?>