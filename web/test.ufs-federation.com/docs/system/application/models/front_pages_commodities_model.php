<?php
class front_pages_commodities_model extends Model{

	function front_pages_commodities_model()
	{
		parent::Model();
	}
    
    function view()
    {
      $items=false;     

      $sql=$this->db->query('select *
                                from `commodities`
                                where `lang`="'.$this->site_lang.'"
                                order by `id` desc;');

      if($sql->num_rows()>0){
      	$items=$sql->result();
      	$items=$this->global_model->adjustment_of_results($items,'commodities');
      }
      
      $sectors = array();
      $oldsector='';
              
      if (is_array($items)) {
               
        foreach ($items as $it) {
               	
       	   if ($oldsector!=$it->sector) {
           	 
       	     $sector=new stdClass();
             $sector->name=$it->sector;
             $sector->items=array();
             array_push($sectors,$sector);
             $oldsector=$it->sector;
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
                
       $data['page_url']=$ret->page_url='pages/'.$this->global_model->pages_url('commodities');
                
       $ret=$this->load->view('body_content_sub_commodities',$data,true);
        
        return $ret;
    }
    

    private function parsePost()
    {
    	var_dump($_REQUEST);
    }
    
    private function last_update()
    {
      $ret='';
      
        $sql=$this->db->query('select `timestamp`
                               from `commodities` 
                               where `lang`="'.$this->site_lang.'"
                               order by `timestamp` desc
                               limit 1;');

            if($sql->num_rows()>0){

            	$res=$sql->row();
                $res=$this->global_model->date_format($res,'d #_n_# Y');

                $ret=$res->timestamp;  
            } 
            
       return $ret;
    }
    
    

}
?>
