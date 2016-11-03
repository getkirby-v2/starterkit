(function($) {

  $.fn.caret = function(begin, end) {
    if(this.length == 0) return;
    if(typeof begin == 'number') {
      end = (typeof end == 'number') ? end : begin;
      return this.each(function() {
        if(this.setSelectionRange) {
          this.setSelectionRange(begin, end);
        } else if(this.createTextRange) {
          var range = this.createTextRange();
          range.collapse(true);
          range.moveEnd('character', end);
          range.moveStart('character', begin);
          try { range.select(); } catch (ex) { }
        }
      });
    } else {

      try {
        if(this[0].setSelectionRange) {
          begin = this[0].selectionStart;
          end = this[0].selectionEnd;
        } else if (document.selection && document.selection.createRange) {
          var range = document.selection.createRange();
          begin = 0 - range.duplicate().moveStart('character', -100000);
          end = begin + range.text.length;
        }
      } catch(e) {
        begin = 0;
        end = 0;
      }

      return { 
        begin: begin, end: end 
      };

    }
  };

  $.fn.selectRange = function(start, end) {
    if(!end) end = start;
    return this.each(function() {
      if(start == -1) {
        start = end = $(this).val().length;
      }
      if(this.setSelectionRange) {
        this.focus();
        this.setSelectionRange(start, end);
      } else if (this.createTextRange) {
        var range = this.createTextRange();
        range.collapse(true);
        range.moveEnd('character', end);
        range.moveStart('character', start);
        range.select();
      }
    });
  };

  $.fn.getSelection = function() {

    var textarea = this[0];

    // IE version
    if(document.selection != undefined) {
      textarea.focus();
      var range     = document.selection.createRange();
      var selection = range.text;

    // Mozilla version
    } else if(textarea.selectionStart != undefined) {
      var start     = textarea.selectionStart;
      var end       = textarea.selectionEnd;
      var selection = textarea.value.substring(start, end);
    }

    return selection;

  };

  $.fn.insertAtCursor = function (myValue) {
    return this.each(function(){

      // IE support
      if(document.selection) {

        this.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
        this.focus();

      // Moz / Netscape support
      } else if (this.selectionStart || this.selectionStart == '0') {

        var startPos  = this.selectionStart;
        var endPos    = this.selectionEnd;
        var scrollTop = this.scrollTop;
        this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
        this.focus();
        this.selectionStart = startPos + myValue.length;
        this.selectionEnd = startPos + myValue.length;
        this.scrollTop = scrollTop;

      } else {

        this.value += myValue;
        this.focus();

      }
    });
  };

})(jQuery);  