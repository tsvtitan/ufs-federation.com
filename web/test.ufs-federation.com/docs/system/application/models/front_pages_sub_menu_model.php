<?php
class front_pages_sub_menu_model extends Model{
	function front_pages_sub_menu_model()
	{
		parent::Model();
	}

    function sub_menu($cat_id,$page_url)
    {
       $ret='';
        
        $sql=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`, `timestamp`
                               from `'.$this->page_name.'` 
                               where `lang`="'.$this->site_lang.'" 
                               and `is_home`="no"
                               and `is_hide`="no" 
                               and `cat_id`="'.$cat_id.'"
                               and `parent_id`="0"
                               order by `sort_id` desc;');

        if($sql->num_rows()>0){
            $res=$sql->result();

            for($i=0;$i<count($res);$i++){
               $res[$i]->select=($page_url==$res[$i]->url)?' class="selected"':'';
               
               $sql_s=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`, `timestamp`
                                         from `'.$this->page_name.'` 
                                         where `lang`="'.$this->site_lang.'" 
                                         and `is_home`="no"
                                         and `is_hide`="no" 
                                         and `cat_id`="'.$cat_id.'"
                                         and `parent_id`="'.$res[$i]->id.'"
                                         order by `sort_id` desc;');

                    if($sql_s->num_rows()>0){
                        $res_s=$sql_s->result();
                        for($m=0;$m<count($res_s);$m++){
                            $res_s[$m]->select=($page_url==$res[$i]->url and $this->uri->segment(5)==$res_s[$m]->url)?' class="selected"':'';
                        }
                       $res[$i]->sub=$res_s;
                    }             
               
            }
          $ret=$res;
        }
 
      return $ret;
    }

}
?>
