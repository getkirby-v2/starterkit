<h2 class="hgroup hgroup-single-line hgroup-compressed cf">
  <span class="hgroup-title">
    <a href="<?php __($page->url('files')) ?>"><?php e($page->isSite(), l('metatags.files'), l('pages.show.files.title')) ?></a>
  </span>
  <span class="hgroup-options shiv shiv-dark shiv-left">
    <span class="hgroup-option-right">
      <a title="<?php _l('pages.show.files.edit') ?>" href="<?php __($page->url('files')) ?>">
        <?php i('pencil', 'left') ?><span><?php _l('pages.show.files.edit') ?></span>
      </a>
      <?php if($page->canHaveMoreFiles()) : ?>
      <a data-upload href="#upload">
        <?php i('plus-circle', 'left') ?><span><?php _l('pages.show.files.add') ?></span>
      </a>
      <?php endif ?>
    </span>
  </span>
</h2>

<?php if($files->count()): ?>
<ul class="nav nav-list sidebar-list">
  <?php foreach($files as $file): ?>
  <?php echo new Kirby\Panel\Snippet('pages/sidebar/file', array('file' => $file)) ?>
  <?php endforeach ?>
</ul>
<?php else: ?>
<p class="marginalia"><a data-upload href="#upload" class="marginalia"><?php _l('pages.show.files.empty') ?></a></p>
<?php endif ?>