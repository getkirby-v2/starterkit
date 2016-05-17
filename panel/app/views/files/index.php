<div class="section">

  <h2 class="hgroup cf">
    <span class="hgroup-title">
      <?php if($page->isSite()): ?>
      <?php _l('metatags.files') ?>
      <?php else: ?>
      <?php _l('files.index.headline') ?> <a href="<?php __($back) ?>"><?php __($page->title()) ?></a>
      <?php endif ?>
      <span class="counter">( <?php echo $files->count() ?> )</span>
    </span>
    <span class="hgroup-options shiv shiv-dark shiv-left cf">

      <a class="hgroup-option-left" href="<?php __($back) ?>">
        <?php i('arrow-circle-left', 'left') . _l('files.index.back') ?>
      </a>

      <?php if($page->hasFiles() and $page->canHaveMoreFiles()): ?>
      <a data-upload class="hgroup-option-right" href="#upload">
        <?php i('plus-circle', 'left') . _l('files.index.upload') ?>
      </a>
      <?php endif ?>
    </span>
  </h2>

  <?php if($files->count()): ?>
  <div class="files" data-api="<?php __($page->url('files')) ?>">

    <div class="grid<?php e($sortable, ' sortable') ?>">

      <?php foreach($files as $file): ?><!--
   --><div class="grid-item" id="<?php __($file->filename()) ?>">
        <figure title="<?php __($file->filename()) ?>" class="file">
          <a class="file-preview file-preview-is-<?php __($file->type()) ?>" href="<?php __($file->url('edit')) ?>">
            <?php if($file->extension() == 'svg'): ?>
            <object data="<?php __($file->url('preview')) ?>"></object>
            <?php elseif($file->canHavePreview()): ?>
            <img src="<?php __($file->crop(400, 266)->url()) ?>" alt="<?php __($file->filename()) ?>">
            <?php else: ?>
            <span><?php __($file->extension()) ?></span>
            <?php endif ?>
          </a>
          <figcaption class="file-info">
            <a href="<?php __($file->url('edit')) ?>">
              <span class="file-name cut"><?php __($file->filename()) ?></span>
              <span class="file-meta marginalia cut"><?php __($file->type() . ' / ' . $file->niceSize()) ?></span>
            </a>
          </figcaption>
          <nav class="file-options cf">

            <a class="btn btn-with-icon" href="<?php __($file->url('edit')) ?>">
              <?php i('pencil', 'left') ?><span><?php _l('files.index.edit') ?></span>
            </a>

            <a data-modal class="btn btn-with-icon" href="<?php __($file->url('delete') . '?_redirect=' . $page->uri('files')) ?>">
              <?php i('trash-o', 'left') ?><span><?php _l('files.index.delete') ?></span>
            </a>

          </nav>
        </figure>
      </div><!--
   --><?php endforeach ?>

    </div>

  </div>

  <?php else: ?>

  <div class="instruction">
    <div class="instruction-content">
      <p class="instruction-text"><?php _l('files.index.upload.first.text') ?></p>
      <?php if($page->canHaveMoreFiles()) : ?>
      <a data-upload data-shortcut="+" class="btn btn-rounded" href="#upload">
        <?php _l('files.index.upload.first.button') ?>
      </a>
      <?php endif ?>
    </div>
  </div>

  <?php endif ?>

</div>

<?php echo $uploader ?>

<script>

var files    = $('.files');
var sortable = files.find('.sortable');
var items    = files.find('.grid-item');
var api      = files.data('api');

if(sortable.find('.grid-item').length > 1) {
  sortable.sortable({
    update: function() {
      $.post(api, {filenames: $(this).sortable('toArray'), action: 'sort'}, function(data) {
        app.content.reload();        
      });
    }
  }).disableSelection();
}

</script>