<?php
class backoffice_popup_case_roles_model extends Model{

	function backoffice_popup_case_roles_model()
	{
		parent::Model();
	}
    
    
    function add($table,$content,$page_name)
    {
      $content['data']='';
        
        if(isset($_REQUEST['submit'])){
            $error='';
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);

                if(empty($ret['role'])){ 
                    $error[]=$this->lang->line('admin_pages_popup_error_name'); 
                }

                if(empty($error)){

                    $this->db->insert($table,$ret);

                    return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
                }else{
                    $content['error']=$error;
                }
        }
        
       return $this->load->view('backoffice_'.$this->page_name.'_edit_roles',$content,true);
    }

    
    function edit($table,$content,$page_name)
    {
      $content['data']='';
      
        if(isset($_REQUEST['submit'])){
        	
            $error='';
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);


                if(empty($ret['role'])){ 
                    $error[]=$this->lang->line('admin_pages_popup_error_name'); 
                }

                if(empty($error)){

                    $sql_set=$this->global_model->create_set_sql_string($ret);								

                    $this->db->query('update `'.$table.'` set 
                                            '.$sql_set.' 
                                          where `id`="'.(int)$_REQUEST['id'].'";');

                    return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));

                }else{
                    $content['error']=$error;
                }
        }

            $sql=$this->db->query('select * from `'.$table.'` where `id`="'.(int)$this->uri->segment(5).'";');
            if($sql->num_rows>0){
                
            	$res=$sql->row();

                $res=$this->global_model->adjustment_of_results($res,$table);

                $content['data']=$res;
            }
            

        return $this->load->view('backoffice_'.$this->page_name.'_edit_roles',$content,true);
    }
    
    
    function del($table,$content,$page_name)
    {
    	$id=(int)$this->uri->segment(5);
    	
        $this->db->query('delete from `login_roles` where `role_id`="'.$id.'";');
        
        $this->db->query('delete from `permissions` where `role_id`="'.$id.'";');
        
        $this->db->query('delete from `'.$table.'` where `id`="'.$id.'";');
        
        return redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));  
    }

    
    function view($table,$content,$page_name)
    {         
        $res_arr='';

        $sql=$this->db->query('select * from `'.$table.'` order by `id`, `role`;');
        if($sql->num_rows>0){
            $res=$sql->result();
            $res=$this->global_model->adjustment_of_results($res,$table);
            $res=$this->global_model->set_sort_elemets($res);
                $x=0;
                for($i=0;$i<count($res);$i++){

                    $res[$i]->name='<strong>'.$res[$i]->role.'</strong>';
                    $res_arr[]=$res[$i];

                }
                
            $res_arr=$this->global_model->rand_css_class($res_arr);

        }

        $content['data']=$res_arr;

        return $this->load->view('backoffice_'.$this->page_name.'_view_roles',$content,true);
    }
    
}
?>
