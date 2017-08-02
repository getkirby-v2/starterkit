(function($) {

  $.fn.urlfield = function() {

    return this.each(function() {

      var $this = $(this);

      if($this.data('urlfield')) {
        return;
      } else {
        $this.data('urlfield', true);
      }

      var $icon = $this.next('.field-icon');

      $icon.css({
        'cursor': 'pointer',
        'pointer-events': 'auto'
      });

      $icon.on('click', function() {

        var url = $.trim($this.val());

        if(url !== '' && $this.is(':valid')) {
          window.open(url);
        } else {
          $this.focus();
        }

      });

    });

  };

})(jQuery);