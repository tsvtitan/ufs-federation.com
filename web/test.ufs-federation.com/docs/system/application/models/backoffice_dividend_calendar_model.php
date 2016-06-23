<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/system/application/libraries/Service.php';

class backoffice_dividend_calendar_model extends Model{

	function backoffice_dividend_calendar_model()
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

            $ret['lang']=$this->site_lang;

            $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name);

        }else{
            $res=new stdClass();
            $res=$this->global_model->adjustment_of_results($res,$this->page_name);
            
            $content['data']=$res;
            
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
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `lang`="'.$this->site_lang.'";');

            $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'` 
                                   where `id`="'.(int)$this->uri->segment(4).'" 
                                   and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                    $res=$this->global_model->adjustment_of_results($res,$this->page_name);  
                    $content['data']=$res;
                }else{
                    $res=new stdClass();
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                    
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        $this->db->query('delete from `'.$this->page_name.'` 
                          where `id`="'.(int)$this->uri->segment(4).'" 
                          and `lang`="'.$this->site_lang.'";');

		    return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    
    
    function export_xls()
    {
        $vals='`t`.`name`, `t`.`ticker`,`t`.`sector`, `t`.`dividend_type`, `t`.`dividends`, 
               `t`.`dividend_yield`, `t`.`close_date`, `t`.`price`';
        $where='';
        $limit=''; 
        $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));   
        
            $content['data']='';

            if($sql->num_rows()>0){	
                $res=$sql->result();	

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                     $res=$this->global_model->set_numbers($res);
                    /*********************************/

                $content['data']=$res;
            }                
            
            $xls=$this->load->view('backoffice_'.$this->page_name.'_export_xls',$content,true);
        
        
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: text/x-csv; charset=utf-8');
        header("Content-Disposition: attachment;filename=dividend_calendar-".date("d-m-Y")."-export.xls");
        header("Content-Transfer-Encoding: binary ");
        
        echo($xls);
        die();
    }
    
    private function set_service_not_found() {
      $this->global_model->set_message($this->lang->line('admin_service_not_found'));
    }
    
    private function set_service_cannot_start_job() {
      $this->global_model->set_message($this->lang->line('admin_service_cannot_start_job'));
    }
    
    function recalc() {
      
      $service = new service\Service();
      if ($service->connect()) {
        
        $r = $service->startJob('www/DividendCalendar');
        
        if (!($r && !$r->error)) {
          $this->set_service_cannot_start_job();
        }
        
      } else {
        $this->set_service_not_found();
      }
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

            $vals='`t`.*';
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                    $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                    $res=$this->global_model->rand_css_class($res);

                    $content['data']=$res;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    
    private function view_sql_str($vals,$where,$limit)
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
