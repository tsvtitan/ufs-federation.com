<?php
class backoffice_debt_market_surveys_on_emitter_model extends Model{

	function backoffice_debt_market_surveys_on_emitter_model()
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
        $this->load->model('maindb_model');
	}
    
    
    function add()
    {        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $next_id=$this->global_model->Auto_inc($this->page_name);

              $ret['url']=$this->global_model->SET_title_url($_REQUEST['text']['name'],$this->page_name);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['lang']=$this->site_lang;

                $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name);

        }else{

            $content['date']=$this->global_model->date_arr('',2,2011);
            $content['data']='';
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
        
    function download()
    {    
       $url=mysql_string($this->uri->segment(4));

            $sql=$this->db->query('select `url`,`_file`
                                   from `debt_market_files`
                                   where `lang`="'.$this->site_lang.'"
                                   and `url`="'.$url.'"
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                    $filetype=explode('.',$res->_file);
                    $filename=$res->url.'.'.$filetype[count($filetype)-1];
                    $download_url=$_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$res->_file;

                    header("Content-type: application/force-download");
                    header("Content-Disposition: attachment; filename=".$filename);
                    header("Content-Transfer-Encoding: binary");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Pragma: public");
                    header("Content-Length: ".filesize($download_url));
                    readfile($download_url);
                }

                
    }
    
    function delfile()
    {
	         $content['the_debt'] = ($this->uri->segment(4)!="")?($this->maindb_model->select_table($this->page_name,' `id`="'.(int)$this->uri->segment(4).'"','id',1,true,'itembg',array('name'))):"";
	    	if(empty($content['the_debt']))redirect($this->uri->segment(1).'/error');
	    	
	    	 $content['data']       = ($this->uri->segment(5)!="")?($this->maindb_model->select_table('debt_market_files',' `id`="'.(int)$this->uri->segment(5).'"','num',1,true,'itembg',array('name'))):"";
	    	if(empty($content['data']))redirect($this->uri->segment(1).'/error');
	    	
	    	@unlink($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$content['data']->_file);
	    	$this->db->delete('debt_market_files',array('id'=>(int)$this->uri->segment(5)));
	    	redirect($this->uri->segment(1).'/'.$this->page_name.'/files/'.$content['the_debt']->id);
	    	
    }
    
    function files()
    {
    	$content['the_debt'] = ($this->uri->segment(4)!="")?($this->maindb_model->select_table($this->page_name,' `id`="'.(int)$this->uri->segment(4).'"','id',1,true,'itembg',array('name'))):"";
    	if(empty($content['the_debt']))redirect($this->uri->segment(1).'/error');
    	
    	$content['data']    = $this->maindb_model->select_table('debt_market_files',' `debt_market_id`="'.(int)$this->uri->segment(4).'"','id','',false,'itembg',array('name'));
    	
    	 return $this->load->view('backoffice_'.$this->page_name.'_files',$content,true);
    }
    
    
 function editfile()
 {
    	$content['the_debt'] = ($this->uri->segment(4)!="")?($this->maindb_model->select_table($this->page_name,' `id`="'.(int)$this->uri->segment(4).'"','id',1,true,'itembg',array('name'))):"";
    	if(empty($content['the_debt']))redirect($this->uri->segment(1).'/error');
 	
 	    $content['data']       = ($this->uri->segment(5)!="")?($this->maindb_model->select_table('debt_market_files',' `id`="'.(int)$this->uri->segment(5).'"','num',1,true,'itembg',array('name'))):"";
 	
	    $content['header']         = $content['the_debt']->name.' -> '.$this->lang->line('admin_tpl_files'); 

	
	if(isset($_REQUEST['submit'])){
		$error                       = "";
		$ret                         = "";
		$ret->name                   = $_REQUEST['name'];
		$ret->id                     = isset($_REQUEST['id'])?$_REQUEST['id']:"";
		
		$the_id                      = ($ret->id!="")?$ret->id:$next_id=$this->global_model->Auto_inc('debt_market_files');
		
		$ret->debt_market_id         = $content['the_debt']->id;
		$ret->url                    = $this->global_model->SET_title_url($ret->name,'debt_market_files',$ret->id);
		$ret->_file                  = upload_file($_FILES['_file'],isset($_REQUEST['old_file'])?$_REQUEST['old_file']:'','upload/downloads','file_'.$the_id.'_',$this->subdir);
		
		
		if(empty($ret->name)){
			$error[] = $this->lang->line('admin_pages_popup_error_name');
		}
 		if(empty($ret->_file)){
 			$error[] = $this->lang->line('admin_pages_popup_error_file');
 		}		
			
		if(is_array($error)){
	 			$content['data']      = $ret;
	 			$content['error']     = $error;
	 	}
	 	else{	 	
				$ret->name           = mysql_string($_REQUEST['name']);              
	 		   
	 		   if(empty($ret->id)){
	 		   	     $ret->lang    = $this->site_lang;
				     $ret->num     = $this->maindb_model->_get_max_num('debt_market_files','num','`debt_market_id`="'.$content['the_debt']->id.'"');
				     $this->db->insert('debt_market_files', $ret);
			   }
			   else{
				     $this->db->where('id', $ret->id);
				     $this->db->update('debt_market_files', $ret);
				    }
				    unset($ret);
				    unset($error);
				    $_SESSION['is_update'] = 'Successfully updated';
				     redirect($this->uri->segment(1).'/'.$this->page_name);
				    //redirect($this->uri->segment(1).'/'.$this->page_name.'/files/'.$content['the_debt']->id);				 		
	 	} // if without errors	
	} // if submit
	
	return  $this->load->view('backoffice_'.$this->page_name.'_files_edit',$content,true);
 }     
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	
            
              $ret['url']=$this->global_model->SET_title_url($_REQUEST['text']['name'],$this->page_name,(int)$_REQUEST['id']);
              $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);

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
                    $res=$sql->result();
                    $res=$res[0];

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'Y|n|j');
                         
                         $date=new stdClass();
                         list($date->year,$date->month,$date->day)=explode('|',$res->timestamp);
                         
                    $content['date']=$this->global_model->date_arr($date,2,2011);   
                    $content['data']=$res;
                }else{
                    $content['date']=$this->global_model->date_arr('',2,2011);
                    $content['data']='';
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
    	
    	$files    = $this->maindb_model->select_table('debt_market_files',' `debt_market_id`="'.(int)$this->uri->segment(4).'"','id','',false,'itembg',array('name'));
    	if(!empty($files)){
    		foreach($files as $item){
    				    	@unlink($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$item->_file);
	    	                $this->db->delete('debt_market_files',array('id'=>$item->id));
    		}
    	}
    	
        $this->db->query('delete from `analytics_reviews_pages` where `analitic_review_id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');
        $this->db->query('delete from `'.$this->page_name.'` where `id`="'.(int)$this->uri->segment(4).'" and `lang`="'.$this->site_lang.'";');

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
            $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
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
        
            $vals='`t`.`id`, `t`.`name`, `t`.`timestamp`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->date_format($res);
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
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.'
              order by `t`.`id` desc
              '.$limit.';';
        
        return $sql;
    }
    
}
?>
