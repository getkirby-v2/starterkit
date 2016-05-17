<?php if(!empty($replaced) || !empty($added) || !empty($removed)): ?>
<div class="text">
  <p><?php echo l('pages.template.warning.text') ?></p>

  <?php if(!empty($removed)): ?>
  <p>
    <strong class="label"><?php echo l('pages.template.warning.removed') ?></strong>
    <em class="input input-is-readonly"><?php echo implode(', ', $removed) ?></em>
  </p>
  <?php endif ?>

  <?php if(!empty($added)): ?>
  <p>
    <strong class="label"><?php echo l('pages.template.warning.added') ?></strong>
    <em class="input input-is-readonly"><?php echo implode(', ', $added) ?></em>
  </p>
  <?php endif ?>

  <?php if(!empty($replaced)): ?>
  <p>
    <strong class="label"><?php echo l('pages.template.warning.replaced') ?></strong>
    <em class="input input-is-readonly"><?php echo implode(', ', $replaced) ?></em>
  </p>
  <?php endif ?>

</div>
<?php endif ?>