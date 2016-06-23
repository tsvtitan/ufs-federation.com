<?php
class backoffice_popup_case_downloads_menu_model extends Model{

	function backoffice_popup_case_downloads_menu_model()
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
                    $ret['lang']=$this->site_lang;

                        $this->db->insert($table,$ret);

                    return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
                }else{
                    $content['error']=$error;
                }
        }
        
        $content['cat_menu']=self::cat_arr($table,0);

       return $this->load->view('backoffice_'.$this->page_name.'_edit_downloads_menu',$content,true);
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

                $content['data']=$res;
                $set_id=$res->parent_id;
            }else{
                $set_id=0;
            }
            
            $content['cat_menu']=self::cat_arr($table,$set_id);

        return $this->load->view('backoffice_'.$this->page_name.'_edit_downloads_menu',$content,true);
    }
    
    
    function del($table,$content,$page_name)
    {
        $sql=$this->db->query('select `id` from `'.$table.'` where `parent_id`="'.(int)$this->uri->segment(5).'" or `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'" limit 1;');
            if($sql->num_rows()>0){	
                $res=$sql->row();
                
                $sql_f=$this->db->query('select `_file` from `downloads` where `cat_id` in ('.(int)$this->uri->segment(5).','.$res->id.') and `lang`="'.$this->site_lang.'";');
                    if($sql_f->num_rows()>0){	
                        $res_f=$sql_f->result();
                        $dir=$_SERVER['DOCUMENT_ROOT'].$this->subdir.'/upload/downloads/';
                        foreach($res_f as $f){
                          @unlink($dir.$f->_file);
                        }
                    }
                
                $this->db->query('delete from `downloads` where `cat_id` in ('.(int)$this->uri->segment(5).','.$res->id.') and `lang`="'.$this->site_lang.'";');
                $this->db->query('delete from `'.$table.'` where `parent_id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
                $this->db->query('delete from `'.$table.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
            }

        return redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));  
    }

    
    function sort($table,$content,$page_name)
    {
        return $this->global_model->item_sort($this->uri->segment(5),$this->uri->segment(6),$this->uri->segment(7),$table,'and `'.$table.'`.`parent_id`="'.$this->uri->segment(8).'"');  
    }
    
    
    function view($table,$content,$page_name)
    {         
        $res_arr='';

        $sql=$this->db->query('select * from `'.$table.'` where `parent_id`="0" and `lang`="'.$this->site_lang.'" order by `sort_id` desc;');
        if($sql->num_rows>0){
            $res=$sql->result();
            $res=$this->global_model->adjustment_of_results($res,$table);
            $res=$this->global_model->set_sort_elemets($res);
                $x=0;
                for($i=0;$i<count($res);$i++){

                    $res[$i]->name='<strong>'.$res[$i]->name.'</strong>';
                    $res_arr[]=$res[$i];

                        $sql2=$this->db->query('select * from `'.$table.'` where `parent_id`="'.$res[$i]->id.'" and `lang`="'.$this->site_lang.'" order by `sort_id` desc;');
                        if($sql2->num_rows>0){
                            $res2=$sql2->result();
                            $res2=$this->global_model->adjustment_of_results($res2,$table);
                            $res2=$this->global_model->set_sort_elemets($res2);

                                for($n=0;$n<count($res2);$n++){

                                    $res2[$n]->name='&nbsp;&nbsp;&nbsp;&bull;&nbsp;'.$res2[$n]->name;
                                    $res_arr[]=$res2[$n];
                                }

                        }

                }
                
            $res_arr=$this->global_model->rand_css_class($res_arr);

        }

        $content['data']=$res_arr;

        return $this->load->view('backoffice_'.$this->page_name.'_view_downloads_menu',$content,true);
    }
    
    
######################################################################################################################
    
    private function cat_arr($table,$set_id=0)
    {
      $ret=array();
        
        $sql=$this->db->query('select `id`,`name` from `'.$table.'` where `parent_id`="0" and `lang`="'.$this->site_lang.'" order by `sort_id` desc;');
        if($sql->num_rows()>0){	
           $res=$sql->result();

            for($i=0;$i<count($res);$i++){ 
                $res[$i]->name=stripslashes($res[$i]->name);

                 if($res[$i]->id==$set_id){
                   $res[$i]->select=' selected="selected"';
                 }else{
                   $res[$i]->select='';
                 }	
            }

           $ret=$res;	
        }
        
      return $ret;
    }
    
}
?>
