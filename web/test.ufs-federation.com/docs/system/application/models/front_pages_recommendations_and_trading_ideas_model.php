<?php
class front_pages_recommendations_and_trading_ideas_model extends Model{

	function front_pages_recommendations_and_trading_ideas_model()
	{
		parent::Model();
	}

	private function recommendations_last_update() {
		
		$ret = '';
	
		$sql = $this->db->query('select `timestamp`
		                       		 from `recommendations`
				                      where `lang`="'.$this->site_lang.'"
				                      order by `timestamp` desc
				                      limit 1;');
	
		if($sql->num_rows()>0){
			$res = $sql->row();
			$res = $this->global_model->date_format($res,'d #_n_# Y');
			$ret = $res->timestamp;
		}
		
		return $ret;
	}
	
	private function trading_ideas_last_update() {
		
		$ret='';
		
		$sql=$this->db->query('select `timestamp`
		                     		from `trade_ideas`
				                   where `lang`="'.$this->site_lang.'"
				                   order by `timestamp` desc
				                   limit 1;');
		
		if($sql->num_rows()>0) {
			$res = $sql->row();
			$res=$this->global_model->date_format($res,'d #_n_# Y');
			$ret=$res->timestamp;
		}
		
		return $ret;
	}
	
	private function recommendations_data() {
		
		$ret = false;
	
  	$sql = $this->db->query('select *
					                     from `recommendations`
					                    where `lang`="'.$this->site_lang.'"
					                      and `type` = "euro"
					                    order by `id` desc;');
		if($sql->num_rows()>0) {
			$res = $sql->result();
			$res = $this->global_model->adjustment_of_results($res,'recommendations');
			$ret = $res;
			
			$sectors = array();
			$oldsector = '';
		}
	
		return $ret;
	}
	
	private function trading_ideas_data() {
		
		$ret = false;
		
		$sql = $this->db->query('select *
					                     from `trade_ideas`
					                    where `lang`="'.$this->site_lang.'"
					                      and `type` = "euro"
					                    order by `id` desc;');

		if($sql->num_rows()>0) {
			
			$res = $sql->result();
			$res = $this->global_model->adjustment_of_results($res,'trade_ideas');
			$ret = $res;
		}

		return $ret;
	}
	
  function view() {
    	
    	$data = false;
      $field_name = '';

      $type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
      if ($type=='') {
      	$type = rand(0,1);
      }
    	switch ($type) {
    		case 0: {
    			$data->items = $this->recommendations_data();
    			$data->last_update = $this->recommendations_last_update();
    			$data->recommendations = true;
    			$field_name = 'isin';
    			break;
    		}
    		case 1: {
    			$data->items = $this->trading_ideas_data();
    			$data->last_update = $this->trading_ideas_last_update();
    			$data->trading_ideas = true;
    			$field_name = 'name';
    			break;
    		}
    		default: {
    			//
    		}
    	} 
    	
    	
	    if ($data) {

	    	$data->groups = array();
	    	$oldgroup = '';
                 
				foreach ($data->items as $it) {
               	
          if ($oldgroup!=$it->{$field_name}) {
          	
            $group = new stdClass();
            $group->name = $it->{$field_name};
            $group->items = array();
            array_push($data->groups,$group);
            $oldgroup = $it->{$field_name};
          }   
          array_push($group->items,$it);
        }

        
        $content['data'] = $data;
        $content['type'] = $type;
        
        return $this->load->view('body_content_sub_recommendations_and_trading_ideas',$content,true);
        
	    }  	   
               
    }

}
?>
