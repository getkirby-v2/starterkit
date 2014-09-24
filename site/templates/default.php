<?php snippet('header') ?>

  <main class="main text" role="main">

    <h1><?php echo $page->title()->html() ?></h1>
    <?php echo $page->text()->kirbytext() ?>

  </main>

<?php snippet('footer') ?>