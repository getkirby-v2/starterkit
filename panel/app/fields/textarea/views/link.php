<div class="modal-content">
  <?php echo $form ?>
</div>

<script>

(function() {

  var form      = $('.modal .form');
  var textarea  = $('#' + form.data('textarea'));
  var selection = textarea.getSelection();
  var urlField  = form.find('input[name=url]');
  var textField = form.find('input[name=text]');

  if(selection.length) {
    if(selection.match(/^http|s\:\/\//)) {
      urlField.val(selection);
    } else {
      textField.val(selection);
    }
  }

  form.on('submit', function() {

    var url  = urlField.val();
    var text = textField.val();

    // make sure not to add invalid parenthesis
    text = text.replace('(', '[');
    text = text.replace(')', ']');

    if(!text.length) {
      if(url.match(/^http|s\:\/\//)) {
        var tag = '<' + url + '>';
      } else if(form.data('kirbytext')) {
        var tag = '(link: ' + url + ')';
      } else {
        var tag = '<' + url + '>';
      }
    } else if(form.data('kirbytext')) {
      var tag = '(link: ' + url + ' text: ' + text + ')';
    } else {
      var tag = '[' + text + '](' + url + ')';
    }

    textarea.insertAtCursor(tag);
    app.modal.close();

  });

})();

</script>