<a id="menu-toggle" class="nav-icon nav-icon-left" data-dropdown="true" href="#menu">
  <?php i('bars fa-lg') ?>
</a>

<nav id="menu" class="dropdown dropdown-left">
  <ul class="nav nav-list dropdown-list">
    <li>
      <a href="<?php _u() ?>">
        <?php i('file-o', 'left') . _l('dashboard') ?>
      </a>
    </li>

    <?php if(panel()->access('options')->isAllowed()): ?>
    <li>
      <a href="<?php _u('options') ?>">
        <?php i('gear', 'left') . _l('metatags') ?>
      </a>
    </li>
    <?php endif ?>

    <?php if(panel()->access('users')->isAllowed()): ?>
    <li>
      <a href="<?php _u('users') ?>">
        <?php i('user', 'left') . _l('users') ?>
      </a>
    </li>
    <?php endif ?>

    <li>
      <a href="<?php echo panel()->urls()->logout() ?>" target="_self">
        <?php i('power-off', 'left') . _l('logout') ?>
      </a>
    </li>
  </ul>
</nav>