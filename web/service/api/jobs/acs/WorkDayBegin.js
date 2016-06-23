
module.exports = {
  
  disabled: true,
  interval: '00 13 * * 1,2,3,4,5',
  autoStart: true,
  event: true,
  options: {},
  
  data: {
    title: 'Список сотрудников, пришедших в здание на {date}',
    columns: ['Сотрудник','Время прихода'],
    recipients: [
      {name:'Sergei',contact:'tsv@ufs-financial.ch'},
      {name:'Boris',contact:'kba@ufs-financial.ch'},
      {name:'Andrey',contact:'lan@ufs-financial.ch'},
      {name:'Miki',contact:'imv@ufs-financial.ch'},
      {contact:'hr@ufs-federation.com'}
    ],
    test: false,
    body: true,
    attachment: true
  },
  
  execute: function(job,done) {
    
    var log = this.log;
    try {
      
      var date = moment().format('DD.MM.YYYY HH:mm:ss');
      
      var options = Utils.extend(this.data,this.options || {});
      
      options.creator = job.name;
      options.title = Utils.format(options.title,{date:date});
      
      log.debug('Options: %s',[options]);
      
      AcsService.getWorkDayBegin(options,date,function(err,mailing){
        
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