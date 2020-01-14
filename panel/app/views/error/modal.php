<div class="modal-content">
  <div class="form">
    <div class="field">
      <?php if(empty($headline)): ?>
      <label class="label"><?php __(l('error.headline')) ?></label>
      <?php else: ?>
      <label class="label"><?php __($headline) ?></label>
      <?php endif ?>
      <div class="text">
        <p><?php echo html($text) ?></p>
      </div>
    </div>
    <div class="buttons buttons-centered cf">
      <?php if(empty($back)) $back = url::current() ?>
      <a class="btn btn-rounded btn-cancel" href="<?php __($back) ?>"><?php _l('ok') ?></a>
    </div>
  </div>
</div>