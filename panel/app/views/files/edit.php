<div class="fileview">

  <figure class="fileview-image">

    <nav class="fileview-nav">

      <?php if($prev = $file->prev()): ?>
      <a title="&lsaquo;" data-shortcut="left" class="fileview-nav-prev" href="<?php __($prev->url('edit')) ?>">
        <?php i('chevron-left fa-lg') ?>
      </a>
      <?php endif ?>

      <?php if($next = $file->next()): ?>
      <a title="&rsaquo;" data-shortcut="right" class="fileview-nav-next" href="<?php __($next->url('edit')) ?>">
        <?php i('chevron-right fa-lg') ?>
      </a>
      <?php endif ?>

    </nav>

    <a title="<?php _l('files.show.open') ?> (o)" data-shortcut="o" target="_blank" class="fileview-image-link fileview-preview-link" href="<?php __($file->url('preview')) ?>">
      <?php if($file->options()->preview() && $file->type() === 'image'): ?>
      <img src="<?php __($file->url('preview')) ?>" alt="<?php __($file->filename()) ?>">
      <?php else: ?>
      <span>
        <strong><?php __($file->filename()) ?></strong>
        <?php __($file->type() . ' / ' . $file->niceSize()) ?>
      </span>
      <?php endif ?>
    </a>

  </figure>

  <aside class="fileview-sidebar">

    <div class="section">

      <?php echo $form ?>

      <nav class="fileview-options">
        <ul class="nav nav-bar nav-btn cf">
          <li>
            <a href="<?php __($page->url('edit')) ?>" class="btn btn-with-icon">
              <?php i('arrow-circle-left', 'left') ?>
              <?php _l('files.show.back') ?>
            </a>
          </li>

          <li>
            <?php if($file->ui()->replace()): ?>
            <a data-upload title="r" data-shortcut="r" href="#replace" class="btn btn-with-icon">
              <?php i('cloud-upload', 'left') ?>
              <?php _l('files.show.replace') ?>
            </a>
            <?php else: ?>
            <span class="btn btn-with-icon btn-disabled">
              <?php i('cloud-upload', 'left') ?>
              <?php _l('files.show.replace') ?>
            </span>
            <?php endif ?>
          </li>

          <li>
            <?php if($file->ui()->delete()): ?>
            <a data-modal title="#" data-shortcut="#" href="<?php __($file->url('delete') . '?_redirect=' . urlencode($returnTo)) ?>" class="btn btn-with-icon">
              <?php i('trash-o', 'left') ?>
              <?php _l('files.show.delete') ?>
            </a>
            <?php else: ?>
            <span class="btn btn-with-icon btn-disabled">
              <?php i('trash-o', 'left') ?>
              <?php _l('files.show.delete') ?>
            </span>
            <?php endif ?>
          </li>

        </ul>
      </nav>
    </div>
  </aside>

</div>

<?php echo $uploader ?>

<script>

$('#form-field-_link').on('click', function() {

  $(this).select();

  try {
    document.execCommand('copy');
  } catch(err) {
    // copy to clipboard is not supported yet
  }

});

</script>