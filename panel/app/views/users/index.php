<div class="section">

  <h2 class="hgroup hgroup-single-line cf">
    <span class="hgroup-title">
      <?php _l('users.index.headline') ?> 
      <span class="counter">( <?php echo $users->pagination()->items() ?> )</span>
    </span>
    <?php if($admin): ?>
    <span class="hgroup-options shiv shiv-dark shiv-left">
      <a title="+" data-shortcut="+" class="hgroup-option-right" href="<?php _u('users/add') ?>">
        <?php i('plus-circle', 'left') . _l('users.index.add') ?>
      </a>
    </span>
    <?php endif ?>
  </h2>

  <div class="items users">
    <?php foreach($users as $user): ?>
    <div class="item item-with-image">
      <div class="item-content">
        <figure class="item-image">
          <a class="item-image-container" href="<?php __($user->url('edit')) ?>">
            <img src="<?php __($user->avatar(50)->url()) ?>" alt="<?php __($user->username()) ?>">
          </a>
        </figure>
        <div class="item-info">
          <a href="<?php __($user->url('edit')) ?>">
            <strong class="item-title"><?php __($user->username()) ?></strong>
            <small class="item-meta marginalia">
              <?php __($user->email()) ?>
              <span style="padding-left: 1em; font-style: italic; font-size: .9em; color: #aaa"><?php __($user->role()->name()) ?></span>
            </small>
          </a>
        </div>
      </div>
      <?php if($admin or $user->isCurrent()): ?>
      <nav class="item-options">

        <ul class="nav nav-bar">
          <li>
            <a class="btn btn-with-icon" href="<?php __($user->url('edit')) ?>">
              <?php i('pencil', 'left') . _l('users.index.edit') ?>
            </a>
          </li>
          <li>
            <a data-modal class="btn btn-with-icon" href="<?php __($user->url('delete')) ?>">
              <?php i('trash-o', 'left') . _l('users.index.delete') ?>
            </a>
          </li>
        </ul>

      </nav>
      <?php endif ?>
    </div>
    <?php endforeach ?>
  </div>

  <?php echo $pagination ?>

</div>