<?php snippet('header') ?>

  <main class="content" role="main">
    <h1><?php echo html($page->title()) ?></h1>
    <?php echo kirbytext($page->text()) ?>

    <section>
      <h2>Latest projects</h2>
      <ul class="teaser cf">
        <?php $projects = $page->children()->limit(3) ?>
        <?php foreach($projects as $project): ?>
        <li>
          <h3><?php echo $project->title() ?></h3>
          <p><?php echo excerpt($project->text(), 80) ?> <a href="<?php echo $project->url() ?>">read more &rsaquo;&rsaquo;</a></p>
          <?php $image = $project->images()->first() ?>
          <a href="<?php echo $project->url() ?>"><?php echo thumb($image, array('width' => 320, 'height' => 200, 'crop' => true)) ?></a>
        </li>
        <?php endforeach ?>
      </ul>
    </section>
  </main>

<?php snippet('footer') ?>