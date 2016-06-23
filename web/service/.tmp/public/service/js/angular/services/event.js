
app.service('Event',['Dictionary','Log',
                     function(Dictionary,Log) {
  
  
  var events = {};
  
  function getError(d,raw) {
    
    var error = false;
    if (raw.statusCode) {
      error = (raw.statusCode===200)?false:d;
    }
    return error;
  }
  
  function post(path,data,onResult) {
    return io.socket.post(path,data,onResult);
  }
  
  function on(event,onResult) {
    return io.socket.on(event,onResult);
  }
  
  function subscribe(event,path,data,onResult,onEvent) {
    
    Log.debug('Event %s is registering...',[event]);
    
    return post(path,data,function(data,raw){
      
      var error = getError(data,raw);
      if (!error) {
        
        if (!events[event]) {
          if (onEvent) {
            on(event,function(d){
              //Log.write(d);
              onEvent(d);
            });
            events[event] = {subscribe:{path:path,data:data}};
            Log.debug('Event %s is registered',[event]);
          }
        }
        if (onResult) onResult({error:false});
        
      } else {
        
        Log.error(error);
        if (onResult) onResult({error:Dictionary.couldNotSubscribeOnEvent()});
      } 
    });
  }
  this.subscribe = subscribe;
  
  function unsubscribe(event,path,data,onResult) {
    
    Log.debug('Event %s is unregistering...',[event]);
    return post(path,data,function(data,raw){
      
      var error = getError(data,raw);
      if (error) Log.error(error);
      else {
        delete events[event];
        Log.debug('Event %s is unregistered',[event]);
      }
              
      onResult({error:(error)?Dictionary.couldNotUnsubscribeFromEvent():false});
    });
  }
  this.unsubscribe = unsubscribe;
  
  on('connect',function(){
    
    for (var k in events) {
      var e = events[k];
      if (e.subscribe) {
        var s = e.subscribe;
        subscribe(k,s.path,s.data);
      }
    }
  });
  
    
}]);
