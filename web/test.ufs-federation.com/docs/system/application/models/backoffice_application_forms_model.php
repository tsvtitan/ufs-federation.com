<?php
class backoffice_application_forms_model extends Model{

	function backoffice_application_forms_model()
	{
		parent::Model();
        
        if(isset($_REQUEST['form_search']))
        {
            $_SESSION['search']=mysql_string($_REQUEST['search']);
        }else{
          if(!isset($_SESSION['search'])){
            $_SESSION['search']='';
          }
        }
	}
    
	function get_next_num() {
	
		$ret = 1;
		 
		$res = $this->db->query('select max(num) as num from application_forms');
		if($res->num_rows()>0){
			$res = $res->result();
			$ret = $res[0]->num;
			$ret++;
		}
	
		return $ret;
	}
	
    function add()
    {        
        if(isset($_REQUEST['submit'])){

        	$ret=$this->global_model->adjustment_of_request_array($_REQUEST);
        	
        	$ret['application_form_id']=$_REQUEST['application_form_id'];
        	$ret['num']=$this->get_next_num();
        	
        	$ret['lang']=$this->site_lang;
        	if (!isset($ret['citizen'])) {
        		$ret['citizen']=null;
        	}
        	if (trim($ret['residence_region_id'])=='') {
        		$ret['residence_region_id']=null;
        	}
        	if (!isset($ret['post_as_residence'])) {
        		$ret['post_as_residence']=null;
        	}
        	if (trim($ret['post_region_id'])=='') {
        		$ret['post_region_id']=null;
        	}
        	if (trim($ret['bank_region_id'])=='') {
        		$ret['bank_region_id']=null;
        	}
        	if (!isset($ret['out_bank'])) {
        		$ret['out_bank']=null;
        	}
        	if (!isset($ret['public_face'])) {
        		$ret['public_face']=null;
        	}
        	if (!isset($ret['official'])) {
        		$ret['official']=null;
        	}
        	if (!isset($ret['laundering'])) {
        		$ret['laundering']=null;
        	}
        	if (!isset($ret['forts'])) {
        		$ret['forts']=null;
        	}
        	if (!isset($ret['special_account'])) {
        		$ret['special_account']=null;
        	}
        	if (!isset($ret['internet_trading'])) {
        		$ret['internet_trading']=null;
        	}
        	if (!isset($ret['orders_in_office'])) {
        		$ret['orders_in_office']=null;
        	}
        	if (!isset($ret['orders_by_mail'])) {
        		$ret['orders_by_mail']=null;
        	}
        	if (!isset($ret['orders_by_phone'])) {
        		$ret['orders_by_phone']=null;
        	}
        	if (!isset($ret['orders_by_email'])) {
        		$ret['orders_by_email']=null;
        	}
        	if (trim($ret['delivery'])=='') {
        		$ret['delivery']=null;
        	}
        	if (!isset($ret['agree'])) {
        		$ret['agree']=null;
        	}
        	 
        	$ret['created']=$this->global_model->date_arr_to_timstamp($_REQUEST['created']);
        	$ret['birth_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['birth_date']);
        	$ret['passport_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['passport_date']);
            
        	$this->db->insert($this->page_name,$ret);
        	
          return redirect($this->uri->segment(1).'/'.$this->page_name);

        } else {
            
        	  $res=new stdClass();
        	  $res->num=$this->get_next_num();
        	  $res->application_form_id=$this->global_model->get_unique_id();
        	  $res->delivery='';

            $res=$this->global_model->adjustment_of_results($res,$this->page_name);
            
            $content['created']=$this->global_model->date_arr('',2,2013);
            $content['birth_date']=$this->global_model->date_arr('',1,1930);
            $content['passport_date']=$this->global_model->date_arr('',1,1940);

            $regions = $this->global_model->get_query_data('select t.* from (select region_id, name, def, '.
                                                           'case when priority is null then 99999 else priority end as priority '.
                                                           'from regions) t order by t.priority, t.name');
            $regions = $this->global_model->data_to_class($regions);
            $content['residence_regions'] = $regions;
            $content['post_regions'] = $regions;
            $content['bank_regions'] = $regions;
            
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
        	
        	
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);

            $id=$_REQUEST['application_form_id'];
            
            $sql=$this->db->query('select * from `'.$this->page_name.'` where `application_form_id`="'.$id.'";');
            
            if($sql->num_rows()>0){	
               
               $res=$sql->result();
               $res=$res[0];
               
        	     $ret['lang']=$this->site_lang;
        	     if (!isset($ret['citizen'])) {
        	       $ret['citizen']=null;
        	     }
        	     if (trim($ret['residence_region_id'])=='') {
        	       $ret['residence_region_id']=null;
        	     }
        	     if (!isset($ret['post_as_residence'])) {
        	     	 $ret['post_as_residence']=null;
        	     }
        	     if (trim($ret['post_region_id'])=='') {
        	     	 $ret['post_region_id']=null;
        	     }
        	     if (trim($ret['bank_region_id'])=='') {
        	     	 $ret['bank_region_id']=null;
        	     }
        	     if (!isset($ret['out_bank'])) {
        	     	 $ret['out_bank']=null;
        	     }
        	     if (!isset($ret['public_face'])) {
        	     	$ret['public_face']=null;
        	     }
        	     if (!isset($ret['official'])) {
        	     	$ret['official']=null;
        	     }
        	     if (!isset($ret['laundering'])) {
        	     	$ret['laundering']=null;
        	     }
        	     if (!isset($ret['forts'])) {
        	     	$ret['forts']=null;
        	     }
        	     if (!isset($ret['special_account'])) {
        	     	$ret['special_account']=null;
        	     }
        	     if (!isset($ret['internet_trading'])) {
        	     	$ret['internet_trading']=null;
        	     }
        	     if (!isset($ret['orders_in_office'])) {
        	     	$ret['orders_in_office']=null;
        	     }
        	     if (!isset($ret['orders_by_mail'])) {
        	     	$ret['orders_by_mail']=null;
        	     }
        	     if (!isset($ret['orders_by_phone'])) {
        	     	$ret['orders_by_phone']=null;
        	     }
        	     if (!isset($ret['orders_by_email'])) {
        	     	$ret['orders_by_email']=null;
        	     }
        	     if (trim($ret['delivery'])=='') {
        	     	$ret['delivery']=null;
        	     }
        	     if (!isset($ret['agree'])) {
        	     	$ret['agree']=null;
        	     }
        	     
        	     $ret['created']=$this->global_model->date_arr_to_timstamp($_REQUEST['created']);
        	     $ret['birth_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['birth_date']);
        	     $ret['passport_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['passport_date']);
               
               $sql_set=$this->global_model->create_set_sql_string($ret);
                           
               $this->db->query('update `'.$this->page_name.'` set 
                                '.$sql_set.' 
                                 where `application_form_id`="'.$id.'";');

               $_SESSION['admin']->is_update=1;
            }   

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        } else {

        	$id=$this->uri->segment(4);

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `application_form_id`="'.$id.'";');
                
             if($sql->num_rows()>0){	
               
               $res=$sql->result();
               $res=$res[0];
               
               $res=$this->global_model->adjustment_of_results($res,$this->page_name);
               $res=$this->global_model->date_format($res,'Y|m|d|H|i|s','created','created');
               $res=$this->global_model->date_format($res,'Y|m|d','birth_date','birth_date');
               $res=$this->global_model->date_format($res,'Y|m|d','passport_date','passport_date');
                
               $date=new stdClass();
               list($date->year,$date->month,$date->day,$date->hour,$date->minute,$date->seconds)=explode('|',$res->created);
               $content['created']=$this->global_model->date_arr($date,2,2013);
               
               $date=new stdClass();
               list($date->year,$date->month,$date->day)=explode('|',$res->birth_date);
               $content['birth_date']=$this->global_model->date_arr($date,1,1930);
                
               $date=new stdClass();
               list($date->year,$date->month,$date->day)=explode('|',$res->passport_date);
               $content['passport_date']=$this->global_model->date_arr($date,1,1940);
               
               $regions = $this->global_model->get_query_data('select t.* from (select region_id, name, def, '.
                                                              'case when priority is null then 99999 else priority end as priority '.
                                                              'from regions) t order by t.priority, t.name');
               $regions = $this->global_model->data_to_class($regions);
               $content['residence_regions'] = $regions;
               $content['post_regions'] = $regions;
               $content['bank_regions'] = $regions;
                
               $content['data']=$res;
             } else {

               $res             = new stdClass();
               $res->login='';
               $res->pass='';
               $res             = $this->global_model->adjustment_of_results($res,$this->page_name);

               $content['created']=$this->global_model->date_arr('',2,2011);
               $content['birth_date']=$this->global_model->date_arr('',1,1930);
               $content['passport_date']=$this->global_model->date_arr('',1,1940);
               
               $regions = $this->global_model->get_query_data('select t.* from (select region_id, name, def, '.
                                                              'case when priority is null then 99999 else priority end as priority '.
                                                              'from regions) t order by t.priority, t.name');
               $regions = $this->global_model->data_to_class($regions);
               $content['residence_regions'] = $regions;
               $content['post_regions'] = $regions;
               $content['bank_regions'] = $regions;
                
               $content['data'] = $res;
            }
                
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        $id=$this->uri->segment(4);
        
        $this->db->query('delete from `'.$this->page_name.'` where `application_form_id`="'.$id.'";');

  		  return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    
    function view()
    { 
      $content['data']='';
        
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where='';
        
        if(!empty($_SESSION['search']))
        {
           $where.=' where last_name like "%'.$_SESSION['search'].'%" and lang="'.$this->site_lang.'" ';
        }  else {
           $where.=' where lang="'.$this->site_lang.'" ';
        }
        
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->result();
            $res_r=$res_r[0];

            /* pager */ 
            $show=20;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(5)==0)?1:(int)$this->uri->segment(5);
            $param['url']='/view';

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
            $vals='*';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'d.m.Y H:i:s','created','created');
                         $res=$this->global_model->set_sort_elemets($res,'application_form_id');
                         $res=$this->global_model->rand_css_class($res);
                        /*********************************/
                         
                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    
    protected function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'` as `t`
              '.$where.'
              order by `t`.`created` desc  
              '.$limit.';';
        
        return $sql;
    }
    
    
}
?>
