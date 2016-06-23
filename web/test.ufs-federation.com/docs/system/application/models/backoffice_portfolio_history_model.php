<?php
class backoffice_portfolio_history_model extends Model {

  private $table = 'portfolio_history';
  
  function backoffice_portfolio_history_model() {
    
    parent::Model();

    if(isset($_REQUEST['form_search'])) {
      
      $_SESSION['search'] = mysql_string($_REQUEST['search']);
     
    } else {
      
      if(!isset($_SESSION['search'])) {
        
        $_SESSION['search']='';
      }
    }
  }
    
  function del() {

    $portfolio_id = (int)$this->uri->segment(4);
    $portfolio_history_id = (int)$this->uri->segment(5);
    
    $this->global_model->delete('portfolio_history_parts',array('portfolio_history_id'=>$portfolio_history_id));
    
    $this->global_model->delete($this->table,array('portfolio_history_id'=>$portfolio_history_id));
    
    return redirect($this->uri->segment(1).'/'.$this->page_name.'/portfolio/'.$portfolio_id);
  }
    
  private function get_default_portfolio() {
    
    $ret = false;
    
    $r = $this->global_model->get_table_data_as_class('portfolios',null,array('locked'=>null,'lang'=>$this->site_lang),array('created'),1);
    if (is_array($r) && sizeOf($r)>0) {
      $ret = $r[0];
    }
    return $ret;
  }
  
  function view($portfolio_id=null) {
    
    $content['data'] = '';

    if ($portfolio_id==0 && empty($_SESSION['search'])) {
      
      $portfolio = $this->get_default_portfolio(); 
      if ($portfolio) {
        return redirect($this->uri->segment(1).'/'.$this->page_name.'/portfolio/'.$portfolio->portfolio_id);
      }
    }
      
    if (isset($_SESSION['admin']->is_update)) {

      $content['is_update'] = $this->lang->line('admin_tpl_page_updated');
      unset($_SESSION['admin']->is_update);
    }
    
    $r = $this->global_model->get_table_data_as_class($this->table,array('count(*) as cnt'),array('portfolio_id'=>$portfolio_id),null,1);
    if (is_array($r) && sizeOf($r)>0) {
      
      $count = 3;
      $rows = $r[0]->cnt;
      if ($rows>0) {
        
        $page = ((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
        $param['url'] = '/portfolio/'.$portfolio_id;
        $pages = $this->global_model->Pager($count,$rows,$param,$page);
        
        $r = $this->global_model->get_query_data_as_class(sprintf('select ph.portfolio_history_id,  ph.portfolio_id, ph.created, ph.method, ph.rest,
                                                                          (case when ph.value is null then 0 else ph.value end) as cost,
                                                                          p.currency_id, 
                                                                          t1.instrument_id, t1.operation, t1.name, t1.isin, t1.position, 
                                                                          t1.value, t1.priority, t1.operation_value, t1.amount, t1.total
                                                                     from %s ph 
                                                                     left join (select php.portfolio_history_id, i.instrument_id, php.operation, i.name, i.isin, 
                                                                                       php.position, php.value, php.priority, php.operation_value, sum(php.amount) as amount, 
                                                                                       sum(php.amount*php.value) as total
                                                                                  from portfolio_history_parts php 
                                                                                  join instruments i on i.instrument_id=php.instrument_id 
                                                                                 group by 1,2,3,4,5,6,7,8 
                                                                                 order by php.priority) t1 on t1.portfolio_history_id=ph.portfolio_history_id
                                                                     join portfolios p on p.portfolio_id=ph.portfolio_id 
                                                                    where ph.portfolio_id=%s 
                                                                    order by ph.created desc, ph.portfolio_history_id desc, t1.priority ',
                                                                   $this->table,$portfolio_id));
        if (is_array($r) && sizeOf($r)>0) {
          
          $temp = $r;
          $r = array();
          $old_history_id = null;
          $cost = 0.0;
          $rest = 0.0;
          
          $page_min = $pages['start'];
          $page_max = $page_min + $pages['show'];
          $page_counter = 0;
          
          foreach($temp as $t) { 
            
            if ($old_history_id!=$t->portfolio_history_id) {
              
              $page_counter++;
              if ($page_counter>$page_max) {
                
                break;
                
              } else {
                
                if ($page_counter>$page_min) {
                  if (isset($i)) {
                    $i->cost = ($cost!=0.0)?($cost+$rest):'';
                    $cost = 0.0;
                  }
                  
                  $i = new stdClass();
                  $i->portfolio_id = $portfolio_id;
                  $this->global_model->copy_props($t,$i,array('portfolio_history_id','method','created','rest','currency_id'));
                  $i->instruments = array();
                  $r[] = $i;
                 
                }
              }
              $old_history_id = $t->portfolio_history_id; 
            } 
            
            if (isset($i) && !is_null($t->instrument_id)) {
              
              $o = new stdClass();
              $this->global_model->copy_props($t,$o,array('instrument_id','operation','name','isin','position','amount','value','total','operation_value'));
              $i->instruments[] = $o;

              $cost = $t->cost;
              $rest = $t->rest;
            }
          }
          if (isset($i)) {
            $i->cost = ($cost!=0.0)?($cost+$rest):'';
          }
          
          $r = $this->global_model->rand_css_class($r);
          $content['data'] = $r;
          $content['pages'] = $pages['html'];
        }
      }
    }
    
    $r = $this->global_model->get_table_data_as_class('portfolios',null,array('lang'=>$this->site_lang),array('created'));
    $r = $this->global_model->set_prop_for($r,array('sel'=>' class="sel"'),array('portfolio_id'=>$portfolio_id));
    $content['portfolios'] = $r;
    
    return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    
  }

  private function get_instrument($instruments,$desc,&$param,$ident,$currency_id,$name,$size,$new=false) {
    
    $ret = false;
    
    if (!empty($ident) && 
        is_array($instruments) && sizeOf($instruments)>0) {
      
      foreach($instruments as $i) {
        
        if (strtoupper($i->ident)==$ident) {
          $ret = $i;
          break;
        }
      }
      
      if ($ret) {
        
        if (!isset($param)) {
          $pm = $this->global_model->get_table_data_as_class('params',null,array('param_id'=>$ret->param_id),null,1);
          if (is_array($pm) && sizeof($pm)>0) {
            $param = $pm[0];
          }
        }
        
        if (!is_null($ret->locked)) {
          $r = $this->global_model->update('instruments',array('locked'=>null),array('instrument_id'=>$ret->instrument_id));
          if ($r) {
            $ret->locked = null;
          }
        }
        
        if (is_null($ret->currency_id) &&  ($ret->currency_id!=$currency_id)) {
          $r = $this->global_model->update('instruments',array('currency_id'=>$currency_id),array('instrument_id'=>$ret->instrument_id));
          if ($r) {
            $ret->currency_id = $currency_id;
          }
        }
        
      } else {
        
        if ($new && isset($param)) {
          if (empty($currency_id)) {
            $currency_id = isset($desc['currency_id'])?$desc['currency_id']->default:null;
          }

          $instrument['currency_id'] = $currency_id;
          $instrument['param_id'] = $param->param_id;
          $instrument['name'] = $name;
          $instrument['ident'] = $ident;

          $r = $this->global_model->insert('instruments',$instrument);
          if ($r) {
            $instrument['instrument_id'] = $this->global_model->last_insert_id();
            $ret = $this->global_model->data_to_class($instrument);
          }
        }
      }
      
      if ($ret && isset($param)) {
        
        $where = array('instrument_id'=>$ret->instrument_id,'param_id'=>$param->param_id);
        $instrument_param = $this->global_model->get_table_data_as_class('instrument_params',null,$where,null,1);
        if (is_array($instrument_param) && sizeOf($instrument_param)>0) {
          
          $instrument_param = $instrument_param[0];
          if (!is_null($instrument_param->locked)) {
            
           // $this->global_model->update('instrument_params',array('locked'=>null),$where);
          }
        } else {
          
          $instrument_param['instrument_id'] = $ret->instrument_id;
          $instrument_param['param_id'] = $param->param_id;
          $instrument_param['size'] = $size;
          $this->global_model->insert('instrument_params',$instrument_param);
        }
      }
    }
    return $ret;
  }
  
  private function get_currency($currency,$ident) {
    
    $ret = false;
    
    if (!empty($ident) && 
        is_array($currency) && sizeOf($currency)>0) {
      
      foreach($currency as $c) {
        
        if (strtoupper($c->currency_id)==$ident) {
          $ret = $c;
          break;
        }
      }
    }
    return $ret;
  }
  
  private function make_part($instrument_id,$operation,$instrument_value_id,$exchange_rate_id,$position,$amount,$value,$operation_value=null) {
    
    $ret = array();
    
    $ret['instrument_id'] = $instrument_id;
    $ret['operation'] = $operation;
    $ret['instrument_value_id'] = $instrument_value_id;
    $ret['exchange_rate_id'] = $exchange_rate_id;
    $ret['position'] = $position;
    $ret['amount'] = $amount;
    $ret['value'] = $value;
    $ret['operation_value'] = $operation_value;
    
    return $ret;
  }
  
  private function load_from_file($portfolio_id,$file,$extension='') {
  
    $ret = false;
  
    if (file_exists($file)) {
  
      $ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));
      if ($ext=='') {
        $ext = $extension;
      }
      $data = false;
  
      switch ($ext) {
        case 'xls': $data = $this->global_model->get_xls_data($file); break;
        case 'xlsx': $data = $this->global_model->get_xlsx_data($file); break;
        default: $data = $this->global_model->get_xls_data($file);
      }
  
      if (is_array($data) && sizeOf($data)==1) {
      
        $portfolio = $this->global_model->get_table_data_as_class('portfolios',null,array('portfolio_id'=>$portfolio_id),null,1);
        $instruments = $this->global_model->get_table_data_as_class('instruments',null,null,array('created'));
        $currency = $this->global_model->get_table_data_as_class('currency');
        $old_history = $this->global_model->get_table_data_as_class('portfolio_history',null,array('portfolio_id'=>$portfolio_id),array('created desc'),1);
        
        $param_ident = 'PX_LAST_ACTUAL';
        $param_def = $this->global_model->get_table_data_as_class('params',null,array('ident'=>$param_ident),null,1);
        
        $instruments_desc = $this->global_model->get_table_desc('instruments');
        $history_parts_desc = $this->global_model->get_table_desc('portfolio_history_parts');
        
        $sheet = $data[0];
        if (is_array($sheet) && sizeOf($sheet)>0 &&
            is_array($portfolio) && sizeOf($portfolio)>0 &&
            is_array($param_def) && sizeOf($param_def)>0) {
        
          $portfolio = $portfolio[0];
          $param_def = $param_def[0];
          
          $parts = false;
          $rest = null;
          $value = null;
          $operation = (isset($history_parts_desc['operation']))?$history_parts_desc['operation']->default:'buy';
          
          $old_history_parts = false;
          if (is_array($old_history) && sizeOf($old_history)>0) {
            
            $old_history = $old_history[0];
            $old_history_parts = $this->global_model->get_query_data_as_class(sprintf('select instrument_id, operation, position, value,
                                                                                              max(instrument_value_id) as instrument_value_id, 
                                                                                              max(exchange_rate_id) as exchange_rate_id,
                                                                                              sum(amount) as amount
                                                                                         from (select instrument_id, "keep" as operation, position, value,
                                                                                                      instrument_value_id, exchange_rate_id, amount
                                                                                                 from portfolio_history_parts
                                                                                                where portfolio_history_id=%s and operation in ("keep","buy")) t1
                                                                                        group by 1,2,3,4 ',
                                                                                       $old_history->portfolio_history_id));
          }
          
          for ($r=3;$r<sizeOf($sheet);++$r) {
            
            $c = 0;
            $newi = false;
            
            $ident = strtoupper($sheet[$r][$c++]->data);
            $name = $sheet[$r][$c++]->data;
            $position = (strtolower($sheet[$r][$c++]->data)=='лонг')?'long':'short';
            $amount = abs((float)$sheet[$r][$c++]->data);
            $s = $sheet[$r][$c++]->data;
            $operation_value = (float)$s;
            $size = null;
            if (!is_null($s)) {
              $pos = strpos($s,'.');
              $s = substr($s,$pos+1);
              if (trim($s)!='') {
                $size = strlen($s);
              } 
            }
            $total = (float)$sheet[$r][$c++]->data;
            $px_last_actual = (float)$sheet[$r][$c++]->data;
            $c++; $c++;
            
            $currency_id = $portfolio->currency_id;
            $cur = $this->get_currency($currency,$sheet[$r][$c++]->data);
            if ($cur) {
              $currency_id = $cur->currency_id;
            }
            
            $param = $param_def;
            $param_ident = $sheet[$r][$c++]->data;
            if (trim($param_ident)!='') {
              $newi = true;
              $param_ident = strtoupper($param_ident);
              $pm = $this->global_model->get_table_data_as_class('params',null,array('ident'=>$param_ident),null,1);
              if ($pm && sizeOf($pm)>0) {
                $param = $pm[0];
              } else {
                $pm['name'] = $param_ident;
                $pm['ident'] = $param_ident;
                $ri = $this->global_model->insert('params',$pm);
                if ($ri) {
                  $pm = $this->global_model->get_table_data_as_class('params',null,array('ident'=>$param_ident),null,1);
                  if ($pm && sizeOf($pm)>0) {
                    $param = $pm[0];
                  }
                }
              }
            } else {
              $param = null;
            }
            
            $instrument = $this->get_instrument($instruments,$instruments_desc,$param,$ident,$currency_id,$name,$size,$newi);
            if (($px_last_actual>0) && ($instrument) && isset($param)) {

              $instrument_value['param_id'] = $param->param_id;
              $instrument_value['instrument_id'] = $instrument->instrument_id;
              $instrument_value['to_date'] = date('Y-m-d H:i:s');
              $instrument_value['currency_id'] = ($currency_id!=$portfolio->currency_id)?$portfolio->currency_id:null;
              $instrument_value['value_number'] = $px_last_actual;

              $flag = $this->global_model->insert('instrument_values',$instrument_value);
              if ($flag) {

                $instrument_value_id = $this->global_model->last_insert_id();
                if ($instrument_value_id && ($amount>0.0)) {

                  $current_value = $px_last_actual;

                  $exchange_rate_id = null;
                  if ($instrument->currency_id!=$portfolio->currency_id) {
                    // does it need to convert value to portfolio currency?
                  }

                  if (is_array($old_history_parts) && sizeOf($old_history_parts)>0) {

                    $exists = false;

                   /* foreach($old_history_parts as $ohp) {

                      if (($ohp->instrument_id==$instrument->instrument_id) &&
                          in_array($ohp->operation,array('buy','keep'))) {

                        $exists = true;
                        $o_value = ($ohp->operation='buy')?$operation_value:null;

                        if ($ohp->amount==$amount) {

                          $parts[] = $this->make_part($instrument->instrument_id,'keep',$ohp->instrument_value_id,$ohp->exchange_rate_id,$ohp->position,$ohp->amount,$current_value);

                        } elseif ($ohp->amount<$amount) {

                          $parts[] = $this->make_part($instrument->instrument_id,'keep',$ohp->instrument_value_id,$ohp->exchange_rate_id,$ohp->position,$ohp->amount,$current_value);
                          $parts[] = $this->make_part($instrument->instrument_id,'buy',$instrument_value_id,$exchange_rate_id,$position,$amount-$ohp->amount,$current_value,$o_value);

                        } elseif ($ohp->amount>$amount) {

                          $parts[] = $this->make_part($instrument->instrument_id,'sell',$ohp->instrument_value_id,$ohp->exchange_rate_id,$ohp->position,-($ohp->amount-$amount),$current_value,$o_value);
                          $parts[] = $this->make_part($instrument->instrument_id,'keep',$ohp->instrument_value_id,$ohp->exchange_rate_id,$ohp->position,$amount,$current_value);
                        }
                      } 
                    } */

                    if (!$exists) {

                      $parts[] = $this->make_part($instrument->instrument_id,$operation,$instrument_value_id,$exchange_rate_id,$position,$amount,$current_value,$operation_value);
                    }

                  } else {

                    $parts[] = $this->make_part($instrument->instrument_id,$operation,$instrument_value_id,$exchange_rate_id,$position,$amount,$current_value,$operation_value);
                  }
                }
              }
            } else {

              if (in_array($name,array('Денежные средства'))) {
                if (!$parts) {
                  $parts = array();
                }
                $rest = $total;
              } elseif (in_array($name,array('Текушая стоимость портфеля:'))) {
                if (!$parts) {
                  $parts = array();
                }                
                $value = $total;
              }
            }
          }
            
          if (is_array($parts)) {
            
            $new_history['portfolio_id'] = $portfolio_id;
            $new_history['method'] = 'manual';
            $new_history['rest'] = $rest;
            if (!is_null($value) && !is_null($rest)) {
              $new_history['value'] = $value - $rest;
            }
            
            $flag = $this->global_model->insert('portfolio_history',$new_history);
            if ($flag) {
              
              $history_id = $this->global_model->last_insert_id();
              if ($history_id) {
                
                if (is_array($old_history_parts) && sizeOf($old_history_parts)>0) {
                  
                /*  foreach ($old_history_parts as $ohp) {
                    
                    $exists = false;
                    
                    foreach ($parts as $p) {
                      
                      $exists = ($ohp->instrument_id==$p['instrument_id'] && $ohp->amount==$p['amount'])?true:false;
                      if ($exists) {
                        break;
                      }
                    }
                    
                    if (!$exists) {
                      $parts[] = $this->make_part($ohp->instrument_id,'sell',$ohp->instrument_value_id,$ohp->exchange_rate_id,$ohp->position,$ohp->amount,$ohp->value,null);
                    }
                  } */
                }
                
                $counter = 1;
                foreach($parts as $p) {
                  
                  $p['portfolio_history_id'] = $history_id;
                  $p['priority'] = $counter++; 
                  $this->global_model->insert('portfolio_history_parts',$p);
                }
              }
            }
          }
        }
        
        $ret = true;
      }
    }
    
    return $ret;
  }
  
  function import_xls() {
    
    $portfolio_id = (int)$this->uri->segment(4);
    
    if (isset($_REQUEST['submit'])) {
    
      if($_FILES['_file']['error']==0) {
    
        error_reporting(E_ALL ^ E_NOTICE);
    
        $file = $_FILES['_file']['tmp_name'];
        $ext = explode('.',$_FILES['_file']['name']);
        $ext = end($ext);
    
        $ret = $this->load_from_file($portfolio_id,$file,$ext);
        if ($ret) {
          $_SESSION['admin']->is_update = 1;
        }
      }
    
      return redirect($this->uri->segment(1).'/'.$this->page_name);
    
    } else { 
    
      return $this->load->view('backoffice_'.$this->page_name.'_import_xls','',true);
    }
  }
  
  function recalc_portfolio_value($porftolio_id) {

    $r = $this->global_model->execute(sprintf('update portfolio_history set value=null
                                                where portfolio_id=%d
                                                  and created>date_add(current_timestamp,interval -1 month);',$porftolio_id));
    if ($r) {

      $date = date('Y-m-d H:i:s');
      $sql = sprintf('select t1.portfolio_history_id, ph.method, 
                             cast(t1.created as date) as created,  
                             (case when t2.operation_total is not null then t2.operation_total else 0 end) as operation_total, ph.rest, 
                             (case when t2.total is not null then t2.total else 0 end) as total,
                             (case when t2.revenue_loss is not null then t2.revenue_loss else 0 end) as revenue_loss,
                             ((case when t2.operation_total is not null then t2.operation_total else 0 end)+ph.rest+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as total_value,
                             ((case when t2.operation_total is not null then t2.operation_total else 0 end)+(case when t2.revenue_loss is not null then t2.revenue_loss else 0 end)) as value
                        from (select max(ph.portfolio_history_id) as portfolio_history_id, max(ph.created) as created
                                from portfolio_history ph
                                join (select cast(iv.to_date as date) as to_date
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
                                       group by 1) t2 on t2.to_date=cast(ph.created as date)
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
                     $porftolio_id,$this->global_model->quote($date));

      $data = $this->global_model->get_query_data($sql);
      if (is_array($data) && sizeOf($data)>0) {

        foreach ($data as $d) {

          $history['value'] = $d['value'];
          $this->global_model->update('portfolio_history',$history,array('portfolio_history_id'=>$d['portfolio_history_id']));
        }
      }

    }
  }
  
  function recalc() {
    
    $portfolio_id = (int)$this->uri->segment(4);
    if (isset($portfolio_id) && $portfolio_id>0) {
      
      $portfolio = $this->global_model->get_table_data_as_class('portfolios',null,array('portfolio_id'=>$portfolio_id));
      if (is_array($portfolio) && sizeOf($portfolio)>0) {
        $portfolio = $portfolio[0];
      }
      $portfolio_history_id = false;
      $portfolio_history = $this->global_model->get_query_data_as_class(sprintf('select ph.portfolio_history_id, ph.rest
                                                                                   from portfolio_history ph
                                                                                   left join (select portfolio_history_id, count(*) as cnt
                                                                                                from portfolio_history_parts
                                                                                               where portfolio_history_id in (select portfolio_history_id 
                                                                                                                                from portfolio_history
                                                                                                                               where portfolio_id=%d)
                                                                                               group by 1) t on t.portfolio_history_id=ph.portfolio_history_id
                                                                                  where ph.portfolio_id=%d
                                                                                   /* and t.cnt>0 */
                                                                                  order by ph.created desc
                                                                                  limit 5',
                                                                                 $portfolio_id,$portfolio_id));
      if (is_array($portfolio_history) && sizeOf($portfolio_history)>0) {
      
        $portfolio_history = $portfolio_history[0];
        $portfolio_history_id = $portfolio_history->portfolio_history_id;
      }
      
      //$param_ident = 'PX_LAST_ACTUAL';
      //$param = $this->global_model->get_query_data_as_class(sprintf('select * from params where ident=%s limit 1', $this->global_model->quote($param_ident)));
      
      if ($portfolio_history_id)/* && is_array($param) && sizeOf($param)>0)*/ {
      
       // $param = $param[0];
        $old_history_parts = $this->global_model->get_query_data_as_class(sprintf('select t.*, i.currency_id, iv.param_id
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
                                                                                     join instrument_values iv on iv.instrument_value_id=t.instrument_value_id
                                                                                    where iv.locked is null',
                                                                                   $portfolio_history_id));
        if (is_array($old_history_parts) && sizeOf($old_history_parts)>0) {
      
          $created = date('Y-m-d H:i:s');
          $rc = $this->global_model->get_query_data_as_class(sprintf('select max(to_date) as date 
                                                                        from instrument_values 
                                                                       where instrument_id in (select instrument_id
                                                                                                 from portfolio_history_parts
                                                                                                where portfolio_history_id=%d)
                                                                         and locked is null                        
                                                                       order by 1 desc 
                                                                       limit 1',$portfolio_history_id));
          if ($rc) {
            $created = $rc[0]->date;
          }
                  
          $new_history['portfolio_id'] = $portfolio_id;
          $new_history['created'] = $created;
          $new_history['method'] = 'auto';
          $new_history['rest'] = $portfolio_history->rest;
      
          $flag = $this->global_model->insert('portfolio_history',$new_history);
          if ($flag) {
      
            $history_id = $this->global_model->last_insert_id();
            if ($history_id) {
      
              $counter = 1;
              foreach ($old_history_parts as $ohp) {
      
                $instrument_value_id = $ohp->instrument_value_id;
                $value = $ohp->value;
                $currency_id = $ohp->currency_id;
                $size = false;
                $instrument_value = $this->global_model->get_query_data_as_class(sprintf('select iv.instrument_value_id, iv.value_number, iv.currency_id, ip.size
                                                                                            from instrument_values iv
                                                                                            join instrument_params ip on ip.param_id=iv.param_id and ip.instrument_id=iv.instrument_id
                                                                                           where iv.param_id=%d
                                                                                             and iv.instrument_id=%d
                                                                                             and iv.locked is null
                                                                                           order by iv.created desc
                                                                                           limit 1',
                                                                                          $ohp->param_id,$ohp->instrument_id));
                if (is_array($instrument_value) && sizeOf($instrument_value)>0) {
          
                  $instrument_value = $instrument_value[0];
                  $instrument_value_id = $instrument_value->instrument_value_id;
                  $value = $instrument_value->value_number;
                  $currency_id = isset($instrument_value->currency_id)?$instrument_value->currency_id:$currency_id;
                  $size = $instrument_value->size;
                }
          
                $exchange_rate_id = $ohp->exchange_rate_id;
                $value = $this->global_model->convert_value($value,$created,$currency_id,$portfolio->currency_id,$exchange_rate_id);
                if ($size) {
                  $value = round($value,$size);
                }
          
                $part['portfolio_history_id'] = $history_id;
                $part['instrument_id'] = $ohp->instrument_id;
                $part['operation'] = $ohp->operation;
                $part['instrument_value_id'] = $instrument_value_id;
                $part['exchange_rate_id'] = $exchange_rate_id;
                $part['position'] = $ohp->position;
                $part['amount'] = $ohp->amount;
                $part['value'] = $value;
                $part['operation_value'] = $ohp->operation_value;
                $part['priority'] = $counter++;
          
                $this->global_model->insert('portfolio_history_parts',$part);
              }
            }
          }
        }
        $this->recalc_portfolio_value($portfolio_id);
      }
      return redirect($this->uri->segment(1).'/'.$this->page_name.'/portfolio/'.$portfolio_id);
    }
  }
  
}
?>