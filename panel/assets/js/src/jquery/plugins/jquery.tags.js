(function($) {

  // returns the current cursor position in an input field
  $.cursor = function(element) {
    var e = $(element);
    var i = e[0];
    var v = e.val();
    if(!i.createTextRange) return i.selectionEnd;
    var r = document.selection.createRange().duplicate();
    r.moveEnd('character', v.length);
    if(r.text == '') return v.length;
    return v.lastIndexOf(r.text);
  };

  // The tags plugin converts a simple input element into
  // an apple-style tag input box
  var Tags = function(element) {

    var self = this;

    // basic elements and stuff
    this.source       = $(element);
    this.element      = $('<div />');
    this.input        = $('<input class="tag-input" type="text" autocomplete="off" />');
    this.tags         = [];
    this.elements     = {};
    this.cursor       = 0;
    this.disabled     = false;
    this.autocomplete = this.source.data('url');
    this.lowercase    = this.source.data('lowercase');
    this.separator    = this.source.data('separator');

    if(this.lowercase) {
      this.input.css('text-transform', 'lowercase');
    }

    if(!this.separator) this.separator = ',';

    // keyboard shortcut helper
    this.keys = function(element, keys) {
      element.on('keydown', function(e) {
        if(keys[e.keyCode]) {
          return keys[e.keyCode](e);
        }
      });
    };

    // move to the next tag or input
    this.next = function(tag) {
      return self.get(tag).next();
    };

    // move to the previous tag
    this.prev = function(tag) {
      return self.get(tag).prev();
    };

    this.first = function() {
      return self.element.find('.tag').first();
    };

    this.last = function() {
      return self.element.find('.tag').last();
    };

    this.goto = function(element, tag) {
      self[element](tag).trigger('focus');
    };

    // clean a given tag
    this.clean = function(tag) {
      var tag = $.trim(tag);
      if(self.lowercase) tag = tag.toLowerCase();
      return tag;
    };

    // get an element by tag
    this.get = function(tag) {
      return self.elements.filter(function(i, element) {
        return $(this).data('tag') == tag.toLowerCase();
      });
    };

    // select a given tag
    this.select = function(tag) {
      return self.get(tag).trigger('focus');
    };

    // deselect a given tag
    this.deselect = function(tag) {
      return self.get(tag).trigger('blur');
    };

    // store the tags array in the source field
    this.store = function() {

      self.source.val(self.tags.join(self.separator)).trigger('change');
    };

    // create a new tag element
    this.add = function(tag) {

      // if there's no given tag, use the input value
      if(!tag) tag = self.input.val();

      // clean the given tag
      var tag = self.clean(tag);
      var reg = new RegExp(self.separator);

      // split those tags
      if(tag.match(reg)) {
        $.each(tag.split(self.separator), function(i, t) {
          self.add(t);
        });
        return;
      }

      // handle multiple arrays
      if($.type(tag) == 'array') {
        $.each(tag, function(i, t) {
          self.add(t);
        });
        return;
      }

      // react on empty or duplicate tags
      if(!tag || tag.length == 0 || $.inArray(tag, self.tags) != -1) {
        return false;
      }

      // register the new tag
      self.tags.push(tag);

      var t = $('<span data-tag="' + tag.toLowerCase() + '" tabindex="-1" class="tag"></span>');
      var x = $('<i class="tag-x">&times;</i>');
      var b = $('<button class="tag-label" type="button"></button>').text(tag);

      t.append(b).append(x);

      // if you focus the tag, go further down and focus the button
      t.on('focus', function() {
        b.focus();
      });

      t.on('click', function(e) {
        e.stopPropagation();
      });

      // the x marks the spot to remove tags
      x.on('click', function(e) {
        self.remove(tag);
      });

      // assign keyboard shortcuts to tags
      self.keys(b, {
        // backspace
        8 : function() {
          self.remove(tag);
          return false;
        },
        // esc
        27 : function() {
          self.focus();
        },
        // left
        37 : function() {
          self.goto('prev', tag);
          return false;
        },
        // right
        39 : function() {
          self.goto('next', tag);
          return false;
        }
      });

      // append the tag to the list
      self.input.before(t);

      // register all elements
      self.elements = self.element.find('.tag');

      // clear the input
      self.input.val('');

      // store the tags array in the source field
      self.store();

      // add the tag to the list of ignored entries for autocompletion
      if(self.autocomplete) {
        self.input.data('autocomplete').ignore(tag);
      }

      // trigger a custom event
      self.element.trigger('tags:add');

    };

    // focus on the input
    this.focus = function() {
      self.input.trigger('focus');
    };

    // remove a tag element
    this.remove = function(tag) {

      if(self.disabled) return;

      self.tags = $.grep(self.tags, function(value) {
        return value != tag;
      });

      // fetch the previous tag before this one is gone
      var prev = self.prev(tag).data('tag');

      // remove the tag
      self.get(tag).remove();

      // register all elements
      self.elements = self.element.find('.tag');

      // check if there's a previous tag…
      if(!prev) {
        // if not, use the first one
        var prev = self.first().data('tag');
        // or focus on the input when there are no tags
        (prev) ? self.select(prev) : self.focus();
      } else {
        // select the previous tag element
        self.select(prev);
      }

      // store the tags array in the source field
      self.store();

      // remove the tag from the list of ignored entries for autocompletion
      if(self.autocomplete) {
        self.input.data('autocomplete').unignore(tag);
      }

      // trigger a custom event
      self.element.trigger('tags:remove');

    };

    // plugin setup
    this.init = function() {

      self.element.attr('class', self.source.attr('class'));
      self.input.attr('id', self.source.attr('id'));
      self.source.attr('type', 'hidden').attr('id', '').removeClass();
      self.source.before(self.element);
      self.element.append(self.input);

      // initiate autocompletion
      if(self.autocomplete) {
        self.input.data('url', self.autocomplete);
        self.input.data('limit', 5);
        self.input.data('sticky', true);
        self.input.autocomplete();
        self.input.on('autocomplete:add', function() {
          self.add();
        });
        self.element.on('tags:add', function() {
          self.input.data('autocomplete').close();
        });
      }

      // focus the input element if the user clicks on the box
      self.element.on('click', function(e) {
        self.focus();
      });

      self.element.on('focusin', function() {
        self.element.addClass('input-is-focused');
        self.element.trigger('tags:focus');
      });

      self.element.on('focusout', function() {
        self.element.removeClass('input-is-focused');
        self.element.trigger('tags:blur');

        // add unconfirmed tag on field unfocus
        setTimeout(function() {
          if(self.element.has($(':focus')).length == 0) {
            self.add();
          }
        }, 100);
      });

      // add unconfirmed tag on form submit
      self.element.parents('.form').find('input[type=submit]').on('click', function(e){
        self.add();
      });

      // create an invisible element to measure the size of the input field
      self.measure = $('<div></div>').css({
        'display'     : 'inline',
        'font-size'   : self.input.css('font-size'),
        'font-family' : self.input.css('font-family'),
        'padding'     : self.input.css('padding'),
        'visibility'  : 'hidden',
        'position'    : 'absolute',
        'top'         : -10000,
        'left'        : -10000
      });

      $('body').append(self.measure);

      // input field keyboard shortcuts
      self.keys(self.input, {
        // enter
        13 : function(e) {
          if(self.input.val().length > 0 && !e.metaKey) {
            self.add();
            return false;
          }
        },
        // tab
        9 : function() {
          if(self.input.val().length == 0) {
            return true;
          } else {
            self.add();
            return false;
          }
        },
        // ,
        188 : function() {
          self.add();
          return false;
        },
        // backspace
        8 : function() {
          if(self.cursor == 0) {
            self.goto('last');
            return false;
          }
        },
        // left
        37 : function() {
          if(self.cursor == 0) {
            self.goto('last');
            return false;
          }
        },
      });

      // resize the input field
      self.input.on('keydown, keyup', function() {
        self.measure.text(self.input.val());
        self.input.css('width', self.measure.outerWidth() + 50);
        self.cursor = $.cursor(self.input);
      });

      // fetch the first set of tags
      self.add(self.source.val());

      if(self.source.attr('readonly')) {
        self.disabled = true;
        self.input.attr('readonly', true).attr('tabindex', -1);
        self.element.find('button').attr('tabindex', -1);
      }

      // trigger a custom event
      self.element.trigger('tags:init');

      // return public methods
      return {
        add      : self.add,
        get      : self.get,
        remove   : self.remove,
        focus    : self.focus,
        select   : self.select,
        tags     : function() { return self.tags },
        elements : function() { return self.elements },
        input    : function() { return self.input }
      };

    };

    // start the plugin
    return this.init();

  };

  // jquery helper for the tags plugin
  $.fn.tags = function() {

    return this.each(function() {

      if($(this).data('tags')) {
        return $(this).data('tags');
      } else {
        var tags = new Tags(this);
        $(this).data('tags', tags);
        return tags;
      }

    });

  };

})(jQuery);
