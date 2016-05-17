<table class="structure-table">
  <thead>
    <tr>
      <?php foreach($field->fields() as $f): ?>
      <th>
        <?php echo html($field->i18n($f['label']), false) ?>
      </th>
      <?php endforeach ?>
      <th class="structure-table-options">  
        &nbsp;
      </th>
    </tr>    
  </thead>
  <tbody>
    <?php foreach($field->entries() as $entry): ?>
    <tr id="structure-entry-<?php echo $entry->id() ?>">
      <?php foreach($field->fields() as $f): ?>
      <td>
        <a data-modal href="<?php __($field->url($entry->id() . '/update')) ?>">
          <?php if(!empty($entry->{$f['name']})): ?>
          <?php echo html($entry->{$f['name']}, false) ?>
          <?php else: ?>
          &nbsp;
          <?php endif ?>
        </a>
      </td>
      <?php endforeach ?>
      <td class="structure-table-options">
        <a data-modal class="btn" href="<?php __($field->url($entry->id() . '/delete')) ?>">
          <?php i('trash-o') ?>
        </a>
      </td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>