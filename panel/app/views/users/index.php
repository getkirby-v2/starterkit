<div class="section">

  <h2 class="hgroup hgroup-single-line cf">
    <span class="hgroup-title">
      <?php _l('users.index.headline') ?> 
      <span class="counter">( <?php echo $users->pagination()->items() ?> )</span>
    </span>
    <?php if(panel()->user()->ui()->create()): ?>
    <span class="hgroup-options shiv shiv-dark shiv-left">
      <a title="+" data-shortcut="+" class="hgroup-option-right" href="<?php _u('users/add') ?>">
        <?php i('plus-circle', 'left') . _l('users.index.add') ?>
      </a>
    </span>
    <?php endif ?>
  </h2>

  <div class="items users">
    <?php foreach($users as $user): ?>
    <?php $read = $user->ui()->read() ?>
    <div class="item item-with-image">
      <div class="item-content">
        <a class="item-image-container" <?= $read ? ' href="' . $user->url('edit') . '"' : '' ?>>
          <figure class="item-image">
            <img src="<?php __($user->avatar(50)->url()) ?>" alt="<?php __($user->username()) ?>">
          </figure>
          <div class="item-info">
            <strong class="item-title"><?php __($user->username()) ?></strong>
            <?php if($read): ?>
            <small class="item-meta marginalia">
              <?php if($user->email()): ?>
                <span style="padding-right: 1em"><?php __($user->email()) ?></span>
              <?php endif ?>
              <span style="padding-right: 1em; font-style: italic; font-size: .9em; color: #aaa"><?php __($user->role()->name()) ?></span>
              <?php if(!$user->password()): ?>
                <span style="font-style: italic; font-size: .9em; color: #aaa"><?php _l('users.index.passwordless') ?></span>
              <?php endif ?>
            </small>
            <?php endif ?>
          </div>
        </a>
      </div>
      <nav class="item-options">

        <ul class="nav nav-bar">
          
          <li>
            <?php if($read && $user->ui()->update()): ?>
            <a class="btn btn-with-icon" href="<?php __($user->url('edit')) ?>">
              <?php i('pencil', 'left') . _l('users.index.edit') ?>
            </a>
            <?php else: ?>
            <span class="btn btn-with-icon btn-disabled">
              <?php i('pencil', 'left') . _l('users.index.edit') ?>
            </span>
            <?php endif ?>
          </li>

          <li>
            <?php if($user->ui()->delete() && !$user->isLastAdmin()): ?>
            <a data-modal class="btn btn-with-icon" href="<?php __($user->url('delete')) ?>">
              <?php i('trash-o', 'left') . _l('users.index.delete') ?>
            </a>
            <?php else: ?>
            <span class="btn btn-with-icon btn-disabled">
              <?php i('trash-o', 'left') . _l('users.index.delete') ?>
            </span>
            <?php endif ?>
          </li>

        </ul>

      </nav>
    </div>
    <?php endforeach ?>
  </div>

  <?php echo $pagination ?>

</div>