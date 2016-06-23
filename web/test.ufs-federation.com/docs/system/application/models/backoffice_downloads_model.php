<?php
class backoffice_downloads_model extends Model{

	function backoffice_downloads_model()
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
            $next_id=$this->global_model->Auto_inc($this->page_name);
            $title=empty($_REQUEST['url'])?$_REQUEST['text']['name']:$_REQUEST['url'];

              $ret['url']=$this->global_model->SET_title_url($title,$this->page_name);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['lang']=$this->site_lang;
              
              $ret['_file']=upload_file($_FILES['_file'],'','upload/downloads','file_'.$next_id.'_',$this->subdir);

                $this->db->insert($this->page_name,$ret);

          return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$ret['cat_id']);

        }else{

            $content['date']=$this->global_model->date_arr('',2,2008);
            $content['menu']=$this->global_model->GET_menu('downloads_menu',$this->cat_id,' selected="selected"');
            $content['data']='';
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $title=empty($_REQUEST['url'])?$_REQUEST['text']['name']:$_REQUEST['url'];
            
              $ret['url']=$this->global_model->SET_title_url($title,$this->page_name,(int)$_REQUEST['id']);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);

              $ret['_file']=upload_file($_FILES['_file'],$_REQUEST['old_file'],'upload/downloads','file_'.(int)$_REQUEST['id'].'_',$this->subdir);

                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$ret['cat_id']);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'Y|n|j');
                         
                         $date=new stdClass();
                         list($date->year,$date->month,$date->day)=explode('|',$res->timestamp);
                         
                    $content['date']=$this->global_model->date_arr($date,2,2008); 
                    $content['menu']=$this->global_model->GET_menu('downloads_menu',$res->cat_id,' selected="selected"');
                    $content['data']=$res;
                }else{
                    $content['date']=$this->global_model->date_arr('',2,2008);
                    $content['menu']=$this->global_model->GET_menu('downloads_menu',$this->cat_id,' selected="selected"');
                    $content['data']='';
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {
        $sql=$this->db->query('select `_file` from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(5).'";');
            if($sql->num_rows()>0){	
                $res=$sql->row();
                $dir=$_SERVER['DOCUMENT_ROOT'].$this->subdir.'/upload/downloads/';
                @unlink($dir.$res->_file);
            }
            
        $this->db->query('delete from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id);
    }
    
    
    function view()
    { 
      $content['data']='';
        
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where='';
        
        if($this->cat_id>0){
            $where.=' and `t`.`cat_id`="'.$this->cat_id.'"';
        }
        
        if(!empty($_SESSION['search']))
        {
            $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
        }
        
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->row();

            /* pager */ 
            $show=20;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/cat/'.$this->cat_id;

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
          //  $vals='`t`.`id`, `t`.`name`, `t`.`timestamp`, `t`.`cat_id`, `t`.`cat_sid`, `t`.`_file`';
            $vals='`t`.`id`, `t`.`name`, `t`.`timestamp`, `t`.`cat_id`, `t`.`_file`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->date_format($res);
                        /*********************************/ 
                         
                      for($i=0;$i<count($res);$i++)
                      {
                          $res[$i]->size=self::get_size($res[$i]->_file);
                      }

                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }
          
          $content['menu']=$this->global_model->GET_menu('downloads_menu',$this->cat_id,' class="sel"');
          
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
##########################################################################################
    
    private function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'` as `t`
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.'
              order by `t`.`timestamp` desc
              '.$limit.';';
        
        return $sql;
    }
    
    private function get_size($file)
    {
        $dir=$_SERVER['DOCUMENT_ROOT'].'/upload/downloads/';
        $ret='0 b';
          if(!empty($file)){
            $size=filesize($dir.$file);
            $ret=$size.' b';
            
            if($size>1024){
                $size=$size/1024;
                $ret=ceil($size).' Kb';
            }
            
            if($size>1024){
                $size=$size/1024;
                $ret=ceil($size).' Mb';
            }
          }
        
        return $ret;        
    }
    
}
?>
