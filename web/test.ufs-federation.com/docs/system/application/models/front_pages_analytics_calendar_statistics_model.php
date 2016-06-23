<?php
class front_pages_analytics_calendar_statistics_model extends Model{

	function front_pages_analytics_calendar_statistics_model()
	{
		parent::Model();
	}
    
    function view($ret)
    {                
        $data['data'][1]=self::get('prev');
        $data['data'][2]=self::get('now');
        $data['data'][3]=self::get('next');

        $ret->content=$this->load->view('view_pages_analytics_calendar_statistics',$data,true);  
        
        return $ret;
    }
    
    private function get($week='now')
    { 
      $ret='';
      
        $id=2;
        
        $first_number = date('N')-1;
        $last_number = 7-date('N');
      
        $week_Monday = strtotime('-'.$first_number.' day');
        $week_Sunday = strtotime('+'.$last_number.' day');
        
        if($week=='next'){
            $week_Monday = strtotime('+1 week',$week_Monday);
            $week_Sunday = strtotime('+1 week',$week_Sunday);
            $id=3;
        }elseif($week=='prev'){
            $week_Monday = strtotime('-1 week',$week_Monday);
            $week_Sunday = strtotime('-1 week',$week_Sunday);
            $id=1;
        }
        
        $week_Monday = date('Y-m-d',$week_Monday).' 00:00:00';
        $week_Sunday = date('Y-m-d',$week_Sunday).' 23:59:59';
        
        $ret->week->monday = $week_Monday;
        $ret->week->sunday = $week_Sunday;
        
        $ret->week=$this->global_model->date_format($ret->week,'j #_n_#','monday','monday');
        $ret->week=$this->global_model->date_format($ret->week,'j #_n_#','sunday','sunday');
        
            $sql=$this->db->query('select *
                                   from `analytics_calendar_statistics` 
                                   where `lang`="'.$this->site_lang.'"
                                   and `timestamp` >= "'.$week_Monday.'"
                                   and `timestamp` <= "'.$week_Sunday.'"
                                   order by `timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'analytics_calendar_statistics');
                         $res=$this->global_model->date_format($res,'j #_n_#','date','timestamp');
                         $res=$this->global_model->date_format($res,'H:i','time','timestamp');
                         $res=$this->global_model->date_format($res,'dmY','sort_date','timestamp');
                        /*********************************/
                         
                         $country_arr=array();
                         $data_arr=array();
                         for($i=0;$i<count($res);$i++)
                         {
                            if(!in_array($res[$i]->country,$country_arr)){
                             $country_arr[]=$res[$i]->country;
                            }
                            
                            if(!empty($_REQUEST['calendar_statistics_country'][$id])){
                                 
                                 if($res[$i]->country==mysql_string($_REQUEST['calendar_statistics_country'][$id])){
                                     if(!isset($data_arr[$res[$i]->sort_date]->date)){
                                       $data_arr[$res[$i]->sort_date]->date=$res[$i]->date;
                                     }
                                     $data_arr[$res[$i]->sort_date]->arr[]=$res[$i];
                                 }
                                   
                             }else{
                            
                                if(!isset($data_arr[$res[$i]->sort_date]->date)){
                                  $data_arr[$res[$i]->sort_date]->date=$res[$i]->date;
                                }
                                $data_arr[$res[$i]->sort_date]->arr[]=$res[$i];
                                
                             }
                         }
                         
                         foreach($data_arr as $kay=>$val)
                         {
                           $data_arr[$kay]->arr=$this->global_model->rand_css_class($data_arr[$kay]->arr,'ntd','last-date-incriment');
                         }

                    $ret->data=$data_arr;
                    $ret->country=$country_arr;
                }

        return $ret;
    }

}
?>
