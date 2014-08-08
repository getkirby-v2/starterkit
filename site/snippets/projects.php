<h2>Latest projects</h2>
<ul class="teaser cf">
  <?php foreach(page('projects')->children()->visible()->limit(3) as $project): ?>
  <li>
    <h3><?php echo html($project->title()) ?></h3>
    <p><?php echo excerpt($project->text(), 80) ?> <a href="<?php echo $project->url() ?>">read more →</a></p>
    <?php if($image = $project->image()): ?>
    <a href="<?php echo $project->url() ?>">
      <img src="<?php echo $image->url() ?>" alt="<?php echo html($project->title()) ?>" >
    </a>
    <?php endif ?>
  </li>
  <?php endforeach ?>
</ul>
