(function($) {

  $.fn.filedrop = function(options) {

    if(options == 'destroy') {
      return this.drop('destroy');
    }

    if(!$.support.fileReader) return false;

    var defaults = {
      drop  : function(event) {},
      files : function(files) {},
    };

    var options = $.extend({}, defaults, options);
    var drop    = options.drop;

    options.drop = function(event) {
      drop.apply(this, [event]);

      if(event.originalEvent.dataTransfer && event.originalEvent.dataTransfer.files) {
        $.fileReader(event.originalEvent.dataTransfer.files, options.files);        
      }

    };

    this.drop(options);

  };

})(jQuery);