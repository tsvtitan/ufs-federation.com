<?php

set_include_path(':'.dirname(__FILE__).':'.get_include_path());

require_once 'consts.php';

set_include_path(':'.dirname(dirname(__FILE__)).'/libs:'.get_include_path());

require_once 'log.php';
require_once 'mysql.php';
require_once 'utils.php';

function convert_value($db,$value,$date,$from_currency_id,$to_currency_id,&$exchange_rate_id) {
  
  $ret = $value;
  if ($from_currency_id!=$to_currency_id) {
    
    $d = isset($date)?$db->quote($date):'current_timestamp';
    $exchange_rate = $db->getRecord(sprintf('select exchange_rate_id, from_value, to_value 
                                               from exchange_rates 
                                              where from_currency_id=%s 
                                                and to_currency_id=%s 
                                                and created<=%s
                                              order by created desc 
                                              limit 1',
                                            $db->quote($from_currency_id),$db->quote($to_currency_id),$d));
    if (is_array($exchange_rate) && sizeOf($exchange_rate)>0) {
      
      $exchange_rate_id = $exchange_rate['exchange_rate_id'];
      $ret = ($value/$exchange_rate['from_value']*$exchange_rate['to_value']);
    }
  }
  return $ret;
}

function recalc_portfolio_value($db,$porftolio_id) {
  
  //$r = $db->query(sprintf('update portfolio_history set value=null where portfolio_id=%d;',$porftolio_id));
  $r = $db->query(sprintf('update portfolio_history set value=null
                            where portfolio_id=%d
                              and created>date_add(current_timestamp,interval -1 month);',$porftolio_id));
  if ($r) {
    
    $date = date('Y-m-d H:i:s');
    /*$sql = sprintf('select t1.portfolio_history_id, ph.method, 
                           cast(t1.created as date) as created, 
                           (case when t2.operation_total is not null then t2.operation_total else 0 end) as operation_total, ph.rest, 
                           (case when t2.total is not null then t2.total else 0 end) as total,
                           (case when t2.revenue_loss is not null then t2.revenue_loss else 0 end) as revenue_loss,
                           ((case when t2.operation_total is not null then t2.operation_total else 0 end)+ph.rest+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as total_value,
                           ((case when t2.operation_total is not null then t2.operation_total else 0 end)+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as value
                      from (select max(ph.portfolio_history_id) as portfolio_history_id, max(ph.created) as created
                              from portfolio_history ph
                              join (select pd.portfolio_id, cast(iv.to_date as date) as to_date
                                      from instrument_values iv
                                      join (select t1.param_id, t1.instrument_id, min(t2.to_date) as to_date
                                              from (select param_id, instrument_id, max(to_date) as to_date  
                                                      from instrument_values
                                                     group by 1, 2, cast(to_date as date)) t1
                                              join (select value_number, param_id, instrument_id, max(to_date) as to_date
                                                      from instrument_values
                                                     group by 1, 2, 3, cast(to_date as date)) t2 on t2.param_id=t1.param_id and t2.instrument_id=t1.instrument_id and t2.to_date=t1.to_date
                                             group by 1,2,t2.value_number) t on t.param_id=iv.param_id and t.instrument_id=iv.instrument_id and t.to_date=iv.to_date
                                      join portfolio_depends pd on pd.param_id=iv.param_id and pd.instrument_id=iv.instrument_id
                                     group by 1,2) t2 on t2.portfolio_id=ph.portfolio_id and t2.to_date=cast(ph.created as date)
                             where ph.portfolio_id=%d
                               and ph.created<=%s
                             group by cast(ph.created as date)) t1
                      left join (select portfolio_history_id, 
                                        sum(value*amount) as total, 
                                        sum(operation_value*amount) as operation_total,
                                        sum(case 
                                              when position="long" then round((value-operation_value)*amount,2)
                                              when position="short" then round((operation_value-value)*amount,2)
                                              else 0.0 
                                            end) as revenue_loss
                                   from portfolio_history_parts
                                  where operation in ("keep","buy")
                                  group by 1) t2 on t2.portfolio_history_id=t1.portfolio_history_id
                      left join portfolio_history ph on ph.portfolio_history_id=t1.portfolio_history_id
                     order by t1.created',
                   $porftolio_id,$db->quote($date));*/
    
       $sql = sprintf('select t1.portfolio_history_id, ph.method, 
                             cast(t1.created as date) as created,  
                             (case when t2.operation_total is not null then t2.operation_total else 0 end) as operation_total, ph.rest, 
                             (case when t2.total is not null then t2.total else 0 end) as total,
                             (case when t2.revenue_loss is not null then t2.revenue_loss else 0 end) as revenue_loss,
                             ((case when t2.operation_total is not null then t2.operation_total else 0 end)+ph.rest+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as total_value,
                             ((case when t2.operation_total is not null then t2.operation_total else 0 end)+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as value
                        from (select max(ph.portfolio_history_id) as portfolio_history_id, max(ph.created) as created
                                from portfolio_history ph
                                join (select pd.portfolio_id, cast(iv.to_date as date) as to_date
                                        from instrument_values iv
                                        join (select t1.param_id, t1.instrument_id, min(t2.to_date) as to_date
                                                from (select param_id, instrument_id, max(to_date) as to_date  
                                                        from instrument_values
                                                       where created>=date_add(current_timestamp,interval -2 month) 
                                                       group by 1, 2, cast(to_date as date)) t1
                                                join (select value_number, param_id, instrument_id, max(to_date) as to_date
                                                        from instrument_values
                                                       where created>=date_add(current_timestamp,interval -2 month) 
                                                       group by 1, 2, 3, cast(to_date as date)) t2 on t2.param_id=t1.param_id and t2.instrument_id=t1.instrument_id and t2.to_date=t1.to_date
                                               group by 1,2,t2.value_number) t on t.param_id=iv.param_id and t.instrument_id=iv.instrument_id and t.to_date=iv.to_date
                                        join portfolio_depends pd on pd.param_id=iv.param_id and pd.instrument_id=iv.instrument_id
                                       group by 1,2) t2 on t2.portfolio_id=ph.portfolio_id and t2.to_date=cast(ph.created as date)
                               where ph.portfolio_id=%d
                                 and ph.created<=%s
                                 and ph.created>date_add(current_timestamp,interval -2 month)
                               group by cast(ph.created as date)) t1
                        left join (select portfolio_history_id, 
                                          sum(value*amount) as total, 
                                          sum(operation_value*amount) as operation_total,
                                          sum(case 
                                                when position="long" then round((value-operation_value)*amount,2)
                                                when position="short" then round((operation_value-value)*amount,2)
                                                else 0.0 
                                              end) as revenue_loss
                                     from portfolio_history_parts
                                    where operation in ("keep","buy")
                                    group by 1) t2 on t2.portfolio_history_id=t1.portfolio_history_id
                        left join portfolio_history ph on ph.portfolio_history_id=t1.portfolio_history_id
                       order by t1.created',
                     $porftolio_id,$db->quote($date));   
   
    $data = $db->getRecords($sql);
    if (is_array($data) && sizeOf($data)>0) {
      
      foreach ($data as $d) {
        
        $history['value'] = $d['value'];
        $db->updateRecord('portfolio_history',$history,array('portfolio_history_id'=>$d['portfolio_history_id']));
      }
    }
    
  }
}

$log = new Log (LOG_PORTFOLIO_RECALC,true,true,false);
if ($log) {
  $stamp = microtime(true);
 
  $log->writeInfo(str_repeat('-',50));
  try {
    $log->writeInfo('Connect to Database ...');

    $db = new Mysql($log,DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if ($db) {
      
      $log->writeInfo('Connected.');
      $db->setCharset('utf8');
      
      //$param_ident = 'PX_LAST_ACTUAL';
      //$param = $db->getRecord(sprintf('select * from params where ident=%s limit 1',$db->quote($param_ident)));
      
      $portfolios = $db->getRecords('select * from portfolios where locked is null order by created');
      if (is_array($portfolios) && sizeOf($portfolios)>0/* && is_array($param) && sizeOf($param)>0*/) {
        
        $log->writeInfo(sprintf('Portfolios are found (%d)',sizeOf($portfolios)));
        
        foreach ($portfolios as $p) {
          
          $portfolio_id = $p['portfolio_id'];
          $log->writeInfo(sprintf('Portfolio name is %s and id is %d.',$p['name'],$portfolio_id));
          
          $portfolio_history_id = false;
          $portfolio_history = $db->getRecord(sprintf('select ph.portfolio_history_id, ph.rest
                                                         from portfolio_history ph
                                                         left join (select portfolio_history_id, count(*) as cnt
                                                                      from portfolio_history_parts
                                                                     where portfolio_history_id in (select portfolio_history_id 
                                                                                                      from portfolio_history 
                                                                                                     where portfolio_id=%d)
                                                                     group by 1) t on t.portfolio_history_id=ph.portfolio_history_id
                                                        where ph.portfolio_id=%d
                                                        /*  and t.cnt>0 */
                                                        order by ph.created desc',
                                                       $portfolio_id,$portfolio_id));
          if (is_array($portfolio_history) && sizeOf($portfolio_history)>0) {
            
            $portfolio_history_id = $portfolio_history['portfolio_history_id'];
          }
          
          if ($portfolio_history_id) {
            
            $log->writeInfo(sprintf('Portfolio history id is %d.',$portfolio_history_id));
            
            $old_history_parts = $db->getRecords(sprintf('select t.*, i.currency_id, iv.param_id
                                                            from (select instrument_id, operation, position, value, operation_value, sum(amount) as amount,
                                                                         max(instrument_value_id) as instrument_value_id, max(exchange_rate_id) as exchange_rate_id
                                                                    from (select instrument_id, "keep" as operation, position, value,
                                                                                 instrument_value_id, exchange_rate_id, amount, priority,
                                                                                 sum(case when operation_value is null then 0.0 else operation_value end) as operation_value
                                                                            from portfolio_history_parts
                                                                           where portfolio_history_id=%d and operation in ("keep","buy")
                                                                           group by 1,2,3,4,5,6,7,8) t
                                                                   group by 1,2,3,4
                                                                   order by priority) t
                                                            join instruments i on i.instrument_id=t.instrument_id
                                                            join instrument_values iv on iv.instrument_value_id=t.instrument_value_id',
                                                         $portfolio_history_id));
            if (is_array($old_history_parts) && sizeOf($old_history_parts)>0) {

              $created = date('Y-m-d H:i:s');
              $rc = $db->getRecord(sprintf('select max(to_date) as date 
                                              from instrument_values 
                                             where instrument_id in (select instrument_id
                                                                       from portfolio_history_parts
                                                                      where portfolio_history_id=%d) 
                                             order by 1 desc 
                                             limit 1',$portfolio_history_id));
              if ($rc) {
                $created = $rc['date'];
              }

              $new_history['portfolio_id'] = $portfolio_id;
              $new_history['created'] = $created;
              $new_history['method'] = 'auto';
              $new_history['rest'] = $portfolio_history['rest'];
              
              $flag = $db->insertRecord('portfolio_history',$new_history);
              if ($flag) {

                $history_id = $db->lastId();
                if ($history_id) {
                  
                  $counter = 1;
                  foreach ($old_history_parts as $ohp) {
                    
                    $instrument_value_id = $ohp['instrument_value_id'];
                    $value = $ohp['value'];
                    $currency_id = $ohp['currency_id'];
                    $param_id = $ohp['param_id'];
                    $size = false;
                    
                    $instrument_value = $db->getRecord(sprintf('select iv.instrument_value_id, iv.value_number, iv.currency_id, ip.size
                                                                  from instrument_values iv
                                                                  join instrument_params ip on ip.param_id=iv.param_id and ip.instrument_id=iv.instrument_id
                                                                 where iv.param_id=%d
                                                                   and iv.instrument_id=%d
                                                                 order by iv.created desc
                                                                 limit 1',
                                                               $param_id,$ohp['instrument_id']));
                    if (is_array($instrument_value) && sizeOf($instrument_value)>0) {
                      
                      $instrument_value_id = $instrument_value['instrument_value_id'];
                      $value = $instrument_value['value_number'];
                      $currency_id = isset($instrument_value['currency_id'])?$instrument_value['currency_id']:$currency_id;
                      $size = $instrument_value['size'];
                    }
                    
                    $exchange_rate_id = $ohp['exchange_rate_id'];
                    $value = convert_value($db,$value,$created,$currency_id,$p['currency_id'],$exchange_rate_id);
                    if ($size) {
                      $value = round($value,$size);
                    }
                    
                    $part['portfolio_history_id'] = $history_id;
                    $part['instrument_id'] = $ohp['instrument_id'];
                    $part['operation'] = $ohp['operation'];
                    $part['instrument_value_id'] = $instrument_value_id;
                    $part['exchange_rate_id'] = $exchange_rate_id;
                    $part['position'] = $ohp['position'];
                    $part['amount'] = $ohp['amount'];
                    $part['value'] = $value;
                    $part['operation_value'] = $ohp['operation_value'];
                    $part['priority'] = $counter++;
                    
                    $db->insertRecord('portfolio_history_parts',$part);
                  }
                }
              }
            }
            
            $log->writeInfo('Recalculate portfolio value ...');
            
            recalc_portfolio_value($db,$portfolio_id);
            
          } else {
            $log->writeError('Portfolio history is not found.');
          }
        }
        
      } else {
        $log->writeError('Portfolios or Param are not found.');
      }
      
      unset ($db);
    }
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>