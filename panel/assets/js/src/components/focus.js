var Focus = function() {

  var elements = null;
  var caret    = 0;
  var id       = false;

  var store = function(form) {

    var element = form.find('input:focus, select:focus, textarea:focus');

    if(element.length > 0) {
      caret = element.caret().end;        
      id    = element.attr('id');
    } 

  };

  var recall = function() {

    if(id) {
      element = $('#' + id);
      element.caret(caret);
      element.focus();
    } 

  };

  var forget = function() {
    element = null;
    id      = null;
    caret   = 0;
  };

  var on = function(form) {

    form     = $(form);
    elements = form.find('input, select, textarea');
    elements.on('keyup.caretchange, click.caretchange', function() {
      store(form);
    });

    recall();

  };

  var off = function() {
    if(elements) elements.off('keyup.caretchange, click.caretchange');
  };

  return {
    store: store,
    forget: forget,
    recall: recall,
    on: on,
    off: off
  };

};