(function($) {

  $.fn.fakefocus = function(classname) {
    return this.each(function(){
      
      $(this).on({
        'click' : function() {
          $(this).find('input, textarea, select').focus();
        },
        'focusin' : function() {
          $(this).addClass(classname);
        },
        'focusout' : function() {
          $(this).removeClass(classname);
        }
      });

    });    

  };

})(jQuery);