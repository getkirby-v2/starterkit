(function($) {

  var Structure = function(el) {

    var element  = $(el);
    var style    = element.data('style');
    var api      = element.data('api');
    var sortable = element.data('sortable');
    var entries  = style == 'table' ? element.find('.structure-table tbody') : element.find('.structure-entries');

    if(sortable === false) return false;

    entries.sortable({
      helper: function(e, ui) {
        ui.children().each(function() {
          $(this).width($(this).width());
        });
        return ui.addClass('structure-sortable-helper');
      },
      update: function() {

        var ids = [];

        $.each($(this).sortable('toArray'), function(i, id) {
          ids.push(id.replace('structure-entry-', ''));
        });

        $.post(api, {ids: ids}, function() {
          app.content.reload();
        });

      }
    });

  };

  $.fn.structure = function() {

    return this.each(function() {

      if($(this).data('structure')) {
        return $(this);
      } else {
        var structure = new Structure(this);
        $(this).data('structure', structure);
        return $(this);
      }

    });

  };

})(jQuery);