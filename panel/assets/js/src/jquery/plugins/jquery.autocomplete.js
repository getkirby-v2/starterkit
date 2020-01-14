(function($) {

  var Autocomplete = function(element, dropdown) {

    var self = this;

    this.element        = $(element);
    this.customDropdown = dropdown ? true : false;
    this.dropdown       = dropdown ? $(dropdown) : $('<div class="autocomplete"></div>').hide();
    this.entered        = false;
    this.lastSearch     = false;
    this.cache          = false;
    this.limit          = this.element.data('limit') || 5;
    this.url            = this.element.data('url');
    this.sticky         = this.element.data('sticky');
    this.ignored        = [];

    // keyboard shortcut helper
    this.keys = function(element, keys) {

      element.on('keydown', function(e) {
        if(keys[e.keyCode]) {
          return keys[e.keyCode]();
        }
      });

    };

    this.highlight = function(string, query) {
      var rgxp = new RegExp('^' + query, 'gi');
      return string.replace(rgxp, function(matched) {
        return '<strong>' + matched + '</strong>';
      });
    };

    this.data = function(callback) {

      if($.isArray(self.url)) {
        return callback(self.url);
      }

      if(!self.cache) {
        $.post(self.url, function(response) {          
          if($.type(response) == 'object' && response.data) {
            self.cache = response.data;
            callback(response.data);            
          }
        });
      }

      return callback(self.cache);

    };

    this.add = function(value) {
      self.element.val(value).focus();
      self.lastSearch = value;
      self.close();
      self.focus();
      self.element.trigger('autocomplete:add');
    };

    this.close = function(focus) {
      if(!focus) focus = true;
      self.dropdown.empty().hide();
      self.element.trigger('autocomplete:close');
    };

    this.focus = function() {
      self.element.trigger('focus');
    };

    this.ignore = function(ignore) {
      var ignore = ignore.toLowerCase();
      if($.inArray(ignore, self.ignored) == -1) self.ignored.push(ignore);
    };

    this.unignore = function(unignore) {
      var unignore = unignore.toLowerCase();
      self.ignored = $.grep(self.ignored, function(value) {
        return value != unignore;
      });
    };

    this.search = function(query) {

      if(query == self.lastSearch) return true;

      self.dropdown.empty();
      self.dropdown.hide();
      self.lastSearch = query;

      if(query.length == 0) {
        self.element.trigger('autocomplete:empty');
        return false;
      }

      self.element.trigger('autocomplete:search');

      self.data(function(data) {

        var results = $.grep(data, function(word) {
          var w = word.toLowerCase();
          var q = query.toLowerCase();
          return w.indexOf(q) == 0 && $.inArray(w, self.ignored) == -1;
        });

        results = results.slice(0, self.limit);

        if(results.length == 0) {
          self.dropdown.hide()
          self.element.trigger('autocomplete:noresults');
          return false;
        }

        $.each(results, function(i, item) {

          var btn = $('<button type="button">' + self.highlight(item, query) + '</button>');

          btn.on('click', function() {
            self.add(item);
            return false;
          });

          self.keys(btn, {
            // enter
            13 : function() {
              self.add(item);
              return false;
            },
            // esc
            27: function() {
              self.close();
              self.focus();
              return false;
            },
            // up
            38 : function() {
              self.go(i-1);
              return false;
            },
            // down
            40 : function() {
              self.go(i+1);
              return false;
            },
            // backspace
            8 : function() {
              self.close();
              self.focus();
              return false;
            }
          });

          self.dropdown.append(btn);

        });

        if(self.sticky) {
          var position = self.element.position();
          position.top = position.top + self.element.outerHeight();
          self.dropdown.css(position);
        }

        self.dropdown.show();
        self.elements = self.dropdown.find('button');
        self.element.trigger('autocomplete:search');

      });

    };

    this.go = function(i) {
      if(i == -1 || !self.elements) return false;
      self.elements.eq(i).focus();
    };

    this.init = function() {

      $(document).on('click', function() {
        self.close();
      });

      self.dropdown.on('click', function(e) {
        e.stopPropagation();
      });

      self.element.attr('autocomplete', 'off');
      if(!self.customDropdown) self.element.after(self.dropdown);

      // don't search immediately for the value in the input
      self.lastSearch = self.element.val();

      self.element.on('keyup', function(e) {
        if(e.keyCode != 13) self.search(self.element.val());
      });

      self.keys(self.element, {
        // down
        40 : function() {
          self.go(0);
          return false;
        },
        // esc
        27 : function() {
          self.close();
          return false;
        }
      });

      return {
        ignore   : self.ignore,
        unignore : self.unignore,
        search   : self.search,
        close    : self.close
      };

    };

    return this.init();

  };

  $.fn.autocomplete = function(dropdown) {

    return this.each(function() {

      if($(this).data('autocomplete')) {
        return $(this).data('autocomplete');
      } else {
        var tags = new Autocomplete(this, dropdown);
        $(this).data('autocomplete', tags);
        return tags;
      }

    });

  };

})(jQuery);