<?php
class front_pages_issuers_debt_market_model extends Model{

	function front_pages_issuers_debt_market_model()
	{
		parent::Model();
	}
    
    function view()
    {
      $where='';
      $ret='';     

      if(isset($_REQUEST['int_euro'])){
      	
      }
           
               $data['data']->euro       = $this->sql('euro',$where);
               $data['data']->rur        = $this->sql('rur',$where);
               $data['data']->int_euro   = $this->sql('int_euro',$where);
               
               $data['euro_names']       = $this->getDistinctField('name','euro');
               $data['rur_names']        = $this->getDistinctField('name','rur');
               $data['int_euro_names']   = $this->getDistinctField('name','int_euro');               
               
               
               $data['euro_currency']     = $this->getDistinctField('currency','euro');
               $data['rur_currency']      = $this->getDistinctField('currency','rur');
               $data['int_euro_currency'] = $this->getDistinctField('currency','int_euro');               
               
               $data['euro_rating_sp']     = $this->getDistinctField('rating_sp','euro');
               $data['rur_rating_sp']      = $this->getDistinctField('rating_sp','rur');
               $data['int_euro_rating_sp'] = $this->getDistinctField('rating_sp','int_euro');               
               
               $data['euro_rating_moodys']     = $this->getDistinctField('rating_moodys','euro');
               $data['rur_rating_moodys']      = $this->getDistinctField('rating_moodys','rur');
               $data['int_euro_rating_moodys'] = $this->getDistinctField('rating_moodys','int_euro');               
               
               $data['euro_rating_fitch']     = $this->getDistinctField('rating_fitch','euro');
               $data['rur_rating_fitch']      = $this->getDistinctField('rating_fitch','rur');
               $data['int_euro_rating_fitch'] = $this->getDistinctField('rating_fitch','int_euro');
               
               
               
               $data['last_update']=self::last_update();
                
               $data['page_url']=$ret->page_url='pages/'.$this->global_model->pages_url('issuers_debt_market');
                
               $ret=$this->load->view('body_content_sub_issuers_debt_market',$data,true);
        
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
	                               from `issuers_debt_market` 
	                               where `lang`="'.$this->site_lang.'"
	                               and `type` = "'.$type.'"
	                               order by `timestamp` desc;');        	
        }
        else{
	        $sql=$this->db->query('select *
	                               from `issuers_debt_market` 
	                               where `lang`="'.$this->site_lang.'"
	                               and `type` = "'.$type.'"
	                               and '.$where.'
	                               order by `timestamp` desc;');         	
        }


            if($sql->num_rows()>0){
                $res=$sql->result();

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,'issuers_debt_market');
                     $res=$this->global_model->date_format($res,'d.m.Y','maturity_date','maturity_date');
                     $res=$this->global_model->date_format($res,'d.m.Y','next_coupon','next_coupon');
                    /*********************************/

                $ret=$res;  
            } 
            
       return $ret;
    }
    
    private function getDistinctField($field,$type='euro')
    {
    	$ret = '';
    	
    	$sql = $this->db->query('select distinct '.$field.' from `issuers_debt_market` 
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
                               from `issuers_debt_market` 
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
