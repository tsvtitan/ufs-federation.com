<?php
class backoffice_popup_case_pages_menu_model extends Model{

	function backoffice_popup_case_pages_menu_model()
	{
		parent::Model();
	}
    
    
    function add($table,$content,$page_name)
    {
      $content['data']='';
        
        if(isset($_REQUEST['submit'])){
            $error='';
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);

                if(empty($ret['name'])){ 
                    $error[]=$this->lang->line('admin_pages_popup_error_name'); 
                }

                if(empty($error)){
                    $ret['sort_id']=$this->global_model->Auto_inc($table);
                    $ret['url']=$this->global_model->SET_title_url($_REQUEST['text']['name'],$table,$ret['sort_id']);
                    $ret['is_hide']=isset($ret['is_hide'])?'yes':'no';
                    $ret['lang']=$this->site_lang;

                        $this->db->insert($table,$ret);

                    return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
                }else{
                    $content['error']=$error;
                }
        }

       return $this->load->view('backoffice_'.$this->page_name.'_edit_pages_menu',$content,true);
    }

    
    function edit($table,$content,$page_name)
    {
      $content['data']='';
            
        if(isset($_REQUEST['submit'])){
            $error='';
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);


                if(empty($ret['name'])){ 
                    $error[]=$this->lang->line('admin_pages_popup_error_name'); 
                }

                if(empty($error)){

                    $ret['url']=$this->global_model->SET_title_url($_REQUEST['text']['name'],$table,(int)$_REQUEST['id']);
                    $ret['is_hide']=isset($ret['is_hide'])?'yes':'no';
                        
                        $sql_set=$this->global_model->create_set_sql_string($ret);								

                        $this->db->query('update `'.$table.'` set 
                                            '.$sql_set.' 
                                          where `id`="'.(int)$_REQUEST['id'].'"
                                          and `lang`="'.$this->site_lang.'";');

                    return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));

                }else{
                    $content['error']=$error;
                }
        }

            $sql=$this->db->query('select * from `'.$table.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
            if($sql->num_rows>0){
                $res=$sql->row();

                    $res=$this->global_model->adjustment_of_results($res,$table);
                    
                    $res->is_hide=($res->is_hide=='yes')?' checked="checked"':'';

                $content['data']=$res;
            }

        return $this->load->view('backoffice_'.$this->page_name.'_edit_pages_menu',$content,true);
    }
    
    
    function del($table,$content,$page_name)
    {
        if($this->global_model->is_delete($table,(int)$this->uri->segment(5))==true){
            $this->db->query('delete from `pages` where `cat_id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
            $this->db->query('delete from `'.$table.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
        }

        return redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));  
    }

    
    function sort($table,$content,$page_name)
    { 
        return $this->global_model->item_sort($this->uri->segment(5),$this->uri->segment(6),$this->uri->segment(7),$table);
    }
    
    
    function view($table,$content,$page_name)
    {   
        $res_arr='';

        $sql=$this->db->query('select * from `'.$table.'` where `lang`="'.$this->site_lang.'" order by `sort_id` desc;');
        if($sql->num_rows>0){
            $res=$sql->result();
            $res=$this->global_model->adjustment_of_results($res,$table);
            $res=$this->global_model->set_sort_elemets($res);                
            $res=$this->global_model->rand_css_class($res);

        }

        $content['data']=$res;

        return $this->load->view('backoffice_'.$this->page_name.'_view_pages_menu',$content,true);
    }
    
}
?>
