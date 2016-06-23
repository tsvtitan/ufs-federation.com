// JobsController

module.exports = {
	
  index: function(req,res) {
    
    res.jsonSuccess();
  },
  
  start: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Start error');
    }
    
    try {
      
      async.waterfall([

        function tryAuth(result) {

          if (req.body && req.body.auth) {
            
            Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,function(err,user){
              result(err,user);
            });
            
          } else if (req.session && req.session.userId) {
            
            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else result(null,user);
            });
            
          } else result(null,null);
        },

        function startJob(user,result) {
          
          var name = req.param('name');
          
          if (user && name) {
            
            var async = req.param('async');
            if (async) {
              
              if (Jobs.start(name)) result(null);
              else result(Utils.format('Job %s is not found',[name]));
              
            } else {
              
              Jobs.start(name,null,null,null,function(err,job){

                if (err) result(err);
                else if (job) result(null);
                else result(Utils.format('Job %s is not found',[name]));
              });
            }
            
          } else result('Name is not defined');
        }

      ],function(err){

        if (err) error(err);
        else {
          res.jsonSuccess();
        }
      });
      
    } catch(e) {
      error(e.message);
    }
    
  }
}