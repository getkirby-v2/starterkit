<ul class="nav nav-list sidebar-list">
  <?php foreach($pages as $c): ?>
  <?php echo new Kirby\Panel\Snippet('pages/sidebar/subpage', array('subpage' => $c)) ?>
  <?php endforeach ?>
</ul>

<?= $pagination ?>

<style>
#pages-widget .pagination {
  margin-top: .5em;
  margin-bottom: 0;
  border-top: 2px solid #ddd;
}
</style>