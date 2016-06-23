// AcsController

module.exports = {
	
  index: function(req,res) {
    
    res.jsonSuccess();
  },
  
  register: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Register error');
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

        function register(user,result) {
          
          var staffId = req.param('id');
          var dtIn = req.param('in');
          var dtOut = req.param('out');
          
          if (user && staffId && (dtIn || dtOut)) {
            
            async.waterfall([
            
              function checkIn(ret) {
                
                if (dtIn) {
                  AcsService.checkIn(staffId,moment(dtIn,'YYYY-MM-DDTHH:mm:ss'),function(err){
                    ret(err);
                  });
                } else ret();
              },
              
              function checkOut(ret) {
                
                if (dtOut) {
                  AcsService.checkOut(staffId,moment(dtOut,'YYYY-MM-DDTHH:mm:ss'),function(err){
                    ret(err);
                  });
                } else ret();
              }
            
            ],function(err){
              result(err);
            });  

          } else result('Staff/In/Out is not defined');
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