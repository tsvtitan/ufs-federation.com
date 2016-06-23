<?php
class front_pages_news_model extends Model{

	function front_pages_news_model()
	{
		parent::Model();
	}
    
    function last($limit=2)
    {
      $ret=array();
        
            $sql=$this->db->query('select `url`,`name`,`short_content`,`timestamp` 
                                   from `news` 
                                   where `lang`="'.$this->site_lang.'" 
                                   order by `timestamp` desc 
                                   limit '.$limit.';');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'news');
                         //$res=$this->global_model->date_format($res,'j #_n_# Y, H:i');
                        $res=$this->global_model->date_format($res,'j.m.Y');
                        /*********************************/

                    for($i=0;$i<count($res);$i++){
                        $res[$i]->page_url=$this->global_model->pages_url('news');
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
                $show=5;
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
                         $res=$this->global_model->adjustment_of_results($res,'news');
                        // $res=$this->global_model->date_format($res,'d #_n_# Y, H:i','date','timestamp');
                        $res=$this->global_model->date_format($res,'j.m.Y','date','timestamp');
                        /*********************************/
                         
                        $data['page_url']=$ret->page_url;
                        
                        if($res_data=='data'){
                            $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                            $this->data['keywords']=$res->meta_keywords;
                            $this->data['description']=$res->meta_description;
                            
                            $res->prev=self::prev($res->timestamp);
                        }
                         
                        $data[$res_data]=$res;
                        $ret->content=$this->load->view('view_pages_news',$data,true);  
                }
           }
        
        return $ret;
    }
    
    protected function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `news`
              where `lang`="'.$this->site_lang.'"
              '.$where.'
              order by `timestamp` desc
              '.$limit.';';
        
        return $sql;
    }
    
    private function prev($timestamp)
    {
      $ret=array();
        
            $sql=$this->db->query('select `url`,`name`,`timestamp` 
                                   from `news` 
                                   where `lang`="'.$this->site_lang.'"
                                   and `timestamp`<"'.$timestamp.'"
                                   order by `timestamp` desc 
                                   limit 3;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'news');
                         //$res=$this->global_model->date_format($res,'j #_n_# Y, H:i');
                        $res=$this->global_model->date_format($res,'j.m.Y');
                        /*********************************/

                    for($i=0;$i<count($res);$i++){
                        $res[$i]->page_url=$this->global_model->pages_url('news');
                    }
                         
                    $ret=$res;
                }
                
      return $ret;
    }

}
?>
