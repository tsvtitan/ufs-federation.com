
app.factory('Dictionary',['Utils','Const',
                          function(Utils,Const){
  
  var dictionary = {};
  
  function getV(e,name,values) {
    if (e) {
      if (e.hasAttribute('data-ng-controller')) {
        var v = dictionary[e.attributes['data-ng-controller'].value];
        if (v) {
          v = v[name];
          if (v) {
            return Utils.format(v,values);
          } else return getV(e.parentElement,name,values);
        } else return getV(e.parentElement,name,values);
      } else return getV(e.parentElement,name,values);
    } else {
      var v = (dictionary[name])?dictionary[name]:name;
      return Utils.format(v,values);
    }
  }
  
  return {
    init: function(d) {
      dictionary = d; 
    },
    dic: function(element) {
      return function(name,values) {
        return getV(element[0],name,values);
      }
    },
    get: function(s,values) {
      return getV(null,s,values);
    },
    format: function(s,values) {
      return Utils.format(s,values);
    },
    connectionFailed: function(d) {
      return (d)?d:this.get(Const.connectionFailed);
    },
    couldNotSubscribeOnEvent: function() {
      return this.get(Const.couldNotSubscribeOnEvent);
    },
    couldNotUnsubscribeFromEvent: function() {
      return this.get(Const.couldNotUnsubscribeFromEvent);
    }
  }
}]);
