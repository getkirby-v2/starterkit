<h2>Latest projects</h2>
<ul class="teaser cf">
  <?php foreach(page('projects')->children()->visible()->limit(3) as $project): ?>
  <li>
    <h3><a href="<?php echo $project->url() ?>"><?php echo html($project->title()) ?></a></h3>
    <p><?php echo excerpt($project->text(), 80) ?> <a href="<?php echo $project->url() ?>">read&nbsp;more &rsaquo;</a></p>
    <?php if($project->image()): ?>
    <a href="<?php echo $project->url() ?>">
      <img src="<?php echo $project->image()->url() ?>" alt="<?php echo html($project->title()) ?>" >
    </a>
    <?php endif ?>
  </li>
  <?php endforeach ?>
</ul>
