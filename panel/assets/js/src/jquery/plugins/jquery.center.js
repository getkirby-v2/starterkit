(function($) {

  $.fn.center = function(margin) {

    return this.each(function() {

      if($(this).data('center')) {
        return $(this).data('center');
      } else {

        var box    = $(this);
        var win    = $(window);
        var height = function() {
          return box.height() + margin;
        }

        box.data('height', height());

        $(document).on('keyup.center', function() {
          box.data('height', height());
          win.trigger('resize.center');
        });

        win.on('resize.center', function() {
          if(win.height() <= box.data('height') + margin) {
            box.css({
              'top'        : 'auto',
              'margin-top' : 0
            });
          } else {
            box.css({
              'top'        : '50%',
              'margin-top' : -(Math.round(box.data('height') / 2))
            });
          }
        }).trigger('resize.center');

      }

    });

  };

})(jQuery);