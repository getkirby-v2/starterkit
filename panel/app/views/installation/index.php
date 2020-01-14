<div class="modal-content">
  <?php echo $form ?>
</div>

<script>

(function() {

  // center the installation box
  $('.modal-content').center(48);

  // setup the message events
  $('.message').message();

  // setup the fake focus for the select box
  $('[data-focus=true]').fakefocus('input-is-focused');

  // start the password suggestion magic
  $('[data-field="passwordSuggestion"]').passwordSuggestion();

})();

</script>