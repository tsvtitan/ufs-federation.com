<?php
class front_pages_analytics_reviews_model extends Model{

	function front_pages_analytics_reviews_model()
	{
		parent::Model();
		
		$this->load->model('maindb_model');
	}
    
    function last($page_id,$limit=3)
    {
      $ret  = array();
      $page = $this->getOwnPageById($page_id);

      $tmp  = $this->getPageRviews($page_id,$limit); 
      
      if(!empty($tmp)){
                    $page_url = 'pages/'.$this->global_model->pages_url_by_url($page->url);
                         
                    for($i=0;$i<count($tmp);$i++){
                        $tmp[$i]->page_url = $page_url;
                    }
                    if(!empty($tmp))$ret=$tmp;   
                        	
      }
        if(!empty($ret)){
        	 usort($ret, array('front_pages_analytics_reviews_model', 'mySortDate'));
        }     
                
      return $ret;
    }   
    
    private function getOwnPage($url)
    {
    	$page = '';
    	
    	if($url!=""){
    		$page   = $this->maindb_model->select_table('pages',' `url`="'.$url.'"','id',1,true,'itembg',array('name'));
    	}
    	
    	return $page;
    }    
    private function getOwnPageById($id)
    {
    	$page = '';
    	
    	if($id!=""){
    		$page   = $this->maindb_model->select_table('pages',' `id`="'.$id.'"','id',1,true,'itembg',array('name'));
    	}
    	
    	return $page;
    }
    
    private function getPageRviews($page_id,$limit='')
    {
    	$reviews = '';
    	$tmp  = '';
    	$sql = $this->db->query('SELECT t1.*, t2.name,t2.timestamp FROM `analytics_reviews_pages` t1 
LEFT JOIN analytics_reviews t2 ON t2.id=t1.analitic_review_id
WHERE t1.`page_id`="'.$page_id.'" and t2.only_mailing is null and t2.company="UFS"
ORDER BY t2.timestamp DESC limit '.$limit);
    	if($sql->num_rows()>0){
    		$tmp    = $sql->result(); 
    	}

    	//$tmp     =  $this->maindb_model->select_table('analytics_reviews_pages',' `page_id`="'.$page_id.'"','id',$limit,false,'','','','',true);
    	
    	if(!empty($tmp)){
    		foreach($tmp as $item){
    			$tmp_review = $this->maindb_model->select_table('analytics_reviews',' `id`="'.$item->analitic_review_id.'"','timestamp',1,true);     			
    			
    			if(!empty($tmp_review)){
    				$tmp_review = $this->global_model->date_format($tmp_review,'d #_n_# Y','date','timestamp');
    				$tmp_review->files = $this->maindb_model->select_table('analytics_reviews_files',
                                                                   " `debt_market_id`='".$tmp_review->id."' ",'id','',false,'itembg',array('name'));
    				$tmp_review->page_id = $page_id;
    				
    				$reviews[] = $tmp_review;
    			}
    			unset($tmp_review);
    		}
    	}
        if(!empty($reviews)){
        	 //usort($reviews, array('front_pages_analytics_reviews_model', 'mySortDate'));
        }   
 	
    	return $reviews;
    }
    
    private function mySortDate($a,$b)
    {
		if ($a->timestamp == $b->timestamp) return 0;
		else return ($a->timestamp > $b->timestamp ? -1 : 1);    	
    }     
    
    function view($ret,$index=5)
    {
    	$is_one_debt       = false;
    	$data['files']     = '';
    	$data['files_all'] = '';
      $segment = $index;
      
    	$page = $this->getOwnPage($this->uri->segment($segment));
    	if (!empty($page)) { 
    	  $segment++;
    	}
    	
        $url               = mysql_string($this->uri->segment($segment));
        $res               = '';
        
        	if(isset($ret->content_editor_class)){
        		$data['content_editor_class'] = $ret->content_editor_class;
        	}        	
        	if(isset($ret->content)){
        		$data['content'] = $ret->content;
        	}        
        
        if(!empty($url) and $url!='all' and $url!='page'){
            $where       = 'and `url`="'.$url.'" ';
            $res_func    = 'row';
            $res_data    = 'data';
            $is_one_debt = true;
            
            /* one rivew  */
            $sql      = $this->db->query('select *
                                   from `analytics_reviews` 
                                   where `lang`="'.$this->site_lang.'"
                                     and company="UFS"
                                   '.$where.'
                                   order by `timestamp` desc;');

                if($sql->num_rows()>0){
                    $res = $sql->$res_func();

                        /*********************************/
                         $res = $this->global_model->adjustment_of_results($res,'analytics_reviews');
                         $res = $this->global_model->date_format($res,'d #_n_# Y','date','timestamp');
                        /*********************************/
                         
                       // $data['page_url'] = $ret->page_url = 'pages/'.$this->global_model->pages_url('analytics_reviews');
                        $data['page_url'] = $ret->page_url = 'pages/'.$this->global_model->pages_url_by_url($this->uri->segment(5));
                        
                        if($res_data=='data'){
                          
                          $this->data['title']       = (empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                          $this->data['keywords']    = $res->meta_keywords;
                          $this->data['description'] = $res->meta_description;
	                        
                          if(!empty($res)){
	                        	
                            $data['files']    = $this->maindb_model->select_table('analytics_reviews_files',
                                                                                  " `debt_market_id`='".$res->id."' ",'id','',false,'itembg',array('name'));
	                        	
                            $links = array();
                            $sql = sprintf("select ar.id, ar.name, ar.timestamp, ar.url, p.id as page_id
                                              from analytics_reviews ar
                                              left join analytics_reviews_pages arp on arp.analitic_review_id=ar.id
                                              left join pages p on p.id=arp.page_id
                                             where ar.id in (select review_id
                                                               from analytics_reviews_keywords
                                                              where keyword_id in (select keyword_id
                                                                                     from analytics_reviews_keywords
                                                                                    where review_id=%d))
                                               and ar.id<>%d
                                               and ar.lang=%s
                                               and ar.company='UFS'
                                               and (ar.only_mailing is null or ar.only_mailing=0)
                                             order by ar.timestamp desc
                                             limit 30",$res->id,$res->id,'"'.$this->site_lang.'"');
                            $res2 = $this->db->query($sql);
                            if ($res2->num_rows()>0) {
                              $res2 = $res2->result();

                              foreach ($res2 as $r) {

                                $item = new stdClass();
                                $item->name = $r->name;
                                $item->date = date('d.m.y',strtotime($r->timestamp));
                                $item->url = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($r->page_id).'/'.$r->url.$this->urlsufix;
                                $links[] = $item; 
                              }
                            }  

                            $data['links'] = $links;
                            
	                        }                            
                        } 
                }            
            /* one rivew  */
            
        }else{
          
          $segment = $index;
          $page = $this->getOwnPage($this->uri->segment($segment));
          if(empty($page))redirect('/error.html');
          
            $where       = '';
            $res_func    = 'result';
            $res_data    = 'data_arr';
            //$data['page_url'] = $ret->page_url = 'pages/'.$this->global_model->pages_url('debt_market_surveys_on_emitter');
            $data['page_url'] = $ret->page_url = 'pages/'.$this->global_model->pages_url_by_url($this->uri->segment($segment));
            
            /* pager */ 
            $show             = 3;
            $rows             = $this->maindb_model->get_count_rows('analytics_reviews_pages',' `page_id`="'.$page->id.'" ');
            $ret_page         = ((int)$this->uri->segment($segment+2)==0)?1:(int)$this->uri->segment($segment+2);
            $param['url']     = '/'.$this->global_model->pages_url_by_url($this->uri->segment($segment));;
            $pages            = $this->global_model->Pager($show,$rows,$param,$ret_page);
            
            $limit            = ' '.$pages['start'].','.$pages['show']; 
                         
            
            $res              = $this->getPageRviews($page->id,$limit);
            $data['pages']    = $pages['html'];
            
            
        }
            

        if(!empty($res)){    
                $data[$res_data]               = $res;
                $ret->content=$this->load->view('view_pages_debt_market_surveys_on_emitter',$data,true);  
        }
        
        return $ret;
    }

}
?>
