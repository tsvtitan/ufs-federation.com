// jsonSuccess

module.exports = function jsonError (data) {
  
  var req = this.req;
  var res = this.res;
  
  var obj = {
    error: false
  }
  
  if (typeof(data)==='object') {
    obj = Utils.extend(obj,data);
  }
  
  if (req.options.jsonp && !req.isSocket) {
    
    return res.jsonp(obj);
    
  } else return res.json(obj);
}
