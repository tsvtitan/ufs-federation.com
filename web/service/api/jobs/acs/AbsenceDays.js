
module.exports = {
  
  disabled: true,
  interval: '00 10 * * 2,3,4,5,6',
  autoStart: true,
  event: true,
  options: {},
  
  data: {
    title: 'Список сотрудников, отсутствующих в здании на {date}',
    columns: ['Сотрудник'],
    recipients: [
      {name:'Sergei',contact:'tsv@ufs-financial.ch'},
      {name:'Boris',contact:'kba@ufs-financial.ch'},
      {name:'Andrey',contact:'lan@ufs-financial.ch'},
      {name:'Miki',contact:'imv@ufs-financial.ch'},
      {contact:'hr@ufs-federation.com'},
      {contact:'zvk@ufs-federation.com'}
    ],
    test: false,
    body: true,
    attachment: true
  },
  
  execute: function(job,done) {
    
    var log = this.log;
    try {
            
      var date = moment().add({days:-1}).format('DD.MM.YYYY');
      
      var options = Utils.extend(this.data,this.options || {});
      
      options.creator = job.name;
      options.title = Utils.format(options.title,{date:date});
      
      log.debug('Options: %s',[options]);
      
      AcsService.getAbsenceDays(options,date,function(err,mailing){
        
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