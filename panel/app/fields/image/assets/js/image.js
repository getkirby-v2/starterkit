(function($) {

  $.fn.imagefield = function() {

    return this.each(function() {

      var field = $(this);

      // avoid multiple init
      if(field.data('imagefield')) return true;
      field.data('imagefield', true);

      var select  = field.find('select');
      var preview = field.find('.input-preview figure');
      var link    = preview.parent('a');

      select.on('keydown change', function() {

        var option = select.find('option:selected');
        var url    = option.data('url');
        var thumb  = option.data('thumb');

        if(option.val() === '') {
          url = '#';
        }

        if(thumb) {
          preview.attr('style', 'background-image: url(' + thumb + ')');          
        } else {
          preview.attr('style', 'background-image: none');
        }

        link.attr('href', url);

      }).trigger('change');

      field.find('.input-preview').on('click', function() {
        if($(this).attr('href') == '#') {
          return false;
        }
      });

      field.find('.input').droppable({
        hoverClass: 'over',
        accept: $('.sidebar .draggable-file'),
        drop: function(e, ui) {
          $(this).find('select').val(ui.draggable.data('helper')).trigger('change');
        }
      });

    });

  };

})(jQuery);