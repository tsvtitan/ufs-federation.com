
module.exports = {
  
  disabled: true,
  interval: '10 seconds',
  autoStart: true,
  data: null,
  event: true,
  
  execute: function(job,done) {
    
    var log = this.log;
    try {
      
      async.waterfall([

        function findMailings(result){
          
          var where = {
            allCount:{'>':0},
            test:false,
            messageId:{'!':null},
            end:{'>=':moment().add({minutes:-5}).toDate()},
            sent: [null,undefined,false]
          }
          
          Mailings.find({where:where},
                        {fields:Mailings.fieldsForShow},
                        function(err,mailings){
            result(err,mailings);
          });
        },

        function getStatuses(mailings,result){

          if (mailings && mailings.length>0) {

            mailings = Utils.reject(mailings,function(m) {
              return m.allCount===m.sentCount;
            });

            MessageGate.getStatusMailings(mailings,function(err,statuses){
              
              if (err) result(err,null);
              else if (statuses && statuses.length>0) {

                for (var i in mailings) {
                  var m = mailings[i];
                  Utils.filter(statuses,function(s){
                    return s.messageId === m.messageId;
                  }).forEach(function(s){
                    s.mailing = m;
                  });
                }
                result(null,statuses);

              } else result(null,null);
            });
          } else result(null,null);
        },

        function updateMailings(statuses,result){
          
          log.debug('Found %d statuses',[(statuses)?statuses.length:0]);

          if (statuses) {

            async.map(statuses,function onUpdateMailing(status,next){

              if (status && status.mailing) {

                var m = {
                  allCount: status.allCount,
                  sentCount: status.sentCount,
                  sent: (status.allCount===status.sentCount)?new Date():undefined
                }
                status.mailing = Utils.extend(status.mailing,m);
                
                Mailings.update({id:status.mailing.id},m,function(err,updated){
                  
                  next(err,(updated)?status.mailing:null);
                });

              } else next(null,null);

            },function(err,results) {

              if (!err && results && results.length>0) {

                for (var i in results) {
                  Mailings.eventUpdate(results[i]);
                }
                result(null,false);

              } else result(err,true);
            });

          } else result(null,true);
        }

      ],function(err,stop) {
        if (err) log.error(err);
        else if (stop) {
          job.stop();
        }
        done();
      });
      
    } catch(e) {
      log.exception(e);
      done();
    }  
  }
}