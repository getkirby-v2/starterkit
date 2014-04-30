<h2>Latest projects</h2>
<ul class="teaser cf">
  <?php foreach(page('projects')->children()->limit(3) as $project): ?>
  <li>
    <h3><a href="<?php echo $project->url() ?>"><?php echo html($project->title()) ?></a></h3>
    <p><?php echo excerpt($project->text(), 80) ?> <a href="<?php echo $project->url() ?>">read more &rsaquo;</a></p>
    <a href="<?php echo $project->url() ?>">
      <?php echo thumb($project->image(), array('width' => 320, 'height' => 200, 'crop' => true)) ?>
    </a>
  </li>
  <?php endforeach ?>
</ul>
