<li>
  <a class="draggable<?php e($subpage->isInvisible(), ' invisible'); ?>" data-helper="<?php __($subpage->title(), 'attr') ?>" data-text="<?php __($subpage->dragText()) ?>" href="<?php __($subpage->url('edit')) ?>">
    <?php echo $subpage->icon() ?><span><?php __($subpage->title()) ?></span>
    <small class="marginalia shiv shiv-left shiv-white"><?php __($subpage->displayNum()) ?></small>
  </a>
  <a class="option" data-context="<?php __($subpage->url('context')) ?>" href="#options"><?php i('ellipsis-h') ?></a>
</li>