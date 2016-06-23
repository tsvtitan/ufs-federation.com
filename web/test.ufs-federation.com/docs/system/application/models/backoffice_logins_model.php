<?php
class backoffice_logins_model extends Model{

	function backoffice_logins_model()
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
    
    
    function add()
    {        
        if(isset($_REQUEST['submit'])){

        	$ret=$this->global_model->adjustment_of_request_array($_REQUEST);
        	
        	$ret['pass']=md5($ret['pass']);
        	$ret['date_create']=$this->global_model->date_arr_to_timstamp($_REQUEST['date_create']);
            
            $tmp=array();
            if (isset($ret['role'])) {
              $tmp=$ret['role'];
              unset($ret['role']);
            }
              
        	$this->db->insert($this->page_name,$ret);
        	
        	$id=$this->db->insert_id();

        	if (isset($tmp) && is_array($tmp)) {
        		foreach ($tmp as $role_id) {
        			$this->db->query('insert into `login_roles` (`login_id`,`role_id`) values ("'.$id.'","'.$role_id.'");');
        		}
        	}
        	 
            return redirect($this->uri->segment(1).'/'.$this->page_name);

        }else{
            
        	$res=new stdClass();
            $res->login='';
            $res->pass='';
            $res->role_array=array();
            $res->role_multi_sel=array();
            
            $sql=$this->db->query('select * from `roles` order by `id`;');
            if($sql->num_rows()>0){
              $ret=$sql->result();
              foreach ($ret as $r) {
              	$res->role_array[$r->id]=$r->role;
              }
            }
            
            $res=$this->global_model->adjustment_of_results($res,$this->page_name);
            
            $content['role_array']=$res->role_array;
            $content['role_multi_sel']=$res->role_multi_sel;
            
            $content['date']=$this->global_model->date_arr('',2,2011);
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
        	
        	
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);

            $id=(int)$_REQUEST['id'];
            
            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.$id.'";');
            
            if($sql->num_rows()>0){	
               
               $res=$sql->result();
               $res=$res[0];
               
               if ($res->pass!=$ret['pass']) {
               	 $ret['pass']=md5($ret['pass']);
               }
               $ret['date_create']=$this->global_model->date_arr_to_timstamp($_REQUEST['date_create']);
               
               $tmp=array();
               if (isset($ret['role'])) {
               	 $tmp=$ret['role'];
                 unset($ret['role']);
               }  
               
               $sql_set=$this->global_model->create_set_sql_string($ret);
                           
               $this->db->query('update `'.$this->page_name.'` set 
                                '.$sql_set.' 
                                 where `id`="'.$id.'";');

               $this->db->query('delete from `login_roles` where `login_id`="'.$id.'";');
               if (isset($tmp) && is_array($tmp)) {
               	 foreach ($tmp as $role_id) {
               	 	$this->db->query('insert into `login_roles` (`login_id`,`role_id`) values ("'.$id.'","'.$role_id.'");');
               	 }
               }
               
               $_SESSION['admin']->is_update=1;
            }   

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{

        	$id=(int)$this->uri->segment(4);

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.$id.'";');
                
             if($sql->num_rows()>0){	
               
               $res=$sql->result();
               $res=$res[0];
               
               $res->role_array=array();
               $res->role_multi_sel=array();
                
               $sql=$this->db->query('select * from `roles` order by `id`;');
               if($sql->num_rows()>0){
                 $ret=$sql->result();
                 foreach ($ret as $r) {
              	   $res->role_array[$r->id]=$r->role;
                 }
               }
               
               $sql=$this->db->query('select role_id from `login_roles` where `login_id`="'.$id.'";');
               if($sql->num_rows()>0){
                 $ret=$sql->result();
                 foreach ($ret as $r) {
              	   array_push($res->role_multi_sel,$r->role_id);
                 }
              }
            
               $res=$this->global_model->adjustment_of_results($res,$this->page_name);
               $res=$this->global_model->date_format($res,'Y|m|d|H|i|s','date_create','date_create');
               
               $date=new stdClass();
               list($date->year,$date->month,$date->day,$date->hour,$date->minute,$date->seconds)=explode('|',$res->date_create);
               
               $content['date']=$this->global_model->date_arr($date,2,2011);
               $content['data']=$res;
             } else {

               $res             = new stdClass();
               $res->login='';
               $res->pass='';
               $res             = $this->global_model->adjustment_of_results($res,$this->page_name);

               $content['date']=$this->global_model->date_arr('',2,2011);
               $content['data'] = $res;
            }
                
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        $id=(int)$this->uri->segment(4);
        
        $this->db->query('delete from `login_roles` where `login_id`="'.$id.'";');
        
        $this->db->query('delete from `'.$this->page_name.'` where `id`="'.$id.'";');

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
            $where.=' where `t`.`login` like "%'.$_SESSION['search'].'%"';
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
        
            $vals='`t`.`id`, `t`.`login`, `t`.`date_create`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'d.m.Y H:i:s','date_create','date_create');
                         $res=$this->global_model->set_sort_elemets($res);
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
              order by `t`.`date_create`, `t`.`login`  
              '.$limit.';';
        
        return $sql;
    }
    
    
}
?>
