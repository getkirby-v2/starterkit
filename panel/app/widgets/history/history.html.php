<div class="dashboard-box">
  <?php if(empty($history)): ?>
  <div class="text"><?php _l('dashboard.index.history.text') ?></div>
  <?php else: ?>
  <ul class="dashboard-items">
    <?php foreach($history as $item): ?>
    <li class="dashboard-item">
      <a title="<?php __($item->title()) ?>" href="<?php __($item->url('edit')) ?>">
        <figure>
          <span class="dashboard-item-icon dashboard-item-icon-with-border"><?php echo $item->icon('') ?></span>
          <figcaption class="dashboard-item-text"><?php __($item->title()) ?></figcaption>
        </figure>
      </a>
    </li>
    <?php endforeach ?>
  </ul>
  <?php endif ?>
</div>
