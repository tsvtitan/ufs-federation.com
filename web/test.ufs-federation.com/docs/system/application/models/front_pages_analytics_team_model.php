<?php
class front_pages_analytics_team_model extends Model{

	function front_pages_analytics_team_model()
	{
		parent::Model();
	}
    
    function last($limit=2,$display='',$director=false)
    {
      $ret=array();
      
        if(!empty($display)){
            $sql=$this->db->query('select * 
                                   from `analytics_team` 
                                   where `lang`="'.$this->site_lang.'" 
                                   and `display` like "%'.$display.'%"
                                   and `type`="'.(($director==true)?'руководство':'команда').'"
                                   order by `sort_id` desc 
                                   limit '.$limit.';');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'analytics_team');
                        /*********************************/
                         
                    $ret=$res;
                }
        }
                
      return $ret;
    }
    
    function view($ret,$display='')
    {           
         if(!empty($display)){
            $sql=$this->db->query('select *
                                   from `analytics_team` 
                                   where `lang`="'.$this->site_lang.'"
                                   and `display` like "%'.$display.'%"
                                   order by `sort_id` desc');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'analytics_team');
                         $res=$this->global_model->date_format($res,'d #_n_# Y','date','timestamp');
                        /*********************************/

                        $data['page_url']=$ret->page_url;

                        $data['data']=$res;
                        $ret->content=$this->load->view('view_pages_analytics_team',$data,true);  
                }  
           }
        
        return $ret;
    }

}
?>
