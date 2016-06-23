// TestController

var moment = require('moment');

module.exports = {
  
  req: function(req,res) {
    
    res.json({
          route: req.route, 
          param: req.param('name'),
          path: req.path,
          method: req.method,
          url: req.url,
          ip: req.ip,
          options: req.options,
          locals: res.locals,
          //sample1: res.i18n("back.mailings.HH:mm:ss YYYY-MM-DD"),
          //sample2: res.dic('User is not found'),
          //sample3: res.i18n('back.mailings.Mailings are not found'),
          //userAgent: req.userAgent,
          //statues: statuses
        });
            
    
    
    /*var inc = 0;
    
    /*Jobs.start('mailings','1 seconds',function(job,done){
      console.log(++inc);
      if (inc>=10) {
        console.log(job);
        Jobs.stop(job.attrs.name);
      }
      done();
    });*/
    
    /*var end = moment().add({days:-1}).toDate();
            
    console.log(end);        

    //Mailings.find({where:{'attachments.extension':"xlsx"}},
    
    Mailings.find({where:{allCount:{'>':0},test:false,messageId:{'!':null},end:{'<':end}}},
                  {fields:{messageId:1,allCount:1,sentCount:1}},function(err,mailings){
      
      //console.log(mailings);
      
      MessageGate.getStatusMailings(mailings,function(err,statuses){
        
        
        
    
        res.json({
          route: req.route,
          param: req.param(),
          path: req.path,
          method: req.method,
          url: req.url,
          ip: req.ip,
          options: req.options,
          locals: res.locals,
          //sample1: res.i18n("back.mailings.HH:mm:ss YYYY-MM-DD"),
          //sample2: res.dic('User is not found'),
          //sample3: res.i18n('back.mailings.Mailings are not found'),
          //userAgent: req.userAgent,
          statues: statuses
        });

        
      });
    
    });*/
  }
  
}

