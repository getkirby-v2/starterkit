(function($) {

  $.fn.message = function() {

    return this.each(function() {

      var message = $(this);
      var form    = message.closest('form');

      message.on('close', function() {
        message.remove();
        form.find('.field-with-error').removeClass('field-with-error');
        form.find('[autofocus]').focus();
        $(document).trigger('keyup.center');
      });

      message.on('click', function() {
        message.trigger('close');
      });

    });

  };

})(jQuery);