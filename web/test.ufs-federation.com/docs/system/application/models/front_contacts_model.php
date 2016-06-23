<?php
class front_contacts_model extends Model{

	function front_contacts_model()
	{
		parent::Model();
	}
    
    function one($display='любая страница')
    {
      $ret='';

            /*$sql=$this->db->query('select * 
                                   from `contacts` 
                                   where `lang`="'.$this->site_lang.'" 
                                   and `display`="'.$display.'"
                                   order by `timestamp` desc 
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                        /*********************************/
              //             $res=$this->global_model->adjustment_of_results($res,'contacts');
                        /*********************************/

                /*    $ret=$res;
                }else{
                    if($display!='любая страница'){
                        $ret=self::one('любая страница');
                    }
                }
                
                */
      return $ret;
    }

	function get_contacts($contact_id)
	{
		$ret="";
		$s="";
		if (is_null($contact_id)) {
			$s="null";
		} else {
			$s="'".$contact_id."'";
		}
			
		$sql=$this->db->query('select *
				                 from `contacts`
			                    where `id`='.$s);

		if($sql->num_rows()>0){

			$res=$sql->row();

			$res=$this->global_model->adjustment_of_results($res,'contacts');

			$ret=$res;
		}

		return $ret;
	}
	
    function view($ret)
    {           

        $sql=$this->db->query('select *
                               from `contacts` 
                               where `lang`="'.$this->site_lang.'"
                               order by `timestamp` desc;');

            if($sql->num_rows()>0){
                $res=$sql->result();

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,'contacts');
                    /*********************************/

                    $data['page_url']=$ret->page_url;

                    $data['data']=$res;
                    $ret->content=$this->load->view('view_contacts',$data,true);  
            }
        
        return $ret;
    }

}
?>
