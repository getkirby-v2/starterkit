<?php snippet('header') ?>

  <main class="content text" role="main">
    <h1><?php echo html($page->title()) ?></h1>
    <?php echo kirbytext($page->text()) ?>
  </main>

<?php snippet('footer') ?>