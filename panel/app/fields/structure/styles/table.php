<table class="structure-table">
  <thead>
    <tr>
      <?php foreach($field->fields() as $f): ?>
      <?php if($f['type'] === 'hidden') continue ?>
      <th>
        <?php echo html($field->i18n($f['label']), false) ?>
      </th>
      <?php endforeach ?>
      <?php if(!$field->readonly()): ?>
      <th class="structure-table-options">  
        &nbsp;
      </th>
      <?php endif ?>
    </tr>    
  </thead>
  <tbody>
    <?php foreach($field->entries() as $entry): ?>
    <tr id="structure-entry-<?php echo $entry->id() ?>">
      <?php foreach($field->fields() as $f): ?>
      <?php if($f['type'] === 'hidden') continue ?>
      <td>
        <?php if(!$field->readonly()): ?>
        <a data-modal href="<?php __($field->url($entry->id() . '/update')) ?>">
        <?php else: ?>
        <span>
        <?php endif ?>
          <?php if(!empty($entry->{$f['name']})): ?>
          <?php echo html($entry->{$f['name']}, false) ?>
          <?php else: ?>
          &nbsp;
          <?php endif ?>
        <?php if(!$field->readonly()): ?>
        </a>
        <?php else: ?>
        </span>
        <?php endif ?>
      </td>
      <?php endforeach ?>
      <?php if(!$field->readonly()): ?>
      <td class="structure-table-options">
        <a data-modal class="btn" href="<?php __($field->url($entry->id() . '/delete')) ?>">
          <?php i('trash-o') ?>
        </a>
      </td>
      <?php endif ?>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>