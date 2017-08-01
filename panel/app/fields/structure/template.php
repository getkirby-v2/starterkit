<div class="structure<?php e($field->readonly(), ' structure-readonly') ?>" 
  data-field="structure" 
  data-api="<?php __($field->url('sort')) ?>" 
  data-sortable="<?php e($field->sortable() && $field->entries()->count(), 'true', 'false') ?>"
  data-style="<?php echo $field->style() ?>">

  <?php echo $field->headline() ?>

  <div class="structure-entries">

    <?php if(!$field->entries()->count()): ?>
    <div class="structure-empty">
      <?php _l('fields.structure.empty') ?> <a data-modal class="structure-add-button" href="<?php __($field->url('add')) ?>"><?php _l('fields.structure.add.first') ?></a>
    </div>
    <?php else: ?>
    <?php require(__DIR__ . DS . 'styles' . DS . $field->style() . '.php') ?>
    <?php endif ?>
  </div>

</div>