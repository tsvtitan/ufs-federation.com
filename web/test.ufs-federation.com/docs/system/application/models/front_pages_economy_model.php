<?php
class front_pages_economy_model extends Model{

	function front_pages_economy_model()
	{
		parent::Model();
	}
    
    function view()
    {
      return $this->load->view('body_content_sub_economy');

    }
}
?>
