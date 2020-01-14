<div class="modal-content">
  <?php echo $form ?>
</div>

<script>

(function() {

  app.modal.root.on('setup', function () {
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

      var tag;
      if(form.data('kirbytext')) {
        if(text.length) {
          tag = '(email: ' + address + ' text: ' + text + ')';
        } else {
          tag = '(email: ' + address + ')';
        }
      } else {
        if(text.length) {
          tag = '[' + text + '](mailto:' + address + ')';
        } else {
          tag = '<' + address + '>';
        }
      }

      textarea.insertAtCursor(tag);
      app.modal.close();

    });
  });

})();

</script>
