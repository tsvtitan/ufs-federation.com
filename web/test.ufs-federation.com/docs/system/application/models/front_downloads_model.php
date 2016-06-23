<?php
if ($this->site_lang != 'ru' && $this->site_lang != 'en') {
    $d = explode('.', $_SERVER['HTTP_HOST']);
    $nd = 'http://en';
    foreach($d as $k => $v) {
        if ($k > 0) {
            $nd .= '.' . $v;
        }
    }
    header('location: ' . $nd . $_SERVER['REQUEST_URI']);
    exit;
}

class front_downloads_model extends Model{

	function front_downloads_model()
	{
		parent::Model();
	}
    
    function view()
    {    
        $sidebar_left['menu']='';
        $content['data']='';
        $temp=new stdClass();
        $temp->parent_url=mysql_string($this->uri->segment(3));
        $temp->cat_sid=($this->uri->segment(4)=='')?'1':mysql_string($this->uri->segment(4));
                
        $temp->header='';
        $temp->cat_id=0;
        
        $temp->parent_name='';
        $temp->content=array();
        
           $temp=self::menu($temp);
           $sidebar_left['menu']=$temp->menu;

            $sql=$this->db->query('select `t`.`url`, `t`.`name`, `t`.`_file`, `t`.`timestamp`
                                   from `'.$this->page_name.'` as `t`
                                   left join `'.$this->page_name.'_menu` as `m`
                                   on `m`.`id`=`t`.`cat_id`
                                   where `t`.`lang`="'.$this->site_lang.'"
                                   and `m`.`id`="'.$temp->cat_id.'"
                                   order by `t`.`timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res);
                        /*********************************/
                         
                      for($i=0;$i<count($res);$i++)
                      {
                          $res[$i]->size=self::get_size($res[$i]->_file);
                      }

                    $temp->content=$res;
                }
                
             $content['data']=$temp;
                
         $this->data['body_sidebar_left']=$this->load->view('body_sidebar_left_disclosure_of_information',$sidebar_left,true);
                
        return $this->load->view('view_'.$this->page_name,$content,true);
    }
    
    
    private function menu($ret)
    {
       $ret->menu=array();
        
        $sql=$this->db->query('select * 
                               from `'.$this->page_name.'_menu` 
                               where `lang`="'.$this->site_lang.'"
                               order by `sort_id` desc;');

            if($sql->num_rows()>0){
                $res=$sql->result();

                    /*********************************/
                     $res=$this->global_model->adjustment_of_results($res,$this->page_name.'_menu');
                    /*********************************/

                for($i=0;$i<count($res);$i++){
                   if($ret->parent_url==$res[$i]->url){
                      $res[$i]->select=' class="selected"';
                      $ret->parent_name=$res[$i]->name;
                      $ret->header=$res[$i]->name;
                      $ret->cat_id=$res[$i]->id;
                   }else{
                      $res[$i]->select='';
                   }

                }
                
                if(empty($ret->parent_url)){
                    $res[0]->select=' class="selected"';
                    $ret->parent_url=$res[0]->url;
                    $ret->parent_name=$res[0]->name;
                    $ret->header=$res[0]->name;
                    $ret->cat_id=$res[0]->id;
                } 

              $ret->menu=$res;
            }
                
        return $ret;
    }
 
    private function get_size($file)
    {
        $dir=$_SERVER['DOCUMENT_ROOT'].'/upload/downloads/';
        $ret='0 b';
          if(!empty($file)){
            $size=filesize($dir.$file);
            $ret=$size.' b';
            
            if($size>1024){
                $size=$size/1024;
                $ret=ceil($size).' Kb';
            }
            
            if($size>1024){
                $size=$size/1024;
                $ret=ceil($size).' Mb';
            }
          }
        
        return $ret;        
    }
}
?>
