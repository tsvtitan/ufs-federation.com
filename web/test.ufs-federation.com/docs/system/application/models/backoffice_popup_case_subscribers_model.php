<?php
class backoffice_popup_case_subscribers_model extends Model{

	function backoffice_popup_case_subscribers_model()
	{
		parent::Model();
	}
    
    
    function view($table,$content,$page_name)
    {         
        $res_arr='';
        $id = $this->uri->segment(4);
        $sql=$this->db->query('select mailing_subscription_id as id, date_format(started,"%d.%m.%Y %H:%i:%S") as started, name, email  
        		                     from mailing_subscriptions
        	                      where	started is not null
        		                      and finished is null '.
                                  (isset($_SESSION['search'])?' and email like "%'.$_SESSION['search'].'%" ':'')
        		                     .'and mailing_section_id='.$id);
        if($sql->num_rows>0) {
            $res=$sql->result();
            $res=$this->global_model->adjustment_of_results($res,$table);
            $res=$this->global_model->set_sort_elemets($res);
                $x=0;
                for($i=0;$i<count($res);$i++){

                    $res[$i]->name='<strong>'.$res[$i]->name.'</strong>';
                    $res_arr[]=$res[$i];

                }
                
            $res_arr=$this->global_model->rand_css_class($res_arr);

        }

        $content['data']=$res_arr;

        return $this->load->view('backoffice_'.$this->page_name.'_view_subscribers',$content,true);
    }
    
    function unsubscribe($table,$content,$page_name) {
      
      $id = (int)$this->uri->segment(5);
      $sql = $this->db->query('select lang, email, mailing_section_id from mailing_subscriptions where mailing_subscription_id='.$id);
      if($sql->num_rows>0) {
        
        $res = $sql->result();
        $res = $res[0];
        
        $flag = $this->db->query(sprintf('update mailing_subscriptions '.
                                          'set finished=current_timestamp '.
                                        'where email=%s '.
                                          'and lang=%s '.
                                          'and finished is null;',
                                         "'".$res->email."'","'".$res->lang."'"));
        if ($flag) {
          
          return redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3).'/'.$res->mailing_section_id);
        }
      }
    }
    
}
?>
