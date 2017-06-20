(function($) {

  $.fn.dropdown = function() {

    return this.each(function() {

      var parent = $(this);

      if(parent.is(document)) {
        // kill all dropdowns when the document is being clicked
        parent.on('click.dropdown', function() {
          parent.find('.dropdown').not('.contextmenu').hide();
        });
        // kill all dropdowns on escape
        parent.on('keydown.dropdown', function(e) {
          if(e.keyCode == 27) parent.trigger('click.dropdown');
        });
        // kill all dropdowns when the browser window is being resized
        $(window).resize(function() {
          parent.find('.dropdown').not('.contextmenu').hide();
        });
      }

      parent.find('.dropdown').hide();
      parent.on('click', '[data-dropdown]', function() {
        parent.trigger('click.dropdown');
        $($(this).attr('href')).show();
        return false;
      });

    });

  };

})(jQuery);