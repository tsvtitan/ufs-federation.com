<?php
class backoffice_share_market_model_portfolio_model extends Model{

	function backoffice_share_market_model_portfolio_model()
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

              $ret['start_building'] = $this->global_model->date_arr_to_timstamp($_REQUEST['start_building']);
              $ret['display']        = isset($ret['display'])?implode(',',$ret['display']):''; 
              $ret['lang']           = $this->site_lang;

                $this->db->insert($this->page_name,$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name);

        }else{
            $res=new stdClass();
            $res->display='';
            $res=$this->global_model->adjustment_of_results($res,$this->page_name);
            
            $content['start_building']=$this->global_model->date_arr('',10,2008);
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }
    
    
    function transaction_add()
    {
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $next_id=$this->global_model->Auto_inc($this->page_name.'_transaction');

              $ret['date']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['portfolio_id']=(int)$this->uri->segment(4);
              $ret['sort_id']=$next_id;
              $ret['is_hide']=isset($ret['is_hide'])?'yes':'no';
              $ret['lang']=$this->site_lang;

                $this->db->insert($this->page_name.'_transaction',$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/transaction_view/'.(int)$this->uri->segment(4));

        }else{
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::transaction_head($content['portfolio_id']);
            
            $res=new stdClass();
            $res->type='';
            $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_transaction');
            
            $content['date']=$this->global_model->date_arr('',10,2008);
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_transaction_edit',$content,true);
        }
    }
    
    
    function composition_add()
    {
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $next_id=$this->global_model->Auto_inc($this->page_name.'_composition');

              $ret['maturity_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['maturity_date']);
              $ret['portfolio_id']=(int)$this->uri->segment(4);
              $ret['lang']=$this->site_lang;

                $this->db->insert($this->page_name.'_composition',$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/composition_view/'.(int)$this->uri->segment(4));

        }else{
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::composition_head($content['portfolio_id']);
            
            $res=new stdClass();
            $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_composition');
            
            $content['maturity_date']=$this->global_model->date_arr('',40,2011);
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_composition_edit',$content,true);
        }
    }
    
    
    function structure_add()
    {
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $next_id=$this->global_model->Auto_inc($this->page_name.'_structure');

              $ret['portfolio_id']=(int)$this->uri->segment(4);
              $ret['lang']=$this->site_lang;

                $this->db->insert($this->page_name.'_structure',$ret);

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/structure_view/'.(int)$this->uri->segment(4));

        }else{
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::structure_head($content['portfolio_id']);
            
            $res=new stdClass();
            $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_structure');
            
            $content['data']=$res;
            
            return $this->load->view('backoffice_'.$this->page_name.'_structure_edit',$content,true);
        }
    }
        
    
    function edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	
            
              $ret['start_building'] = $this->global_model->date_arr_to_timstamp($_REQUEST['start_building']);
              $ret['display']        = isset($ret['display'])?implode(',',$ret['display']):'';

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
                         $res=$this->global_model->date_format($res,'Y|n|j','start_building','start_building');
                         
                         $start_building=new stdClass();
                         list($start_building->year,$start_building->month,$start_buildinge->day)=explode('|',$res->start_building);
                         
                    $content['start_building']=$this->global_model->date_arr($start_building,10,2008);   
                    $content['data']=$res;
                }else{
                    $res=new stdClass();
                    $res->display = '';
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                    
                    $content['start_building']=$this->global_model->date_arr('',10,2008);
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
        }
    }    
        
    
    function transaction_edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	
            
              $ret['date']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
              $ret['is_hide']=isset($ret['is_hide'])?'yes':'no';

                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'_transaction` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `portfolio_id`="'.(int)$this->uri->segment(4).'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/transaction_view/'.(int)$this->uri->segment(4));
        }else{
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::transaction_head($content['portfolio_id']);

            $sql=$this->db->query('select * from `'.$this->page_name.'_transaction` 
                                   where `id`="'.(int)$this->uri->segment(5).'"
                                   and `portfolio_id`="'.(int)$this->uri->segment(4).'"
                                   and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_transaction');
                         $res=$this->global_model->date_format($res,'Y|n|j','date','date');
                         
                         $res->is_hide=($res->is_hide=='yes')?' checked="checked"':'';
                         
                         $date=new stdClass();
                         list($date->year,$date->month,$date->day)=explode('|',$res->date);
                         
                    $content['date']=$this->global_model->date_arr($date,10,2008);   
                    $content['data']=$res;
                }else{
                    $res=new stdClass();
                    $res->type='';
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_transaction');
                    
                    $content['date']=$this->global_model->date_arr('',10,2008);
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_transaction_edit',$content,true);
        }
    }    
        
    
    function composition_edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	
            
              $ret['maturity_date']=$this->global_model->date_arr_to_timstamp($_REQUEST['maturity_date']);

                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'_composition` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `portfolio_id`="'.(int)$this->uri->segment(4).'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/composition_view/'.(int)$this->uri->segment(4));
        }else{
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::composition_head($content['portfolio_id']);

            $sql=$this->db->query('select * from `'.$this->page_name.'_composition` 
                                   where `id`="'.(int)$this->uri->segment(5).'"
                                   and `portfolio_id`="'.(int)$this->uri->segment(4).'"
                                   and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_composition');
                         $res=$this->global_model->date_format($res,'Y|n|j','maturity_date','maturity_date');
                         
                         $maturity_date=new stdClass();
                         list($maturity_date->year,$maturity_date->month,$maturity_date->day)=explode('|',$res->maturity_date);
                         
                    $content['maturity_date']=$this->global_model->date_arr($maturity_date,40,2011);   
                    $content['data']=$res;
                }else{
                    $res=new stdClass();
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_composition');
                    
                    $content['maturity_date']=$this->global_model->date_arr('',40,2011);
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_composition_edit',$content,true);
        }
    }    
        
    
    function structure_edit()
    {                        
        if(isset($_REQUEST['submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);	

                $sql_set=$this->global_model->create_set_sql_string($ret);								

                $this->db->query('update `'.$this->page_name.'_structure` set 
                                    '.$sql_set.' 
                                  where `id`="'.(int)$_REQUEST['id'].'"
                                  and `portfolio_id`="'.(int)$this->uri->segment(4).'"
                                  and `lang`="'.$this->site_lang.'";');

                 $_SESSION['admin']->is_update=1;

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/structure_view/'.(int)$this->uri->segment(4));
        }else{
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::structure_head($content['portfolio_id']);

            $sql=$this->db->query('select * from `'.$this->page_name.'_structure` 
                                   where `id`="'.(int)$this->uri->segment(5).'"
                                   and `portfolio_id`="'.(int)$this->uri->segment(4).'"
                                   and `lang`="'.$this->site_lang.'";');
                if($sql->num_rows()>0){	
                    $res=$sql->row();

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_structure');
  
                    $content['data']=$res;
                }else{
                    $res=new stdClass();
                    $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_structure');
                    
                    $content['data']=$res;
                }
            return $this->load->view('backoffice_'.$this->page_name.'_structure_edit',$content,true);
        }
    }    
    
    
    function del()
    {  
        $this->db->query('delete from `'.$this->page_name.'` 
                          where `id`="'.(int)$this->uri->segment(4).'" 
                          and `lang`="'.$this->site_lang.'";');
        
        $this->db->query('delete from `'.$this->page_name.'_transaction` 
                          where `portfolio_id`="'.(int)$this->uri->segment(4).'" 
                          and `lang`="'.$this->site_lang.'";');
        
        $this->db->query('delete from `'.$this->page_name.'_composition` 
                          where `portfolio_id`="'.(int)$this->uri->segment(4).'" 
                          and `lang`="'.$this->site_lang.'";');
        
        $this->db->query('delete from `'.$this->page_name.'_structure` 
                          where `portfolio_id`="'.(int)$this->uri->segment(4).'" 
                          and `lang`="'.$this->site_lang.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name);
    }
    
    
    function transaction_del()
    {          
        $this->db->query('delete from `'.$this->page_name.'_transaction` 
                          where `id`="'.(int)$this->uri->segment(5).'" 
                          and `lang`="'.$this->site_lang.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name.'/transaction_view/'.(int)$this->uri->segment(4));
    }
    
    
    function composition_del()
    {          
        $this->db->query('delete from `'.$this->page_name.'_composition` 
                          where `id`="'.(int)$this->uri->segment(5).'" 
                          and `lang`="'.$this->site_lang.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name.'/composition_view/'.(int)$this->uri->segment(4));
    }
    
    
    function structure_del()
    {          
        $this->db->query('delete from `'.$this->page_name.'_structure` 
                          where `id`="'.(int)$this->uri->segment(5).'" 
                          and `lang`="'.$this->site_lang.'";');

		return redirect($this->uri->segment(1).'/'.$this->page_name.'/structure_view/'.(int)$this->uri->segment(4));
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
        
            $vals='`t`.*';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->date_format($res,'__timestamp__','start_timestamp','start_building');
                         $res=$this->global_model->date_format($res,'','start_building','start_building');
                        /*********************************/
                        
                        $res=self::transaction_count($res);
                        $res=self::composition_count($res);
                        $res=self::structure_count($res);
                        
                        $res=self::calc_yield($res);

                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    
    function transaction_view()
    { 
      $content['data']='';
      $content['portfolio_id']=(int)$this->uri->segment(4);
      self::transaction_head($content['portfolio_id']);
      
        
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where=' and `t`.`portfolio_id`="'.$content['portfolio_id'].'"';
        
        if(!empty($_SESSION['search']))
        {
            $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
        }
        
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::transaction_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->result();
            $res_r=$res_r[0];

            /* pager */ 
            $show=20;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/transaction_view/'.$content['portfolio_id'].'/';

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
            $vals='`t`.*';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::transaction_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_transaction');
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->set_sort_elemets($res);
                         $res=$this->global_model->date_format($res,'','date','date');
                        /*********************************/

                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_transaction_view',$content,true);
    }
    
    
    function composition_view()
    { 
      $content['data']='';
      $content['portfolio_id']=(int)$this->uri->segment(4);
      self::composition_head($content['portfolio_id']);
      
        
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where=' and `t`.`portfolio_id`="'.$content['portfolio_id'].'"';
        
        if(!empty($_SESSION['search']))
        {
            $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
        }
        
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::composition_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->result();
            $res_r=$res_r[0];

            /* pager */ 
            $show=20;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/composition_view/'.$content['portfolio_id'].'/';

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
            $vals='`t`.*';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::composition_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_composition');
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->set_numbers($res);
                         $res=$this->global_model->date_format($res,'','maturity_date','maturity_date');
                         $res=$this->global_model->date_format($res);
                        /*********************************/

                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_composition_view',$content,true);
    }
    
    
    function structure_view()
    { 
      $content['data']='';
      $content['portfolio_id']=(int)$this->uri->segment(4);
      self::structure_head($content['portfolio_id']);
      
        
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where=' and `t`.`portfolio_id`="'.$content['portfolio_id'].'"';
        
        if(!empty($_SESSION['search']))
        {
            $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
        }
        
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::structure_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){	
            $res_r=$sql_r->result();
            $res_r=$res_r[0];

            /* pager */ 
            $show=20;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/structure_view/'.$content['portfolio_id'].'/';

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
            /*********/
        
            $vals='`t`.*';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::structure_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){	
                    $res=$sql->result();	

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_structure');
                         $res=$this->global_model->rand_css_class($res);
                        /*********************************/

                    $content['pages']=$pages['html'];
                    $content['data']=$res;
                }                
          }
            
        return $this->load->view('backoffice_'.$this->page_name.'_structure_view',$content,true);
    }
    
    
    function transaction_sort()
    {
        return $this->global_model->item_sort($this->uri->segment(5),
                                              $this->uri->segment(6),
                                              $this->uri->segment(7),
                                              $this->page_name.'_transaction',
                                              ' and `'.$this->page_name.'_transaction`.`portfolio_id`="'.(int)$this->uri->segment(4).'"');  
    }  

    
    function structure_import_xls()
    {       
        if(isset($_REQUEST['submit'])){
            
            if($_FILES['_file']['error']==0){
                error_reporting(E_ALL ^ E_NOTICE);
                
                $exel_file=$_FILES['_file']['tmp_name'];
                $exel_type=explode('.',$_FILES['_file']['name']);
                $exel_type=end($exel_type);
                
                if($exel_type=='xls'){
                    $arr=self::structure_xls_reader($exel_file);
                }elseif($exel_type=='xlsx'){
                    $arr=self::structure_xlsx_reader($exel_file);
                }

                if(isset($arr)){
                        if(isset($_REQUEST['del_all'])){
                            $this->db->query('delete from `'.$this->page_name.'_structure` 
                                              where `lang`="'.$this->site_lang.'"
                                              and `portfolio_id`="'.(int)$this->uri->segment(4).'";');
                        }

                        foreach($arr as $item)
                        {
                            $ret=$this->global_model->adjustment_of_request_array($item);

                              $ret['lang']=$this->site_lang;
                              $ret['portfolio_id']=(int)$this->uri->segment(4);

                                $this->db->insert($this->page_name.'_structure',$ret);  
                        }

                   $_SESSION['admin']->is_update=1;
               }
            } 

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/structure_view/'.(int)$this->uri->segment(4));
        }else{	
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::composition_head($content['portfolio_id']);

            return $this->load->view('backoffice_'.$this->page_name.'_structure_import_xls',$content,true);
        }
    }    
    
    function composition_import_xls()
    {       
        if(isset($_REQUEST['submit'])){
            
            if($_FILES['_file']['error']==0){
                error_reporting(E_ALL ^ E_NOTICE);
                
                $exel_file=$_FILES['_file']['tmp_name'];
                $exel_type=explode('.',$_FILES['_file']['name']);
                $exel_type=end($exel_type);
                
                if($exel_type=='xls'){
                    $arr=self::composition_xls_reader($exel_file);
                }elseif($exel_type=='xlsx'){
                    $arr=self::composition_xlsx_reader($exel_file);
                }

                if(isset($arr)){
                        if(isset($_REQUEST['del_all'])){
                            $this->db->query('delete from `'.$this->page_name.'_composition` 
                                              where `lang`="'.$this->site_lang.'"
                                              and `portfolio_id`="'.(int)$this->uri->segment(4).'";');
                        }

                        foreach($arr as $item)
                        {
                            $ret=$this->global_model->adjustment_of_request_array($item);

                              $ret['lang']=$this->site_lang;
                              $ret['portfolio_id']=(int)$this->uri->segment(4);

                                $this->db->insert($this->page_name.'_composition',$ret);  
                        }

                   $_SESSION['admin']->is_update=1;
               }
            } 

            return redirect($this->uri->segment(1).'/'.$this->page_name.'/composition_view/'.(int)$this->uri->segment(4));
        }else{	
            $content['portfolio_id']=(int)$this->uri->segment(4);
            self::composition_head($content['portfolio_id']);

            return $this->load->view('backoffice_'.$this->page_name.'_composition_import_xls',$content,true);
        }
    }
    
   
    
    function structure_export_xls()
    {
        $vals='`t`.*';
        $where='and `t`.`portfolio_id`="'.(int)$this->uri->segment(4).'"';
        $limit=''; 
        $sql=$this->db->query(self::structure_sql_str($vals,$where,$limit));   
        
            $content['data']='';

            if($sql->num_rows()>0){	
                $res=$sql->result();	

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_structure');
                     $res=$this->global_model->set_numbers($res);
                     $res=$this->global_model->date_format($res,'','timestamp','timestamp');
                    /*********************************/

                $content['data']=$res;
            }                
            
            $xls=$this->load->view('backoffice_'.$this->page_name.'_structure_export_xls',$content,true);
        
        
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: text/x-csv; charset=utf-8');
        header("Content-Disposition: attachment;filename=model-portfolio-structure-".date("d-m-Y")."-export.xls");
        header("Content-Transfer-Encoding: binary ");
        
        echo($xls);
        die();
    }
        
     
    function composition_export_xls()
    {
        $vals='`t`.*';
        $where='and `t`.`portfolio_id`="'.(int)$this->uri->segment(4).'"';
        $limit=''; 
        $sql=$this->db->query(self::composition_sql_str($vals,$where,$limit));   
        
            $content['data']='';

            if($sql->num_rows()>0){	
                $res=$sql->result();	

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_composition');
                     $res=$this->global_model->set_numbers($res);
                     $res=$this->global_model->date_format($res,'','maturity_date','maturity_date');
                    /*********************************/

                $content['data']=$res;
            }                
            
            $xls=$this->load->view('backoffice_'.$this->page_name.'_composition_export_xls',$content,true);
        
        
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: text/x-csv; charset=utf-8');
        header("Content-Disposition: attachment;filename=model-portfolio-composition-".date("d-m-Y")."-export.xls");
        header("Content-Transfer-Encoding: binary ");
        
        echo($xls);
        die();
    }
    
    
##############################################################################
    
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
    
    
    private function transaction_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'_transaction` as `t`
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.'
              order by `t`.`sort_id` desc
              '.$limit.';';
        
        return $sql;
    }
    
    
    private function composition_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'_composition` as `t`
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.'
              order by `t`.`timestamp` asc
              '.$limit.';';
        
        return $sql;
    }
    
    
    private function structure_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'_structure` as `t`
              where `t`.`lang`="'.$this->site_lang.'"
              '.$where.'
              order by `t`.`id` desc
              '.$limit.';';
        
        return $sql;
    }
    
    
    private function transaction_head($portfolio_id)
    {
        if($portfolio_id>0){	
            $vals='`t`.`name`';
            $where='and `t`.`id`="'.$portfolio_id.'"';
            $limit='limit 1'; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit)); 

                if($sql->num_rows()>0){	
                    $res=$sql->row();

                    $this->tpl_header.='<br><small>('.stripslashes($res->name).' / '.
                                       $this->lang->line('admin_share_market_model_portfolio_last_transaction').')</small>';
                }
        }
        
    }
    
    
    private function composition_head($portfolio_id)
    {
        if($portfolio_id>0){	
            $vals='`t`.`name`';
            $where='and `t`.`id`="'.$portfolio_id.'"';
            $limit='limit 1'; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit)); 

                if($sql->num_rows()>0){	
                    $res=$sql->row();

                    $this->tpl_header.='<br><small>('.stripslashes($res->name).' / '.
                                       $this->lang->line('admin_share_market_model_portfolio_composition_portfolio').')</small>';
                }
        }
        
    }
    
    
    private function structure_head($portfolio_id)
    {
        if($portfolio_id>0){	
            $vals='`t`.`name`';
            $where='and `t`.`id`="'.$portfolio_id.'"';
            $limit='limit 1'; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit)); 

                if($sql->num_rows()>0){	
                    $res=$sql->row();

                    $this->tpl_header.='<br><small>('.stripslashes($res->name).' / '.
                                       $this->lang->line('admin_share_market_model_portfolio_structure_portfolio').')</small>';
                }
        }
        
    }
    
    
    private function transaction_count($ret)
    {        
        if(!is_array($ret)){	
           $ret=array($ret);
           $not_array=true;
        }
        
          for($i=0;$i<count($ret);$i++)
          {
            $vals='count("t") as `transaction_count`';
            $where='and `t`.`portfolio_id`="'.$ret[$i]->id.'"';
            $limit=''; 
            $sql=$this->db->query(self::transaction_sql_str($vals,$where,$limit)); 

                if($sql->num_rows()>0){	
                    $res=$sql->row();
                    $ret[$i]->transaction_count=$res->transaction_count;
                }else{
                    $ret[$i]->transaction_count=0;
                }
          }

         if(isset($not_array)){
             $ret=reset($ret);
         }
        
      return $ret;        
    }
    
    
    private function composition_count($ret)
    {        
        if(!is_array($ret)){	
           $ret=array($ret);
           $not_array=true;
        }
        
          for($i=0;$i<count($ret);$i++)
          {
            $vals='count("t") as `composition_count`';
            $where='and `t`.`portfolio_id`="'.$ret[$i]->id.'"';
            $limit=''; 
            $sql=$this->db->query(self::composition_sql_str($vals,$where,$limit)); 

                if($sql->num_rows()>0){	
                    $res=$sql->row();
                    $ret[$i]->composition_count=$res->composition_count;
                }else{
                    $ret[$i]->composition_count=0;
                }
          }

         if(isset($not_array)){
             $ret=reset($ret);
         }
        
      return $ret;        
    }
    
    
    private function structure_count($ret)
    {        
        if(!is_array($ret)){	
           $ret=array($ret);
           $not_array=true;
        }
        
          for($i=0;$i<count($ret);$i++)
          {
            $vals='count("t") as `structure_count`';
            $where='and `t`.`portfolio_id`="'.$ret[$i]->id.'"';
            $limit=''; 
            $sql=$this->db->query(self::structure_sql_str($vals,$where,$limit)); 

                if($sql->num_rows()>0){	
                    $res=$sql->row();
                    $ret[$i]->structure_count=$res->structure_count;
                }else{
                    $ret[$i]->structure_count=0;
                }
          }

         if(isset($not_array)){
             $ret=reset($ret);
         }
        
      return $ret;        
    }
    
    
    private function calc_yield($ret)
    {        
        if(!is_array($ret)){	
           $ret=array($ret);
           $not_array=true;
        }
        
          for($i=0;$i<count($ret);$i++)
          {
            $days=(time() - $ret[$i]->start_timestamp)/(60*60*24);
            $amount=($ret[$i]->current_value - $ret[$i]->initial_amount);
            
            if($days!=0 and $amount!=0 and $ret[$i]->initial_amount!=0){ 
                $ret[$i]->yield=($amount / $ret[$i]->initial_amount)/$days*365;
                $ret[$i]->yield=number_format($ret[$i]->yield,2,'.','');
            }else{
                $ret[$i]->yield=0.00;
            }
          }

         if(isset($not_array)){
             $ret=reset($ret);
         }
        
      return $ret;        
    }
    
    
        private function structure_xls_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/excel_reader2.php');
            $xls = new Spreadsheet_Excel_Reader();

            $xls->read($file);


            for($r=$xls->sheets[0]['numRows'];$r>=2;$r--)
            {
               $c=0;

                $arr[$r]['text']['name']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['select']['proportion_currency']=$xls->sheets[0]['cells'][$r][++$c];


                 $mdate=array();
                 $mdate['year']=date('Y',$arr[$r]['select']['timestamp']);
                 $mdate['month']=date('n',$arr[$r]['select']['timestamp']);
                 $mdate['day']=date('j',$arr[$r]['select']['timestamp']);

                 $arr[$r]['select']['timestamp']=$this->global_model->date_arr_to_timstamp($mdate);
            }
        
        return $arr;
    }
    
    private function structure_xlsx_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/simplexlsx.class.php');
            $obj = new SimpleXLSX($file);
            
            $xlsx=$obj->rows();

            for($r=(count($xlsx)-1);$r>=1;$r--)
            {
               $c=0;

                $arr[$r]['text']['name']=$xlsx[$r][++$c];
                $arr[$r]['select']['proportion_currency']=$xlsx[$r][++$c];



                 $mdate=array();
                 $mdate['year']=date('Y',$arr[$r]['select']['timestamp']);
                 $mdate['month']=date('n',$arr[$r]['select']['timestamp']);
                 $mdate['day']=date('j',$arr[$r]['select']['timestamp']);

                 $arr[$r]['select']['timestamp']=$this->global_model->date_arr_to_timstamp($mdate);
            }
        
        return $arr;
    }    
    
    private function composition_xls_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/excel_reader2.php');
            $xls = new Spreadsheet_Excel_Reader();

            $xls->read($file);


            for($r=$xls->sheets[0]['numRows'];$r>=2;$r--)
            {
               $c=1;

                $arr[$r]['text']['name']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['select']['maturity_date']=strtotime($xls->sheets[0]['cells'][$r][++$c]);
                $arr[$r]['float']['price_starting']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['price_current']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['nominal_volume']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['float']['cost']=$xls->sheets[0]['cells'][$r][++$c];


                 $mdate=array();
                 $mdate['year']=date('Y',$arr[$r]['select']['maturity_date']);
                 $mdate['month']=date('n',$arr[$r]['select']['maturity_date']);
                 $mdate['day']=date('j',$arr[$r]['select']['maturity_date']);

                 $arr[$r]['select']['maturity_date']=$this->global_model->date_arr_to_timstamp($mdate);
            }
        
        return $arr;
    }
    
    private function composition_xlsx_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/simplexlsx.class.php');
            $obj = new SimpleXLSX($file);
            
            $xlsx=$obj->rows();

            for($r=(count($xlsx)-1);$r>=1;$r--)
            {
               $c=0;

                $arr[$r]['text']['name']=$xlsx[$r][++$c];
                $arr[$r]['select']['maturity_date']=strtotime($xlsx[$r][++$c]);
                $arr[$r]['float']['price_starting']=$xlsx[$r][++$c];
                $arr[$r]['float']['price_current']=$xlsx[$r][++$c];
                $arr[$r]['float']['nominal_volume']=$xlsx[$r][++$c];
                $arr[$r]['float']['cost']=$xlsx[$r][++$c];


                 $mdate=array();
                 $mdate['year']=date('Y',$arr[$r]['select']['maturity_date']);
                 $mdate['month']=date('n',$arr[$r]['select']['maturity_date']);
                 $mdate['day']=date('j',$arr[$r]['select']['maturity_date']);

                 $arr[$r]['select']['maturity_date']=$this->global_model->date_arr_to_timstamp($mdate);
            }
        
        return $arr;
    }
    
}
?>
