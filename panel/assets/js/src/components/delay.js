var Delay = function() {

  var delays = {};

  var start = function(id, callback, time) {
    stop(id);
    delays[id] = setTimeout(callback, time);
  };

  var stop = function(id) {
    if(!id) {
      for(var id in delays) {
        if(delays.hasOwnProperty(id)) stop(id);
      }
    } else if(delays[id]) {
      clearTimeout(delays[id]);      
    }
  };

  return {
    start: start,
    stop: stop
  };

};