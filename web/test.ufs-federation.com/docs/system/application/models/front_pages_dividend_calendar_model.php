<?php
class front_pages_dividend_calendar_model extends Model{

	function front_pages_dividend_calendar_model()
	{
		parent::Model();
	}
    
    function view()
    {
      $data = $this->global_model->get_query_data('select t.*,
                                                          case when t.close_date=current_date then 0 else 1 end as close
                                                     from (select *, null as past, 1 as que
                                                             from dividend_calendar
                                                            where lang="'.$this->site_lang.'"
                                                              and close_date>=current_date
                                                            union all
                                                           select *, close_date as past, 
                                                                  2 as que 
                     		                                     from dividend_calendar
      		                                                  where lang="'.$this->site_lang.'"
                                                              and close_date<current_date) t
                                                   order by /*t.que, t.name*/ t.id desc');
      $data = $this->global_model->data_to_class($data);
      /*if ($data) {
        foreach ($data as &$d) {

          $vplus = '';
          if ($d->close=='0') {
            $vplus = 'close';
          }
          if (trim($d->params)!='') {
            $params = explode("\n",$d->params);
            foreach ($params as $p) {

              list($name,$value) = explode('=',$p);
              $d->classes[$name] = trim($value.' '.$vplus);
            }
            foreach($d as $k=>$p) {
              if (!isset($d->classes[$k])) { 
            	  $d->classes[$k] = $vplus;
              }
            }
          } else {
            foreach($d as $k=>$p) {
              $d->classes[$k] = $vplus;
            }
          }
          
        }
      }*/

      //$data['data'] = $data;
      
      $sectors = array();
      $oldsector='';
      
      if ($data) {
               
        foreach ($data as &$d) {
               	
          if (!is_null($d->sector)) {
          
            if ($oldsector!=$d->sector) {

              $sector=new stdClass();
              $sector->name=$d->sector;
              $sector->items=array();
              array_push($sectors,$sector);
              $oldsector=$d->sector;
            }   

            $vplus = '';
            if ($d->close=='0') {
              $vplus = 'close';
            }
            if (trim($d->params)!='') {
              $params = explode("\n",$d->params);
              foreach ($params as $p) {

                list($name,$value) = explode('=',$p);
                $d->classes[$name] = trim($value.' '.$vplus);
              }
              foreach($d as $k=>$p) {
                if (!isset($d->classes[$k])) { 
                  $d->classes[$k] = $vplus;
                }
              }
            } else {
              foreach($d as $k=>$p) {
                $d->classes[$k] = $vplus;
              }
            }

            if (isset($sector)) {
              array_push($sector->items,$d);
            }
          }
        }  
      }               	
       
      $data['sectors']=$sectors;
      
      $data['last_update'] = self::last_update();
      $data['page_url'] = $ret->page_url='pages/'.$this->global_model->pages_url('dividend_calendar');
                
      $ret=$this->load->view('body_content_sub_dividend_calendar',$data,true);
        
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
                               from `dividend_calendar` 
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
