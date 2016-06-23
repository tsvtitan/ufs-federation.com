<?php
class backoffice_analytics_reviews_groups_model extends Model{

	function backoffice_analytics_reviews_groups_model()
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
        	
        	$s = '';
        	if ($ret['parent_id']=='') {
        	  $s = ' is null ';	
        	  $ret['parent_id'] = NULL;
        	} else {
        	  $s = '= "'.$ret['parent_id'].'"';
        	}
        	
            if ($ret['description']=='') {
              $ret['description'] = NULL;
            }
            
            if ($ret['priority']=='') {
              $ret['priority'] = 1;
            }
            
            if ($ret['view_count']=='') {
            	$ret['view_count'] = NULL;
            }
            
        	$ret['level'] = 0;
        	
        	$sql=$this->db->query('select priority, level from `'.$this->page_name.'` where parent_id '.$s.' and `lang`="'.$this->site_lang.'" '.
        			              'order by level, priority desc limit 1;');
        	if($sql->num_rows()>0) {
        	  $res = $sql->result();
      	      //$ret['priority'] = intval($res[0]->priority)+1;
        	  $ret['level'] = $res[0]->level;
        	} else {
        	  $sql=$this->db->query('select level from `'.$this->page_name.'` where group_id '.$s.' limit 1;');
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
        	$res->group_id=NULL;
            $res->parents = array();
            $res->name='';
            $res->description=NULL;
            $res->level=0;
            $res->priority=1;
            $res->day_count=7;
            
            $parents = array();
            $sql=$this->db->query('select * from `'.$this->page_name.'` where `lang`="'.$this->site_lang.'" order by `level`, `priority` ;');
            if($sql->num_rows()>0){

            	$parents = $sql->result();
            	$parents = $this->global_model->adjustment_of_results($parents,$this->page_name);
            	$parents = $this->global_model->make_tree($parents,'group_id');
                $parents = $this->global_model->tree_list($parents);
                
                $this->global_model->add_name($parents,'select');
                
            }
            
            $content['parents']=$parents;
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
        	
        	
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);

            $group_id=(int)$_REQUEST['group_id'];
            
            $sql=$this->db->query('select * from `'.$this->page_name.'` where `group_id`="'.$group_id.'" and `lang`="'.$this->site_lang.'";');
            
            if ($sql->num_rows()>0) {	
               
               $res=$sql->result();

               $s = '';
               if ($ret['parent_id']=='') {
               	 $s = ' is null ';
               	 $ret['parent_id'] = NULL;
               } else {
               	 $s = '= "'.$ret['parent_id'].'"';
               }
               
               if ($ret['description']=='') {
               	 $ret['description'] = NULL;
               }
               	 
               if ($ret['view_count']=='') {
            	 $ret['view_count'] = NULL;
               }
               //$ret['priority'] = $res[0]->priority;
               $ret['level'] = 0;
                
               $sql=$this->db->query('select priority, level, group_id from `'.$this->page_name.'` where parent_id '.$s.' and `lang`="'.$this->site_lang.'" '.
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
                                 where `group_id`="'.$group_id.'";');
               
               self::recalc_level($group_id,$ret['level']);

               $_SESSION['admin']->is_update=1;
            }   

            return redirect($this->uri->segment(1).'/'.$this->page_name);
            
        } else {

        	$group_id=(int)$this->uri->segment(4);

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `group_id`="'.$group_id.'" and `lang`="'.$this->site_lang.'";');
                
             if($sql->num_rows()>0){	
               
               $res=$sql->result();

               $res=$res[0];
               
               $parents = array();
               $sql=$this->db->query('select * from `'.$this->page_name.'` where `lang`="'.$this->site_lang.'" order by `level`, `priority` ;');
               if($sql->num_rows()>0){

            	  $parents = $sql->result();
            	  $parents = $this->global_model->adjustment_of_results($parents,$this->page_name);
            	  $parents = $this->global_model->make_tree($parents,'group_id');
                  $parents = $this->global_model->tree_list($parents);
                
                  $this->global_model->add_name($parents,'select');
                  
                  foreach ($parents as $p) {
                  	if ($p->group_id==$res->parent_id) {
                  		$p->select = 'selected';
                  		break;
                  	}
                  }
               }
                              
               $content['parents']=$parents;
               $content['data']=$res;
               
             } else {

        	   $res=new stdClass();
        	   $res->group_id=NULL;
               $res->parents = array();
               $res->name='';
               $res->description='';
               $res->level=0;
               $res->priority=1;
               $res->day_count=7;
             	
               $parents = array();
               
               $content['parents']=$parents;
               $content['data'] = $res;
            }
                
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        $group_id=(int)$this->uri->segment(4);
        
        $this->db->query('delete from `'.$this->page_name.'` where `group_id`="'.$group_id.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    function sort()
    {
    	$direction = $this->uri->segment(4);
    	$group_id = (int)$this->uri->segment(5);
    	$priority = (int)$this->uri->segment(6);
    	
    	if ($direction=='up') {
    	  $priority--;
    	} else {		
    	  $priority++;
    	} 

    	if ($priority<1) {
    	  $priority=1;
    	} else {
    	  $sql = sprintf('select max(priority) as priority from `%s` where group_id=%d',
    		 	         $this->page_name,$group_id);
    	  $ret = $this->db->query($sql);
    	  if($ret->num_rows()>0) {
     		$res = $ret->result();
     		if ($priority>$res[0]->priority) {
     		  $priority = $res[0]->priority +1;
     		} 
    	  }
    	}
    	
    	$sql = sprintf('update `%s` set priority=%d where group_id=%d',
    			       $this->page_name,$priority,$group_id);
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
        
        if(!empty($_SESSION['search'])) {
        	
          $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
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
        
            $vals='`t`.`group_id`, `t`.`name`, `t`.`parent_id`, `t`.`level`, `t`.`priority` ';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

            if($sql->num_rows()>0){	
               
            	$res=$sql->result();	

                $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                
                $res=$this->global_model->make_tree($res,'group_id');
                $res=$this->global_model->tree_list($res);
                $res=$this->global_model->set_sort_elemets($res,'group_id');
                
                $res=$this->global_model->rand_css_class($res);
                
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
              order by `t`.`level`, case when t.level=0 then t.priority else `t`.`name` end  
              '.$limit.';'; 
        
        return $sql;
    }
    
    protected function recalc_level($group_id,$level) {
    	
    	$l = $level+1;
    	$sql = sprintf('update `%s` set level=%d where parent_id=%d;',
    			       $this->page_name,$l,$group_id);
    	$this->db->query($sql);
    	
    	$sql = sprintf('select group_id from `%s` where parent_id=%d',
    			       $this->page_name,$group_id);
    	$ret = $this->db->query($sql);
    	if($ret->num_rows()>0) {
           
    	   $res = $ret->result();
    	   foreach ($res as $r) {
    	   	 
    	   	 self::recalc_level($r->group_id,$l);
           	
           }   		
    	}
    	
    }
    
}
?>
