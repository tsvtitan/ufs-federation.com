<?php
class front_pages_planned_placements_model extends Model{

	function front_pages_planned_placements_model()
	{
		parent::Model();
	}
    
    function view()
    {
      $items=false;     

      $sql=$this->db->query('select *
                     		 from `planned_placements`
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
              	 
           array_push($sector->items,$it);
         }  
       }               	    
               
       $data['sectors']=$sectors;
                     
       $data['last_update']=self::last_update();
                
       $data['page_url']=$ret->page_url='pages/'.$this->global_model->pages_url('planned_placements');
                
       $ret=$this->load->view('body_content_sub_planned_placements',$data,true);
        
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
                               from `planned_placements` 
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
