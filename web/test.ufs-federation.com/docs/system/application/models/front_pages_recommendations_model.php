<?php
class front_pages_recommendations_model extends Model{

	function front_pages_recommendations_model()
	{
		parent::Model();
	}
    
    function view()
    {
      $where          = '';
      $where_euro     = '';
      $where_rur      = '';
      $where_int_euro = '';
      $ret='';     

      $data['search_filter'] = '';
      
      if(isset($_REQUEST['is_send_filter'])){
      	$filter_type  = $_REQUEST['filter_type'];
      	$filter_field = $_REQUEST['filter_field'];
      	$filter_value = $_REQUEST['filter_value'];
      	
      	$data['search_filter'][$filter_type][$filter_field] = $filter_value;
      	$tmp_where             = '`'.$filter_field.'`="'.$filter_value.'"';
      	
      	if($filter_value!=""){
	      	switch ($filter_type){
	      		case 'euro':
	      			  $where_euro     = $tmp_where;
	      			break;
	      		case 'rur':
	      			  $where_rur      = $tmp_where;
	      			break;
	      		case 'int_euro':
	      			  $where_int_euro = $tmp_where;
	      			break;
	      		default:break;
	      	}      		
      	}

      }
           
               $data['data']->euro       = $this->sql('euro',$where_euro);
               $data['data']->rur        = $this->sql('rur',$where_rur);
               $data['data']->int_euro   = $this->sql('int_euro',$where_int_euro);
               
               $data['euro_names']       = $this->getDistinctField('name','euro');
               $data['rur_names']        = $this->getDistinctField('name','rur');
               $data['int_euro_names']   = $this->getDistinctField('name','int_euro');               
               
               
               $data['euro_isin']     = $this->getDistinctField('isin','euro');
               $data['rur_isin']      = $this->getDistinctField('isin','rur');
               $data['int_euro_isin'] = $this->getDistinctField('isin','int_euro');               
               
               $data['euro_recommendation']     = $this->getDistinctField('recommendation','euro');
               $data['rur_recommendation']      = $this->getDistinctField('recommendation','rur');
               $data['int_recommendation']      = $this->getDistinctField('recommendation','int_euro');               
               

               $sectors = array();
               
               $items = $data['data']->euro;
               
               $oldsector='';
               
	       if (is_array($items)) {
                 
		 foreach ($items as $it) {
               	
               	   if ($oldsector!=$it->isin) {
               	      
                      $sector=new stdClass();
               	      $sector->name=$it->isin;
               	      $sector->items=array();
               	      array_push($sectors,$sector);
               	      $oldsector=$it->isin;
               	   }  
                   
                   $class = '';
                   
                   switch (strtolower($it->recommendation)) {
                     case 'покупать': {
                       $class = 'r-buy';
                       break;
                     }
                     case 'buy': {
                       $class = 'r-buy';
                       break;
                     }
                     case 'держать': {
                       $class = 'r-hold';
                       break;
                     }
                     case 'пересмотр': {
                       $class = 'r-hold';
                       break;
                     }
                     case 'hold': {
                       $class = 'r-hold';
                       break;
                     }
                     case 'revision': {
                       $class = 'r-hold';
                       break;
                     }
                     case 'продавать': {
                       $class = 'r-sell';
                       break;
                     }
                     case 'sell': {
                       $class = 'r-sell';
                       break;
                     }
                   }
                   $it->class = $class;
               	 
               	   array_push($sector->items,$it);
                 } 				   
               }
               
               $data['sectors']=$sectors;
               
               $data['last_update']=self::last_update();
                
               $data['page_url']=$ret->page_url='pages/'.$this->global_model->pages_url('recommendations');
                
               $ret=$this->load->view('body_content_sub_recommendations',$data,true);
        
        return $ret;
    }
    

    private function parsePost()
    {
    	var_dump($_REQUEST);
    }
    
    private function sql($type='euro',$where='')
    {
      $ret='';
        
        if($where==''){
	        $sql=$this->db->query('select *
	                               from `recommendations` 
	                               where `lang`="'.$this->site_lang.'"
	                               and `type` = "'.$type.'"
	                               order by `id` desc;');        	
        }
        else{
	        $sql=$this->db->query('select *
	                               from `recommendations` 
	                               where `lang`="'.$this->site_lang.'"
	                               and `type` = "'.$type.'"
	                               and '.$where.'
	                               order by `id` desc;');         	
        }


            if($sql->num_rows()>0){
                $res=$sql->result();

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,'recommendations');
                     //$res=$this->global_model->date_format($res,'d.m.Y','maturity_date','maturity_date');
                     //$res=$this->global_model->date_format($res,'d.m.Y','next_coupon','next_coupon');
                    /*********************************/

                $ret=$res;  
            } 
            
       return $ret;
    }
    
    private function getDistinctField($field,$type='euro')
    {
    	$ret = '';
    	
    	$sql = $this->db->query('select distinct '.$field.' from `recommendations` 
 where `lang`="'.$this->site_lang.'" and `type` = "'.$type.'" and '.$field.'!="" order by '.$field);
    	
    	if($sql->num_rows()>0){
    		$res = $sql->result();
    		$ret = $res; 
    	}
    	
    	return $ret;
    }
    
    private function last_update()
    {
      $ret='';
      
        $sql=$this->db->query('select `timestamp`
                               from `recommendations` 
                               where `lang`="'.$this->site_lang.'"
                               order by `timestamp` desc
                               limit 1;');

            if($sql->num_rows()>0){
                $res=$sql->row();

                    /*********************************/
                     $res=$this->global_model->date_format($res,'d #_n_# Y');
                    /*********************************/

                $ret=$res->timestamp;  
            } 
            
       return $ret;
    }
    
    

}
?>
