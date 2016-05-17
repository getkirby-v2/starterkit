<div class="modal-content">
  <?php echo $form ?>
</div>
<script>

var modal = $('.modal-content');

modal.find('select').on('change', function() {

  var action = modal.find('form').attr('action');
  var field  = modal.find('.field').last();
  var data   = {info: $(this).val(), csrf: $('body').attr('data-csrf')};

  $.post(action, data, function(response) {
    field.html(response);
    $(document).trigger('keyup');
  });

}).trigger('change');

</script>