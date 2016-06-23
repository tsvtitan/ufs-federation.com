<?php
class front_error_model extends Model{

	function front_error_model()
	{
		parent::Model();

        $this->load->model('front_contacts_model');
	}
    
    function view()
    {  
		$content['error'] = 'Error';
        $title = '';
        $this->data['body_css_class'] = 'inner';

        if ((int)$this->uri->segment(3)>0){
            
            $sql=$this->db->query('select * from `pages` 
                                   where `sub_page_type`="error'.(int)$this->uri->segment(3).'" 
                                   and `lang`="'.$this->site_lang.'" 
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'pages');
                        /*********************************/

                    $content['data']=$res;

                    $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                    $this->data['keywords']=$res->meta_keywords;
                    $this->data['description']=$res->meta_description;
                    
                   $content['error']=empty($res->meta_title)?'Error':$res->meta_title;
                }

        }
        
        $sidebar_left['menu']='';
        $sidebar_right['data']='';
        
        $sidebar_right['contacts']=$this->front_contacts_model->one();
        
        $this->data['body_sidebar_left']=$this->load->view('body_sidebar_left',$sidebar_left,true);
//        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);

        return $this->load->view('view_' . $this->page_name, $content, true);
    }
    
}
?>
