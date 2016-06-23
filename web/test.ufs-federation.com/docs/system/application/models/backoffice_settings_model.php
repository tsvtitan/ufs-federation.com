<?php
class backoffice_settings_model extends Model{

	function backoffice_settings_model()
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
    
	function edit_language()
	{
        if(isset($_REQUEST['submit'])){
        	
        	$this->db->query('update `lang` set is_display=0');
        	
        	if(isset($_REQUEST['is_display'])){
        		$is_display_arr = $_REQUEST['is_display'];
        		if(is_array($is_display_arr)){
        			foreach($is_display_arr as $k=>$v){
		                $this->db->query('update `lang` set is_display=1 where `id`="'.$k.'";');        				
        			}
        		}
        	}
                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            $sql=$this->db->query('select * from `lang` order by id');
                if($sql->num_rows()>0){	
                    $res = $sql->result();

                    $content['data']=$res;
                }else{
                    $content['data']='';
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit_language',$content,true);
        }		
	}
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret        = $this->global_model->adjustment_of_request_array($_REQUEST);	
            $id_setting = (int)$_REQUEST['id'];
            
            if($id_setting==2){
            	$ret['is_active'] = isset($_REQUEST['is_active'])?1:0;
            }
            
            
                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(4).'";');
                if($sql->num_rows()>0){	
                    $res=$sql->result();
                    $res=$res[0];

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);

                    $content['data']=$res;
                }else{
                    $content['data']='';
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
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
            $where.='where `name` like "%'.$_SESSION['search'].'%"';
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
        
            $vals='`t`.*';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
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
              order by `t`.`id` asc
              '.$limit.';';
        
        return $sql;
    }
    
}
?>
