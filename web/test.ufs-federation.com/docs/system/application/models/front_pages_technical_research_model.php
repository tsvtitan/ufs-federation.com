<?php
class front_pages_technical_research_model extends Model {

	function front_pages_technical_research_model()
	{
		parent::Model();
		$this->load->model('maindb_model');
	}
    
    function view()
    {
    	
    	$url = $this->uri->segment(6);
    	$ret='';
    	
    	if (!$url) {
    	
    	  $groups = array();
    	  $sql = 'select * from analytics_reviews_groups order by level, case when level=0 then priority else name end';
    	  $ret = $this->db->query($sql);
    	  if ($ret->num_rows()>0) {
    	  
    	    $arg = $ret->result();
    	    $arg = $this->global_model->make_tree($arg,'group_id');

    	    foreach ($arg as $r0) {

    	  	  $group = new stdClass();
    	  	  $group->group_id = $r0->group_id; 
    	  	  $group->name = $r0->name;
    	  	  $group->shorts = array();
    	  	  $group->longs = array();
    	  	  $groups[] = $group;
    	  	  
    	  	  $view_count=1000;
    	  	  if (isset($r0->view_count)) {
    	  	  	$view_count=$r0->view_count;
    	  	  }
    	  	
    	  	  // shorts
  	  	      $sql = sprintf('select t.timestamp, arg.name, ar.url, arp.page_id, arg.day_count '.
    	  	    	          'from ( '.
    	  	  		          'select arg.group_id, max(ar.timestamp) as timestamp '.
  	  	      		          'from analytics_reviews ar '. 
  	  	      		          'left join analytics_reviews_groups arg on arg.group_id=ar.group_id '. 
  	  	      		          'where arg.parent_id=%d '.
    	  	  		          'and ar.lang=%s and ar.company="UFS" '.
    	  	  		          'group by 1) t '.
    	  	  		          'join analytics_reviews ar on ar.group_id=t.group_id and ar.timestamp=t.timestamp '.
  	  	      		          'join analytics_reviews_groups arg on arg.group_id=t.group_id '.
    	  	  		          'left join analytics_reviews_pages arp on arp.analitic_review_id=ar.id '.
  	  	      		          'order by t.timestamp desc, arg.priority '.
    	  	  		          'limit %d',$r0->group_id,'"'.$this->site_lang.'"',$view_count);
    	  	  $ret = $this->db->query($sql);
    	  	  if ($ret->num_rows()>0) {

              $ar = $ret->result();
              foreach ($ar as $r) {

                 $item = new stdClass();
                 $item->date = date('d.m.y',strtotime($r->timestamp));
                 $item->name = $r->name;
                 $item->url = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($r->page_id).'/'.$r->url.$this->urlsufix;

                 if (!is_null($r->day_count)) {

                   $t1 = strtotime($r->timestamp);
                   $diff = (time()-$t1)/(24*3600);
                   if ($diff<$r->day_count) {
                     $item->new = true;
                   } 
                 }

                 $group->shorts[] = $item;
               }
    	  	  }

    	  	  //longs
    	  	  foreach ($r0->items as $r1) {
    	  	  
    	  	  	$sql = sprintf('select ar.timestamp, ar.url, p.id as page_id '.
    	  	  			       'from analytics_reviews ar '.
    	  	  			       'left join analytics_reviews_pages arp on arp.analitic_review_id=ar.id '.
    	  	  			       'left join pages p on p.id=arp.page_id '.
    	  	  			       'where ar.group_id=%d '.
    	  	  			       'and ar.lang=%s '.
                         'and ar.company="UFS" '.
    	  	  			       'order by ar.timestamp desc '.
    	  	  			       'limit 1',$r1->group_id,'"'.$this->site_lang.'"');
    	  	  	$ret = $this->db->query($sql);
    	  	  	if ($ret->num_rows()>0) {
    	  	  
    	  	  		$ar = $ret->result();
    	  	  		$ar = $ar[0];
    	  	  
    	  	  		$item = new stdClass();
    	  	  		$item->date = date('d.m.y',strtotime($ar->timestamp));
    	  	  		$item->name = $r1->name;
    	  	  		$item->url = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($ar->page_id).'/'.$ar->url.$this->urlsufix;
    	  	  
    	  	  		if (!is_null($r1->day_count)) {
    	  	  			$t1 = strtotime($ar->timestamp);
    	  	  			$diff = (time()-$t1)/(24*3600);
    	  	  			if ($diff<$r1->day_count) {
    	  	  				$item->new = true;
    	  	  			}
    	  	  		}
    	  	  			
    	  	  		$group->longs[] = $item;
    	  	  
    	  	  	}
    	  	  }
    	  	  
    	    }
    	  }
    			 
    	  $data['groups'] = $groups;
                
        $ret=$this->load->view('body_content_sub_technical_research',$data,true);
          
    	} else {

    	  $url = mysql_string($url);
    	  $sql = sprintf('select * from analytics_reviews where url=%s limit 1','"'.$url.'"');
    	  $res = $this->db->query($sql);
    	  if ($res->num_rows()>0) {
    	  	$res = $res->result();
    	  	$res = $res[0];
    	  	
    	  	$res = $this->global_model->adjustment_of_results($res,'analytics_reviews');
    	  	$res = $this->global_model->date_format($res,'d #_n_# Y','date','timestamp');
    	  	 
    	  	#$data['page_url'] = 'pages/'.$this->global_model->pages_url_by_url($this->uri->segment(5));
    	  	
  	  		$this->data['title']       = (empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
   	  		$this->data['keywords']    = $res->meta_keywords;
   	  		$this->data['description'] = $res->meta_description;
   	  		
          $data['files'] = $this->maindb_model->select_table('analytics_reviews_files',
                                                             " `debt_market_id`='".$res->id."' ",'id','',false,'itembg',array('name'));
          $data['data'] = $res;

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
                             and p.id in (647,650)
                             and (ar.only_mailing is null or ar.only_mailing=0)
                           order by ar.timestamp desc
                           limit 30",$res->id,$res->id,'"'.$this->site_lang.'"');
          $res = $this->db->query($sql);
          if ($res->num_rows()>0) {
            $res = $res->result();

            foreach ($res as $r) {

              $item = new stdClass();
              $item->name = $r->name;
              $item->date = date('d.m.y',strtotime($r->timestamp));
              $item->url = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($r->page_id).'/'.$r->url.$this->urlsufix;
              $links[] = $item; 
            }
          }  

          $data['links'] = $links;
   	  		
   	  		$ret = $this->load->view('view_pages_technical_research',$data,true);
    	  } 	
    		
    	}
        return $ret;
    }

}
?>