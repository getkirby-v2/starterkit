<div class="item item-condensed" id="<?php __($subpage->uid()) ?>" data-index="<?php __($subpage->num()) ?>">
  <div class="item-content" title="<?php __($subpage->title()) ?>">
    <div class="item-info">
      <span class="item-title"><?php __($subpage->title()) ?></span>
    </div>
  </div>
  <nav class="item-options item-options-three">
    <ul class="nav nav-bar">
      <li>
        <a class="btn btn-with-icon" href="<?php __($subpage->url('edit')) ?>">
          <i class="icon icon-left marginalia"><?php __($subpage->displayNum()) ?></i>
          <span>Move</span>
        </a>
      </li>
      <li>
        <a class="btn btn-with-icon" href="<?php __($subpage->url('edit')) ?>">
          <?php i('pencil', 'left') ?>
          <span>Edit</span>
        </a>
      </li>
      <li>
        <a data-modal class="btn btn-with-icon" href="<?php __($subpage->url('delete') . '?_redirect=' . $page->uri('subpages')) ?>">
          <?php i('trash-o', 'left') ?>
          <span>Delete</span>
        </a>
      </li>
    </ul>
  </nav>
</div>