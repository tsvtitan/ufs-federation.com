

var includeAll	= require('include-all'),
    path = require('path'),    
    NRP = require('node-redis-pubsub');    

function Events(name) {

  this.name = name;
  this.files = {};
  this.nrp = new NRP(sails.config.events);
}

Events.prototype = {

  setFile: function(name,file) {
    this.files[name] = file;
  },

  on: function(event,result) {
    this.nrp.on(event,function(data){
      this.log = (this.log)?this.log:Log.extend({},null,event).log;
      result(data);
    });
    this.log.debug('%s is on',[event]);
  },

  off: function(event,result) {
    this.nrp.off(event,result);
    this.log.debug('%s is off',[event]);
  },

  emit: function(event,data) {
    this.nrp.emit(event,data || {});
  },

  quit: function() {
    this.nrp.quit();
  },

  end: function() {
    this.nrp.end();
  }
}

function loadEvents(dir) {

  dir = path.resolve(sails.config.appPath,dir);
  return includeAll({
    dirname: dir,
    filter: /(.+)\.js$/,
    excludeDirs : /^\.(git|svn)$/,
    optional: true,
    keepDirectoryPath: true,
    flattenDirectories: true
  }) || {};
}

function setOptions(file) {

  if (sails.config.events) {

    var opts = Utils.path2obj(sails.config.events,file.name,'/');
    file.disabled = (opts && Utils.isDefined(opts.disabled))?opts.disabled:file.disabled;
  }

  if (Config.events) {

    var opts = Utils.path2obj(Config.events,file.name,'/');
    file.disabled = (Utils.isDefined(opts))?!opts:file.disabled;
  }
}

function registerEvents(events,dir,prefix) {

  var log = events.log;
  var files = loadEvents(dir);
  if (files) {

    for (var i in files) {

      var file = files[i];

      if (file) {

        file.name = (file.name)?file.name:i.toString();
        file.name = (prefix)?prefix+file.name:file.name;

        setOptions(file);

        if (!file.disabled) {

          events.setFile(file.name,file);

          log.debug('{name} is loading...',file);

          for (var j in file) {
            var fn = file[j];
            if (Utils.isFunction(fn)) {
              events.on(Utils.format('%s/%s.%s',[events.name.toLowerCase(),file.name,j]),fn);
            }
          }
        }
      }
    }
  }
}

module.exports = function() {

  var events = new Events('Events');
  events = Log.extend(events,null,events.name);
  
  var need = Config.events || (!Config.events && !sails.config.events.disabled);
  if (need) registerEvents(events,sails.config.events.directory);
  
  return events;
}