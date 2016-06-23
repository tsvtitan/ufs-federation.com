<?php
class front_pages_press_about_us_model extends Model{

	function front_pages_press_about_us_model()
	{
		parent::Model();
	}
    
    function last($limit=2)
    {
      $ret=array();
        
            $sql=$this->db->query('select `url`,`name`,`img`,`short_content`,`timestamp` 
                                   from `press_about_us` 
                                   where `lang`="'.$this->site_lang.'" 
                                   order by `timestamp` desc 
                                   limit '.$limit.';');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'press_about_us');
                         $res=$this->global_model->date_format($res,'j.m.Y, H:i');
                        /*********************************/
                         
                    for($i=0;$i<count($res);$i++){
                        $res[$i]->page_url=$this->global_model->pages_url('press_about_us');
                    }

                    $ret=$res;
                }
                
      return $ret;
    }
    
    function view($ret)
    {
        $url=mysql_string($this->uri->segment(5));
        
        if(!empty($url) and $url!='all'){
           $where='and `url`="'.$url.'"';           
           $vals='*';
           $limit='limit 1';
            
             $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));
            
           $res_func='row';
           $res_data='data';
        }else{
          $where='';            
          $vals='count("t") as Rows';
          $limit=''; 
          $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));
            
            if($sql_r->num_rows()>0){	
                $res_r=$sql_r->row();

                /* pager */ 
                $show=15;
                $rows=$res_r->Rows;
                $ret_page=((int)$this->uri->segment(7)==0)?1:(int)$this->uri->segment(7);
                $param['url']=$ret->page_url.'/all';
                $param['tpl_set']='body_pager_front';

                $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
                /*********/
                
                $this->data['pages']=$pages['html'];
                $data['pages']=$pages['html'];

                $vals='*';
                $limit='limit '.$pages['start'].','.$pages['show']; 
                $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));
            }
            
            $res_func='result';
            $res_data='data_arr';
        }

           if(isset($sql)){
                if($sql->num_rows()>0){
                    $res=$sql->$res_func();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'press_about_us');
                         $res=$this->global_model->date_format($res,'j.m.Y, H:i','date','timestamp');
                        /*********************************/
                         
                        $data['page_url']=$ret->page_url;
                        
                        if($res_data=='data'){
                            $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                            $this->data['keywords']=$res->meta_keywords;
                            $this->data['description']=$res->meta_description;
                        }
                         
                        $data[$res_data]=$res;
                        $ret->content=$this->load->view('view_pages_press_about_us',$data,true);  
                }
           }
        
        return $ret;
    }
    
    private function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `press_about_us`
              where `lang`="'.$this->site_lang.'"
              '.$where.'
              order by `timestamp` desc
              '.$limit.';';
        
        return $sql;
    }

}
?>
