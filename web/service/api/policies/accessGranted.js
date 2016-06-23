// accessGranted

module.exports = function(req, res, next) {

  var restricted = res.dic('You are not permitted to perform this action');
  
  if (req.body && req.body.auth) {
    
    Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,
                             function(err1,user){
      
      if (err1) res.serverError(err1);
      else if (user) {
        
        Users.isGranted(user,req.url,req,function(err2,granted){
          
          if (err2) res.serverError(err2);
          else if (granted) {
            
            next();
            
          } else res.forbidden(restricted);
        });
        
      } else res.forbidden(restricted);
    });
    
  } else if (req.session && req.session.userId) {
    
    Users.isGranted(req.session.userId,req.url,req,function(err,granted) {
      
      if (err) res.serverError(err);
      else if (granted) {
        
        next();
        
      } else res.forbidden(restricted);
    });
    
  } else res.forbidden(restricted);
};


