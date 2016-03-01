<div class="dashboard-box">
  <a class="dashboard-item" target="_blank" href="<?php echo url() ?>">
    <figure>
      <span class="dashboard-item-icon dashboard-item-icon-with-border"><i class="fa fa-chain"></i></span>
      <figcaption class="dashboard-item-text"><?php e(url::isAbsolute(url()), url::short(url()), url()) ?></figcaption>
    </figure>
  </a>
</div>