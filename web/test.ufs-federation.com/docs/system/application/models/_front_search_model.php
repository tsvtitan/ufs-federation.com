<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/sphinxapi.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/strutils.php');

class front_search_model extends Model{

	private $sClasses = array(
				'0'  => 'analytics_news',
				'1'  => 'questions_comment',
				'2'  => 'pages',
				'3'  => 'press_about_us',
				'4'  => 'analytics_reviews',
			    '5'  => 'downloads',
			    '6'  => 'news',
			    '7'  => 'conferencies_comment'
			);    
    
	function front_search_model()
	{
		parent::Model();
        
        
        $this->load->model('maindb_model');
	}
	
    private function mySortDate($a,$b)
    {
		if ($a['timestamp'] == $b['timestamp']) return 0;
		else return ($a['timestamp'] > $b['timestamp'] ? -1 : 1);    	
    } 	
    
    function view()
    {  
        //var_dump($this->uri);
    	$data                         = '';
    	$data['results']              = array();
    	$sidebar_right['data']        = '';
    	$sidebar_right['indexes_box'] = '';
    	$sidebar_right['contacts']    = '';
    	$sidebar_left['menu']         = '';
    	
        $search_word     = '';
    	$kword           = isset($_REQUEST['searched'])?$_REQUEST['searched']:'';
    	$kword           = stripslashes($kword);
    	$option          = isset($_REQUEST['option'])?$_REQUEST['option']:'';
    	
    	if (!isset($option)) {
          $option = SPH_MATCH_ANY;
    	}

    	$data['kword']   = $kword;
        $items           = array();
        $r_items         = array();
        $result_count    = 0;
        
        $search_page     = $this->maindb_model->select_table('pages',' `sub_page_type`="search" and lang="'.$this->site_lang.'"','id',1,true);
        if(empty($search_page))redirect('/error.html');
        $sidebar_left['menu']             = self::sub_menu($search_page->cat_id,'search');
        
        $sidebar_right['indexes_box']     = $search_page->indexes_box;
        
        $this->data['body_sidebar_right'] = $this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
        $this->data['body_sidebar_left']  = $this->load->view('body_sidebar_left',$sidebar_left,true);
        
    	if(!empty($kword))
		{
               $_SESSION['the_search_word'] = $kword;
               
               require_once (APPPATH.'config/sphinx'.EXT);
               
			    $sphinx = new SphinxClient();			
			    // Подсоединяемся к Sphinx-серверу
			    $sphinx->SetServer(SPHINX_SOCKET);			   
			    // Совпадение по любому слову
			    $sphinx->SetMatchMode($option);	// SPH_MATCH_ALL	SPH_MATCH_ANY	   
			    // Результаты сортировать по релевантности
			    $sphinx->SetSortMode(SPH_SORT_RELEVANCE);	//SPH_SORT_RELEVANCE		   
			    // Задаем полям веса (для подсчета релевантности)
			    $sphinx->SetFieldWeights(array ('name' => 20, 'short_content' => 10));

			    
			    // $sphinx->SetRankingMode(SPH_RANK_WORDCOUNT);
			    
			    // Результат по запросу (* - использование всех индексов)
			    
			    $resArray_sanalytics_news         = $sphinx->Query($kword,'i_sanalytics_news');	
			    $resArray_squestions              = $sphinx->Query($kword,'i_squestions');	
			    $resArray_spages                  = $sphinx->Query($kword,'i_spages');	
			    $resArray_spress_about            = $sphinx->Query($kword,'i_spress_about');	
			    $resArray_sanalytics_reviews      = $sphinx->Query($kword,'i_sanalytics_reviews');	
			    $resArray_sdownloads              = $sphinx->Query($kword,'i_sdownloads');
			    $resArray_snews                   = $sphinx->Query($kword,'i_snews');
			    $resArray_sconferencies           = $sphinx->Query($kword,'i_sconferencies');
			     

                if($resArray_sanalytics_news===false or 
                   $resArray_squestions===false or 
                   $resArray_spages===false or 
                   $resArray_spress_about===false or
                   $resArray_sanalytics_reviews===false or
                   $resArray_sdownloads===false or 
                   $resArray_snews===false or 
                   $resArray_sconferencies===false) {
                	
                   echo "Query failed: " . $sphinx->GetLastError() . ".\n"; // выводим ошибку если произошла
                }
                else{
                    	$sanalytics_news = $this->formatSearchArr($resArray_sanalytics_news,0,$kword);
                        if(count($sanalytics_news)>0){
                            $items = $sanalytics_news;
                        }
						
                        $squestions     = $this->formatSearchArr($resArray_squestions,1,$kword);
						if(count($squestions)>0){
							if(count($items)>0) $items = array_merge($items,$squestions);  else $items = $squestions;
						}						
						
						$pages        = $this->formatSearchArr($resArray_spages,2,$kword);
                	    if(count($pages)>0){
							if(count($items)>0) $items = array_merge($items,$pages);  else $items = $pages;
						}						
						
						$press_about   = $this->formatSearchArr($resArray_spress_about,3,$kword);
						if(count($press_about)>0){
							if(count($items)>0) $items = array_merge($items,$press_about);  else $items = $press_about;
						} 						
						
						$reviews       = $this->formatSearchArr($resArray_sanalytics_reviews,4,$kword);
						if(count($reviews)>0){
							if(count($items)>0) $items = array_merge($items,$reviews);  else $items = $reviews;
						} 
						
						$downloads   = $this->formatSearchArr($resArray_sdownloads,5,$kword);
						if(count($downloads)>0){
							if(count($items)>0) $items = array_merge($items,$downloads);  else $items = $downloads;
						}
						$news   = $this->formatSearchArr($resArray_snews,6,$kword);
						if(count($news)>0){
							if(count($items)>0) $items = array_merge($items,$news);  else $items = $news;
						}
						$conferencies   = $this->formatSearchArr($resArray_sconferencies,7,$kword);
						if(count($conferencies)>0){
							if(count($items)>0) $items = array_merge($items,$conferencies);  else $items = $conferencies;
						}
						
                }
            if(count($items)){
                 usort($items, array('front_search_model', 'mySortDate'));
            } 
            $_SESSION['search_results'] = $items;               
    	}


           if(isset($_SESSION['the_search_word'])){
               $search_word = $_SESSION['the_search_word'];
           }
           $data['search_word']   = $search_word;
           $data['option']        = $option;
            
           if(isset($_SESSION['search_results'])){
               if(is_array($_SESSION['search_results'])){
                   $result_count     = count($_SESSION['search_results']);
                   $tmp_items        = $_SESSION['search_results'];
                   $data['page_url'] = $ret->page_url = 'search';
                   
                    /* pager */ 
                    $show             = 5;
                    $rows             = count($_SESSION['search_results']);
                    $ret_page         = ((int)$this->uri->segment(4)==0)?1:(int)$this->uri->segment(4);
                    $param['url']     = '';
                    $pages            = $this->global_model->Pager($show,$rows,$param,$ret_page);

                    //$limit            = ' '.$pages['start'].','.$pages['show']; 

                    for($i=$pages['start'];$i<($pages['start']+$pages['show']);$i++)
                        {
                            if(isset($tmp_items[$i])){	                		
                                $r_items[] = $tmp_items[$i];
                            }
                        }
                    $data['pages']    = $pages['html'];                    
               }
           }
;        
        
        $data['items'] = $r_items;
        $data['result_count'] = $result_count;
        
        return $this->load->view('view_search_result',$data,true);
    }
    
    
	private function formatSearchArr($arr,$class_id,$keyword){
		
		$result = array();
			if ( $arr != false )
			{
				if(! empty($arr) and isset($arr['matches'])){
					if(count($arr['matches'])>0){
						foreach($arr['matches'] as $k=>$v){
							    $obj = '';
								$tmp = array();						
								$tmp['type']   = $class_id;	
                                if($class_id==2){
                                    $obj           = $this->maindb_model->select_table($this->sClasses[$class_id],'id='.$k.' and lang="'.$this->site_lang.'" and link_to_page="" ','id',1,true);
                                }
                                else{
                                    $obj           = $this->maindb_model->select_table($this->sClasses[$class_id],'id='.$k.' and lang="'.$this->site_lang.'" ','id',1,true);
                                }
                                
								if(!empty($obj)){
                                    $tmp['id']     = $obj->id;
                                    if(isset($obj->timestamp)){
                                        $obj       = $this->global_model->date_format($obj,'d #_n_# Y','date','timestamp');
                                        $tmp['timestamp'] = $obj->timestamp;
                                    }
                                    else{
                                        $tmp['timestamp'] = '';
                                    }
                                    
                                    
                                    switch ($class_id){
                                        case 0: // analytics_news

                                        	 $page_news   = $this->maindb_model->select_table('pages','sub_page_type="analytical_news" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($page_news)){
                                             	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_news->id).$this->urlsufix;
                                             }
                                             else{
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                        	 $tmp['menu'][1] = array ('name'=>trim_utf8(dictionary($page_news->name)),'link'=>$the_url);
                                        	 
                                        	 $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$page_news->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                        	 if(!empty($parent_page)){
                                        	 	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                        	 } else {
                                        	 	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                        	 }
                                        	 array_unshift($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                        	 
                                        	 $the_url=$this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_news->id).'/'.$obj->url.$this->urlsufix;
                                        	 $tmp['menu'][2] = array ('name'=>trim_utf8($obj->name),'link'=>$the_url);
                                        	
                                        	 $tmp['name']   = $obj->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $the_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text(array($obj->short_content,$obj->content), $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text(array($obj->short_content,$obj->content), $keyword), $keyword,'mark')):truncate_text((($obj->short_content!="")?$obj->short_content:$obj->content), 30);             
                                             if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                            break;
                                        case 1: // questions
                                             
                                        	 $page_questions   = $this->maindb_model->select_table('pages','sub_page_type="questions" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($page_questions)){
                                             	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_questions->id).$this->urlsufix;
                                             }
                                             else{
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                        	 $tmp['menu'][1] = array ('name'=>trim_utf8(dictionary($page_questions->name)),'link'=>$the_url);
                                        	 
                                        	 $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$page_questions->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                        	 if(!empty($parent_page)){
                                        	 	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                        	 } else {
                                        	 	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                        	 }
                                        	 array_unshift($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                        	 
                                        	 $res = $this->maindb_model->select_table('questions','id="'.$obj->c_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                        	 
                                        	 $the_url=$this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_questions->id).'/'.$res->url.$this->urlsufix;
                                        	 $tmp['menu'][2] = array ('name'=>trim_utf8($res->name),'link'=>$the_url);

                                        	 $content=$obj->query.' - '.$obj->answer;
                                        	 
                                        	 $tmp['name']   = $obj->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $the_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text($content, $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text($content, $keyword), $keyword,'mark')):truncate_text($content, 30); 
                                            if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                        	break;
                                        case 2: // pages
                                        	
                                        	 $tmp['menu']=array();
                                        	 
                                             $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$obj->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($parent_page)){
                                            	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                             } else {
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                             array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                        	 
                                             $parent_page   = $this->maindb_model->select_table('pages','id="'.$obj->parent_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($parent_page)){
                                            	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($parent_page->id).'/'.$parent_page->url.$this->urlsufix;
                                             	array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                             }
                                        	 
                                             $the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($obj->id).'/'.$obj->url.$this->urlsufix;
                                             array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($obj->name)),'link'=>$the_url));
                                                                                     	
                                        	 $tmp['name']   = $obj->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $the_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text($obj->content, $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text($obj->content, $keyword), $keyword,'mark')):truncate_text($obj->content, 30); 
                                            if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                            break;
                                        case 3: // press_about_us

                                        	 $page_press   = $this->maindb_model->select_table('pages','sub_page_type="press_about_us" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($page_press)){
                                             	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_press->id).$this->urlsufix;
                                             } else{
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             } 
                                        	 $tmp['menu'][1] = array ('name'=>trim_utf8(dictionary($page_press->name)),'link'=>$the_url);
                                        	 
                                        	 $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$page_press->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                        	 if(!empty($parent_page)){
                                        	 	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                        	 } else {
                                        	 	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                        	 }
                                        	 array_unshift($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                        	 
                                        	 $the_url=$this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_press->id).'/'.$obj->url.$this->urlsufix;
                                        	 $tmp['menu'][2] = array ('name'=>trim_utf8($obj->name),'link'=>$the_url);
                                        	 
                                        	 $tmp['name']   = $obj->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                           
                                             $tmp['link']   = $the_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text(array($obj->short_content,$obj->content), $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text(array($obj->short_content,$obj->content), $keyword), $keyword,'mark')):truncate_text((($obj->short_content!="")?$obj->short_content:$obj->content), 30);                    
                                            if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }                                             
                                            break;
                                        case 4: // analytics_reviews
                                        	
                                             $tmp['menu']=array();
                                        	 
                                             $page_review   = $this->maindb_model->select_table('analytics_reviews_pages','analitic_review_id="'.$obj->id.'" ','id',1,true);
                                             if(!empty($page_review)){
                                            	$review_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_review->page_id).'/'.$obj->url.$this->urlsufix;
                                             } else {
                                             	$review_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                             
                                        	 $res = $this->maindb_model->select_table('pages','id="'.$page_review->page_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             
                                        	 $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$res->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($parent_page)){
                                             	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                             } else {
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                             array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                              
                                             
                                             $parent_page   = $this->maindb_model->select_table('pages','id="'.$res->parent_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($parent_page)){
                                             	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($parent_page->id).$this->urlsufix;
                                             	array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                             }
                                             
                                             $the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_review->page_id).$this->urlsufix;
                                             array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($res->name)),'link'=>$the_url));
                                             
                                             array_push($tmp['menu'],array ('name'=>trim_utf8(dictionary($obj->name)),'link'=>$review_url));
                                              
                                        	 $tmp['name']   = $obj->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $review_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text($obj->content, $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text($obj->content, $keyword), $keyword,'mark')):truncate_text($obj->content, 30); 
                                            if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                            break;
                                        case 5: // downloads

                                        	 $parent_page   = $this->maindb_model->select_table('downloads_menu','id="'.$obj->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($parent_page)){
                                             	$the_url    = $this->phpself.'downloads/'.$parent_page->url.$this->urlsufix;
                                             } else {
                                             	$the_url    = $this->phpself.'downloads'.$this->urlsufix;
                                             }
                                         	 
                                        	 $tmp['menu'][0] = array ('name'=>dictionary('Файлы для скачивания'),'link'=>$this->phpself.'downloads'.$this->urlsufix);
                                        	 $tmp['menu'][1] = array ('name'=>trim_utf8($parent_page->name),'link'=>$the_url);
                                        	 
                                             $tmp['name']   = $parent_page->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $this->phpself.'download-files/'.$obj->url.$this->urlsufix;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text($obj->name, $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text($obj->name, $keyword), $keyword,'mark')):truncate_text($obj->name, 30); 
                                            if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                            break;
                                        case 6: // news
                                             
                                        	 $page_news   = $this->maindb_model->select_table('pages','sub_page_type="news" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($page_news)){
                                             	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_news->id).$this->urlsufix;
                                             }
                                             else{
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                        	 $tmp['menu'][1] = array ('name'=>trim_utf8(dictionary($page_news->name)),'link'=>$the_url);
                                        	 
                                        	 $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$page_news->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                        	 if(!empty($parent_page)){
                                        	 	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                        	 } else {
                                        	 	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                        	 }
                                        	 array_unshift($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                        	 
                                        	 $the_url=$this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_news->id).'/'.$obj->url.$this->urlsufix;
                                        	 $tmp['menu'][2] = array ('name'=>trim_utf8($obj->name),'link'=>$the_url);
                                        	
                                        	 $tmp['name']   = $obj->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $the_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text(array($obj->short_content,$obj->content), $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text(array($obj->short_content,$obj->content), $keyword), $keyword,'mark')):truncate_text((($obj->short_content!="")?$obj->short_content:$obj->content), 30);             
                                             if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                            break;
                                        case 7: // conferencies
                                             
                                        	 $page_conferencies   = $this->maindb_model->select_table('pages','sub_page_type="conferencies" and lang="'.$obj->lang.'" ','id',1,true);
                                             if(!empty($page_conferencies)){
                                             	$the_url    = $this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_conferencies->id).$this->urlsufix;
                                             }
                                             else{
                                             	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                             }
                                             
                                        	 $tmp['menu'][1] = array ('name'=>trim_utf8(dictionary($page_conferencies->name)),'link'=>$the_url);
                                        	 
                                        	 $parent_page   = $this->maindb_model->select_table('pages_menu','id="'.$page_conferencies->cat_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                        	 if(!empty($parent_page)){
                                        	 	$the_url    = $this->phpself.'pages/'.$parent_page->url.$this->urlsufix;
                                        	 } else {
                                        	 	$the_url    = $this->phpself.'pages'.$this->urlsufix;
                                        	 }
                                        	 array_unshift($tmp['menu'],array ('name'=>trim_utf8(dictionary($parent_page->name)),'link'=>$the_url));
                                        	 
                                             $res = $this->maindb_model->select_table('conferencies','id="'.$obj->c_id.'" and lang="'.$obj->lang.'" ','id',1,true);
                                             
                                        	 $the_url=$this->phpself.'pages/'.$this->global_model->pages_url_by_id($page_conferencies->id).'/'.$res->url.$this->urlsufix;
                                        	 $tmp['menu'][2] = array ('name'=>trim_utf8($res->name),'link'=>$the_url);

                                        	 $content=$obj->query.' - '.$obj->answer;
                                        	 
                                        	 $tmp['name']   = $res->name;
                                             $tmp['date']   = isset($obj->date)?$obj->date:'';
                                             $tmp['link']   = $the_url;
                                             $tmp['text']   = (highlight_search_result(get_search_cutted_text($content, $keyword), $keyword,'mark')!="")?(highlight_search_result(get_search_cutted_text($content, $keyword), $keyword,'mark')):truncate_text($content, 30); 
                                            if(empty_fck_text($tmp['text'])){
                                                $tmp['name']   = '<a href="'.$tmp['link'].'">'.$tmp['name'].'</a>';
                                            }   

                                            break;
                                    }
                                    
                                    unset($obj);
                                }
                                
							
								if(isset($tmp['id']))$result[]=$tmp;
								unset($tmp);
						}
					}
				}
			}	
				
			
		return $result;	
	}
	
    
    private function sub_menu($cat_id,$page_url)
    {
       $ret='';
        
        $sql=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`
                               from `pages` 
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
               
                $sql_s=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`
                                         from `pages` 
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
