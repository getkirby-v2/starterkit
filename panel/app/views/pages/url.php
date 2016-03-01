<div class="modal-content modal-content-medium">
  <?php echo $form ?>
</div>

<script>

(function() {

  $.slug.table = <?php echo slugTable() ?>;

  var modal   = $('.modal-content');
  var toggle  = modal.find('.label a');
  var input   = modal.find('.input');
  var preview = modal.find('.uid-preview span');

  toggle.on('click', function() {
    input.val(toggle.data('title')).trigger('blur').focus();
    return false;
  });

  input.on('keyup', function() {
    preview.text($.slug(input.val()));
  });

  input.on('blur', function() {
    var slug = $.slug(input.val());
    preview.text(slug);
    input.val(slug);
  });

})();

</script>