<?php snippet('header') ?>

  <main class="main" role="main">

    <h1><?php echo $page->title()->html() ?></h1>
    <?php echo $page->text()->kirbytext() ?>

    <hr>

    <?php snippet('projects') ?>

  </main>

<?php snippet('footer') ?>