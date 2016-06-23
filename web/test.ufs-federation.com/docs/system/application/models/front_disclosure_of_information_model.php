<?php
class front_disclosure_of_information_model extends Model{

	function front_disclosure_of_information_model()
	{
		parent::Model();
	}
    
    function view()
    {    
        $sidebar_left['menu']='';
        $content['data']='';
        $temp=new stdClass();
        $temp->parent_url=mysql_string($this->uri->segment(3));
        $temp->page_url=($this->uri->segment(4)=='')?'all':mysql_string($this->uri->segment(4));
        $temp->year_url=($this->uri->segment(5)=='')?'0':mysql_string($this->uri->segment(5));
                
        $temp->header='';
        $temp->cat_id=0;
        $temp->year_arr=array();
        
        $temp->parent_name='';
        $temp->page_name='';
        $temp->content=array();
        $temp->calendar_type=0;
        
           $temp=self::menu($temp);
           $sidebar_left['menu']=$temp->menu;

  //          $sql=$this->db->query('select `t`.`url`, `t`.`name`, `t`.`_file`, `t`.`timestamp`
           $sql=$this->db->query('select `t`.`url`, `t`.`name`, `t`.`_file`, DATE_FORMAT(`t`.`timestamp`,\'%d.%m.%Y\') as published,  adddate(`t`.`timestamp`,interval -1 month) as timestamp, m.calendar_type
                                   from `'.$this->page_name.'` as `t`
                                   left join `'.$this->page_name.'_menu` as `m`
                                   on `m`.`id`=`t`.`cat_id`
                                   where `t`.`lang`="'.$this->site_lang.'"
                                   and (`m`.`id`="'.$temp->cat_id.'" or `m`.`parent_id`="'.$temp->cat_id.'")
                                   order by `t`.`timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res,'Y|n','time_sort');
                         $res=$this->global_model->date_format($res);
                        /*********************************/
                         
                      $arr=array();
                      for($i=0;$i<count($res);$i++)
                      {
                          $res[$i]->size=self::get_pdf_size($res[$i]->_file);
                          $exp=explode('|',$res[$i]->time_sort);
                          
                          $temp->year_arr[$exp[0]]=$exp[0];
                          $temp->year_url=($temp->year_url>0)?$temp->year_url:reset($temp->year_arr);
                          $temp->calendar_type=$res[$i]->calendar_type;
                          
                          $res[$i]->time_sort=$exp[1];  
                          $res[$i]->time_sort=self::set_quarter($res[$i]->time_sort);

                          if ($temp->calendar_type=='0') {
                            if($temp->year_url==$exp[0]){
                                if(!isset($arr[$res[$i]->time_sort->id]->name)){                                  
                                  $arr[$res[$i]->time_sort->id]->name=$res[$i]->time_sort->name;
                                }
                                $arr[$res[$i]->time_sort->id]->arr[]=$res[$i];
                            }
                          } else {
                          	if(!isset($arr[$res[$i]->time_sort->id]->name)){
                          		$arr[$res[$i]->time_sort->id]->name=$res[$i]->time_sort->name;
                          	}
                          	$arr[$res[$i]->time_sort->id]->arr[]=$res[$i];
                          }  
                      }

                    $temp->content=$arr;
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
                               and `parent_id`=0
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
                   
                        $sql_s=$this->db->query('select * 
                                                 from `'.$this->page_name.'_menu` 
                                                 where `lang`="'.$this->site_lang.'"
                                                 and `parent_id`="'.$res[$i]->id.'"
                                                 order by `sort_id` desc;');

                            if($sql_s->num_rows()>0){
                                $res_s=$sql_s->result();

                                    /*********************************/
                                     $res_s=$this->global_model->adjustment_of_results($res_s,$this->page_name.'_menu');
                                    /*********************************/

                                     for($m=0;$m<count($res_s);$m++){
                                       if($ret->page_url==$res_s[$m]->url){
                                          $res_s[$m]->select=' class="selected"';
                                          $ret->page_name=$res_s[$m]->name;
                                          $ret->header=$res_s[$m]->name;
                                          $ret->cat_id=$res_s[$m]->id;
                                       }else{
                                          $res_s[$m]->select='';
                                       }
                                     }
                                     
                                $res[$i]->sub=$res_s;
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
    
    private function set_quarter($id)
    {
      $ret=(object)array('id'=>$id,'name'=>'');
      
          switch($id)
          {
              case 1:
              case 2:
              case 3:
                 $ret->id=1;
                 $ret->name=dictionary('Первый квартал');
              break;

              case 4:
              case 5:
              case 6:
                 $ret->id=2;
                 $ret->name=dictionary('Второй квартал');
              break;

              case 7:
              case 8:
              case 9:
                 $ret->id=3; 
                 $ret->name=dictionary('Третий квартал');
              break;

              case 10:
              case 11:
              case 12:
                 $ret->id=4; 
                 $ret->name=dictionary('Четвертый квартал');
              break;

              default:
                 $ret->id=1;
                 $ret->name=dictionary('Первый квартал');
          }
          
       return $ret;
    }
    
    private function get_pdf_size($file)
    {
        $dir=$_SERVER['DOCUMENT_ROOT'].'/upload/disclosure_of_information/';
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
