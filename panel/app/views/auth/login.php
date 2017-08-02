<div class="modal-content">
  <?php echo $form ?>
</div> 

<script>

(function() {  
  
  $('.message').message();

  $('.form').on('submit', function() {
    $(this).addClass('loading');
  });

  $('.modal-content').center(48);

})();

</script>