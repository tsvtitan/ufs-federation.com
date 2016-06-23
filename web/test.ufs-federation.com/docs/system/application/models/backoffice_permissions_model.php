<?php
class backoffice_permissions_model extends Model{

	function backoffice_permissions_model()
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
        	
            $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/role/'.$ret['role_id']);

        }else{

            $content['roles']=$this->global_model->GET_roles($this->role_id,' selected');
            $content['access_list']=$this->GET_access_list('');
            
            $content['data']='';
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            
        	$ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            
            $sql_set=$this->global_model->create_set_sql_string($ret);								

            $this->db->query('update `'.$this->page_name.'` set 
                             '.$sql_set.' 
                             where `id`="'.(int)$_REQUEST['id'].'";');

            $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/role/'.$ret['role_id']);
            
        }else{			
        	$id=(int)$this->uri->segment(5);

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.$id.'";');
                
            if($sql->num_rows()>0){	
                    
              	$res=$sql->row();

                $res=$this->global_model->adjustment_of_results($res,$this->page_name);

                $content['roles']=$this->global_model->GET_roles($this->role_id,' selected');
                $content['access_list']=$this->GET_access_list($res->access);
                $content['data']=$res;
                
            }else{
                $content['roles']=$this->global_model->GET_roles($this->role_id,' selected');
            	$content['access_list']=$this->GET_access_list('');
                $content['data']='';
            }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {

    	$this->db->query('delete from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(5).'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name.'/role/'.$this->role_id);
    }
    
    
    function view()
    { 
      $content['data']='';

      if($this->role_id==0 and empty($_SESSION['search'])){
      	return redirect($this->uri->segment(1).'/'.$this->page_name.'/role/'.$this->global_model->GET_role_first());
      }
      
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where='';
        
        if($this->role_id>0){
            $where.=' where `t`.`role_id`="'.$this->role_id.'"';
        }
        
        if(!empty($_SESSION['search']))
        {
        	$s=' `t`.`path` like "%'.$_SESSION['search'].'%"';
        	
        	if ($where=="") {
        	  $where.=' where '.$s;
        	} else {
        	  $where.=' and '.$s;
        	}
        		
        }
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->row();

            $show=1000;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/role/'.$this->role_id;

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
        
            $vals='`t`.`id`, `t`.`path`, `t`.`access`, `t`.`role_id`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->date_format($res);
                         

                    $content['data']=$res;
                }                
          }
          
          $content['roles']=$this->global_model->GET_roles($this->role_id,' class="sel"');
          
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    private function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'` as `t`
              '.$where.'
              order by `t`.`path`, t.`access`
              '.$limit.';';
        
        return $sql;
    }
    
    private function GET_access_list($value='')
    {
      $ret=array();
       	
      $res[0]=new stdClass();
      $res[0]->value='yes';
      $res[0]->sel='';
      
      $res[1]=new stdClass();
      $res[1]->value='no';
      $res[1]->sel='';
      
      for ($i=0; $i<count($res); ++$i) {
      	if ($res[$i]->value==$value) {
      		$res[$i]->sel=' selected';
      	} else {
      		$res[$i]->sel='';
      	}
      }
      
      $ret=$res;
      
      return $ret;
    }
}
?>
