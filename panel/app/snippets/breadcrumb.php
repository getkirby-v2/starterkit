<nav class="breadcrumb">
  <a class="nav-icon nav-icon-left" data-dropdown href="#breadcrumb-menu">
    <?php i('sitemap fa-lg') ?>
  </a>
  <ul class="nav nav-bar breadcrumb-list cf">
    <li>
      <a title="<?php _l('dashboard') ?>" class="breadcrumb-link" href="<?php _u() ?>"><span class="breadcrumb-label"><?php _l('dashboard') ?></span></a>
    </li>
    <?php if(!empty($items)): ?>
    <?php foreach($items as $item): ?>
    <li>
      <a title="<?php __($item['title']) ?>" class="breadcrumb-link" href="<?php __($item['url']) ?>">
        <span class="breadcrumb-label"><?php __($item['title']) ?></span>
      </a>
    </li>
    <?php endforeach ?>
    <?php endif ?>
  </ul>
</nav>