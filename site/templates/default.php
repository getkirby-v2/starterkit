<?php snippet('header') ?>

  <main class="content" role="main">
    <h1><?php echo html($page->title()) ?></h1>
    <?php echo kirbytext($page->text()) ?>
  </main>

<?php snippet('footer') ?>