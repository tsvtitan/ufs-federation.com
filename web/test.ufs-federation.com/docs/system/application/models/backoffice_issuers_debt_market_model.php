<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/system/application/libraries/Service.php';

class backoffice_issuers_debt_market_model extends Model{

	function backoffice_issuers_debt_market_model()
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
        
        if(!isset($_SESSION['issuers_debt_market_type'])){
           $_SESSION['issuers_debt_market_type']='euro'; 
        }
	}
    
    
    function add()
    {        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $next_id=$this->global_model->Auto_inc($this->page_name);

              $ret['maturity_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['mdate']);
              $ret['next_coupon']=$this->global_model->date_arr_to_timstamp($_REQUEST['next_coupon']);
              $ret['lang']=$this->site_lang;
              $ret['type']=$_SESSION['issuers_debt_market_type'];

                $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name);

        }else{
            $res=new stdClass();
            $res=$this->global_model->adjustment_of_results($res,$this->page_name);
            
            $content['mdate']=$this->global_model->date_arr('',40,2010);
            $content['next_coupon']=$this->global_model->date_arr('',40,2010);
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	
            
              $ret['maturity_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['mdate']);
              $ret['next_coupon']=$this->global_model->date_arr_to_timstamp($_REQUEST['next_coupon']);

                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `lang`="'.$this->site_lang.'"
                                  and `type`="'.$_SESSION['issuers_debt_market_type'].'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            $sql=$this->db->query('select * from `'.$this->page_name.'` 
                                   where `id`="'.(int)$this->uri->segment(4).'" 
                                   and `lang`="'.$this->site_lang.'"
                                   and `type`="'.$_SESSION['issuers_debt_market_type'].'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'Y|n|j','maturity_date','maturity_date');
                         $res=$this->global_model->date_format($res,'Y|n|j','next_coupon','next_coupon');
                         
                         $mdate=new stdClass();
                         list($mdate->year,$mdate->month,$mdate->day)=explode('|',$res->maturity_date);
                         
                         $n_coupon=new stdClass();
                         list($n_coupon->year,$n_coupon->month,$n_coupon->day)=explode('|',$res->next_coupon);
                         
                    $content['mdate']=$this->global_model->date_arr($mdate,40,2010);   
                    $content['next_coupon']=$this->global_model->date_arr($n_coupon,40,2010);   
                    $content['data']=$res;
                }else{
                    $res=new stdClass();
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                    
                    $content['mdate']=$this->global_model->date_arr('',40,2010);
                    $content['next_coupon']=$this->global_model->date_arr('',40,2010);
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        $this->db->query('delete from `'.$this->page_name.'` 
                          where `id`="'.(int)$this->uri->segment(4).'" 
                          and `lang`="'.$this->site_lang.'"
                          and `type`="'.$_SESSION['issuers_debt_market_type'].'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    
    function set_type()
    {
         switch($this->uri->segment(4))
         {
             case 'euro':
                 $_SESSION['issuers_debt_market_type']='euro';
             break;
             case 'rur':
                 $_SESSION['issuers_debt_market_type']='rur';
             break;
             case 'int_euro':
                 $_SESSION['issuers_debt_market_type']='int_euro';
             break;
         }
         
       header('Location: '.$_SESSION['save_last_url']);
       die();
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
                }

                if(isset($arr)){
                        if(isset($_REQUEST['del_all'])){
                            $this->db->query('delete from `'.$this->page_name.'` 
                                              where `lang`="'.$this->site_lang.'"
                                              and `type`="'.$_SESSION['issuers_debt_market_type'].'";');
                        }

                        foreach($arr as $item)
                        {
                            $ret=$this->global_model->adjustment_of_request_array($item);

                              $ret['lang']=$this->site_lang;
                              $ret['type']=$_SESSION['issuers_debt_market_type'];

                                $this->db->insert($this->page_name,$ret);  
                        }

                   $_SESSION['admin']->is_update=1;
               }*/
                
                $ret = $this->global_model->load_analytics_from_file($_FILES['_file']['tmp_name'],$exel_type,isset($_REQUEST['del_all']),false);
                if ($ret) {
                	$_SESSION['admin']->is_update=1;
                }
                
            } 

            return redirect($this->uri->segment(1).'/'.$this->page_name);
        }else{			

            return $this->load->view('backoffice_'.$this->page_name.'_import_xls','',true);
        }
    }
    
    
    function export_xls()
    {
        $vals='`t`.`name`, `t`.`isin`, `t`.`volume`, `t`.`currency`, 
               `t`.`rate`, `t`.`payments_per_year`, `t`.`maturity_date`, `t`.`next_coupon`, `t`.`closing_price`, 
               `t`.`income`, `t`.`duration`, `t`.`rating_sp`, `t`.`rating_moodys`, `t`.`rating_fitch`,
               t.industry, t.country';
        $where='';
        $limit=''; 
        $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));   
        
            $content['data']='';

            if($sql->num_rows()>0){	
                $res=$sql->result();	

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                     $res=$this->global_model->date_format($res,'','maturity_date','maturity_date');
                     $res=$this->global_model->date_format($res,'','next_coupon','next_coupon');
                     $res=$this->global_model->set_numbers($res);
                    /*********************************/

                $content['data']=$res;
            }                
            
            $xls=$this->load->view('backoffice_'.$this->page_name.'_export_xls',$content,true);
        
        
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: text/x-csv; charset=utf-8');
        header("Content-Disposition: attachment;filename=issuers-debt-market-".date("d-m-Y")."-export.xls");
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
        
        $r = $service->startJob('www/IssuersDebtMarket');
        
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

            /* pager */ 
            //$show=20;
            //$rows=$res_r->Rows;
            //$ret_page=((int)$this->uri->segment(5)==0)?1:(int)$this->uri->segment(5);
            //$param['url']='/view';

            //$pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
            $vals='`t`.`id`, `t`.`name`, `t`.`isin`, `t`.`volume`, `t`.`currency`, 
                   `t`.`rate`, `t`.`payments_per_year`, `t`.`maturity_date`, `t`.`next_coupon`, `t`.`closing_price`, 
                   `t`.`income`, `t`.`duration`, `t`.`rating_sp`, `t`.`rating_moodys`, `t`.`rating_fitch`,
                   t.industry, t.country';
            //$limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->date_format($res,'','mdate','maturity_date');
                         $res=$this->global_model->date_format($res,'','next_coupon','next_coupon');
                        /*********************************/

                    //$content['pages']=$pages['html'];
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
              and `t`.`type`="'.$_SESSION['issuers_debt_market_type'].'"
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
               $c=1;

                $arr[$r]['text']['name']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['select']['maturity_date']=strtotime($xls->sheets[0]['cells'][$r][++$c]);
                $arr[$r]['text']['isin']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['currency']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['closing_price']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['income']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['duration'][3]=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['rate']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['select']['next_coupon']=strtotime($xls->sheets[0]['cells'][$r][++$c]);    
                $arr[$r]['int']['volume']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['int']['payments_per_year']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['rating_sp']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['rating_moodys']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['rating_fitch']=$xls->sheets[0]['cells'][$r][++$c];


                 $mdate=array();
                 $mdate['year']=date('Y',$arr[$r]['select']['maturity_date']);
                 $mdate['month']=date('n',$arr[$r]['select']['maturity_date']);
                 $mdate['day']=date('j',$arr[$r]['select']['maturity_date']);

                 $arr[$r]['select']['maturity_date']=$this->global_model->date_arr_to_timstamp($mdate);
                 
                 $n_coupon=array();
                 $n_coupon['year']=date('Y',$arr[$r]['select']['next_coupon']);
                 $n_coupon['month']=date('n',$arr[$r]['select']['next_coupon']);
                 $n_coupon['day']=date('j',$arr[$r]['select']['next_coupon']);

                 $arr[$r]['select']['next_coupon']=$this->global_model->date_arr_to_timstamp($n_coupon);
            }
        
        return $arr;
    }
    
    private function xlsx_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/simplexlsx.class.php');
            $obj = new SimpleXLSX($file);
            
            $xlsx=$obj->rows();

            for($r=(count($xlsx)-1);$r>=2;$r--)
            {
               $c=0;

                $arr[$r]['text']['name']=$xlsx[$r][++$c];
                $arr[$r]['select']['maturity_date']=strtotime($xlsx[$r][++$c]);
                $arr[$r]['text']['isin']=$xlsx[$r][++$c];
                $arr[$r]['text']['currency']=$xlsx[$r][++$c];
                $arr[$r]['float']['closing_price']=$xlsx[$r][++$c];
                $arr[$r]['float']['income']=$xlsx[$r][++$c];
                $arr[$r]['float']['duration'][3]=$xlsx[$r][++$c];
                $arr[$r]['float']['rate']=$xlsx[$r][++$c];
                $arr[$r]['select']['next_coupon']=strtotime($xlsx[$r][++$c]);
                $arr[$r]['int']['volume']=$xlsx[$r][++$c];
                $arr[$r]['int']['payments_per_year']=$xlsx[$r][++$c];
                $arr[$r]['text']['rating_sp']=$xlsx[$r][++$c];
                $arr[$r]['text']['rating_moodys']=$xlsx[$r][++$c];
                $arr[$r]['text']['rating_fitch']=$xlsx[$r][++$c];


                 $mdate=array();
                 $mdate['year']=date('Y',$arr[$r]['select']['maturity_date']);
                 $mdate['month']=date('n',$arr[$r]['select']['maturity_date']);
                 $mdate['day']=date('j',$arr[$r]['select']['maturity_date']);

                 $arr[$r]['select']['maturity_date']=$this->global_model->date_arr_to_timstamp($mdate);
                 
                 $n_coupon=array();
                 $n_coupon['year']=date('Y',$arr[$r]['select']['next_coupon']);
                 $n_coupon['month']=date('n',$arr[$r]['select']['next_coupon']);
                 $n_coupon['day']=date('j',$arr[$r]['select']['next_coupon']);

                 $arr[$r]['select']['next_coupon']=$this->global_model->date_arr_to_timstamp($n_coupon);
            }
        
        return $arr;
    }
    
}
?>
