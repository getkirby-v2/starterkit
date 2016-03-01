<?php 

$attr = attr(array(
  'multiple' => isset($multiple) ? $multiple : true,
  'accept'   => isset($accept) ?   $accept   : false
));

?>
<form id="upload" class="hidden" action="<?php __($url) ?>" method="post" enctype="multipart/form-data">
  <input type="file" name="file" <?php echo $attr ?>>
  <input type="hidden" name="csrf" value="<?php __(panel()->csrf()) ?>">
</form>

<script>

$('#upload').uploader(function() {
  app.content.reload();
});

</script>