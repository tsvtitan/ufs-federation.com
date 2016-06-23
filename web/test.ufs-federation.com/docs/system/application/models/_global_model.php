<?
class global_model extends Model{

	function global_model()
	{
		parent::Model();
		
		//session_start();
		
		$this->load->helper('url');
		$this->load->helper('pic');
		$this->load->helper('string');
		$this->load->helper('email');
		$this->load->helper('file');
		$this->load->helper('xml');
	}
    
    /* set admin menu */
    function admin_menu()
    {
        $ret=array();
        
            $ret['home']                           = array('name'=>$this->lang->line('admin_menu_home'),'sel'=>'');

            $ret['logins']                         = array('name'=>$this->lang->line('admin_menu_logins'),'sel'=>'');
            $ret['permissions']                    = array('name'=>$this->lang->line('admin_menu_permissions'),'sel'=>'');
            
            $ret['pages']                          = array('name'=>$this->lang->line('admin_menu_pages'),'sel'=>'');
            $ret['analytics_team']                 = array('name'=>$this->lang->line('admin_menu_analytics_team'),'sel'=>'');
			$ret['contacts']                       = array('name'=>$this->lang->line('admin_menu_contacts'),'sel'=>'');
			$ret['downloads']                      = array('name'=>$this->lang->line('admin_menu_downloads'),'sel'=>'');
			$ret['disclosure_of_information']      = array('name'=>$this->lang->line('admin_menu_disclosure_of_information'),'sel'=>'');
			$ret['dictionary']                     = array('name'=>$this->lang->line('admin_menu_dictionary'),'sel'=>'');
			$ret['settings']                       = array('name'=>$this->lang->line('admin_menu_settings'),'sel'=>'');
			
			$ret['news']                           = array('name'=>$this->lang->line('admin_menu_news'),'sel'=>'');
            $ret['press_about_us']                 = array('name'=>$this->lang->line('admin_menu_press_about_us'),'sel'=>'');
            $ret['conferencies']                   = array('name'=>$this->lang->line('admin_menu_conferencies'),'sel'=>'');
            $ret['feedback']                       = array('name'=>$this->lang->line('admin_menu_feedback'),'sel'=>'');
			
			$ret['analytics_reviews']              = array('name'=>$this->lang->line('admin_menu_analytics_reviews'),'sel'=>'');
			$ret['analytics_news']                 = array('name'=>$this->lang->line('admin_menu_analytics_news'),'sel'=>'');
			$ret['questions']                      = array('name'=>$this->lang->line('admin_menu_questions'),'sel'=>'');
			$ret['issuers_debt_market']            = array('name'=>$this->lang->line('admin_menu_issuers_debt_market'),'sel'=>'');
            $ret['trade_ideas']                    = array('name'=>$this->lang->line('admin_menu_trade_ideas'),'sel'=>'');
            $ret['recommendations']                = array('name'=>$this->lang->line('admin_menu_recommendations'),'sel'=>''); // рекомендации
            $ret['commodities']                    = array('name'=>$this->lang->line('admin_menu_commodities'),'sel'=>'');
            $ret['planned_placements']             = array('name'=>$this->lang->line('admin_menu_planned_placements'),'sel'=>'');
            
            // $ret['share_market_daily_comments']    = array('name'=>$this->lang->line('admin_menu_share_market_daily_comments'),'sel'=>'');
            //$ret['share_market_reviews_issuers']   = array('name'=>$this->lang->line('admin_menu_share_market_reviews_issuers'),'sel'=>'');
            //$ret['share_market_special_comments']  = array('name'=>$this->lang->line('admin_menu_share_market_special_comments'),'sel'=>'');
            //$ret['share_market_trading_ideas']     = array('name'=>$this->lang->line('admin_menu_share_market_trading_ideas'),'sel'=>'');
            $ret['share_market_model_portfolio']   = array('name'=>$this->lang->line('admin_menu_share_market_model_portfolio'),'sel'=>'');
            $ret['analytics_calendar_statistics']  = array('name'=>$this->lang->line('admin_menu_analytics_calendar_statistics'),'sel'=>'');
           // $ret['debt_market_surveys_on_emitter'] = array('name'=>$this->lang->line('admin_menu_debt_market_surveys_on_emitter'),'sel'=>'');
		    
            $ret['quik_news']                      = array('name'=>$this->lang->line('admin_menu_quik_news'),'sel'=>'');
            $ret['analytics_reviews_groups']       = array('name'=>$this->lang->line('admin_menu_analytics_reviews_groups'),'sel'=>'');
            
            
        return $ret;
    }
 
 
 	/* ssl check function */
	function ssl_check($ssl=true)
	{
		if($_SERVER['SERVER_PORT']!=443 and $ssl==true){
			header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			die();
		}elseif($_SERVER['SERVER_PORT']==443 and $ssl==false){
			header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			die();
        }
	}

	//component array	
	function component_arr($table,$temp_id,$orderby='name',$nat_sort=false,$one_name=false,$asc_desc='asc',$ret_name='name',$row_par='id')
	{
	$ret='';
	
	$nat_str=($nat_sort==true)?'+0':'';
	
		switch($one_name)
		{
			case true:
                $set_lang=(isset($this->site_lang) and $table!='lang')?' and `lang`="'.$this->site_lang.'"':'';
				$sql=$this->db->query('select * from `'.$table.'` where `'.$row_par.'`="'.$temp_id.'"'.$set_lang.';');
					if($sql->num_rows()>0){	
						$res=$sql->result();
						$ret=stripcslashes($res[0]->$ret_name);
					}else{
						$ret='';
					}
			break;
			
			case false:
                $set_lang=(isset($this->site_lang) and $table!='lang')?' where `lang`="'.$this->site_lang.'"':'';
				$sql=$this->db->query('select * from `'.$table.'`'.$set_lang.' order by `'.$orderby.'`'.$nat_str.' '.$asc_desc.';');
					if($sql->num_rows()>0){	
						$res=$sql->result();	
							if(is_array($temp_id)){
							
								for($i=0;$i<count($res);$i++){
									$res[$i]->$ret_name=stripcslashes($res[$i]->$ret_name);
									$res[$i]->select='';
										foreach($temp_id as $item){
											if($item==$res[$i]->$row_par){
												$res[$i]->select=' selected="selected"';
												break;
											}
										}
								}
								
							}else{
							
								for($i=0;$i<count($res);$i++){
									$res[$i]->$ret_name=stripcslashes($res[$i]->$ret_name);
									
									if($temp_id==$res[$i]->$row_par){
										$res[$i]->select=' selected="selected"';
										if($one_name==true){
											$ret=$res[$i]->$ret_name;
											break;
										}
									}else{
										$res[$i]->select='';
									}
								}
								
							}
						$ret=$res;
					}else{
						$ret=array();
					}
			break;
		}
		
	return $ret;
	}


	function adjustment_of_results($res,$table,$set_xml=false,$trim=false)
	{
        //---
         $sql_d=$this->db->query('describe `'.$table.'`;');
         $res_d=$sql_d->result();
        //---


        if(!is_array($res)){
            $not_array=true;
            $tmp=array($res);
        }else{ 
            $tmp=$res;
        }

            for($i=0;$i<count($tmp);$i++)
            {
                foreach($res_d as $item){
                    $param=$item->Field;
                    if(isset($tmp[$i]->$param)){
                        $tmp[$i]->$param=stripcslashes($tmp[$i]->$param);

                        if($set_xml==true)
                        {
                            $tmp[$i]->$param=HTML_decode($tmp[$i]->$param);
                            $tmp[$i]->$param=preg_replace('/\&/', '&amp;', $tmp[$i]->$param);
                        }

                        if($trim==true)
                        {
                            $tmp[$i]->$param=trim($tmp[$i]->$param);
                        }
                        
                        if($param=='short_content' and isset($this->front_trim_short)){
                            $tmp[$i]->$param=character_limiter(strip_tags($tmp[$i]->$param),350);
                        }
                    }
                    
                    if($set_xml==false){
                        if(preg_match('/^(enum|set)\(\'(.[^\(\)]+)\'\)$/',$item->Type,$arr))
                        {
                            $arr_par=$param.'_array';
                            $tmp[$i]->$arr_par=explode("','",$arr[2]);
                            
                            if($arr[1]=='set')
                            {
                               $arr_par=$param.'_multi_sel';
                               $tmp[$i]->$arr_par=explode(',',$tmp[$i]->$param); 
                               $tmp[$i]->$param=str_replace(',',', ',$tmp[$i]->$param);
                            }
                        }
                    }
                }
            }

        if(isset($not_array)){
            $res=reset($tmp);
        }

	  return $res;
	}
	
	
	function rand_css_class($res,$css_class='itembg',$last_css='')
	{	
	   $x=0;
		for($i=0;$i<count($res);$i++)
		{									
			 if($x==1){
				$res[$i]->css_class=' class="'.$css_class.'"';
				$x=0;
			 }else{
				$res[$i]->css_class='';
				$x=1;
			 }
		}
        
        if(!empty($last_css)){
           $res[count($res)-1]->css_class=' class="'.$last_css.'"'; 
        }
		
	  return $res;
	}
	
	function set_sort_elemets($res,$id_name='id')
	{	
	 $first_el=reset($res); 
	 $end_el=end($res);
		
		for($i=0;$i<count($res);$i++)
		{	
			if (!isset($res[$i]->items)) {							
			  $res[$i]->first_el=($res[$i]->{$id_name}==$first_el->{$id_name})?1:0;
			  $res[$i]->end_el=($res[$i]->{$id_name}==$end_el->{$id_name})?1:0;
			} else {
			  if (isset($res[$i]->level)) {
			  	
			  	$res[$i]->first_el=0;
			  	$res[$i]->end_el=0;
			  }		
			  
			}  	
		}
		
	  return $res;
	}

	function set_numbers($res)
	{			
		for($i=0;$i<count($res);$i++)
		{									
			$res[$i]->set_number=($i+1);	
		}
		
	  return $res;
	}

	/* date arr */
	function date_arr($date_temp='',$limit=5,$year=2005)
	{
	 $date=new stdClass();
	 
		//years
		for($i=(int)$year;$i<((int)date("Y")+(int)$limit);$i++){
			$date->year[$i]->id=$i;
			$date->year[$i]->select='';
		}
		
			if(isset($date_temp->year)){
                if(isset($date->year[(int)$date_temp->year])){
                    $date->year[(int)$date_temp->year]->select=' selected="selected"';
                }else{
                    $date->year[(int)date('Y')]->select=' selected="selected"';
                }
			}else{
				$date->year[(int)date('Y')]->select=' selected="selected"';
			}
			
			
		//months
		for($i=1;$i<=12;$i++){
			$date->month[$i]->id=$i;
			$date->month[$i]->select='';
		}
		
			if(isset($date_temp->month)){
				$date->month[(int)$date_temp->month]->select=' selected="selected"';
			}else{
				$date->month[(int)date('m')]->select=' selected="selected"';
			}
			
			
		//days
		for($i=1;$i<=31;$i++){
			$date->day[$i]->id=$i;
			$date->day[$i]->select='';
		}
		
			if(isset($date_temp->day)){
				$date->day[(int)$date_temp->day]->select=' selected="selected"';
			}else{
				$date->day[(int)date('d')]->select=' selected="selected"';
			}
            
            
		//hour
		for($i=0;$i<=23;$i++){
			$date->hour[$i]->id=$i;
			$date->hour[$i]->select='';
		}
		
			if(isset($date_temp->hour)){
				$date->hour[(int)$date_temp->hour]->select=' selected="selected"';
			}else{
				$date->hour[(int)date('H')]->select=' selected="selected"';
			}
            
            
		//minute
		for($i=0;$i<=59;$i++){
			$date->minute[$i]->id=$i;
			$date->minute[$i]->select='';
		}
		
			if(isset($date_temp->minute)){
				$date->minute[(int)$date_temp->minute]->select=' selected="selected"';
			}else{
				$date->minute[(int)date('i')]->select=' selected="selected"';
			}
            

        //seconds
		for($i=0;$i<=59;$i++){
			$date->seconds[$i]->id=$i;
			$date->seconds[$i]->select='';
		}
		
			if(isset($date_temp->seconds)){
				$date->seconds[(int)$date_temp->seconds]->select=' selected="selected"';
			}else{
				$date->seconds[(int)date('s')]->select=' selected="selected"';
			}    
			
			
	 return $date;
	}
	
	/* months rus */
	function months_ru($ret=0)
	{		
		$months='';
		$months[1]='Январь';
		$months[2]='Февраль';
		$months[3]='Март';
		$months[4]='Апрель';
		$months[5]='Май';
		$months[6]='Июнь';
		$months[7]='Июль';
		$months[8]='Август';
		$months[9]='Сентябрь';
		$months[10]='Октябрь';
		$months[11]='Ноябрь';
		$months[12]='Декабрь';
		
		
		$ret=$months[date('n',$ret)];			
			
	 return $ret;
	}
    
    function date_arr_to_timstamp($date=array())
    {
        if(!isset($date['year'])){
            $date['year']=(int)date('Y');
        }
        if(!isset($date['month'])){
            $date['month']=(int)date('m');
        }
        if(!isset($date['day'])){
            $date['day']=(int)date('d');
        }
        if(!isset($date['hour'])){
            $date['hour']=(int)date('H');
        }
        if(!isset($date['minute'])){
            $date['minute']=(int)date('i');
        }
        if(!isset($date['seconds'])){
            $date['seconds']=(int)date('s');
        }
        
        if($date['month']<10){
            $date['month']='0'.$date['month'];
        }
        if($date['day']<10){
            $date['day']='0'.$date['day'];
        }
        if($date['hour']<10){
            $date['hour']='0'.$date['hour'];
        }
        if($date['minute']<10){
            $date['minute']='0'.$date['minute'];
        }
        if($date['seconds']<10){
            $date['seconds']='0'.$date['seconds'];
        }
        
        $ret=$date['year'].'-'.$date['month'].'-'.$date['day'].' '.$date['hour'].':'.$date['minute'].':'.$date['seconds'];
        return $ret;
    }
	
	function convert_ret_array($arr,$ses_name='admin')
	{
		
		if(isset($_SESSION[$ses_name]->form_ret)){
			foreach($_SESSION[$ses_name]->form_ret as $kay=>$val)
			{
				$arr->$kay=$val;
			}
			unset($_SESSION[$ses_name]->form_ret);
		}
		
		return $arr;
	}
	

	/*function create_set_sql_string($ret){
		$sql_set=''; $x=0;
		foreach($ret as $kay=>$val)
		{
			if($x==0){
				$sql_set.='`'.$kay.'`="'.$val.'"';
				$x=1;
			}else{
				$sql_set.=', `'.$kay.'`="'.$val.'"';
			}
		}
	  return $sql_set;
	}*/

	function create_set_sql_string($ret){
		$sql_set=''; 
		$x=0;
		$s="";
		
		foreach($ret as $kay=>$val)
		{
			if (is_null($val)) {
			  $s="null";
			} else {
			  $s="'".$val."'";
			}
			
			if($x==0){
				$sql_set.='`'.$kay.'`='.$s;
				$x=1;
			}else{
				$sql_set.=', `'.$kay.'`='.$s;
			}
		}
		return $sql_set;
	}


	function adjustment_of_request_array($request)
	{
	 $ret='';
	 
	 	if(isset($request['text']))
		{
			foreach($request['text'] as $kay=>$val)
			{
				$ret[$kay]=mysql_string($val);
			}
		}
		
	 	if(isset($request['fck']))
		{
			foreach($request['fck'] as $kay=>$val)
			{
				$ret[$kay]=mysql_string_fck($val);
			}
		}
		
	 	if(isset($request['select']))
		{
			foreach($request['select'] as $kay=>$val)
			{
				$ret[$kay]=$val;
			}
		}
		
	 	if(isset($request['password']))
		{
			foreach($request['password'] as $kay=>$val)
			{
				if(!empty($val)){
					$ret[$kay]=md5($val);
				}
			}
		}
        
	 	if(isset($request['float']))
		{
            foreach($request['float'] as $kay=>$val)
            {
                if(is_array($val)){
                    list($numb,$val)=each($val);
                }else{
                    $numb=2;
                }
                
                $val=str_replace(',','.',$val);
                if (is_numeric($val)) {
                  $ret[$kay]=number_format($val,$numb,'.','');
                }
            }
		}
        
	 	if(isset($request['int']))
		{
			foreach($request['int'] as $kay=>$val)
			{
				$val=str_replace(',','.',$val);
                $ret[$kay]=number_format($val,0,'.','');
			}
		}
	 	
	 return $ret;
	}
	
	
	/*sort function*/
	function item_sort($move,$id_1,$sort_id_1,$table='',$param='', $join_par=''){
		if(($move!='up' and $move!='down') or empty($id_1) or empty($sort_id_1)){ die(); }
		  
          $id_1=(int)$id_1;
          $sort_id_1=(int)$sort_id_1;
        
		$table=empty($table)?$this->page_name:$table;
        $set_lang=isset($this->site_lang)?' and `'.$table.'`.`lang`="'.$this->site_lang.'"':'';
		
		switch ($move){
			case 'up':
				$sql=$this->db->query('select `'.$table.'`.`id`, `'.$table.'`.`sort_id` from `'.$table.'`'.$join_par.' where `'.$table.'`.`sort_id`>"'.$sort_id_1.'"'.$set_lang.$param.' order by `'.$table.'`.`sort_id` asc limit 1;');
				$res=$sql->result();
					$id_2=$res[0]->id;	
					$sort_id_2=$res[0]->sort_id;	
				break;
											
			case 'down':
				$sql=$this->db->query('select `'.$table.'`.`id`, `'.$table.'`.`sort_id` from `'.$table.'`'.$join_par.' where `'.$table.'`.`sort_id`<"'.$sort_id_1.'"'.$set_lang.$param.' order by `'.$table.'`.`sort_id` desc limit 1;');
				$res=$sql->result();
					$id_2=$res[0]->id;	
					$sort_id_2=$res[0]->sort_id;	
				break;
		}
												
		$this->db->query('update `'.$table.'` set `sort_id`="'.$sort_id_2.'" where `id`="'.$id_1.'";');
		$this->db->query('update `'.$table.'` set `sort_id`="'.$sort_id_1.'" where `id`="'.$id_2.'";');
								
	header('Location: '.$_SERVER['HTTP_REFERER']);
	die();		
	}
	
	
	/*pager function*/
	function Pager($show,$rows=0,$param='',$ret_page=0)
	{
	    $pages['arr']=array();
		$pages['rows']=$rows;
		$pages['show']=$show;
        $param['tpl_set']=isset($param['tpl_set'])?$param['tpl_set']:'body_pager';
        $param['url']=isset($param['url'])?$param['url']:'';
        
	  		if($ret_page>0){
				$pages['ret_page']=$ret_page;
			}else{
				$pages['ret_page']=((int)$this->uri->segment(4)==0)?1:(int)$this->uri->segment(4);
			}
			
			$pages['count']=ceil($pages['rows']/$pages['show']);
			$param['count']=$pages['count'];
			$pages['start']=$pages['show']*($pages['ret_page']-1);
			
			$min=(($pages['ret_page']>4) ? ($pages['ret_page']-2) : 1);
			$max=(($pages['count']-$pages['ret_page'])>3) ? ($pages['ret_page']+2) : $pages['count'];
				
				if($pages['ret_page']>1){
					$pages['arr'][]=array('id'=>($pages['ret_page']-1),'type'=>'prev');
				}

				
				if($pages['ret_page']>4){
					$pages['arr'][]=array('id'=>'1','type'=>'link');
					$pages['arr'][]=array('id'=>'...','type'=>'text');
				}
				
				
				for($i=$min;$i<=$max;$i++){ 
					if($pages['ret_page']==$i){
						$pages['arr'][]=array('id'=>$i,'type'=>'select'); 
					}else{
						$pages['arr'][]=array('id'=>$i,'type'=>'link'); 
					}
				}
				
				
				if(($pages['count']-$pages['ret_page'])>3){
					$pages['arr'][]=array('id'=>'...','type'=>'text');
					$pages['arr'][]=array('id'=>$pages['count'],'type'=>'link');				
				}
				
				
				if(($pages['count']-$pages['ret_page'])>0){
					$pages['arr'][]=array('id'=>($pages['ret_page']+1),'type'=>'next');
				}
				
				$content['param']=$param;
				$content['data']=$pages['arr'];							
				$pages['html']=$this->load->view($param['tpl_set'],$content,true);
			
	 return $pages;
	}
	
	function Auto_inc($table)
	{
		$sql=$this->db->query('show table status like "'.$table.'";');
		$res=$sql->result();

            return $res[0]->Auto_increment;
	}
	
	function Count_table($table,$where='',$join='',$select='id')
	{
        $where=empty($where)?'':' '.$where;
        $join=empty($join)?'':' '.$join;
        $set_lang=isset($this->site_lang)?((empty($where)?' where':' and').' `'.$table.'`.`lang`="'.$this->site_lang.'"'):'';

            $sql=$this->db->query('select `'.$table.'`.`'.$select.'` from `'.$table.'`'.$join.$where.$set_lang.';');

        return $sql->num_rows();
	}

    function SET_title_url($title,$table,$id=0,$where='')
	{
	  $url='';
	  $url=url_title($title,true);
      $set_lang=isset($this->site_lang)?' and `lang`="'.$this->site_lang.'"':'';
		
		if($id>0){
			$sql=$this->db->query('select `id` from `'.$table.'` where `url`="'.$url.'" and `id`!="'.$id.'"'.$set_lang.$where.';');
			if($sql->num_rows()>0 or $url=='all'){					
				$url=$id.'-'.$url;
			}
		}else{
			$sql=$this->db->query('select `id` from `'.$table.'` where `url`="'.$url.'"'.$set_lang.$where.';');
			if($sql->num_rows()>0 or $url=='all'){					
				$id=$this->global_model->Auto_inc($table);
				$url=$id.'-'.$url;
			}
		}
			
	  return $url;
	}
	
	function GET_menu_first_cat($table,$parent=true)
	{
        $where=($parent==true)?' where `parent_id`="0"':'';
        $set_lang=isset($this->site_lang)?(empty($where)?' where':' and').' `lang`="'.$this->site_lang.'"':'';
        
		$sql=$this->db->query('select `id` from `'.$table.'`'.$where.$set_lang.' order by `sort_id` desc limit 1;');
		if($sql->num_rows()>0){	
		   $res=$sql->result();
		   return $res[0]->id;	
		}else{
		   return 1;
		}
	}

	function GET_menu($table,$id=0,$sel='sel')
	{
	  $menu=array();
      $set_lang=isset($this->site_lang)?' where `lang`="'.$this->site_lang.'"':'';
			
		$sql=$this->db->query('select * from `'.$table.'`'.$set_lang.' order by `sort_id` desc;');
		if($sql->num_rows()>0){	
		   $res=$sql->result();			
		   	
			for($i=0;$i<count($res);$i++)
			{
				$res[$i]->name=stripcslashes($res[$i]->name);
				
				if(is_array($id)){
					$res[$i]->sel='';
					for($m=0;$m<count($id);$m++)
					{
						if($res[$i]->id==$id[$m]){
							$res[$i]->sel=$sel;
							break;
						}
					}
				}else{
					$res[$i]->sel=($res[$i]->id==$id)?$sel:'';
				}	
			}
			
		  $menu=$res;
		}
			
	  return $menu;
	}

	function GET_contacts($table,$id=0,$sel='sel')
	{
		$contacts=array();
		$set_lang=isset($this->site_lang)?' where `lang`="'.$this->site_lang.'"':'';
			
		$sql=$this->db->query('select * from `'.$table.'`'.$set_lang.' order by `name` asc;');
		
		if($sql->num_rows()>0){
		  $res=$sql->result();
	
		  for($i=0;$i<count($res);$i++) {
	  		$res[$i]->name=stripcslashes($res[$i]->name);
	
			if(is_array($id)){
			
			  $res[$i]->sel='';
			  
			  for($m=0;$m<count($id);$m++) {
			    
			  	if($res[$i]->id==$id[$m]) {
			      $res[$i]->sel=$sel;
				  break;
			    }
			  }
			} else {
			  $res[$i]->sel=($res[$i]->id==$id)?$sel:'';
			}
		  }
					
		  $contacts=$res;
		}
					
	  return $contacts;
	}

	function GET_role_first()
	{
		$sql=$this->db->query('select `id` from `roles` order by `id`, `role`;');
		if($sql->num_rows()>0){	
		   $res=$sql->result();
		   return $res[0]->id;	
		}else{
		   return 1;
		}
	}
	
	function GET_roles($id=0,$sel='sel')
	{
		$data=array();
			
		$sql=$this->db->query('select * from `roles` order by `id`, `role`;');
	
		if($sql->num_rows()>0){
			$res=$sql->result();
	
			for($i=0;$i<count($res);$i++) {
				$res[$i]->role=stripcslashes($res[$i]->role);
	
				if(is_array($id)){
						
					$res[$i]->sel='';
						
					for($m=0;$m<count($id);$m++) {
						 
						if($res[$i]->id==$id[$m]) {
							$res[$i]->sel=$sel;
					  break;
						}
					}
				} else {
					$res[$i]->sel=($res[$i]->id==$id)?$sel:'';
				}
			}
				
			$data=$res;
		}
			
		return $data;
	}
	
	function GET_slider_link_types($id,$sel='sel')
	{
		$slider_link_types=array();

		$res[0]=new stdClass();
		$res[0]->id=2;
		$res[0]->name='Торговые идеи';
		$res[0]->url='';
		
		$res[1]=new stdClass();
		$res[1]->id=0;
		$res[1]->name='Стратегии';
		$res[1]->url='';
		
		$res[2]=new stdClass();
		$res[2]->id=1;
		$res[2]->name='Фонды';
		$res[2]->url='';
		
		for($i=0;$i<count($res);$i++) {
			$res[$i]->name=stripcslashes($res[$i]->name);

			if (!is_null($id)) {
			  $res[$i]->sel=($res[$i]->id==$id)?$sel:'';
			} else {
			  $res[$i]->sel='';
			}
			
		}

		$slider_link_types=$res;
			
		return $slider_link_types;
	}
	
	function calc_rate($table,$name,$id)
	{
	  $ret=0;
      $set_lang=isset($this->site_lang)?' and `lang`="'.$this->site_lang.'"':'';
	
		$sql=$this->db->query('select `rate` from `'.$table.'` where `'.$name.'`="'.$id.'"'.$set_lang.';');
			if($sql->num_rows()>0){	
				$res=$sql->result();
					 
					 $count=0;
					 $num=0;
					 
					 foreach($res as $item){	
						$num=$num+$item->rate;
						$count++;
					 }		
					 
				$ret=round($num/$count);
			}
			
	  return $ret;
	}
    
    function date_format($res,$format='',$newpar='timestamp',$source='timestamp')
	{	
      if(empty($format)){
          $format=$this->lang->line('global_date_format');
      }
      
      if(strpos($format,'#_n_#')){
          $my_month=true;
      }
      
      if(is_array($res)){
          $tmp=$res;
      }else{
          $tmp[0]=$res;
          $not_array=true;
      }
      
		for($i=0;$i<count($tmp);$i++)
		{									
			 if(isset($tmp[$i]->$source)){
                 $tmp[$i]->$newpar=strtotime($tmp[$i]->$source);
                 
                 if($format!='__timestamp__'){
                    $tmp[$i]->$newpar=date($format,$tmp[$i]->$newpar);
                    
                    if(isset($my_month)){
                        preg_match('/\#_([0-9]{1,2})_\#/',$tmp[$i]->$newpar,$arr);
                        $mask=$arr[0];
                        $replace=self::my_month($arr[1]);
                        
                        $tmp[$i]->$newpar=str_replace($mask,$replace,$tmp[$i]->$newpar);
                    }
                 }
			 }
		}
        
       if(isset($not_array)){
           $res=$tmp[0];
       }else{
           $res=$tmp;
       }
		
	  return $res;
	}
    
	private function my_month($ret=0)
	{		
		$months[0]='';
		$months[1]=dictionary('Января');
		$months[2]=dictionary('Февраля');
		$months[3]=dictionary('Марта');
		$months[4]=dictionary('Апреля');
		$months[5]=dictionary('Мая');
		$months[6]=dictionary('Июня');
		$months[7]=dictionary('Июля');
		$months[8]=dictionary('Августа');
		$months[9]=dictionary('Сентября');
		$months[10]=dictionary('Октября');
		$months[11]=dictionary('Ноября');
		$months[12]=dictionary('Декабря');
		
		
		$ret=$months[$ret];			
			
	 return $ret;
	}

    function is_delete($table,$id,$name='id')
	{
	  $ret=false;
      $set_lang=isset($this->site_lang)?' and `lang`="'.$this->site_lang.'"':'';
	
		$sql=$this->db->query('select `id` from `'.$table.'` where `'.$name.'`="'.$id.'" and `is_delete`="yes"'.$set_lang.';');
			if($sql->num_rows()>0){	
				$ret=true;
			}
			
	  return $ret;
	}


	function is_add($table,$id,$name='id')
	{
	  $ret=false;
      $set_lang=isset($this->site_lang)?' and `lang`="'.$this->site_lang.'"':'';
	
		$sql=$this->db->query('select `id` from `'.$table.'` where `'.$name.'`="'.$id.'" and `is_add`="yes"'.$set_lang.';');
			if($sql->num_rows()>0){	
				$ret=true;
			}
			
	  return $ret;
	} 
    
    
    function lang_select($site_lang='ru',$url='')
    {
        $_SESSION['site_lang']=$site_lang;
        
        if(empty($url)){
            $url=$this->phpself;
        }
        header('Location: '.$url);
        die();
    }
    
    function pages_url($page='default')
    {
        $ret='';
        
            $sql=$this->db->query('select `p`.`url`,`m`.`url` as `m_url` , `sp`.`url` as `sp_url`
                                   from `pages` as `p`
                                   left join `pages_menu` as `m`
                                   on `m`.`id`=`p`.`cat_id`
                                   left join `pages` as `sp`
                                   on `sp`.`id`=`p`.`parent_id`
                                   where `p`.`lang`="'.$this->site_lang.'" 
                                   and `p`.`is_home`="no"
                                   and `m`.`lang`="'.$this->site_lang.'"
                                   and `m`.`is_home`="no"
                                   and `p`.`sub_page_type`="'.$page.'"
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                    $ret=$res->m_url.'/'.(is_null($res->sp_url)?'':$res->sp_url.'/').$res->url;
                } 
        
        return $ret;
    }    
    
    function pages_url_by_url($url_page)
    {
        $ret='';
        
            $sql=$this->db->query('select `p`.`url`,`m`.`url` as `m_url` , `sp`.`url` as `sp_url`
                                   from `pages` as `p`
                                   left join `pages_menu` as `m`
                                   on `m`.`id`=`p`.`cat_id`
                                   left join `pages` as `sp`
                                   on `sp`.`id`=`p`.`parent_id`
                                   where `p`.`lang`="'.$this->site_lang.'" 
                                   and `p`.`is_home`="no"
                                   and `m`.`lang`="'.$this->site_lang.'"
                                   and `m`.`is_home`="no"
                                   and `p`.`url`="'.$url_page.'"
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                    $ret=$res->m_url.'/'.(is_null($res->sp_url)?'':$res->sp_url.'/').$res->url;
                } 
        
        return $ret;
    }	    
    
    function pages_url_by_id($page_id)
    {
        $ret='';
        
            $sql=$this->db->query('select `p`.`url`,`m`.`url` as `m_url` , `sp`.`url` as `sp_url`
                                   from `pages` as `p`
                                   left join `pages_menu` as `m`
                                   on `m`.`id`=`p`.`cat_id`
                                   left join `pages` as `sp`
                                   on `sp`.`id`=`p`.`parent_id`
                                   where `p`.`lang`="'.$this->site_lang.'" 
                                   and `p`.`is_home`="no"
                                   and `m`.`lang`="'.$this->site_lang.'"
                                   and `m`.`is_home`="no"
                                   and `p`.`id`="'.$page_id.'"
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                    $ret=$res->m_url.'/'.(is_null($res->sp_url)?'':$res->sp_url.'/').$res->url;
                } 
        
        return $ret;
    }    
    
    function pages_part_url_by_id($page_id)
    {
        $ret='';
        
            $sql=$this->db->query('select `p`.`url`,`m`.`url` as `m_url` , `sp`.`url` as `sp_url`
                                   from `pages` as `p`
                                   left join `pages_menu` as `m`
                                   on `m`.`id`=`p`.`cat_id`
                                   left join `pages` as `sp`
                                   on `sp`.`id`=`p`.`parent_id`
                                   where `p`.`lang`="'.$this->site_lang.'" 
                                   and `p`.`is_home`="no"
                                   and `m`.`lang`="'.$this->site_lang.'"
                                   and `m`.`is_home`="no"
                                   and `p`.`id`="'.$page_id.'"
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                    $ret=$res->m_url.'/'.(is_null($res->sp_url)?'':$res->sp_url.'/');
                } 
        
        return $ret;
    }


/**********************************************************************************/	
	
	
	function debuger($ret,$vardump=false){
        
        $func=($vardump==true)?'var_dump':'print_r';
		
		echo('<pre>');
		$func($ret);
		echo('</pre>');
		die();
		
	}
	
	protected function find_parent($res, $parent_id, $id_name) {
		
		$ret=NULL;
		
		foreach($res as $r) {
			
			if ($r->{$id_name}==$parent_id) {
			  $ret = $r;
	          break;		  	
			}
			
		}
			
		return $ret;
	}
	
	function make_tree($res, $id_name='id', $parent_id_name='parent_id') {
		
		$ret = array();
		$res_copy = array();
		
		if (is_array($res)) {
			
			foreach ($res as $r) {

				$n = clone $r;
				
				$res_copy[] = $n;
				
				$parent = self::find_parent($res_copy,$r->{$parent_id_name},$id_name);
				if (!is_null($parent)) {

				  $parent->items[] = $n;
				  $n->parent = &$parent;
				  $n->items = array();
				  
				} else {

				  $n->parent = NULL;
				  $n->items = array();
				  $ret[] = $n;
				}
			}
		}
		
		return $ret;
	}
	
	function tree_list($tree, $prefix='&nbsp&nbsp&nbsp', $level=-1) {
		
		$ret = array();

		if (is_array($tree)) {
		  $level++;
		
		  foreach ($tree as $item) {
			
			$item->prefix = str_repeat($prefix,$level);
			
			$ret[] = $item;
			
			$r = self::tree_list($item->items,$prefix,$level);
			
			$ret = array_merge($ret,$r);
		  }
		}  
		
		return $ret;
	}
	
	function add_name(&$res, $name='') {
		
		foreach ($res as $r) {
			
			$r->{$name} = NULL;
		}
		
	}
	
	function get_xls_data($file,$maxsheets=50,$maxrows=1000,$maxcols=20) {
		
		$ret = false;
		
		include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/excel_reader2.php');
		$obj = new Spreadsheet_Excel_Reader();
		$obj->read($file);
		
		$sheet_count = sizeOf($obj->sheets);
		if ($sheet_count>$maxsheets) {
			$sheet_count = $maxsheets;
		} 
		
		for ($sheet=0;$sheet<=($sheet_count-1);++$sheet) {
			
			$row_count = $obj->rowcount($sheet);
			if ($row_count>$maxrows) {
				$row_count = $maxrows;
			}
			$col_count = $obj->colcount($sheet);
			if ($col_count>$maxcols) {
				$col_count = $maxcols;
			}	
			
		  if ($row_count>0) {
        	
      	$i = $sheet;
        for ($r=0;$r<=($row_count-1);++$r) {

        	for ($c=0;$c<=($col_count-1);++$c) {

            $ret[$i][$r][$c] = $obj->sheets[$i]['cells'][$r+1][$c+1]; 
          }
        }
      }    	
		}
				
		return $ret;
	}
	
  function get_xlsx_data($file,$maxsheets=50,$maxrows=1000,$maxcols=20)  {

  	$ret = false;
        
    include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/simplexlsx.class.php');
      
    $obj = new SimpleXLSX($file);

    for ($sheet=1;$sheet<=$maxsheets;$sheet++) {
        
    	$ret = array();
      	
    	$rows = $obj->rows($sheet);
      $row_count = count($rows);
      if ($row_count>$maxrows) {
        $row_count = $maxrows;
      }
        
      list($cols,) = $obj->dimension($sheet);
      $col_count = sizeOf($cols);
      if ($col_count>$maxcols) {
      	$col_count = $maxcols;
      }
        
      if ($row_count>0) {
        	
      	$i = $sheet-1;
        for ($r=0;$r<=($row_count-1);$r++) {

        	for ($c=0;$c<=($col_count-1);$c++) {

            $ret[$i][$r][$c] = $rows[$r][$c]; 
          }
        }
      }    	
    }
    return $ret;
  }
    
  function load_analytics_from_file($file,$extension='',$empty=false) {

   	$ret = false;
    	
  	if (file_exists($file)) {
    	
    	$ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    	if ($ext=='') {
    		$ext = $extension;
    	}
    	$data = false;
    	
    	switch ($ext) {
    		case 'xls': $data = self::get_xls_data($file); break;
    		case 'xlsx': $data = self::get_xlsx_data($file); break;
    		default: $data = self::get_xls_data($file); 
    	} 
    	
    	if ($data) {
    		
    		foreach ($data as $sheet=>$v) {
    			
    		  $table = '';
    		  $where = '';
    		  $rows = array();
    		  $extra = array();
    		  $repeats = array();
    		
    			switch ($sheet) {
    				case 0: 
    				case 1: {
    					$table = 'recommendations';

    					if ($sheet==0) { $extra['lang'] = 'ru'; }
    					if ($sheet==1) { $extra['lang'] = 'en'; }
    					 
    					$extra['type'] = 'euro';
    					
  						$where = sprintf(' where lang=%s and type=%s','"'.$extra['lang'].'"','"'.$extra['type'].'"');
    					
    					for ($r=(sizeOf($v)-1);$r>=3;--$r) {
    						$c = 0;
    						$first = $v[$r][++$c];
    						if (trim($first)<>'') {
    							$i = (sizeOf($v)-1) - $r;
    					    $rows[$i]['text']['ticker'] = $first;   	
    					    $rows[$i]['text']['name'] = $v[$r][++$c];
    					    $rows[$i]['text']['isin'] = $v[$r][++$c];
    					    $rows[$i]['text']['price_currency'] = $v[$r][++$c];
    					    $rows[$i]['text']['price_fair'] = $v[$r][++$c];
    					    $rows[$i]['text']['potential'] = rtrim($v[$r][++$c],'%');
    					    $rows[$i]['text']['recommendation'] = $v[$r][++$c];
    						}  
    					}
    					break;
    				}
    			  case 2: {
    					$table = 'commodities';
    					
    					$extra['lang'] = 'ru'; 
    					
  						$where = sprintf(' where lang=%s','"'.$extra['lang'].'"');

    				  for ($r=(sizeOf($v)-1);$r>=3;--$r) {
    						$c = 0;
    						$first = $v[$r][++$c];
    						if (trim($first)<>'') {
    							$i = (sizeOf($v)-1) - $r;
    					    $rows[$i]['text']['name'] = $first;   	
    					    $rows[$i]['text']['ticker'] = $v[$r][++$c];
    					    $rows[$i]['text']['sector'] = $v[$r][++$c];
    					    $rows[$i]['text']['price_current'] = $v[$r][++$c];
    					    $rows[$i]['text']['price_fair'] = $v[$r][++$c];
    					    $rows[$i]['text']['potential'] = rtrim($v[$r][++$c],'%');
    					    $rows[$i]['text']['recommendation'] = $v[$r][++$c];
    						}  
    					}
    					break;
    				}
    			  case 3: 
    			  case 4:
    			  case 5: {
    					$table = 'trade_ideas';
    					
    			    if ($sheet==3) { $extra['lang'] = 'ru'; }
    					if ($sheet==4) { $extra['lang'] = 'en'; }
    			    if ($sheet==5) { $extra['lang'] = 'de'; }
    					    					    					
  						$where = sprintf(' where lang=%s','"'.$extra['lang'].'"');

    				  for ($r=(sizeOf($v)-1);$r>=3;--$r) {
    						$c = 0;
    						$first = $v[$r][++$c];
    						if (trim($first)<>'') {
    							$i = (sizeOf($v)-1) - $r;
    					    $rows[$i]['text']['ticker'] = $first;   	
    					    $rows[$i]['text']['name'] = $v[$r][++$c];
    					    $rows[$i]['text']['isin'] = $v[$r][++$c];
    					    $rows[$i]['text']['sector'] = $v[$r][++$c];
    					    $rows[$i]['text']['price_currency'] = $v[$r][++$c];
    					    $rows[$i]['text']['price_fair'] = $v[$r][++$c];
    					    $rows[$i]['text']['potential'] = rtrim($v[$r][++$c],'%');
    					    $rows[$i]['text']['recommendation'] = $v[$r][++$c];
    						}  
    					}
    					break;
    				}
    				case 6:
    				case 7:
    				case 8: {
    					$table = 'issuers_debt_market';

    					$repeats[]['lang'] = 'ru';
    					$repeats[]['lang'] = 'en';
    					$repeats[]['lang'] = 'de';
    					
    					if ($sheet==6) { $extra['type'] = 'euro'; }
    					if ($sheet==7) { $extra['type'] = 'rur'; }
    					if ($sheet==8) { $extra['type'] = 'int_euro'; }
    						
    					$where = sprintf(' where type=%s','"'.$extra['type'].'"');
    				
    					for ($r=(sizeOf($v)-1);$r>=2;--$r) {
    						$c = 0;
    						$first = $v[$r][++$c];
    						if (trim($first)<>'') {
    							$i = (sizeOf($v)-1) - $r;
    							$rows[$i]['text']['name'] = $first;
    							$rows[$i]['select']['maturity_date'] = strtotime($v[$r][++$c]);
    							$rows[$i]['text']['isin'] = $v[$r][++$c];
    							$rows[$i]['text']['currency'] = $v[$r][++$c];
    							$rows[$i]['float']['closing_price'] = $v[$r][++$c];
    							$rows[$i]['float']['income'] = $v[$r][++$c];
    							$rows[$i]['float']['duration'] = $v[$r][++$c];
    							$rows[$i]['float']['rate'] = $v[$r][++$c];
    							$rows[$i]['select']['next_coupon'] = strtotime($v[$r][++$c]);
    							$rows[$i]['int']['volume'] = $v[$r][++$c];
    							$rows[$i]['int']['payments_per_year'] = $v[$r][++$c];
    							$rows[$i]['text']['rating_sp'] = $v[$r][++$c];
    							$rows[$i]['text']['rating_moodys'] = $v[$r][++$c];
    							$rows[$i]['text']['rating_fitch'] = $v[$r][++$c];
    							
    							$mdate=array();
    							$mdate['year'] = date('Y',$rows[$i]['select']['maturity_date']);
    							$mdate['month'] = date('n',$rows[$i]['select']['maturity_date']);
    							$mdate['day'] = date('j',$rows[$i]['select']['maturity_date']);
    							
    							$rows[$i]['select']['maturity_date'] = $this->global_model->date_arr_to_timstamp($mdate);
    							
    							$n_coupon=array();
    							$n_coupon['year'] = date('Y',$rows[$i]['select']['next_coupon']);
    							$n_coupon['month'] = date('n',$rows[$i]['select']['next_coupon']);
    							$n_coupon['day'] = date('j',$rows[$i]['select']['next_coupon']);
    								
    							$rows[$i]['select']['next_coupon'] = $this->global_model->date_arr_to_timstamp($n_coupon);
    						}
    					}
    					break;
    				}
    			  case 9: {
    					$table = 'planned_placements';
    					
    					$extra['lang'] = 'ru'; 
    					
  						$where = sprintf(' where lang=%s','"'.$extra['lang'].'"');

    				  for ($r=(sizeOf($v)-1);$r>=2;--$r) {
    						$c = -1;
    						$first = $v[$r][++$c];
    						if (trim($first)<>'') {
    							$i = (sizeOf($v)-1) - $r;
    					    $rows[$i]['text']['name'] = $first;   	
    					    $rows[$i]['text']['sector'] = $v[$r][++$c];
    					    $rows[$i]['text']['placement'] = $v[$r][++$c];
    					    $rows[$i]['text']['volume'] = $v[$r][++$c];
    						}  
    					}
    					break;
    				}
    			}
    			
    			if ($table!='') {
    				
    				if ($empty) {
    					$this->db->query(sprintf('delete from %s%s;',$table,$where));
    				}	
    				
    				if (sizeOf($repeats)>0) {

    					foreach ($repeats as $repeat) {
    						
    						foreach($rows as $row) {
    							$row = self::adjustment_of_request_array($row);
    							$row = array_merge($row,$extra);
    							$row = array_merge($row,$repeat);
    							$this->db->insert($table,$row);
    						}
    					}
    				} else {
    					
    					foreach($rows as $row) {
    						$row = self::adjustment_of_request_array($row);
    						$row = array_merge($row,$extra);
    						$this->db->insert($table,$row);
    					}
    				}
    			  
    			}
    		}
    		$ret = true;
    	}
  	}
  	return $ret;    		
  } 
		

}
?>