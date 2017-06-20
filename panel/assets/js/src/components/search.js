var Search = function() {

  $(document).on('click', function() {
    $('#search').hide();
  });

  $(document).on('click', '[href="#search"]', function(e) {
    e.stopPropagation();

    var $search = $('#search');

    if($search.is(':visible')) {
      $search.hide();
    } else {
      $search.show();
      $search.find('.search-input').focus();
    }

    return false;

  });

  $(document).on('click', '#search', function(e) {
    e.stopPropagation();
  });

  var navigate = function(key) {

    var list   = $('#search .search-results li');
    var active = list.filter('.active');
    var index  = list.index(active);

    switch(key) {
      case 13:
        active.find('a').trigger('click');
        return true;
      case 38:
        var index = index - 1;
        break;
      case 40:
        var index = index + 1;
        break;
    }
      
    if(index < 0) {
      index = -1;
    }
    
    if(index >= list.length) {
      index = 0;
    }

    active.removeClass('active');
    list.eq(index).addClass('active');

  };

  $(document).on('keyup', '.search-input', function(e) {

    switch(e.keyCode) {
      case 13: // enter
      case 38: // up
      case 40: // down
        navigate(e.keyCode);
        e.preventDefault();
        break;
      default:
        var q   = $(this).val();
        var api = $('#search form').attr('action');
        $('.search-results').load(api, {q: q});
        break;
    }

  });

  $(document).on('submit', '#search form', function() {
    return false;
  });

};