(function($) {

  $.support.fileReader = !!(window.File && window.FileList && window.FileReader);

  $.fileReader = function(files, callback) {

    if(!$.support.fileReader) return false;

    var complete = [];
    var onload   = function(file) {
      return function() {
        complete.push(file);
        if(complete.length === files.length) callback(complete);
      };
    };
    for(var i = 0; i < files.length; i++) {
      var reader = new FileReader();
      reader.onload = onload(files[i]);
      reader.readAsArrayBuffer(files[i]);
    }

  };

})(jQuery);