<div class="modal-content">
  <?php echo $form ?>
</div> 

<script>

(function() {

  var form         = $('.modal .form');
  var textarea     = $('#' + form.data('textarea'));
  var selection    = textarea.getSelection();
  var addressField = form.find('input[name=address]');
  var textField    = form.find('input[name=text]');

  if(selection.length) {
    if(selection.match(/\@/)) {
      addressField.val(selection);
    } else {
      textField.val(selection);
    }
  }

  form.on('submit', function() {

    var address = addressField.val();
    var text    = textField.val();

    // make sure not to add invalid parenthesis
    text = text.replace('(', '[');
    text = text.replace(')', ']');

    if(!text.length) {
      var tag = '<' + address + '>';
    } else if(form.data('kirbytext')) {
      var tag = '(email: ' + address + ' text: ' + text + ')';
    } else {
      var tag = '[' + text + '](mailto:' + address + ')';
    }

    textarea.insertAtCursor(tag);
    app.modal.close();

  });

})();

</script>