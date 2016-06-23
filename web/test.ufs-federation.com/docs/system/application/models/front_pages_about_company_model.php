<?php
class front_pages_about_company_model extends Model{

	function front_pages_about_company_model()
	{
		parent::Model();

        $this->load->model('front_pages_analytics_team_model');
	}
    
    function view()
    {
        $data['director']=$this->front_pages_analytics_team_model->last(1,'о компании',true);
        $data['team']=$this->front_pages_analytics_team_model->last(2,'о компании',false);
        
        $this->data['body_content_sub']=$this->load->view('view_pages_about_company',$data,true);
    }

}
?>
