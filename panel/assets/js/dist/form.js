(function($) {

  $.fn.date = function() {

    return this.each(function() {

      if($(this).data('pikaday')) {
        return $(this);
      }

      var input  = $(this).attr('type', 'text');
      var hidden = input.next();
      var format = input.data('format');
      var val    = input.val();
      var date   = val ? moment(val).format(format) : null;

      input.attr('placeholder', format);
      input.val(date);

      // don't initialize the datepicker on readonly fields
      if(input.is('[readonly]')) {
        return false;
      }

      input.on('change', function() {
        var val = input.val();
        if(val) {
          hidden.val(moment(val, format).format('YYYY-MM-DD'));
        } else {
          hidden.val('');
        }
      });

      var pikaday = new Pikaday({
        field    : this,
        firstDay : 1,
        format   : format,
        i18n     : input.data('i18n'),
        onSelect : function(date) {
          hidden.val(moment(date).format('YYYY-MM-DD'));
        }
      });

      $(this).data('pikaday', pikaday);

    });

  };

})(jQuery);
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
(function($) {

  $.fn.counter = function() {

    return this.each(function() {

      var counter = $(this);

      if(counter.data('counter')) {
        return counter;
      }

      var field  = counter.parent('.field').find('.input');
      var length = $.trim(field.val()).length;
      var max    = field.data('max');
      var min    = field.data('min');
      
      field.keyup(function() {
        length = $.trim(field.val()).length;
        counter.text(length + (max ? '/' + max : ''));
        if((max && length > max) || (min && length < min)) {
          counter.addClass('outside-range');
        } else {
          counter.removeClass('outside-range');
        }
      }).trigger('keyup');

      counter.data('counter', true);

    });

  };

}(jQuery));
(function($) {

  $.fn.editor = function() {

    return this.each(function() {

      if($(this).data('editor')) {
        return $(this);
      }

      var textarea = $(this);
      var buttons  = textarea.parent().find('.field-buttons');

      // start autosizing
      textarea.autosize();

      buttons.find('.btn').on('click.editorButton', function(e) {

        textarea.focus();
        var button = $(this);

        if(button.data('action')) {
          app.modal.open(button.data('action'), window.location.href);
        } else {

          var sel  = textarea.getSelection();
          var tpl  = button.data('tpl');
          var text = button.data('text');

          if(sel.length > 0) text = sel;

          var tag = tpl.replace('{text}', text);

          textarea.insertAtCursor(tag);
          textarea.trigger('autosize.resize');

        }

        return false;

      });

      buttons.find('[data-editor-shortcut]').each(function(i, el) {
        var key    = $(this).data('editor-shortcut');
        var action = function(e) {
          $(el).trigger('click');
          return false;
        };

        textarea.bind('keydown', key, action);

        if(key.match(/meta\+/)) {
          textarea.bind('keydown', key.replace('meta+', 'ctrl+'), action);
        }

      });

      textarea.data('editor', true);

    });

  };

})(jQuery);
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