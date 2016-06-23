<?php

class backoffice_mailing_sections_model extends Model{

	function backoffice_mailing_sections_model()
	{
		parent::Model();
        
        if(isset($_REQUEST['form_search'])) {
            $_SESSION['search']=mysql_string($_REQUEST['search']);
        } else {
          if(!isset($_SESSION['search'])){
            $_SESSION['search']='';
          }
        }
	}
    
    
    function add()
    {        
        if(isset($_REQUEST['submit'])){

        	$ret=$this->global_model->adjustment_of_request_array($_REQUEST);
        	
        	$s = '';
        	if ($ret['parent_id']=='') {
        	  $s = ' is null ';	
        	  $ret['parent_id'] = NULL;
        	} else {
        	  $s = '= "'.$ret['parent_id'].'"';
        	}
        	
          if ($ret['group']=='') {
            $ret['group'] = NULL;
          }
          
        	if ($ret['description']=='') {
            $ret['description'] = NULL;
          }
            
          if ($ret['priority']=='') {
            $ret['priority'] = 1;
          }
            
        	$ret['level'] = 0;
        	
        	$sql=$this->db->query('select priority, level from `'.$this->page_name.'` '.
        			                  'where parent_id '.$s.' and `lang`="'.$this->site_lang.'" '.
        			                  'order by level, priority desc limit 1;');
        	if($sql->num_rows()>0) {
        	  $res = $sql->result();
      	      //$ret['priority'] = intval($res[0]->priority)+1;
        	  $ret['level'] = $res[0]->level;
        	} else {
        	  $sql=$this->db->query('select level from `'.$this->page_name.'` where mailing_section_id '.$s.' limit 1;');
        	  if($sql->num_rows()>0) {
        	  	$res = $sql->result();
        	  	$ret['level'] = intval($res[0]->level)+1;
        	  }
        	}
        	
        	$ret['lang'] = $this->site_lang;
        	
        	$this->db->insert($this->page_name,$ret);
        	
          return redirect($this->uri->segment(1).'/'.$this->page_name);

        } else {
            
        	$res=new stdClass();
        	$res->mailing_section_id = NULL;
          $res->parents = array();
          $res->name = '';
          $res->group = '';
          $res->description = '';
          $res->level = 0;
          $res->priority = 1;
          $res->type = '';
          $res->company = 'UFS';
            
          $parents = array();
          $sql=$this->db->query('select * from `'.$this->page_name.'` where `lang`="'.$this->site_lang.'" order by `level`, `priority`;');
          if($sql->num_rows()>0){

            $parents = $sql->result();
            $parents = $this->global_model->adjustment_of_results($parents,$this->page_name);
            $parents = $this->global_model->make_tree($parents,'mailing_section_id');
            $parents = $this->global_model->tree_list($parents);
                
            $this->global_model->add_name($parents,'select');
                
          }
            
          $content['parents'] = $parents;
          $content['data'] = $res;
            
          return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
        	
        	
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);

            $id=(int)$_REQUEST['mailing_section_id'];
            
            $sql=$this->db->query('select * from `'.$this->page_name.'` where `mailing_section_id`="'.$id.'" and `lang`="'.$this->site_lang.'";');
            
            if ($sql->num_rows()>0) {	
               
               $res=$sql->result();

               $s = '';
               if ($ret['parent_id']=='') {
               	 $s = ' is null ';
               	 $ret['parent_id'] = NULL;
               } else {
               	 $s = '= "'.$ret['parent_id'].'"';
               }
               
               if ($ret['group']=='') {
               	 $ret['group'] = NULL;
               }
               
               if ($ret['description']=='') {
               	 $ret['description'] = NULL;
               }
               	 
               //$ret['priority'] = $res[0]->priority;
               $ret['level'] = 0;
                
               $sql=$this->db->query('select priority, level, mailing_section_id from `'.$this->page_name.'` '.
               		                   'where parent_id '.$s.' and `lang`="'.$this->site_lang.'" '.
               		                   'order by level, priority desc limit 1;');
               if($sql->num_rows()>0) {
               	 $res2 = $sql->result();
               	 if ($res[0]->parent_id!=$ret['parent_id']) {
               	  // $ret['priority'] = intval($res2[0]->priority)+1;
               	 }  
               	 $ret['level'] = $res2[0]->level;
               } else {
               	 $ret['level'] = intval($res[0]->level)+1;
               }
               
               $sql_set=$this->global_model->create_set_sql_string($ret);
                           
               $this->db->query('update `'.$this->page_name.'` set 
                                '.$sql_set.' 
                                 where `mailing_section_id`="'.$id.'";');
               
               self::recalc_level($id,$ret['level']);

               $_SESSION['admin']->is_update=1;
            }   

            return redirect($this->uri->segment(1).'/'.$this->page_name);
            
        } else {

        	   $id=(int)$this->uri->segment(4);

             $sql=$this->db->query('select * from `'.$this->page_name.'` where `mailing_section_id`="'.$id.'" and `lang`="'.$this->site_lang.'";');
                
             if($sql->num_rows()>0){	
               
               $res=$sql->result();

               $res=$res[0];
               
               $parents = array();
               $sql=$this->db->query('select * from `'.$this->page_name.'` where `lang`="'.$this->site_lang.'" order by `level`, `priority` ;');
               if($sql->num_rows()>0){

            	   $parents = $sql->result();
            	   $parents = $this->global_model->adjustment_of_results($parents,$this->page_name);
            	   $parents = $this->global_model->make_tree($parents,'mailing_section_id');
                 $parents = $this->global_model->tree_list($parents);
                
                  $this->global_model->add_name($parents,'select');
                  
                  foreach ($parents as $p) {
                  	if ($p->mailing_section_id==$res->parent_id) {
                  		$p->select = 'selected';
                  		break;
                  	}
                  }
               }
               
               $content['parents'] = $parents;
               $content['data'] = $res;
               
             } else {

          	   $res=new stdClass();
          	   $res->mailing_section_id = NULL;
               $res->parents = array();
               $res->name = '';
               $res->group = '';
               $res->description = '';
               $res->level = 0;
               $res->priority = 1;
               $res->type = '';
               $res->company = 'UFS';
             	
               $content['parents'] = array();
               $content['data'] = $res;
               
            }
                
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
      $id=(int)$this->uri->segment(4);
        
      $this->db->query('delete from `'.$this->page_name.'` where `mailing_section_id`="'.$id.'";');

   		return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    function sort()
    {
    	$direction = $this->uri->segment(4);
    	$id = (int)$this->uri->segment(5);
    	$priority = (int)$this->uri->segment(6);
    	
    	if ($direction=='up') {
    	  $priority--;
    	} else {		
    	  $priority++;
    	} 

    	if ($priority<1) {
    	  $priority=1;
    	} else {
    	  $sql = sprintf('select max(priority) as priority from `%s` where mailing_section_id=%d',
    	     	 	         $this->page_name,$id);
    	  $ret = $this->db->query($sql);
    	  if($ret->num_rows()>0) {
     		$res = $ret->result();
     		if ($priority>$res[0]->priority) {
     		  $priority = $res[0]->priority +1;
     		} 
    	  }
    	}
    	
    	$sql = sprintf('update `%s` set priority=%d where mailing_section_id=%d',
    			           $this->page_name,$priority,$id);
    	$this->db->query($sql);
    	
    	return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    function view()
    { 
      $content['data']='';
        
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where = ' where `t`.`lang`="'.$this->site_lang.'"'; 
        
        if(trim($_SESSION['search'])!='') {
        	
          //$where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
          $where.= ' and `t`.`mailing_section_id` in (select mailing_section_id from mailing_subscriptions where email like "%'.trim($_SESSION['search']).'%") ';
        }
        
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	

        	  $res_r=$sql_r->result();
            $res_r=$res_r[0];

            $show=1000;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(5)==0)?1:(int)$this->uri->segment(5);
            $param['url']='/view';

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
        
            $vals='`t`.`mailing_section_id`, `t`.`name`, `t`.`group`, `t`.`parent_id`, `t`.`level`, `t`.`priority`, t.company ';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

            if($sql->num_rows()>0){	
               
            	$res=$sql->result();	

              $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                
              $res=$this->global_model->make_tree($res,'mailing_section_id');
              $res=$this->global_model->tree_list($res);
              $res=$this->global_model->set_sort_elemets($res,'mailing_section_id');
                
              $res=$this->global_model->rand_css_class($res);
                
              $content['pages']=$pages['html'];
              $content['data']=$res;
            }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    
    protected function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.',
                     (case when t1.sub_count is null then 0 else t1.sub_count end) as sub_count
              from `'.$this->page_name.'` as `t`
              left join (select t.mailing_section_id, sum(t.sub_count) as sub_count
                           from (select email, mailing_section_id, count(*) as sub_count
                                   from mailing_subscriptions
                                  where started is not null
                                    and finished is null
                                    and lang = "'.$this->site_lang.'"
                                    and email'.((trim($_SESSION['search'])!='')?' like "%'.trim($_SESSION['search']).'%"':' is not null').'  
                                  group by 1,2) t
                          where t.sub_count=1        
                          group by 1) t1 on t1.mailing_section_id=t.mailing_section_id
              '.$where.'
              order by `t`.`level`, t.priority  
              '.$limit.';'; 
        
        return $sql;
    }
    
    protected function recalc_level($mailing_section_id,$level) {
    	
    	$l = $level+1;
    	$sql = sprintf('update `%s` set level=%d where parent_id=%d;',
    			           $this->page_name,$l,$mailing_section_id);
    	$this->db->query($sql);
    	
    	$sql = sprintf('select mailing_section_id from `%s` where parent_id=%d',
    			           $this->page_name,$mailing_section_id);
    	$ret = $this->db->query($sql);
    	if($ret->num_rows()>0) {
           
    	   $res = $ret->result();
    	   foreach ($res as $r) {
    	   	 
    	   	 self::recalc_level($r->mailing_section_id,$l);
           	
         }   		
    	}
    	
    }
    
    function download() {
      
      $id = $this->uri->segment(4);
      
      $sql = null;
      if (trim($id)!='') {
        $sql = sprintf('select email
                          from (select email, mailing_section_id, count(*) as sub_count
                                  from mailing_subscriptions
                                 where started is not null
                                   and finished is null
                                   and mailing_section_id = %s
                                   and lang = %s
                                 group by 1,2) t
                          where t.sub_count=1
                          group by 1
                          order by 1',
                       $id,"'".$this->site_lang."'");  
      } else {
        $sql = sprintf('select email
                          from (select email, mailing_section_id, count(*) as sub_count
                                  from mailing_subscriptions
                                 where started is not null
                                   and finished is null
                                   and lang = %s
                                 group by 1,2) t
                          where t.sub_count=1
                          group by 1
                          order by 1',
                       "'".$this->site_lang."'");  
      }
      
      $csv = false;
      
      if (!is_null($sql)) {
        
        $ret = $this->db->query($sql);
    	  if($ret->num_rows()>0) {
          
          $csv = '';
          
          $res = $ret->result();
    	    foreach ($res as $r) {
            
            $email = '"'.$r->email.'"';
            $csv = ($csv=='')?$email:$csv."\n".$email;  
          }
        }
      }
      
      if ($csv) {
        
        header('Content-type: application/csv');
        header('Content-length: '.strlen($csv));
        header(sprintf('Content-Disposition: attachment; filename="mailing_subscriptions_%s.csv"',$this->site_lang));
        echo ($csv);
        die();
      } else {
        return redirect($this->uri->segment(1).'/'.$this->page_name);
      }
    }  
}
?>
