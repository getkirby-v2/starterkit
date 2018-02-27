var Form = function(form, params) {

  var form = $(form);

  // remove all event handlers from the form
  form.off();

  var defaults = {
    focus    : false,
    returnTo : false,
    url      : form.attr('action'),
    redirect : function(response) {},
    submit   : function(form) {}
  };

  var options = $.extend({}, defaults, params);

  form.find('[data-focus=true]').fakefocus('input-is-focused');

  // setup all field plugins
  form.find('[data-field]').each(function() {
    var el  = $(this);
    var key = el.data('field');
    if(el[key]) el[key]();
  });

  // keep changes on updates to avoid data loss
  if(form.data('keep')) {

    form.on('keep', function() {
      $.post(form.data('keep'), form.serializeObject())
    });

    form.on('change.keep', ':input', function() {
      form.trigger('keep');
    });

  }

  // focus the right field
  if(options.focus) {
    form.find('[autofocus]').focus();
  }

  // don't setup a form submission action
  if(form.data('autosubmit') == 'native') {
    return true;
  }

  // special treatment for addit buttons
  form.find('.btn-addit').on('click', function() {
    // change the form action
    form.attr('action', $(this).data('action'));
  });

  // hook up the form submission
  form.on('submit', function(e) {

    if (form.hasClass('loading')) {
      return false;
    }

    form.addClass('loading');

    // auto submission can be switched off via a data attribute
    // to setup your own submission action
    if(form.data('autosubmit') == false) {
      e.preventDefault();
      return;
    }

    // submission event
    options.submit(form);

    // on submit all errors should be removed. Looks weird otherwise
    form.find('.field-with-error').removeClass('field-with-error');

    // show the loading indicator
    if(app) app.isLoading(true);

    // handle the post request for the form and serialize all the data
    $.post(form.attr('action'), form.serializeObject(), function(response, message, xhr) {

      // hide the loading indicator
      if(app) app.isLoading(false);

      form.removeClass('loading');

      // handle redirection and replacement of data
      options.redirect(response);

    }).error(function(response, message) {

      // hide the loading indicator
      if(app) app.isLoading(false);

      form.removeClass('loading');

      if(response.responseJSON && response.responseJSON.message) {
        alert(response.responseJSON.message);
      } else {
        alert('An unexpected error occurred');
      }

    });

    return false;

  });

};
