(function($) {

  var xhr            = new XMLHttpRequest();
  $.support.xhr2     = !!(xhr && ('upload' in xhr) && ('onprogress' in xhr.upload));
  $.support.formData = window.FormData !== undefined;
  $.support.upload   = $.support.xhr2 && $.support.formData;

  $.upload = function(files, options) {

    if(!$.support.upload) return false;

    var defaults = {
      url      : '/', 
      field    : 'file',
      formData : false,
      method   : 'POST',
      accept   : 'text',
      xhr      : false,
      complete : function() {},
      error    : function() {},
      success  : function() {}, 
      progress : function() {}
    };

    var options = $.extend({}, defaults, options);

    var uploadFile = function(file, last) {
      var formData = new FormData();
      formData.append(options.field, file);
      if(options.formData) options.formData(formData, file);

      var xhr = new XMLHttpRequest();
      xhr.open(options.method || "POST", options.url || "/", true);
      xhr.upload.onprogress = progress(file);
      xhr.setRequestHeader('Accept', options.accept);
      xhr.setRequestHeader('Cache-Control', 'no-cache');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.setRequestHeader('X-File-Name', encodeURIComponent(file.name));

      if(options.xhr) options.xhr(xhr);

      xhr.onload = function() {
        options.success(xhr, file);
        options.progress(file, 100);
        if(last) options.complete();
      };
      xhr.onerror = function() {
        options.error(xhr, file);
        if(last) options.complete();
      };
      xhr.send(formData);
    };

    var uploadFiles = function(files) {
      for(i = 0; i < files.length; i++) {        
        uploadFile(files[i], i === files.length-1);
      }
    };

    var progress = function(file) {
      return function(event) {
        if(!event.lengthComputable || !options.progress) return;
        var percent = Math.max(0, Math.min(100, (event.loaded / event.total) * 100));
        options.progress(file, Math.ceil(percent));
      };
    };

    uploadFiles(files);

    return true;

  };

})(jQuery);