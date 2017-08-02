<?php if($pagination->pages() > 1): ?>
<nav class="pagination cf">
  <a class="pagination-prev<?php e(!$pagination->hasPrevPage(), ' pagination-inactive') ?>" href="<?php echo $prevUrl ?>"><?php i('chevron-left') ?></a>
  <span class="pagination-index">
    <?php echo $pagination->page() . ' / ' . $pagination->pages() ?>
    <select onchange="app.content.open(this.value)">
      <?php foreach(range(1, $pagination->pages()) as $p): ?>
      <option value="<?php echo $pagination->pageUrl($p) ?>"<?php e($p == $pagination->page(), ' selected') ?>><?php echo $p ?></option>
      <?php endforeach ?> 
    </select>
  </span>
  <a class="pagination-next<?php e(!$pagination->hasNextPage(), ' pagination-inactive') ?>" href="<?php echo $nextUrl ?>"><?php i('chevron-right') ?></a>
</nav>
<?php endif ?>