<?php snippet('header') ?>

  <main role="main">
    <h1><?php echo html($page->title()) ?></h1>
    <ul class="meta cf">
      <li><b>Year:</b> <time datetime="<?php echo $page->date('c') ?>"><?php echo $page->date('Y', 'year') ?></time></li>
      <li><b>Tags:</b> <?php echo $page->tags() ?></li>
    </ul>
    <div class="text">
      <?php echo kirbytext($page->text()) ?>

      <?php foreach($page->images() as $image): ?>
      <figure>
        <img src="<?php echo $image->url() ?>" alt="<?php echo html($page->title()) ?>">
      </figure>
      <?php endforeach ?>

    </div>
    <nav class="nextprev cf">
      <?php if($prev = $page->prevVisible()): ?>
      <a class="btn prev" href="<?php echo $prev->url() ?>">&larr; previous</a>
      <?php endif ?>
      <?php if($next = $page->nextVisible()): ?>
      <a class="btn next" href="<?php echo $next->url() ?>">next &rarr;</a>
      <?php endif ?>
    </nav>
  </main>

<?php snippet('footer') ?>