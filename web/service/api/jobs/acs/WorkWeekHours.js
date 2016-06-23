
module.exports = {
  
  disabled: true,
  interval: '00 10 * * 1',
  autoStart: true,
  event: true,
  options: {},
  
  data: {
    title: 'Список сотрудников, присутствующих в здании за 7 дней ({dateFrom} - {dateTo})',
    columns: ['Сотрудник','Первый день','Последний день','Внутри (час)','Снаружи (час)'],
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
      
      var dateFrom = moment().add({days:-7}).format('DD.MM.YYYY');
      var dateTo = moment().add({days:-1}).format('DD.MM.YYYY');
      
      var options = Utils.extend(this.data,this.options || {});
      
      options.creator = job.name;
      options.title = Utils.format(options.title,{dateFrom:dateFrom,dateTo:dateTo});
      
      log.debug('Options: %s',[options]);
      
      AcsService.getWorkWeekHours(options,dateFrom,dateTo,function(err,mailing){
        
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