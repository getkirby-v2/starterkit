(function($) {

  $.fn.passwordSuggestion = function() {

    return this.each(function() {

      var input     = $(this);
      var field     = input.closest('.field');
      var form      = input.closest('.form');
      var suggest   = field.find('.pw-suggestion');
      var passwords = form.find('[type=password]');

      // Password suggestion
      field.find('.pw-reload').on('click', function(e) {
        e.preventDefault();
        suggest.text($.token());
      }).trigger('click');

      passwords.on('blur', function() {
        passwords.attr('type', 'password');
      });

      suggest.click(function(e) {
        e.preventDefault();
        var pass = suggest.text();
        input.attr('type', 'text').val(pass).first().select();

        // try to find a matching confirmation find and fill that as well
        form.find('[name=passwordconfirmation]').attr('type', 'text').val(pass);

      });

    });

  };

})(jQuery);