<div class="modal-content modal-content-medium" data-slug-table="">
  <?php echo $form ?>
</div>

<script>

(function() {

  $.slug.table = <?php echo slugTable() ?>;

  var modal = $('.modal-content');
  var title = modal.find('[name=title]');
  var uid   = modal.find('[name=uid]');

  title.on('keyup', function() {
    uid.val($.slug(title.val()));
  });

  uid.on('blur', function() {
    uid.val($.slug(uid.val()));
  });

})();

</script>