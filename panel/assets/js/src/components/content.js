var Content = function() {

  var root = $('.main');

  var element = function(selector) {
    return root.find(selector);
  };

  var focus = Focus();

  var scroll = {

    mainbar: 0,
    sidebar: 0,
    
    save : function() {      
      scroll.mainbar = $('.mainbar').scrollTop();
      scroll.sidebar = $('.sidebar').scrollTop();
    },

    restore: function() {
      if($('.mainbar')[0]) $('.mainbar')[0].scrollTop = scroll.mainbar;
      if($('.sidebar')[0]) $('.sidebar')[0].scrollTop = scroll.sidebar;
    }

  };

  var on = function() {

    // make sure everything is nice and clean first
    off();

    // setup the breadcrumb mobile version
    element('.breadcrumb').breadcrumb();

    // setup the sidebar toggle
    element('.sidebar').sidebar();

    // register all keyboard shortcuts within the main container
    root.shortcuts();

    app.delay.start('message', function() {
      element('.message-is-notice').trigger('click');
    }, 3000);

    element('.message a, .message').on('click', function(e) {
      element('.mainbar .field-with-error').removeClass('field-with-error');
      element('.message').remove();
      app.delay.stop('message');
      return false;
    });

    // hook up the main form
    Form('.main .form', {
      submit: function() {
        scroll.save();
      },
      redirect: function(response) {

        if($.type(response) == 'object' && response.url) {
          open(response.url);                        
        } else {
          replace(response.content);
        }

      }
    });

    // recall the focus and caret position
    focus.on('.mainbar .form');

  };

  // clean all registered events and remove generated elements
  var off = function() {
    
    // stop all delays
    app.delay.stop();

    // stop caret recording
    focus.off();

    // remove all shortcuts
    $(document).unbind('keydown.shortcuts');

    // remove window resizing events
    $(window).off('resize');

  };

  var open = function(url, state) {

    app.load(url, 'content', function(response) {
      // handle redirects
      if(response.url) {
        open(response.url);
      } else {
        replace(response.content, url);
      }
    });

  };

  var replace = function(content, url) {

    // close all context menus
    $(document).trigger('click.contextmenu');

    // close all modals
    app.modal.close();      

    root.html(content);    

    // change the history
    if(url) {
      if(window.location.href != url) {                
        focus.forget();            
        try {
          path = url.replace(window.location.origin, '');
          window.history.pushState({path: path}, document.title, path);                    
        } catch(e) {
          window.location.href = url;
        }
      }
    }

    // switch on all events for the mainbar
    on();

    // restore the previous scroll position
    scroll.restore();          

  };

  var reload = function() {
    scroll.save();
    open(document.location);
  };

  var shortcuts = function() {
    root.shortcuts();
  };

  var form = function() {
    return $('.main .form');
  };

  var setup = function() {

    $(window).on('popstate', function(e) {      
      open(document.location);
    });

    on();

  };

  return {
    root : root,
    element: element,
    on: on,
    off: off,
    open: open,
    replace: replace,
    reload: reload,
    shortcuts: shortcuts,
    form: form,
    focus: focus,
    setup: setup, 
    scroll: scroll
  };

};