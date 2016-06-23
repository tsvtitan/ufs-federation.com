
module.exports = {
  
  disabled: true,
  interval: '00 10 1 * *',
  autoStart: true,
  event: true,
  options: {},
  
  data: {
    title: 'Список сотрудников, присутствующих в здании за месяц ({dateFrom} - {dateTo})',
    recipients: [
      {name:'Sergei',contact:'tsv@ufs-financial.ch'},
      {name:'Boris',contact:'kba@ufs-financial.ch'},
      {name:'Andrey',contact:'lan@ufs-financial.ch'},
      {name:'Miki',contact:'imv@ufs-financial.ch'},
      {contact:'hr@ufs-federation.com'}
    ],
    test: false,
    body: false,
    attachment: true
  },
  
  execute: function(job,done) {
    
    var log = this.log;
    try {
      
      var dateFrom = moment().subtract({months:1}).startOf('month').format('DD.MM.YYYY');
      var dateTo = moment().subtract({months:1}).endOf('month').format('DD.MM.YYYY');
      
      var options = Utils.extend(this.data,this.options || {});
      
      options.creator = job.name;
      options.title = Utils.format(options.title,{dateFrom:dateFrom,dateTo:dateTo});
      
      log.debug('Options: %s',[options]);
      
      AcsService.getWorkMonthHours(options,dateFrom,dateTo,function(err,mailing){
        
        if (err) log.error(err);
        else log.info('Count: {allCount}',mailing);
        done();
      });
      
    } catch(e) {
      log.exception(e);
      done();
    }
  }
}