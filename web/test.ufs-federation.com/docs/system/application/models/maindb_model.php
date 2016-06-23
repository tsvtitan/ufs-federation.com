<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Maindb_model extends Model{
	
 function __construct()
 {
    parent::Model();
    
    $this->load->helper('url');
	$this->load->helper('string');
	$this->load->helper('email');
	$this->load->helper('file');
 }
 
 function _get_max_num($table,$field,$where='')
 {
     $max_num = 1;
     if($table!="" and $field!="")
     {
     	 $sql_string = 'select MAX(`'.$field.'`) as `max_num` from `'.$table.'` ';
     	 $sql_string.= ($where!="")?(' where '.$where):'';
         $sql=$this->db->query($sql_string);
         if($sql->num_rows()>0){
             $max_num = $sql->row();
             $max_num = $max_num->max_num+1;
         }
     }
     
     return $max_num;
 }
 
 function get_count_rows($table,$where='')
 {
 	$count      = 0;
 	
 	$sel_fields = '';
 	$sql_string = 'select COUNT( * ) as `result_count` from `'.$table.'` ';
 	$sql_string.= (!empty($where))?('where '.$where.' '):'';
 	
 	$sql        = $this->db->query($sql_string);
 	if($sql->num_rows()>0)
 	{
 		$res   = $sql->row();
 		$count = $res->result_count;
 	}
 	
 	return $count;
 } 
 
 function select_table($table,$where='',$order_by='',$limit='',$the_one=false,$css_class='itembg',$text_fields="",$photo_fields="",$fields="",$desc=false)
 {
     $list       = '';
         
     if($table != ''){ 
     	 $sel_fields = (!empty($fields))?$fields:'*';
     	
         $sql_string = 'select '.$sel_fields.' from `'.$table.'` ';
         $sql_string.= (!empty($where))?('where '.$where.' '):'';
         $sql_string.= (!empty($order_by))?(' order by '.$order_by.' '.(($desc)?'desc':'asc')):'';
         $sql_string.= (!empty($limit))?(' limit '.$limit.';'):';';
   
         $sql        = $this->db->query($sql_string);
         if($sql->num_rows()>0)
         {
         	 $x      = 0;
             $res    = $sql->result(); 
             
             foreach ($res as $item){
             	 //$param=$item->Field;
	             if($x==1){
					$item->css_class=' class="'.$css_class.'"';
					$x=0;
				 }else{
					$item->css_class='';
					$x=1;
				 }
              $list[] = $this->strip_slach_db_item($item,$text_fields,$photo_fields);         
             }
             unset($res);
         } 
     }
     return ($the_one)?((!empty($list))?$list[0]:""):$list;
 }
 
 /* 
   $photo_fields in format array('photo1'=>'img/small','photo2'=>'img/small'); 
   $text_fields  in format array('name','nickname','phone');
   */
  function strip_slach_db_item($item,$text_fields="",$photo_fields="")
  {
  	 if(!empty($item)){
  	 	if(!empty($photo_fields)){
  	 		foreach($photo_fields as $k=>$v){
  	 			if(isset($item->$k) and $item->$k!=""){
  	 				clearstatcache();
  	 				if(is_file(_DOOCUMENT_ROOT).'/'.$v.'/'.$item->$k){
  	 					$is_photo ='is_'.$k;
  	 					$item->$is_photo = true;
  	 				}
  	 			}
  	 		}
  	 	}
  	 }
  	 if(!empty($text_fields)){
  	 	
  	 	foreach($text_fields as $val){
  	 		if(isset($item->$val)){$item->$val=stripslashes($item->$val);}
  	 	}
  	 }
  	 
  	 return $item;
  }
 
 /*================================ up/dwon function ===========================*/
 function up($table,$where,$num_field='num',$else_where='')
	{
	    if($table!="" and $where!="")
	    {
	        $sql_string = 'select * from `'.$table.'` where '.$where.' ';
	        $sql_string.= ($else_where!="")?(' and  '.$else_where):'';
	        $sql_f = $this->db->query($sql_string);
	        if($sql_f->num_rows()>0)
	        {
	            $res_f = $sql_f->row();
	            if(isset($res_f->$num_field) and isset($res_f->id))
	            {
	               $num_f = $res_f->$num_field; // current num
	               $sql_string2 = 'select * from `'.$table.'` where `'.$num_field.'`<"'.$num_f.'" ';
                   $sql_string2.= ($else_where!="")?(' and  '.$else_where):'';
	               $sql_string2.= ' order by `'.$num_field.'` desc;';
	               $sql_s = $this->db->query($sql_string2);
	               if($sql_s->num_rows()>0)
	               {
	                   $res_s = $sql_s->row(); // second num
	                   if(isset($res_s->$num_field) and isset($res_s->id)){
	                       $num_s = $res_s->$num_field;
	                       
	                       $this->db->query('update `'.$table.'` set `'.$num_field.'`="'.$num_f.'" where `id`="'.$res_s->id.'";');
	                       $this->db->query('update `'.$table.'` set `'.$num_field.'`="'.$num_s.'" where `id`="'.$res_f->id.'";');
	                   }
	               }
	            }
	            
	        }
	    }
	}
	
 function down($table,$where,$num_field='num',$else_where='')
	{
	    if($table!="" and $where!="")
	    {
	        $sql_string = 'select * from `'.$table.'` where '.$where;
	        $sql_string.= ($else_where!="")?(' and  '.$else_where):'';
	        
	        $sql_f = $this->db->query($sql_string);
	        if($sql_f->num_rows()>0)
	        {
	            $res_f = $sql_f->row();
	            if(isset($res_f->$num_field) and isset($res_f->id))
	            {
	               $num_f = $res_f->$num_field; // current num
	               //var_dump($num_f);
	               $sql_string2 = 'select * from `'.$table.'` where `'.$num_field.'`>"'.$num_f.'" ';
                   $sql_string2.= ($else_where!="")?(' and  '.$else_where):'';
	               $sql_string2.= ' order by `'.$num_field.'`;';
	               
	               $sql_s = $this->db->query($sql_string2);
	               if($sql_s->num_rows()>0)
	               {
	                   $res_s = $sql_s->row(); // second num
	                   if(isset($res_s->$num_field) and isset($res_s->id)){
	                       $num_s = $res_s->$num_field;
	                       $this->db->query('update `'.$table.'` set `'.$num_field.'`="'.$num_f.'" where `id`="'.$res_s->id.'";');
	                       $this->db->query('update `'.$table.'` set `'.$num_field.'`="'.$num_s.'" where `id`="'.$res_f->id.'";');
	                   }
	               }
	            }
	            
	        }
	    }
	}

 function _get_curr_id_table($table)
 {
     $id='';
     if($table!=''){
       $sql=$this->db->query('show table status like "'.$table.'";');
       $res=$sql->row();
       $id=$res->Auto_increment-1;  
     }
     return $id;
 }
	
  function is_page_exist($table,$where)
 {
     $result = false;
     if($table!='' and $where!=""){
         $sql_string = 'select * from `'.$table.'` ';
         $sql_string.= ' where '.$where.';';
         $sql = $this->db->query($sql_string);
         if($sql->num_rows()>0){
             $result = true;
         }
     }
     return $result;
 }
 
 function debuger($var){
	    $args = func_get_args();
	    $argn = func_num_args();	    
	    for ($i = 0; $i < $argn; $i++)
	    {
	        echo '<pre>';
	        print_r($args[$i]);
	        echo '</pre>';
	    }	    
	    die(); // жЫвотное!
	}
}
?>