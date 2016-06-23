<?php
class backoffice_economics_model extends Model{

	function backoffice_economics_model()
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
    
    
    function import_xls()
    {       
        if(isset($_REQUEST['submit'])){
            
            if($_FILES['_file']['error']==0){
                error_reporting(E_ALL ^ E_NOTICE);
                
                $exel_file=$_FILES['_file']['tmp_name'];
                $exel_type=explode('.',$_FILES['_file']['name']);
                $exel_type=end($exel_type);
                
                /*if($exel_type=='xls'){
                    $arr=self::xls_reader($exel_file);
                }elseif($exel_type=='xlsx'){
                    $arr=self::xlsx_reader($exel_file);
                }*/
                
                $this->global_model->load_analytics_from_file($_FILES['_file']['tmp_name'],$exel_type,isset($_REQUEST['del_all']));

                if(isset($arr)){
                        if(isset($_REQUEST['del_all'])){
                            $this->db->query('delete from `'.$this->page_name.'` 
                                              where `lang`="'.$this->site_lang.'";');
                        }

                        foreach($arr as $item)
                        {
                            $ret=$this->global_model->adjustment_of_request_array($item);

                            $ret['lang']=$this->site_lang;
                            $this->db->insert($this->page_name,$ret);  
                        }

                   $_SESSION['admin']->is_update=1;
               }
            } 

            return redirect($this->uri->segment(1).'/'.$this->page_name);
            
        } else {			

            return $this->load->view('backoffice_'.$this->page_name.'_import_xls','',true);
        }
    }
    
    
    function export_xls()
    {
        $vals='`t`.`name`, `t`.`sector`, `t`.`placement`, `t`.`volume`';
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
        header("Content-Disposition: attachment;filename=planned_placements-".date("d-m-Y")."-export.xls");
        header("Content-Transfer-Encoding: binary ");
        
        echo($xls);
        die();
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

            $vals='`t`.`id`, `t`.`name`, `t`.`sector`, `t`.`placement`, `t`.`volume`';
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
    
    private function xls_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/excel_reader2.php');
            $xls = new Spreadsheet_Excel_Reader();

            $xls->read($file);

            for($r=$xls->sheets[0]['numRows'];$r>=3;$r--)
            {
               $c=0;

                $name = $xls->sheets[0]['cells'][$r][++$c];
                if (trim($name)<>'') {
                  $arr[$r]['text']['name']=$name;
                  $arr[$r]['text']['sector']=$xls->sheets[0]['cells'][$r][++$c];
                  $arr[$r]['text']['placement']=$xls->sheets[0]['cells'][$r][++$c];
                  $arr[$r]['text']['volume']=$xls->sheets[0]['cells'][$r][++$c];
                }  
            }
        
        return $arr;
    }
    
    private function xlsx_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/simplexlsx.class.php');
            $obj = new SimpleXLSX($file);
            
            $xlsx=$obj->rows();

            for($r=(count($xlsx)-1);$r>=2;$r--) {
               $c=-1;

               $name = $xlsx[$r][++$c];
               if (trim($name)<>'') {
                 $arr[$r]['text']['name']=$name;
                 $arr[$r]['text']['sector']=$xlsx[$r][++$c];
                 $arr[$r]['text']['placement']=$xlsx[$r][++$c];
                 $arr[$r]['text']['volume']=$xlsx[$r][++$c];
               }  
            }
        
        return $arr;
    }
    
}
?>