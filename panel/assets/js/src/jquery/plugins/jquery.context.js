var Context = function() {

  $(window).on('scroll', function() {
    $('.contextmenu').remove();
  });

  $(document).on('click.contextmenu', function(e) {
    $('.contextmenu').remove();      
  });        

  $(document).on('keyup', function(e) {
    if(e.keyCode == 27) $(this).trigger('click.contextmenu');
  });

  $(document).on('click', '.contextmenu', function(e) {
    e.stopPropagation();
  });

  $(document).on('click.contextmenu', '[data-context]', function(e) {

    $('.contextmenu').remove();      

    var link = $(this);
    var url  = link.data('context');

    $.get(url, function(response) {

      var menu = $(response);      

      $('body').append(menu.show().css('position', 'fixed'));

      var top     = e.clientY;
      var left    = e.clientX;
      var width   = menu.innerWidth();
      var height  = menu.innerHeight();
      var wwidth  = $(window).width();
      var wheight = $(window).height();

      if((left + width) > wwidth) {
        left = wwidth - width;
      } 

      if((top + height) > wheight) {
        top = wheight - height;
      } 

      menu.css({
        top: top,
        left: left
      });

    });

    return false;

  });

};
