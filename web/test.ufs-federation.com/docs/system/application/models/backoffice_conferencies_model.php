<?php
class backoffice_conferencies_model extends Model{

	function backoffice_conferencies_model()
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

              $ret['url']=$this->global_model->SET_title_url($_REQUEST['text']['name'],$this->page_name);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['lang']=$this->site_lang;
              $ret['is_moderation']=isset($ret['is_moderation'])?'yes':'no';

                $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name);

        }else{

            $content['date']=$this->global_model->date_arr('',2,2011);
            $content['data']='';
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	
            
              $ret['url']=$this->global_model->SET_title_url($_REQUEST['text']['name'],$this->page_name,(int)$_REQUEST['id']);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['is_moderation']=isset($ret['is_moderation'])?'yes':'no';

                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'Y|m|d|H|i');
                         $res->is_moderation=($res->is_moderation=='yes')?' checked="checked"':'';
                         
                         $date=new stdClass();
                         list($date->year,$date->month,$date->day,$date->hour,$date->minute)=explode('|',$res->timestamp);
                         
                    $content['date']=$this->global_model->date_arr($date,2,2011);   
                    $content['data']=$res;
                }else{
                    $content['date']=$this->global_model->date_arr('',2,2011);
                    $content['data']='';
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }  
    
    
    function edit_comment()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	

              $ret['status']=isset($ret['status'])?'hide':'show';
            
                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'_comment` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'_comment` where `id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->result();
                    $res=$res[0];

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res->status=($res->status=='hide')?' checked="checked"':'';

                    $content['data']=$res;
                }else{
                    $content['data']='';
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit_comment',$content,true);
        }
    }
    
    
    function del()
    {  
        $this->db->query('delete from `'.$this->page_name.'_comment` where `c_id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');
        $this->db->query('delete from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name);
    }


    function del_comment()
    {  
        $this->db->query('delete from `'.$this->page_name.'_comment` where `id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');

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
            $where.='and `t`.`name` like "%'.$_SESSION['search'].'%"  
                     and `c`.`name` like "%'.$_SESSION['search'].'%"
                     and `c`.`query` like "%'.$_SESSION['search'].'%"
                     and `c`.`answer` like "%'.$_SESSION['search'].'%"';
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
        
            $vals='`t`.`id`, `t`.`name`, `t`.`timestamp`, `c`.`id` as `com_id`, `c`.`name` as `user`, 
                   `c`.`query`, `c`.`answer`, `c`.`status`, `c`.`timestamp` as `comment_date`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_comment');
                         $res=$this->global_model->date_format($res,'d.m.Y H:i');
                         $res=$this->global_model->date_format($res,'d.m.Y H:i:s','comment_date','comment_date');
                        /*********************************/
                         
                      $arr=array();
                      for($i=0;$i<count($res);$i++)
                      {                      
                          if(!isset($arr[$res[$i]->id]->conf)){
                            $arr[$res[$i]->id]->conf->name=$res[$i]->name;
                            $arr[$res[$i]->id]->conf->timestamp=$res[$i]->timestamp;
                          }
                          if(!is_null($res[$i]->com_id)){
                            $res[$i]->status=$this->lang->line('admin_tpl_'.$res[$i]->status);
                            $arr[$res[$i]->id]->arr[]=$res[$i];
                          }
                      }

                      foreach($arr as $kay=>$val)
                      {
                         if(isset($arr[$kay]->arr)){
                           $arr[$kay]->arr=$this->global_model->rand_css_class($arr[$kay]->arr);
                         }
                      }

                    $content['pages']=$pages['html'];
                    $content['data']=$arr;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    
    protected function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'` as `t`
              left join `'.$this->page_name.'_comment` as `c`
              on `c`.`c_id`=`t`.`id`
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.'
              order by `t`.`timestamp` desc, `c`.`timestamp` desc
              '.$limit.';';
        
        return $sql;
    }
    
}
?>
