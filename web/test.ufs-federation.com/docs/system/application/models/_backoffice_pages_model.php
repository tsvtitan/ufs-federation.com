<?php
class backoffice_pages_model extends Model{

	function backoffice_pages_model()
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
      if($this->global_model->is_add('pages_menu',$this->cat_id)==false){
          return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id);
      }
        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $next_id=$this->global_model->Auto_inc($this->page_name);
            $title=empty($_REQUEST['url'])?$_REQUEST['text']['name']:$_REQUEST['url'];
            
            for($i=1;$i<=6;$i++){ 
             $str='img_'.$i;
             $promo='promo_hide_'.$i;
             $ret[$promo]=isset($ret[$promo])?'yes':'no';
             
                if(isset($_FILES[$str])){
                  $ret[$str]=upload_pic($_FILES[$str],'','upload/home','img_'.$i.'_'.$this->site_lang.'_',true,'',$this->subdir); 
                  if(!empty($ret[$str])){
                      self::img_resize($ret[$str]);
                  }
                }
            }

              $ret['url']=$this->global_model->SET_title_url($title,$this->page_name);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['sort_id']=$next_id;
              $ret['is_hide']=isset($ret['is_hide'])?'yes':'no';
              $ret['lang']=$this->site_lang;
              
              $exp_cat=explode('|',$ret['cat_id']);
              $ret['cat_id']=$exp_cat[0];
              $ret['parent_id']=$exp_cat[1];
              
              if ($ret['redirect']=='') {
              	$ret['redirect'] = NULL;
              }
              
              if ($ret['redirect_code']=='') {
              	$ret['redirect_code'] = NULL;
              }
              
              if ($ret['contact_id']=='') {
              	$ret['contact_id'] = NULL;
              }

              if ($ret['slider_link_type']=='') {
              	$ret['slider_link_type'] = NULL;
              }
              
              if ($ret['content']=='&nbsp;') {
              	$ret['content']='';
              }
              
                $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id);

        }else{
            
            $content['date']=$this->global_model->date_arr('',2,2011);
            $content['menu']=self::get_parent_pages($this->cat_id,(int)$this->uri->segment(5));
            $content['contacts']=$this->global_model->GET_contacts('contacts');
            $content['slider_link_types']=$this->global_model->GET_slider_link_types(null);
            $res=new stdClass();
            $res->sub_page_type='';
            $res->indexes_box='';
            $res=$this->global_model->adjustment_of_results($res,$this->page_name);
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $title=empty($_REQUEST['url'])?$_REQUEST['text']['name']:$_REQUEST['url'];
            
            for($i=1;$i<=6;$i++){ 
             $str='img_'.$i;
             $promo='promo_hide_'.$i;
             $ret[$promo]=isset($ret[$promo])?'yes':'no';
             
                if(isset($_FILES[$str])){
                  $ret[$str]=upload_pic($_FILES[$str],$_REQUEST['img_old'][$i],'upload/home','img_'.$i.'_'.$this->site_lang.'_',true,'',$this->subdir); 
                  if(!empty($ret[$str])){
                      self::img_resize($ret[$str]);
                  }
                }
            }
            
              $ret['url']=$this->global_model->SET_title_url($title,$this->page_name,(int)$_REQUEST['id']);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['is_hide']=isset($ret['is_hide'])?'yes':'no';
              
              $exp_cat=explode('|',$ret['cat_id']);
              $ret['cat_id']=$exp_cat[0];
              $ret['parent_id']=$exp_cat[1];
              
              if ($ret['contact_id']=="") {
              	$ret['contact_id']=NULL;
              } 
              
              if ($ret['slider_link_type']=="") {
              	$ret['slider_link_type']=NULL;
              }
              
              if ($ret['redirect_code']=='') {
              	$ret['redirect_code'] = NULL;
              }
              
              if ($ret['redirect_code']=='') {
              	$ret['redirect_code'] = NULL;
              }
              
              if ($ret['content']=='&nbsp;') {
              	$ret['content']='';
              }
              
                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'Y|n|j');
                         
                         $date=new stdClass();
                         list($date->year,$date->month,$date->day)=explode('|',$res->timestamp);
                         
                         $res->is_hide=($res->is_hide=='yes')?' checked="checked"':'';
                         
                    $content['date']=$this->global_model->date_arr($date,2,2011);
                    $content['menu']=self::get_parent_pages($res->cat_id,$res->parent_id);
                    $content['contacts']=$this->global_model->GET_contacts('contacts',$res->contact_id,' selected="selected"');
                    $content['slider_link_types']=$this->global_model->GET_slider_link_types($res->slider_link_type,' selected="selected"');
                    $content['data']=$res;
                }else{
                    $content['date']=$this->global_model->date_arr('',2,2011);
                    $content['menu']=self::get_parent_pages($this->cat_id);
                    $content['contacts']=$this->global_model->GET_contacts('contacts',$res->contact_id,' selected="selected"');
                    $content['slider_link_types']=$this->global_model->GET_slider_link_types($res->slider_link_type,' selected="selected"');
                    
                    $res=new stdClass();
                    $res->sub_page_type='';
                    $res->indexes_box='';
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        if($this->global_model->is_delete($this->page_name,(int)$this->uri->segment(5))==true){
            self::img_del((int)$this->uri->segment(5));
            $this->db->query('delete from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
            $this->db->query('delete from `'.$this->page_name.'` where `parent_id`="'.(int)$this->uri->segment(5).'" and `lang`="'.$this->site_lang.'";');
        }

		return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id);
    }
    
    
    function sort()
    {
        return $this->global_model->item_sort($this->uri->segment(5),$this->uri->segment(6),$this->uri->segment(7),$this->page_name,' and `'.$this->page_name.'`.`cat_id`="'.$this->cat_id.'" and `'.$this->page_name.'`.`parent_id`="'.$this->uri->segment(8).'"');  
    }
    
    
    function view()
    { 
      $content['data']='';
        
        if($this->cat_id==0 and empty($_SESSION['search'])){
            return redirect($this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->global_model->GET_menu_first_cat('pages_menu',false));
        }
        
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
            $where.=' and (`t`.`name` like "%'.$_SESSION['search'].'%")';
        }
        
        
        $vals='count("t") as Rows';
        $limit='';
        $where_sub=' and `t`.`parent_id`="0"';
        $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit,$where_sub));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->result();
            $res_r=$res_r[0];

            /* pager */ 
            $show=20;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/cat/'.$this->cat_id;

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
            $vals='`t`.`id`, `t`.`name`, `t`.`cat_id`, `t`.`sort_id`, `t`.`is_delete`, `t`.`timestamp`, `t`.`parent_id`, `t`.`sub_page_type`, `t`.`link_to_page`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $where_sub=' and `t`.`parent_id`="0"';
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit,$where_sub));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->set_sort_elemets($res);
                         $res=$this->global_model->date_format($res);
                        /*********************************/
                         
                         $arr=array();
                         $limit='';
                         foreach($res as $item){
                            $item->list_level='main'; 
                            $arr[]=$item;
 
                            $where_sub=' and `t`.`parent_id`="'.$item->id.'"';
                            $sql_s=$this->db->query(self::view_sql_str($vals,$where,$limit,$where_sub)); 
                            
                            if($sql_s->num_rows()>0){	
                                $res_s=$sql_s->result();	

                                    /*********************************/
                                     $res_s=$this->global_model->adjustment_of_results($res_s,$this->page_name);
                                     $res_s=$this->global_model->set_sort_elemets($res_s);
                                     $res_s=$this->global_model->date_format($res_s);
                                    /*********************************/
                                     
                                foreach($res_s as $val){
                                    $val->list_level='sub';
                                    $arr[]=$val;
                                }
                            }
                            
                         }
                         
                      $res=$this->global_model->rand_css_class($arr);

                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }

          $content['menu']=$this->global_model->GET_menu('pages_menu',$this->cat_id,' class="sel"');
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
/************************************************************************************/
        
    private function view_sql_str($vals,$where,$limit,$where_sub='')
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'` as `t`
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.$where_sub.'
              order by `t`.`sort_id` desc
              '.$limit.';';
        
        return $sql;
    }
    
    private function img_resize($img)
    {
        img_resize_new('upload/home/'.$img,'upload/home/small/'.$img,2000,500,0xFFFFFF,100,$this->subdir);
        
     return true;
    }
    
    private function img_del($id)
    {
     $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.$id.'" and `lang`="'.$this->site_lang.'" limit 1;');
        if($sql->num_rows()>0){	
            $res=$sql->row(); 
            $dir=$_SERVER['DOCUMENT_ROOT'].'/upload/home/';
            
            for($i=1;$i<=6;$i++){ $str='img_'.$i;
              if(!empty($res->$str)){
                @unlink($dir.$res->$str);
                @unlink($dir.'small/'.$res->$str);
              }
            }
        }
      return true;
    }
    
    private function get_parent_pages($cat_id,$id=0)
    {
      $ret=$this->global_model->GET_menu('pages_menu',$cat_id,' selected="selected"');
      
         for($i=0;$i<count($ret);$i++)
         {
             $sql=$this->db->query('select `id`, `name` 
                                    from `'.$this->page_name.'` 
                                    where `cat_id`="'.$ret[$i]->id.'" 
                                    and `parent_id`="0"
                                    and (`sub_page_type`="default" or 
                                         `sub_page_type`="share_market" or 
                                         `sub_page_type`="debt_market" or 
                                         `sub_page_type`="brokerage")
                                    and `link_to_page`=""
                                    and `lang`="'.$this->site_lang.'"
                                    order by `sort_id` desc;');

                if($sql->num_rows()>0){	
                    $res=$sql->result(); 

                        $res=$this->global_model->adjustment_of_results($res,$this->page_name);

                    for($n=0;$n<count($res);$n++)
                    {
                        if($res[$n]->id==$id){
                            $res[$n]->sel=' selected="selected"';
                            $ret[$i]->sel='';
                        }else{
                            $res[$n]->sel='';
                        }
                    }

                    $ret[$i]->sub=$res;
                }   
         }
            
      return $ret;
    }
    
}
?>
