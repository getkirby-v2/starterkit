<?php snippet('header') ?>

  <main class="content" role="main">
    <h1><?php echo html($page->title()) ?></h1>
    <?php echo kirbytext($page->text()) ?>

    <section>
      <?php snippet('projects') ?>
    </section>
  </main>

<?php snippet('footer') ?>