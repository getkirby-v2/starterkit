(function($) {

  $.support.dropload = $.support.upload && $.support.fileReader;

  $.fn.dropload = function(options) {

    if(!$.support.dropload) return false;

    var defaults = {
      url      : '/',
      start    : function() {},
      complete : function() {},
      progress : function() {}
    };

    var options = $.extend({}, defaults, options);
    var upload  = function(url, files) {

      var totalCount   = files.length;
      var totalPerc    = totalCount * 100;
      var currentCount = 0;
      var currentPerc  = 0;

      options.start();

      $.upload(files, {
        url: url,
        error: function() {
          currentCount++;
        },
        success: function() {
          currentCount++;
        },
        progress: function(file, percentage) {
          currentPerc = (currentCount * 100) + percentage;
          options.progress(currentPerc / totalPerc);
        },
        complete: function() {
          options.complete(files);
        },            
      })

    };

    return this.each(function() {

      var element      = $(this);
      var form         = element.is('form') ? element : element.closest('form');
      var url          = form.attr('action');
      var input        = element.find('[type=file]');
      var totalCount   = 0;
      var totalPerc    = 0;
      var currentCount = 0;
      var currentPerc  = 0;

      input.on('change', function() {
        upload(url, this.files);
        $(this).val('');
      });

      element.filedrop({
        dragover : function() {
          element.addClass('over');
        },
        dragexit : function() {
          element.removeClass('over');
        },   
        drop: function() {
          element.removeClass('over');
        },     
        files : function(files) {
          upload(url, files);
        }
      });

    });

  };

})(jQuery);