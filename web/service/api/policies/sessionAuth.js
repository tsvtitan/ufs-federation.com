// sessionAuth

module.exports = function(req, res, next) {

  var session = req.session;
  
  if (session && session.userId) {
    
    return next();
    
  } else return res.forbidden(res.dic('You are not permitted to perform this action'));
};