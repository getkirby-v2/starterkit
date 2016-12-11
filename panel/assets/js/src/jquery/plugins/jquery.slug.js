(function($) {

  $.slug = function(text) {

    text = $.trim(text.toLowerCase());

    $.each($.slug.table || {}, function(key, val) {
      text = text.split(key).join(val);
    });

    return text
      .replace(/[^\x09\x0A\x0D\x20-\x7E]/, '')
      .replace(/[^a-z0-9]/gi, '-')
      .replace(/(-)\1+/g, '-')
      .replace(/-+$/,'')
      .replace(/^-+/,'');  

  };

})(jQuery);