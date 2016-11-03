(function($) {

  $.token = function(length) {

    var length = length || 28;
    var set    = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#$%&*?';
    var token  = '';

    for(x=0; x<length; x++) {
      token += set[Math.floor(set.length * Math.random())];
    }

    return token;

  };

})(jQuery);