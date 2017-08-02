<?php if($pages->count()): ?>
<section class="search-section">
  <ul class="nav nav-list">
    <?php foreach($pages as $page): ?>
    <li>
      <a href="<?php _u('pages/' . $page['uri'] . '/edit') ?>">
        <?php i('file-o', 'left') ?>
        <span>
          <strong><?php __($page['title']) ?></strong>
          <small><?php echo $page['uri'] ?></small>
        </span>
      </a>
    </li>
    <?php endforeach ?>
  </ul>
</section>
<?php endif ?>

<?php if($users->count()): ?>
<section class="search-section">
  <ul class="nav nav-list">
    <?php foreach($users as $user): ?>
    <li>
      <a href="<?php _u('users/' . $user['username'] . '/edit') ?>">
        <?php i('user', 'left') ?>
        <span>
          <strong><?php __(ucfirst($user['username'])) ?></strong>
          <small><?php echo $user['email'] ?></small>
        </span>
      </a>
    </li>
    <?php endforeach ?>
  </ul>
</section>
<?php endif ?>