(function($) {

  $.shortcuts = {

    events : {},

    add : function(key, event) {
      // don't register an event twice
      if(key in this.events) return true;

      // register the event
      this.events[key] = event;

      // bind it to the document's keydown event
      $(document).bind('keydown.shortcuts', key, function(e) {
        e.preventDefault();
        event(e);
      });

    },

    reset : function() {
      $(document).unbind('keydown.shortcuts');
      this.events = {};
    }

  };

  $.fn.shortcuts = function() {

    $.shortcuts.reset();

    return this.each(function() {

      // register the keyboard shorcuts for this element
      $(this).find('[data-shortcut]').each(function() {

        var item = $(this);
        var key  = item.data('shortcut');

        $.shortcuts.add(key, function(e) {

          if(item.is('button')) {
            item.trigger('click');
          } else if(item.is('a')) {
            if(item.attr('target') == '_blank') {
              window.open(item.attr('href'));
            } else if(item.is('[data-modal]')) {
              app.modal.open(item.attr('href'));
            } else if(item.is('[data-upload]')) {          
              return false;
            } else {
              app.content.open(item.attr('href'));
            }
          }

        });

      });

    });

  };

})(jQuery);