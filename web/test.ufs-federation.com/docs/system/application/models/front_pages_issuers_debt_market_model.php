<?php
class front_pages_issuers_debt_market_model extends Model{

    function front_pages_issuers_debt_market_model()
    {
            parent::Model();
    }
    
    function build_sectors($items,$name) {
      
      $sectors = array();
      $oldsector='';
      
      if (is_array($items)) {
       
        foreach ($items as $it) {

          if ($oldsector!=$it->{$name}) {

            $sector = new stdClass();
            $sector->name = $it->{$name};
            $sector->items = array();
            $sectors[] = $sector;
            
            $oldsector = $it->{$name};
          } 
          $sector->items[] = $it;
        }
      }
      return $sectors;
    }
    
    function view()
    {
      $where='';
      $ret='';     

      if(isset($_REQUEST['int_euro'])){
      	
      }
           
        $data['data']->euro       = $this->sql('euro',$where,'industry,');
        $data['data']->euro_sectors = $this->build_sectors($data['data']->euro,'industry');
        
        $data['data']->rur        = $this->sql('rur',$where);
        
        $data['data']->int_euro   = $this->sql('int_euro',$where,'country,');
        $data['data']->int_euro_sectors = $this->build_sectors($data['data']->int_euro,'country');

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
    
    private function sql($type='euro',$where='',$order='')
    {
      $ret='';

      if($where!='') {
      	$where='and '.$where;
      }	
                  
        $sql = sprintf ('select id, name, isin, sector, volume, currency, rate, 
                                next_coupon, payments_per_year, maturity_date,
                                case when closing_price=0.00 then null else closing_price end as closing_price,  
                                case when income=0.00 then null else income end as income,  
                                case when duration=0.000 then null else duration end as duration,  
                                rating_sp, rating_moodys, rating_fitch, type, timestamp, lang,
                                industry, country
                           from `issuers_debt_market` 
                          where `lang`="'.$this->site_lang.'"
                            and `type` = "'.$type.' %s "
                          order by %s`id` desc;',$where,$order);
        
        
         $sql=$this->db->query($sql);        	


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