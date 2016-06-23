
module.exports = {
  
  disabled: true,
  interval: '15 10 * * *',
  autoStart: true,
  event: true,
  options: {},
  
  data: {
    subject: 'Обновление товарного рынка',
    recipients: [
      {name:'Sergei',contact:'tsv@ufs-financial.ch'},
      {contact:'ufsblb@ufs-federation.com'}
    ],
    test: false
  },
  
  execute: function(job,done) {
    
    var log = this.log;
    try {
      
      var options = Utils.extendSeries([this.data,job.attrs.data,this.options]);
      
      options.creator = job.name;
      
      log.debug('Options: %s',[options]);
      
      WwwService.recalcCommodities(options,function(err){
        
        if (err) log.error(err);
        done();
      });
      
    } catch(e) {
      log.exception(e);
      done();
    }
  }
}