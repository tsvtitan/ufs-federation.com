<?php
class front_pages_debt_market_surveys_on_emitter_model extends Model{

	function front_pages_debt_market_surveys_on_emitter_model()
	{
		parent::Model();
		
		$this->load->model('maindb_model');
	}
    
    function last($limit=3)
    {
      $ret=array();
        
            $sql=$this->db->query('select `url`,`name`,`short_content`,`timestamp` 
                                   from `debt_market_surveys_on_emitter` 
                                   where `lang`="'.$this->site_lang.'" 
                                   order by `timestamp` desc 
                                   limit '.$limit.';');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'debt_market_surveys_on_emitter');
                         $res=$this->global_model->date_format($res,'j #_n_# Y');
                        /*********************************/
                         
                    $page_url='pages/'.$this->global_model->pages_url('debt_market_surveys_on_emitter');
                         
                    for($i=0;$i<count($res);$i++){
                        $res[$i]->page_url=$page_url;
                    }
                         
                    $ret=$res;
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
    
    private function getPageRviews($page_id)
    {
    	$reviews = '';
    	 
    	$tmp     =  $this->maindb_model->select_table('analytics_reviews_pages',' `page_id`="'.$page_id.'"','id','',false);
    	
    	if(!empty($tmp)){
    		foreach($tmp as $item){
    			$tmp_review = $this->maindb_model->select_table('analytics_reviews',' `id`="'.$item->analitic_review_id.'"','id',1,true); 
    			if(!empty($tmp_review)){
    				$tmp_review = $this->global_model->date_format($tmp_review,'d #_n_# Y','date','timestamp');
    				$tmp_review->files = $this->maindb_model->select_table('analytics_reviews_files',
                                                                   " `debt_market_id`='".$tmp_review->id."' ",'id','',false,'itembg',array('name'));
    				
    				$reviews[] = $tmp_review;
    			}
    			unset($tmp_review);
    		}
    	}
    	
    	return $reviews;
    }
    
    function view($ret)
    {
    	$is_one_debt       = false;
    	$data['files']     = '';
    	$data['files_all'] = '';

    	$page              = $this->getOwnPage($this->uri->segment(5));
    	if(empty($page))redirect('/error.html');
    	
        $url               = mysql_string($this->uri->segment(6));
        $res               = '';
        
        	if(isset($ret->content_editor_class)){
        		$data['content_editor_class'] = $ret->content_editor_class;
        	}        	
        	if(isset($ret->content)){
        		$data['content'] = $ret->content;
        	}        
        
        if(!empty($url) and $url!='all'){
            $where       = 'and `url`="'.$url.'"';
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
                         
                        $data['page_url'] = $ret->page_url = 'pages/'.$this->global_model->pages_url('analytics_reviews');
                        
                        if($res_data=='data'){
                            $this->data['title']       = (empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                            $this->data['keywords']    = $res->meta_keywords;
                            $this->data['description'] = $res->meta_description;
	                        if(!empty($res)){
	                        	$data['files']    = $this->maindb_model->select_table('analytics_reviews_files',
                                                                                  " `debt_market_id`='".$res->id."' ",'id','',false,'itembg',array('name'));
	                        	
	                        }                            
                        } 
                }            
            /* one rivew  */
            
        }else{
            $where       = '';
            $res_func    = 'result';
            $res_data    = 'data_arr';
            $data['page_url'] = $ret->page_url = 'pages/'.$this->global_model->pages_url('debt_market_surveys_on_emitter');
            $res         = $this->getPageRviews($page->id);
        }
            

                if(!empty($res)){    
                        $data[$res_data]               = $res;
                        $ret->content=$this->load->view('view_pages_debt_market_surveys_on_emitter',$data,true);  
                }
        
        return $ret;
    }

}
?>
