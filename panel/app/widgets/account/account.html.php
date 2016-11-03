<div class="dashboard-box">
  <a class="dashboard-item"<?php e($read, ' href="' . $user->url() . '"') ?>>
    <figure>
      <img class="dashboard-item-icon" src="<?php echo $user->avatar(50)->url() ?>" alt="<?php __($user->username()) ?>">
      <figcaption class="dashboard-item-text">
        <?php __($user->username()) ?>
      </figcaption>
    </figure>
  </a>
</div>