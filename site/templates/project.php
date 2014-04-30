<?php snippet('header') ?>

  <main role="main">
    <h1><?php echo html($page->title()) ?></h1>
    <ul class="meta cf">
      <li><b>Year:</b> <time datetime="<?php echo $page->date('c') ?>"><?php echo $page->date('Y', 'year') ?></time></li>
      <li><b>Tags:</b> <?php echo $page->tags() ?></li>
    </ul>
    <?php echo kirbytext($page->text()) ?>

    <nav class="nextprev cf">
      <?php if($prev = $page->prevVisible()): ?>
      <a class="prev" href="<?php echo $prev->url() ?>">&larr;</a>
      <?php endif ?>
      <?php if($next = $page->nextVisible()): ?>
      <a class="next" href="<?php echo $next->url() ?>">&rarr;</a>
      <?php endif ?>
    </nav>
  </main>

<?php snippet('footer') ?>