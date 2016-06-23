<?php
class front_coming_soon_model extends Model{

	function front_coming_soon_model()
	{
		parent::Model();
        $this->load->model('front_contacts_model');
	}
    
    function view()
    { 
      $content['data']='';

        $sql=$this->db->query('select *
                               from `pages` 
                               where `lang`="'.$this->site_lang.'"
                               and `sub_page_type`="coming_soon"
                               limit 1;');

            if($sql->num_rows()>0){
                $res=$sql->row();

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,'pages');
                    /*********************************/

                    $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                    $this->data['keywords']=$res->meta_keywords;
                    $this->data['description']=$res->meta_description;

                    $content['data']=$res;      
            }
            
        $sidebar_left['menu']='';
        $sidebar_right['data']='';
        
        $sidebar_right['contacts']=$this->front_contacts_model->one();
        
        $this->data['body_sidebar_left']=$this->load->view('body_sidebar_left',$sidebar_left,true);
        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
            
       return $this->load->view('view_coming_soon',$content,true);     
    }

}
?>
