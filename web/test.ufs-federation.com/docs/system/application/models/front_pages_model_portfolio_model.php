<?php
class front_pages_model_portfolio_model extends Model{

  function front_pages_model_portfolio_model() {
    
    parent::Model();
  }

  private function get_portfolio($portfolio_id) {
  
    $ret = false;
    $r = $this->global_model->get_table_data_as_class('portfolios',null,array('portfolio_id'=>$portfolio_id));
    if (is_array($r) && sizeOf($r)>0) {
      $ret = $r[0];
    }
    return $ret;
  }
  
  private function get_portfolio_history_first($portfolio_id) {
    
    $ret = false;
    /*$sql = sprintf('select ph.* 
                      from portfolio_history ph
                      join (select pd.portfolio_id, cast(iv.to_date as date) as to_date
                              from instrument_values iv
                              join (select instrument_id, param_id, value_number, min(to_date) as to_date
                                      from instrument_values
                                     group by 1,2,3
                                     order by to_date) t on t.param_id=iv.param_id and t.instrument_id=iv.instrument_id and t.to_date=iv.to_date
                              join portfolio_depends pd on pd.param_id=iv.param_id and pd.instrument_id=iv.instrument_id
                             group by 1,2
                            order by iv.to_date) t2 on t2.portfolio_id=ph.portfolio_id and t2.to_date=cast(ph.created as date)
                     where ph.portfolio_id=%d
                     order by ph.created
                     limit 1',
                   $portfolio_id);*/

    $sql = sprintf('select ph.* 
                      from portfolio_history ph
                     where ph.portfolio_id=%d
                       and ph.rest is not null
                       and ph.value is not null
                     order by ph.created
                     limit 1',
                   $portfolio_id);
    
    $r = $this->global_model->get_query_data_as_class($sql);
    
    if (is_array($r) && sizeOf($r)>0) {
      $ret = $r[0];
    }
    return $ret;
  }
  
  private function get_portfolio_history_last($portfolio_id,$date) {
  
    $ret = false;
    
    /*$sql = sprintf('select ph.* 
                      from portfolio_history ph
                      join (select pd.portfolio_id, cast(iv.to_date as date) as to_date
                              from instrument_values iv
                              join (select instrument_id, param_id, value_number, min(to_date) as to_date
                                      from instrument_values
                                     group by 1,2,3
                                     order by to_date) t on t.param_id=iv.param_id and t.instrument_id=iv.instrument_id and t.to_date=iv.to_date
                              join portfolio_depends pd on pd.param_id=iv.param_id and pd.instrument_id=iv.instrument_id
                             group by 1,2
                            order by iv.to_date) t2 on t2.portfolio_id=ph.portfolio_id and t2.to_date=cast(ph.created as date)
                     where ph.portfolio_id=%d
                       and ph.created<=%s
                     order by ph.created desc
                     limit 1',
                   $portfolio_id,$this->global_model->quote($date));*/

    $sql = sprintf('select ph.* 
                      from portfolio_history ph
                     where ph.portfolio_id=%d
                       and ph.created<=%s
                       and ph.rest is not null
                       and ph.value is not null
                     order by ph.created desc
                     limit 1',
                   $portfolio_id,$this->global_model->quote($date));
    
    $r = $this->global_model->get_query_data_as_class($sql);
   if (is_array($r) && sizeOf($r)>0) {
     $ret = $r[0];
   }
   return $ret;
  }
  
  
  private function get_table_portfolio_data($portfolio_id,$date,
                                               &$total_first,&$total_last,
                                               &$rest_first,&$rest_last,&$total_revenue_loss,&$total_shift) {
    
    $ret = false;
    
    $first = $this->get_portfolio_history_first($portfolio_id);
    if ($first) {
      $rest_first = $first->rest;
    }
    
    $last = $this->get_portfolio_history_last($portfolio_id,$date);
    if ($last) {
      $rest_last = $last->rest;
    }
    
    if ($first && $last) {
    
      $sql = sprintf('select t.name, t.isin, t.instrument_id, t.position,
                             (case when t.position="long" then t.amount else -t.amount end) as amount,
                             t.operation_value, t.total_first, t.total_last, t.value, t.revenue_loss, t.size,
                             round(100*round(t.revenue_loss/(t.amount*t.operation_value),5),2) as shift
                        from (select i.name, i.isin, t1.instrument_id, t1.position,
                                     t1.amount, t1.operation_value,
                                     round(t1.amount*t1.operation_value,2) as total_first,
                                     t1.value, round(t1.amount*t1.value,2) as total_last,
                                     (case 
                                        when t1.position="long" then round((t1.value-t1.operation_value)*t1.amount,2)
                                        when t1.position="short" then round((t1.operation_value-t1.value)*t1.amount,2)
                                        else 0.0 
                                     end) as revenue_loss,
                                     ip.size
                                from (select instrument_id, position, value, 
                                             sum(case when operation_value is null then 0.0 else operation_value end) as operation_value, 
                                             sum(amount) as amount, max(instrument_value_id) as instrument_value_id
                                        from portfolio_history_parts
                                       where portfolio_history_id%s
                                         and operation in ("keep","buy")
                                       group by 1,2,3
                                       order by priority) t1
                                join instruments i on i.instrument_id=t1.instrument_id
                                left join instrument_values iv on iv.instrument_value_id=t1.instrument_value_id
                                left join instrument_params ip on ip.instrument_id=iv.instrument_id and ip.param_id=iv.param_id) t',
                     (($last)?'='.$last->portfolio_history_id:' is null'));
      $data = $this->global_model->get_query_data_as_class($sql); 
      
      if (is_array($data) && sizeOf($data)) {
        
        foreach($data as $d) {
          $total_first+= $d->total_first;
          $total_last+= $d->total_last;
          $total_revenue_loss+= $d->revenue_loss;
        }
        if ($total_first>0) {
          $total_shift = round($total_revenue_loss*100/$total_first,2);
        }
        
        foreach($data as $k=>$n) {
          //$data[$k]->amount = ($data[$k]->position=='long')?$data[$k]->amount:-$data[$k]->amount;
          $data[$k]->percent = 0.0;
          if ($total_last>0) {
            $data[$k]->percent = round($data[$k]->total_last*100/$total_last,2);
          }
        }
        $ret = $data;
      }
    }
    return $ret;
  }
  
  private function append_table_portfolio_record($data,$name,$total_first,$revenue_loss=null,$shift=null) {
    
    $new = new stdClass();
    $r = isset($data[0])?$data[0]:null;
    if (is_array($r)) {
      foreach ($r as $n=>$v) {
        $v = null;
        switch ($n) {
          case 'name': $v = dictionary($name); break;
          case 'total_first': $v = $total_first; break;
          case 'revenue_loss': $v = $revenue_loss; break;
          case 'shift': $v = $shift; break;
        }
        $new->{$n} = $v;
      }
    } else {
      $new->name = dictionary($name);
      $new->isin = null;
      $new->instrument_id = null;
      $new->position = null;
      $new->amount = null;
      $new->operation_value = null;
      $new->total_first = $total_first;
      $new->total_last = null;
      $new->value = null;
      $new->revenue_loss = $revenue_loss;
      $new->size = null;
      $new->shift = $shift;
      $new->percent = null;
    }
    return $new;
  }
  
  private function get_table_portfolio_json($portfolio_id,$date) {
    
    $total_first = 0.0;
    $total_last = 0.0;
    $rest_first = 0.0;
    $rest_last = 0.0;
    $total_revenue_loss = 0.0;
    $total_shift = 0.0;
    
    $ret = $this->get_table_portfolio_data($portfolio_id,$date,$total_first,$total_last,
                                           $rest_first,$rest_last,$total_revenue_loss,$total_shift);
    if (!$ret) {
      $ret = array();  
    }
    if (is_array($ret)) {
      
      $investment = 0.0;
      //$portfolio_total = $total_last+$rest_last;
      $portfolio_total = $total_first+$rest_last+((sizeOf($ret)>0)?$total_revenue_loss:0);
      $portfolio_shift = 0.0;
      $portfolio = $this->get_portfolio($portfolio_id);
      if ($portfolio && isset($portfolio->investment)) {
        $investment = (float)$portfolio->investment;
        if ($investment!=0) {
          $portfolio_shift = round(100*($portfolio_total-$investment)/$investment,2);
        }
      }
      
      $ret[] = $this->append_table_portfolio_record($ret,'Портфель ЦБ',$total_first,(sizeOf($ret)>0)?$total_revenue_loss:null,(sizeOf($ret)>0)?$total_shift:null);
      $ret[] = $this->append_table_portfolio_record($ret,'Денежные средства',(float)$rest_last);
      $ret[] = $this->append_table_portfolio_record($ret,'Общая стоимость портфеля',$portfolio_total,$portfolio_total-$investment,$portfolio_shift);
      
      foreach ($ret as $rk=>$rv) {

        $default = (isset($rv->size))?$rv->size:2;
        foreach ($rv as $k=>$v){
          $tv = $v;
          if (is_null($tv)) {
            $tv = null;
          } else {
            $decimals = null;
            switch ($k) {
              case 'value': $decimals = $default; break;
              case 'amount': $decimals = 0; break;
              case 'total_first': $decimals = 2; break;
              case 'operation_value': $decimals = $default; break;
              case 'total_last': $decimals = 2; break;
              case 'revenue_loss': $decimals = 2; break;
              case 'shift': $decimals = 2; break;
            }
            if (!is_null($decimals)) {
              $tv = number_format($tv,$decimals,'.',' ');
            }
          }
          $ret[$rk]->{$k} = $tv;
        }
      }

      $ret = @json_encode($ret);
    }
    return $ret;
  }
  
  private function get_pie_portfolio_json($portfolio_id,$date) {
  
    $total_first = 0.0;
    $total_last = 0.0;
    $rest_first = 0.0;
    $rest_last = 0.0;
    $total_revenue_loss = 0.0;
    $total_shift = 0.0;
  
    $ret = $this->get_table_portfolio_data($portfolio_id,$date,$total_first,$total_last,
                                           $rest_first,$rest_last,$total_revenue_loss,$total_shift);
    if (!$ret) {
      $ret = array();
    }
    if (is_array($ret)) {
      $data = array();
      
      //$portfolio_total = $total_last+$rest_last;
      $portfolio_total = $total_first+$rest_last+((sizeOf($ret)>0)?$total_revenue_loss:0);
      
      foreach ($ret as $k=>$v) {
        
        $ret[$k]->percent = 0.0;
        if ($portfolio_total>0) {
          $ret[$k]->percent = round($ret[$k]->total_last*100/$portfolio_total,2);
        }
      }
      
      foreach ($ret as $r) {
        $data[] = array($r->name,$r->percent);
      }
      
      $percent = 0.0;
      if ($portfolio_total>0) {
        $percent = round($rest_last*100/$portfolio_total,2);
      }
      $data[] = array(dictionary('Денежные средства'),$percent);
      
      $ret = @json_encode($data,JSON_NUMERIC_CHECK);
    }
    return $ret;
  }
  
  private function get_series_portfolio_data($portfolio_id,$date) {
    
    $ret = false;
    
  /*  $sql = sprintf('select t1.portfolio_history_id, ph.method, 
                           cast(t1.created as date) as created, 
                           (case when t2.operation_total is not null then t2.operation_total else 0 end) as operation_total, ph.rest, 
                           (case when t2.total is not null then t2.total else 0 end) as total,
                           (case when t2.revenue_loss is not null then t2.revenue_loss else 0 end) as revenue_loss,
                           ((case when t2.operation_total is not null then t2.operation_total else 0 end)+ph.rest+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as value
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
                   $portfolio_id,$this->global_model->quote($date)); */
    
    $sql = sprintf('select portfolio_history_id, method,
                           created as created,
                           (value + rest) as value
                      from portfolio_history 
                     where value is not null
                       and portfolio_id=%d
                       and created<=%s
                     order by created',
                   $portfolio_id,$this->global_model->quote($date));
    
    $data = $this->global_model->get_query_data_as_class($sql);
    if (is_array($data) && (sizeOf($data)>0)) {
      $ret = $data;
    }
    return $ret;
  }
  
  private function get_series_portfolio_json($portfolio_id,$date) {
    
    $ret = $this->get_series_portfolio_data($portfolio_id,$date);
    //$ret = false;
    if (!$ret) {
      $ret = array();
    }
    if (is_array($ret)) {
      
      $data = array();
      $first = false;
      
      $currency = '';
      $portfolio = $this->get_portfolio($portfolio_id);
      if ($portfolio) {
        $currency = ($portfolio->currency_id=='RUR')?' р.':' '.$portfolio->currency_id;
      }
      
      foreach ($ret as $r) {
        
        if (!$first) {
          $first = $r->value;
        }
        
        $d = new stdClass();
        $d->x = strtotime($r->created)*1000;
        $d->y = round(($r->value*100)/$first,2);
        //$d->first = $first;
        $d->value = number_format($r->value,2,'.',' ').$currency;
        /*if ($r->method=='manual') {
          $d->marker->enabled = true;
          $d->marker->states->select->enabled = true;
          $d->marker->symbol = 'circle';
        } else {
          $d->marker->enabled = true;
          $d->marker->states->select->enabled = true;
          $d->marker->symbol = 'diamond';
        }*/
        $data[] = $d;
      }
      
      if (sizeOf($data)>0) {
        $l = sizeOf($data)-1;
        $data[$l]->last = true;
        /*$data[$l]->marker->enabled = true;
        $data[$l]->marker->states->select->enabled = true;
        $data[$l]->marker->states->select->fillColor = 'red';
        $data[$l]->marker->states->select->lineColor = 'green';*/
      }
      $ret = @json_encode($data,JSON_NUMERIC_CHECK);
    }
    return $ret;
  }
  
  
  private function get_series_micex_data($portfolio_id,$date) {
    
    $ret = false;
    
    $param = $this->global_model->get_table_data_as_class('params',null,array('ident'=>'PREV_CLOSE_VAL'));
    $instrument = $this->global_model->get_table_data_as_class('instruments',null,array('ident'=>'INDEXCF INDEX'));
    
    $first = $this->get_portfolio_history_first($portfolio_id);
    
    if (is_array($param) && sizeOf($param)>0 &&
        is_array($instrument) && sizeOf($instrument)>0 &&
        is_object($first)) {
      
      $param = $param[0];
      $instrument = $instrument[0];
      
/*      $sql = sprintf('select cast(t.to_date as date) as to_date, round(t.value_number,2) as value
                        from (select value_number, min(to_date) as to_date
                                from (select t2.value_number, t2.to_date
                                        from (select param_id, instrument_id, max(to_date) as to_date  
                                                from instrument_values
                                               where instrument_id=%d and param_id=%d
                                               group by 1, 2, cast(to_date as date)) t1
                                        join (select value_number, param_id, instrument_id, max(to_date) as to_date
                                                from instrument_values
                                               where instrument_id=%d and param_id=%d
                                               group by 1, 2, 3, cast(to_date as date)) t2 on t2.param_id=t1.param_id and t2.instrument_id=t1.instrument_id and t2.to_date=t1.to_date
                                where t1.to_date<=%s
                                  and t1.instrument_id=%d
                                  and t1.param_id=%d) t
                                group by 1) t
                       where cast(t.to_date as date) in (select cast(created as date) 
                                                           from portfolio_history
                                                          where portfolio_id=%d 
                                                          group by 1)
                       order by t.to_date',
                      $instrument->instrument_id,$param->param_id,
                      $instrument->instrument_id,$param->param_id,
                      $this->global_model->quote($date),
                      $instrument->instrument_id,
                      $param->param_id,
                      $portfolio_id);*/

      $sql = sprintf('select t.to_date, round(t.value_number,2) as value
                        from (select t2.value_number, t2.to_date
                                from (select param_id, instrument_id, max(to_date) as to_date  
                                        from instrument_values
                                       where instrument_id=%d and param_id=%d
                                         and locked is null
                                       group by 1, 2, cast(to_date as date)) t1
                                join (select value_number, param_id, instrument_id, max(to_date) as to_date
                                        from instrument_values
                                       where instrument_id=%d and param_id=%d
                                       group by 1, 2, 3, cast(to_date as date)) t2 on t2.param_id=t1.param_id and t2.instrument_id=t1.instrument_id and t2.to_date=t1.to_date
                               where t1.to_date<=%s
                                 and t1.instrument_id=%d
                                 and t1.param_id=%d) t
                       where cast(t.to_date as date) in (select cast(created as date) 
                                                           from portfolio_history
                                                          where portfolio_id=%d 
                                                            and value is not null
                                                          group by 1)
                       order by t.to_date',
                      $instrument->instrument_id,$param->param_id,
                      $instrument->instrument_id,$param->param_id,
                      $this->global_model->quote($date),
                      $instrument->instrument_id,
                      $param->param_id,
                      $portfolio_id);
      
      $data = $this->global_model->get_query_data_as_class($sql);
      if (is_array($data) && sizeOf($data)) {
      
        $ret = $data;
      }
    }
    return $ret;
  }
  
  private function get_series_micex_json($portfolio_id,$date) {
    
    $ret = $this->get_series_micex_data($portfolio_id,$date);
    if (!$ret) {
      $ret = array();
    }
    if (is_array($ret)) {
      
      $data = array();
      $first = false;
      
      foreach ($ret as $r) {
        
        if (!$first) {
          $first = $r->value;
        }

        $d = new stdClass();
        $d->x = strtotime($r->to_date)*1000;
        //$d->y = -(100-round(($r->value*100)/$first,2));
        $d->y = round(($r->value*100)/$first,2);
        $d->value = number_format($r->value,2,'.',' ');
        
        $data[] = $d;
      }
      $ret = @json_encode($data,JSON_NUMERIC_CHECK);
    }
    return $ret;
  }

  
  function view($portfolio_id=null) {

    $ret = false;
    $data = array();
    $portfolio = false;
    
    $type = isset($_REQUEST['type'])?$_REQUEST['type']:false;
    $date = isset($_REQUEST['date'])?$_REQUEST['date']:date('Y-m-d');
    if (!$this->global_model->is_date($date)) {
      $date = date('Y-m-d H:i:s');
    } else {
      $date = date('Y-m-d 23:59:59',strtotime($date));
    }
    
    $callback = isset($_REQUEST['callback'])?$_REQUEST['callback']:false;
    
    if (!isset($portfolio_id)) {
      $portfolio_id = isset($_REQUEST['portfolio_id'])?$_REQUEST['portfolio_id']:null;
    }
    
    if (isset($portfolio_id)) {
      $portfolio = $this->global_model->get_table_data_as_class('portfolios',null,array('portfolio_id'=>$portfolio_id));
    } else {
      $portfolio = $this->global_model->get_table_data_as_class('portfolios',null,array('locked'=>null),array('created'),1);
    }
    
    if (is_array($portfolio) && sizeOf($portfolio)>0) {
      
      $portfolio = $portfolio[0];
      $portfolio_id = $portfolio->portfolio_id;
      
      if ($type) {
      
        $json = false;
        switch ($type) {
          case 'table': {
            $json = $this->get_table_portfolio_json($portfolio_id,$date);
            break;
          }
          case 'pie': {
            $json = $this->get_pie_portfolio_json($portfolio_id,$date);
            break;
          }
          case 'chart': {
            
            $series = isset($_REQUEST['series'])?$_REQUEST['series']:false;
            switch ($series) {
              case 'portfolio': {
                $json = $this->get_series_portfolio_json($portfolio_id,$date);
                break;
              }
              case 'micex': {
                $json = $this->get_series_micex_json($portfolio_id,$date);
                break;
              }
            }
            break;
          }
        }
      
        if ($json) {
          
          if ($callback) {
            header('Content-type: text/javascript');
            $json = $callback.'('.$json.');';
          } else {
            header('Content-type: text/json');
          }
          header('Content-length: '.strlen($json));
          header('Access-Control-Allow-Origin: *');
           
          echo ($json);
          die();
          
        }
      
      } else {
        
        $ret = $this->load->view('body_content_sub_model_portfolio',$data,true);
      }
    }
    
    return $ret; 
  }

}
?>