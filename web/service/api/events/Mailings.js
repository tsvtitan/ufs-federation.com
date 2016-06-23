
module.exports = {
  
  disabled: true,
  
  create: function(mailing) {
     
    var log = this.log;
    
    if (mailing && mailing.id) {
      
      Mailings.find({where:{id:mailing.id}},
                    {fields:Mailings.fieldsForShow},
                    function(err,mailings){
        
        if (err) log.error(err);
        else {
          
          for (var i in mailings) {
            Mailings.publishCreate(Mailings.prepareForShow(mailings[i]));
          }
        }
      });
    } 
  },

  update: function(mailing) {
    
    var log = this.log;
    
    if (mailing && mailing.id) {
      
      Mailings.find({where:{id:mailing.id}},
                    {fields:Mailings.fieldsForShow},
                    function(err,mailings){
        
        if (err) log.error(err);
        else {
          
          for (var i in mailings) {
            
            var r = Mailings.prepareForShow(mailings[i]);
            if (r) {
              var m = {
                allCount: r.allCount,
                sentCount: r.sentCount,
                active: r.active,
                time: r.time,
                suspended: r.suspended,
                canceled: r.canceled
              }
              Mailings.publishUpdate(r.id,m);
            }
          }
        }
      });
    } 
  }
  
}