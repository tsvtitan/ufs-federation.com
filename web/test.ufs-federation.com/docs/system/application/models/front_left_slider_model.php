<?php
class front_left_slider_model extends Model{

	function front_left_slider_model()
	{
		parent::Model();
	}
    
	function get_caption()
	{
	  $res=array('Куда и как успешно инвестировать?',
	  		     'Как эффективно инвестировать в моменте?');

	  $index=rand(0,count($res)-1);
	  $ret=dictionary($res[$index]);
	  
      return $ret;
	}
	
	function get_menu_page($link_type) 
	{
	   	$ret=new stdClass();
	   	$ret->url='';
	   	
        $sql=$this->db->query('select id, url
                               from `pages` 
                               where `slider_link_type`="'.$link_type.'"
                               order by rand() limit 1;');

        if($sql->num_rows()>0) {

           $res=$sql->result();

           /*********************************/
           $res=$this->global_model->adjustment_of_results($res,'pages');
           /*********************************/

           
           for($i=0;$i<count($res);$i++){
              $res[$i]->url=$this->global_model->pages_url_by_id($res[$i]->id);
              $res[$i]->url.=$this->urlsufix;
           }
                      
           $ret->url=$res[0]->url;
        }
           
        return $ret; 
	
	}
	
	function get_menu()
	{
		$res=$this->global_model->GET_slider_link_types(null);
		
		for($i=0;$i<count($res);$i++){
		   
			$page=self::get_menu_page($res[$i]->id);
			
			$res[$i]->url=$page->url;
		   
		    $res[$i]->name=dictionary($res[$i]->name);
		}
				
		return $res;
	}
	
    function view($ret)
    {           

    }

}
?>