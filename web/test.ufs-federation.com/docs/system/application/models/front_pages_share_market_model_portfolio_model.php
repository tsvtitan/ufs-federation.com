<?php
class front_pages_share_market_model_portfolio_model extends Model{

	function front_pages_share_market_model_portfolio_model()
	{
		parent::Model();
	}
    
    function one($display='')
    {
      $ret='';
        
            $sql=$this->db->query('select * 
                                   from `share_market_model_portfolio`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `display`="'.$display.'"
                                   order by `start_building` desc
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'share_market_model_portfolio');
                         $res=$this->global_model->date_format($res,'__timestamp__','start_timestamp','start_building');
                         $res=$this->global_model->date_format($res,'d #_n_# Y','start_building','start_building');
                        /*********************************/
                         
                     $res=self::calc_yield($res);
                     
                     $res->initial_amount=number_format($res->initial_amount,2,',',' ');
                     $res->current_value=number_format($res->current_value,2,',',' ');
                        
                     $res->graph[1]=self::graph($res->id,strtotime('-1 month'));
                     $res->graph[2]=self::graph($res->id,strtotime('-3 month'));
                     $res->graph[3]=self::graph($res->id,strtotime('-1 years'));
                     $res->graph[4]=self::graph($res->id,strtotime('-5 years'));
                     $res->graph[5]=self::graph($res->id);
                         
                    $ret=$res;
                }
                
      return $ret;
    }
    
    private function graph_old($portfolio_id=0,$time=0)
    {
      $ret=array();
      
        $where_time='and `date` >= "'.date('Y-m-d H:i:s',$time).'"';
        
            $sql=$this->db->query('select `date`, `transaction_amount` 
                                   from `share_market_model_portfolio_transaction`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `portfolio_id`="'.$portfolio_id.'"
                                   and `is_hide`="no"
                                   and `date` <= now()
                                   '.$where_time.'
                                   order by `date` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->date_format($res,'__timestamp__','timestamp','date');
                         $res=$this->global_model->date_format($res,'j.m.Y','date_limit','date');
                         $res=$this->global_model->date_format($res,'j.m.y','date','date');
                        /*********************************/
                         
                    $ret=array_reverse($res);
                }
                
      return $ret;
    }
    
    private function graph($portfolio_id=0,$time=0)
    {
      $ret=array();
      $res=array();

        $where_time='and `timestamp` >= "'.date('Y-m-d H:i:s',$time).'"';
        
        $sql_time = $this->db->query('select DISTINCT  DATE_FORMAT(`timestamp`, "%Y-%m-%d") as `dt`  
                                   from `share_market_model_portfolio_composition`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `portfolio_id`="'.$portfolio_id.'"
                                   and `timestamp` <= now()
                                   '.$where_time.'
                                   order by `timestamp` desc;');

        if($sql_time->num_rows()>0){
        	$res_time = $sql_time->result();
        	foreach($res_time as $item){
        		$the_time = date('Y-m-d',strtotime($item->dt));
        		$the_time.= ' 23:59:59';
        		$where_time_res = 'and `timestamp` <= "'.$the_time.'"';
	            $sql = $this->db->query('select  SUM(`cost`) as `cost_sum`, `timestamp` 
	                                   from `share_market_model_portfolio_composition`
	                                   where `lang`="'.$this->site_lang.'" 
	                                   and `portfolio_id`="'.$portfolio_id.'"
	                                   '.$where_time_res.'
	                                   '.$where_time.'
	                                   ;');

	                if($sql->num_rows()>0){
	                    $res_=$sql->row();
	                    $res_->timestamp = $the_time;
	                    $res[] = $res_;

	                }        		
        	}
        }
        if(count($res)>0){
   	                        
	                        
	                         $res=$this->global_model->date_format($res,'j.m.Y','date_limit','timestamp');
	                         $res=$this->global_model->date_format($res,'j.m.y','date','timestamp'); 
	                         $res=$this->global_model->date_format($res,'__timestamp__','timestamp','timestamp');
        }                    
	                         
	                    $ret=array_reverse($res);     	
            /*$sql=$this->db->query('select  `cost` as `cost_sum`, `timestamp` 
                                   from `share_market_model_portfolio_composition`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `portfolio_id`="'.$portfolio_id.'"
                                   and `timestamp` <= now()
                                   '.$where_time.'
                                   order by `timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();
                        
                        
                         $res=$this->global_model->date_format($res,'j.m.Y','date_limit','timestamp');
                         $res=$this->global_model->date_format($res,'j.m.y','date','timestamp'); 
                         $res=$this->global_model->date_format($res,'__timestamp__','timestamp','timestamp');
                       
                         
                    $ret=array_reverse($res);
                }*/
      //print_r($ret);          
      return $ret;
    }    
    
    private function last_transaction($portfolio_id=0,$limit=1)
    {
      $ret=array();
        
            $sql=$this->db->query('select * 
                                   from `share_market_model_portfolio_transaction`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `portfolio_id`="'.$portfolio_id.'"
                                   and `is_hide`="no"
                                   order by `sort_id` desc
                                   limit '.$limit.';');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'share_market_model_portfolio_transaction');
                         $res=$this->global_model->date_format($res,'d.m.Y','date','date');
                        /*********************************/
                         
                       for($i=0;$i<count($res);$i++)
                       {
                           $res[$i]->quantity=number_format($res[$i]->quantity,0,',',' ');
                           $res[$i]->transaction_amount=number_format($res[$i]->transaction_amount,2,',',' ');
                       }
                         
                    $ret=$res;
                }
                
      return $ret;
    }
    
    private function structure($portfolio_id=0)
    {
      $ret=array();
        
            $sql=$this->db->query('select `name`, `proportion_currency`
                                   from `share_market_model_portfolio_structure`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `portfolio_id`="'.$portfolio_id.'"
                                   order by `timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'share_market_model_portfolio_structure');
                         $res=$this->global_model->date_format($res,'d.m.Y','date','date');
                        /*********************************/
                       
                       $sum=0;
                       for($i=0;$i<count($res);$i++)
                       {
                           $sum+=$res[$i]->proportion_currency;
                       }
                         
                       for($i=0;$i<count($res);$i++)
                       {
                         if($sum>0){
                           $res[$i]->procent=$res[$i]->proportion_currency/$sum*100;
                           $res[$i]->procent_print=number_format($res[$i]->procent,2,',',' ');
                         }else{
                           $res[$i]->procent=0.00;
                           $res[$i]->procent_print=0.00;
                         }
                       }
                       
                       
                         
                    $ret=$res;
                }

                
      return $ret;
    }
    
    private function composition($portfolio_id=0)
    {
      $ret['last_date']='';
      $ret['sum']=0;
      $ret['data']=array();
        
            $sql=$this->db->query('select *
                                   from `share_market_model_portfolio_composition`
                                   where `lang`="'.$this->site_lang.'" 
                                   and `portfolio_id`="'.$portfolio_id.'"
                                   order by `timestamp` asc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'share_market_model_portfolio_composition');
                         $res=$this->global_model->set_numbers($res);
                         $res=$this->global_model->rand_css_class($res,'ntd');
                         $res=$this->global_model->date_format($res,'j #_n_# Y','date');
                         $res=$this->global_model->date_format($res,'__timestamp__');
                         $res=$this->global_model->date_format($res,'d.m.Y','maturity_date','maturity_date');
                        /*********************************/
                     
                    $last_date->timestamp = '';
                    $last_date->date      = '';
                    $sum=0;
                    
                    for($i=0;$i<count($res);$i++)
                    {
                        $last_date->date=($last_date->timestamp<$res[$i]->timestamp)?$res[$i]->date:$last_date->date;
                        $last_date->timestamp=($last_date->timestamp<$res[$i]->timestamp)?$res[$i]->timestamp:$last_date->timestamp;
                        
                        $sum+=$res[$i]->cost;
                        
                        if($res[$i]->price_starting>$res[$i]->price_current){
                            $res[$i]->price_stats='down';
                        }elseif($res[$i]->price_starting<$res[$i]->price_current){
                            $res[$i]->price_stats='up';
                        }else{
                            $res[$i]->price_stats='normal';
                        }
                        
                        $res[$i]->price_starting=number_format($res[$i]->price_starting,2,',',' ');
                        $res[$i]->price_current=number_format($res[$i]->price_current,2,',',' ');
                        $res[$i]->nominal_volume=number_format($res[$i]->nominal_volume,2,',',' ');
                        $res[$i]->cost=number_format($res[$i]->cost,2,',',' ');
                    }
   
                    $ret['last_date'] = $last_date;
                    $ret['sum']       = number_format($sum,2,',',' ');
                    $ret['data']      = $res;

                }

                
      return $ret;
    }
    
    function view($ret,$display='')
    {            
        //$data['data']=self::one('рынок акций');
        $data['data']=self::one($display);
        if(isset($data['data']->id)){
          $data['transaction'] = self::last_transaction($data['data']->id,4);
          $data['structure']   = self::structure($data['data']->id);
          $data['composition'] = self::composition($data['data']->id);
        }
            $ret->content=$this->load->view('view_pages_share_market_model_portfolio',$data,true); 
 
            $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_portfolio',$data,true);      
            $this->data['body_content_sub']=$this->load->view('body_content_sub_share_market_portfolio',$data,true);        
        return $ret;
    }
    
    private function calc_yield($ret)
    {        
        if(!is_array($ret)){	
           $ret=array($ret);
           $not_array=true;
        }
        
          for($i=0;$i<count($ret);$i++)
          {
            $days=(time() - $ret[$i]->start_timestamp)/(60*60*24);
            $amount=($ret[$i]->current_value - $ret[$i]->initial_amount);
            
            if($days!=0 and $amount!=0 and $ret[$i]->initial_amount!=0){ 
                $ret[$i]->yield=($amount / $ret[$i]->initial_amount)/$days*365;
                $ret[$i]->yield=number_format($ret[$i]->yield,2,'.','');
            }else{
                $ret[$i]->yield=0.00;
            }
          }

         if(isset($not_array)){
             $ret=reset($ret);
         }
        
      return $ret;        
    }

}
?>
