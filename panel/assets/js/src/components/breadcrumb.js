(function($) {

  $.fn.breadcrumb = function() {

    return this.each(function() {

      var el = $(this);
      var dropdown = el.clone();

      dropdown.removeClass('breadcrumb');
      dropdown.addClass('dropdown')
              .addClass('dropdown-left')
              .addClass('breadcrumb-dropdown');

      dropdown.attr('id', 'breadcrumb-menu');
      dropdown.find('.nav-icon').remove();
      dropdown.find('.breadcrumb-list').removeClass('nav-bar').removeClass('breadcrumb-list').addClass('dropdown-list');
      dropdown.find('.breadcrumb-link').removeClass('breadcrumb-link');
      dropdown.find('.breadcrumb-label').removeClass('breadcrumb-label');

      el.append(dropdown);

    });

  };

})(jQuery);