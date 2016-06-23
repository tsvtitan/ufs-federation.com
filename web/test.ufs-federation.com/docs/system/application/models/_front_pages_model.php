<?php
class front_pages_model extends Model{

	function front_pages_model()
	{
		parent::Model();
        
        $this->load->model('front_pages_about_company_model');
        $this->load->model('front_pages_news_model');
        $this->load->model('front_pages_press_about_us_model');
        $this->load->model('front_pages_conferencies_model'); 
        $this->load->model('front_pages_feedback_model');
        $this->load->model('front_pages_feedback_brokerage_model');
        $this->load->model('front_pages_analytics_news_model');
        $this->load->model('front_pages_analytics_team_model');
        $this->load->model('front_contacts_model');
        $this->load->model('front_right_adv_model');
        $this->load->model('front_pages_share_market_daily_comments_model');
        $this->load->model('front_pages_share_market_reviews_issuers_model');
        $this->load->model('front_pages_share_market_special_comments_model');
        $this->load->model('front_pages_share_market_trading_ideas_model');
        $this->load->model('front_pages_issuers_debt_market_model');
        $this->load->model('front_pages_trade_ideas_model');
		    $this->load->model('front_pages_recommendations_model');
				$this->load->model('front_pages_commodities_model');
				$this->load->model('front_pages_planned_placements_model');
				$this->load->model('front_pages_quotes_model');
				$this->load->model('front_pages_technical_research_model');
				$this->load->model('front_pages_share_market_model_portfolio_model');
        $this->load->model('front_pages_analytics_calendar_statistics_model');
        $this->load->model('front_pages_debt_market_surveys_on_emitter_model');
        $this->load->model('front_pages_analytics_reviews_model');
        $this->load->model('front_pages_debt_market_model');
        $this->load->model('front_pages_questions_model');
	}
    
    function view($page_id)
    {    
        $sidebar_left['menu']='';
        $sidebar_right['data']='';
        $sidebar_right['indexes_box']='';
        $content['data']='';
        $cat_url=mysql_string($this->uri->segment(3));
        $page_url=mysql_string($this->uri->segment(4));
        $s_page_url=mysql_string($this->uri->segment(5));
        
        if(empty($cat_url)){
            $url=$this->page_url.self::first_cat().$this->urlsufix;
            redirect($url);
        }
        
      /*    if(empty($page_url)){
             $where_url='and `t`.`parent_id`="0"'; 
          }else{
             if(empty($s_page_url))
             {
                 $tmp_url=$page_url;
             }else{
               $type=self::get_page_type($page_url);
                 if($type=='default' or $type=='share_market' or $type=='debt_market' or $type=='brokerage'){
                    $tmp_url=$s_page_url;
                 }else{
                    $tmp_url=$page_url; 
                 }
             }
             
             $where_url='and `t`.`url`="'.$tmp_url.'"';
          }

            $sql=$this->db->query('select `t`.`url`, 
                                          `t`.`name`, 
                                          `t`.`content`, 
                                          `t`.`meta_title`, 
                                          `t`.`meta_keywords`, 
                                          `t`.`meta_description`, 
                                          `t`.`timestamp`,
                                          `t`.`sub_page_type`,
                                          `t`.`cat_id`,
                                          `t`.`link_to_page`,
                                          `t`.`indexes_box`,
            		                      `t`.`contact_id`, 
                                          
                                          `m`.`sub_page_type` as `page_type`, 
                                          `m`.`url` as `parent_url`,
                                          `m`.`name` as `parent_name`,
                                          `m`.`link_to_page` as `m_link_to_page`,
                                          
                                          `st`.`url` as `s_url`,
                                          `st`.`name` as `s_name`,
                                          `st`.`content` as `s_content`, 
                                          `st`.`meta_title` as `s_meta_title`, 
                                          `st`.`meta_keywords` as `s_meta_keywords`, 
                                          `st`.`meta_description` as `s_meta_description`,
                                          `st`.`timestamp` as `s_timestamp`,
                                          `st`.`sub_page_type` as `s_sub_page_type`,
                                          `st`.`cat_id`as `s_cat_id`,
                                          `st`.`link_to_page` as `s_link_to_page`
            		
                                   from `'.$this->page_name.'` as `t` 
                                   left join `'.$this->page_name.'_menu` as `m` on `m`.`id`=`t`.`cat_id`
                                   left join `'.$this->page_name.'` as `st` on `st`.`id`=`t`.`parent_id`
                                   where `t`.`lang`="'.$this->site_lang.'" 
                                   and `t`.`is_home`="no"
                                   and `m`.`lang`="'.$this->site_lang.'" 
                                   and `m`.`is_home`="no"
                                   and `m`.`url`="'.$cat_url.'"
                                   '.$where_url.'
                                   order by `t`.`sort_id` desc
                                   limit 1;'); 
                                   */
        
        
        $sql=$this->db->query('select `t`.`url`,
        			  				`t`.`name`,
        							`t`.`content`,
        							`t`.`meta_title`,
        							`t`.`meta_keywords`,
        							`t`.`meta_description`,
        							`t`.`timestamp`,
        							`t`.`sub_page_type`,
        							`t`.`cat_id`,
        							`t`.`link_to_page`,
        							`t`.`indexes_box`,
        							`t`.`contact_id`,
	        
    					    		`m`.`sub_page_type` as `page_type`,
        							`m`.`url` as `parent_url`,
        							`m`.`name` as `parent_name`,
        							`m`.`link_to_page` as `m_link_to_page`,
        
						       		`st`.`url` as `s_url`,
        							`st`.`name` as `s_name`,
        							`st`.`content` as `s_content`,
        							`st`.`meta_title` as `s_meta_title`,
        							`st`.`meta_keywords` as `s_meta_keywords`,
        							`st`.`meta_description` as `s_meta_description`,
        							`st`.`timestamp` as `s_timestamp`,
        							`st`.`sub_page_type` as `s_sub_page_type`,
        							`st`.`cat_id`as `s_cat_id`,
        							`st`.`link_to_page` as `s_link_to_page`
        
        							from `'.$this->page_name.'` as `t`
                                   	left join `'.$this->page_name.'_menu` as `m` on `m`.`id`=`t`.`cat_id`
                                   	left join `'.$this->page_name.'` as `st` on `st`.`id`=`t`.`parent_id`
        							where `t`.`id`="'.$page_id.'"
        							limit 1;');
        

                if($sql->num_rows()>0){
                    $res=$sql->row();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res);
                        /*********************************/

                    $sidebar_left['menu']=self::sub_menu($res->cat_id,$page_url);
                    
                    $res->header=$res->name;
                    $sidebar_right['indexes_box']=$res->indexes_box;
                    
                    if(!empty($s_page_url) and $s_page_url==$res->url)
                    {
                        $res=$this->global_model->date_format($res,'','s_timestamp','s_timestamp');
                        $res->url=$res->s_url;
                        $res->name=$res->s_name;
                        $res->timestamp=$res->s_timestamp;  
                        
                        if($res->s_sub_page_type=='brokerage')
                        {
                            $res->sub_page_type=$res->s_sub_page_type;
                        }
                    }

                    $content['data']=$res;
                    
                        if(!empty($res->link_to_page)){
                            header('Location: '.$res->link_to_page,null,301);
                            die();
                        }
                        if(!empty($res->s_link_to_page)){
                            header('Location: '.$res->s_link_to_page,null,301);
                            die();
                        }
                        if(!empty($res->m_link_to_page)){
                            header('Location: '.$res->m_link_to_page,null,301);
                            die();
                        }
                        if (empty($page_url)) {
                        	
                        	$sql2=$this->db->query('select `t`.`url`
                   							       from `'.$this->page_name.'` as `t`
                                   	               left join `'.$this->page_name.'_menu` as `m` on `m`.`id`=`t`.`cat_id`
                                                   where `m`.`url`="'.$cat_url.'" 
												   and (`t`.parent_id is null or `t`.parent_id=0)
												   order by `t`.sort_id desc
        							               limit 1;');                                                                  			
                        	if($sql2->num_rows()>0) {
                        	   $res2=$sql2->row();

                        	   $s = $this->phpself.$this->page_url.'/'.$cat_url.'/'.$res2->url.$this->urlsufix; 	
                        	   header('Location: '.$s,null,301);
                        	   die();
                        	}	   
                        }
                        
                        
                        $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                        $this->data['keywords']=$res->meta_keywords;
                        $this->data['description']=$res->meta_description;
                        
                        switch($res->page_type){
                            case 'dcm':
                                switch($res->sub_page_type){
                                    case 'dcm_team':
                                        $this->data['body_css_class']='inner sub-inner dcm';
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        $content['data']=$this->front_pages_analytics_team_model->view($content['data'],'dcm');
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_dcm',$sidebar_right,true);
                                        $this->data['body_content_sub']=$this->load->view('body_content_sub','',true);
                                    break;
                                    default:
                                        $this->data['body_css_class']='inner sub-inner dcm';
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_dcm',$sidebar_right,true);
                                        $this->data['body_content_sub']=$this->load->view('body_content_sub','',true);
                                }
                            break;
                            case 'analytics':
                                switch($res->sub_page_type){
                                    case 'analytics':
                                        $this->data['body_css_class']='inner sub-inner analitika';
                                        $tmp['img']='body-illustration-2.jpg';
                                        $this->data['illustration']=$this->load->view('body_illustration',$tmp,true);
                                        
                                       // $sub_analytics['news']=$this->front_pages_analytics_news_model->last();
                                        $sub_analytics['debt_market']=self::one_page('debt_market',true);
                                        $sub_analytics['share_market']=self::one_page('share_market',true);
                                        $this->data['body_content_sub']=$this->load->view('body_content_sub_analytics',$sub_analytics,true);

                                        $content['data']->no_head_suburl=true;
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'analytical_news':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        
                                        $content['data']=$this->front_pages_analytics_news_model->view($content['data']);
                                        
                                        $content['data']->no_head_suburl=true;

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'analytical_team':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        
                                        $content['data']=$this->front_pages_analytics_team_model->view($content['data'],'аналитика');
                                        
                                        $content['data']->no_head_suburl=true;
                                        
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                
                                    case 'share_market':
                                        $this->data['body_css_class']          = 'inner analitics_sharemarket no_illustration';
                                        $content['data']->content_editor_class = true;
                                        
                                        $this->data['share_market_script']=$this->load->view('body_share_market_script','',true);
                                        
                                        /*  daily_comments  */
                                        $sub_share_market['daily_comments'] = '';
                                        $tmp                                  = $this->maindb_model->select_table('pages','sub_page_type = "share_market_daily_comments" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
                                        if(!empty($tmp )){
                                        	$sub_share_market['daily_comments']   = $this->front_pages_analytics_reviews_model->last($tmp->id);
                                        	$sub_share_market['daily_comments']   = $this->global_model->date_format($sub_share_market['daily_comments'],'d #_n_# Y','date','timestamp');
                                        }
                                        unset($tmp);                                                                                
                                        
                                        
                                        /*  reviews_issuers  */
                                        $sub_share_market['reviews_issuers'] = '';
                                        $tmp                                  = $this->maindb_model->select_table('pages','sub_page_type = "share_market_reviews_issuers" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
                                        if(!empty($tmp )){
                                        	$sub_share_market['reviews_issuers']  = $this->front_pages_analytics_reviews_model->last($tmp->id);
                                        	$sub_share_market['reviews_issuers']  = $this->global_model->date_format($sub_share_market['reviews_issuers'],'d #_n_# Y','date','timestamp');
                                        }
                                        unset($tmp);                                                                               
                                        
                                        
                                        /*  trading_ideas  */
                                        $sub_share_market['trading_ideas'] = '';
                                        $tmp                                  = $this->maindb_model->select_table('pages','sub_page_type = "trade_ideas" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
                                        if(!empty($tmp )){
                                        	$sub_share_market['trading_ideas']    = $this->front_pages_analytics_reviews_model->last($tmp->id,1);
                                        	$sub_share_market['trading_ideas']    = $this->global_model->date_format($sub_share_market['trading_ideas'],'d #_n_# Y','date','timestamp');
                                        }
                                        unset($tmp);
										

                                        /* month_reviews  */
                                        $sub_share_market['month_reviews'] = '';
                                        $tmp                                  = $this->maindb_model->select_table('pages','sub_page_type = "monthly_reviews_debt_market" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
                                        if(!empty($tmp )){
                                        	$sub_share_market['month_reviews']    = $this->front_pages_analytics_reviews_model->last($tmp->id,3);
                                        	$sub_share_market['month_reviews']    = $this->global_model->date_format($sub_share_market['month_reviews'],'d #_n_# Y','date','timestamp');
                                        }
                                        unset($tmp);                                        

                                        /* special_comments  */
                                        $sub_share_market['special_comments'] = '';
                                        $tmp                                  = $this->maindb_model->select_table('pages','sub_page_type = "share_market_special_comments" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
                                        if(!empty($tmp )){
                                        	$sub_share_market['special_comments'] = $this->front_pages_analytics_reviews_model->last($tmp->id,3);
                                        	$sub_share_market['special_comments'] = $this->global_model->date_format($sub_share_market['special_comments'],'d #_n_# Y','date','timestamp');
                                        }
                                        unset($tmp);
                                        
                                      //  $sub_share_market['portfolio']        = $this->front_pages_share_market_model_portfolio_model->one('рынок акций');
									    $sub_share_market['portfolio'] = '';
                                        
                                        $content['data']->sub_content=$this->load->view('body_content_sub_share_market',$sub_share_market,true);
                                    break;
                                    case 'share_market_model_portfolio':
                                        $this->data['body_css_class']          = 'inner model-portfolio';
                                        $content['data']->content_editor_class = true;
                                        
                                        $this->data['share_market_script']     = $this->load->view('body_share_market_script','',true);
                                        
                                        $content['data']                       = $this->front_pages_share_market_model_portfolio_model->view($content['data'],'рынок акций');
                                    break;                                    
                                    case 'evroobligatsii':
                                        $this->data['body_css_class']          = 'inner model-portfolio';
                                        $content['data']->content_editor_class = true;
                                        
                                        $this->data['share_market_script']     = $this->load->view('body_share_market_script','',true);
                                        
                                        $content['data']                       = $this->front_pages_share_market_model_portfolio_model->view($content['data'],'еврооблигации');
                                    break;                                    
                                    case 'obligatsii_rossiyiskiyi_runok':
                                        $this->data['body_css_class']          = 'inner model-portfolio';
                                        $content['data']->content_editor_class = true;
                                        
                                        $this->data['share_market_script']     = $this->load->view('body_share_market_script','',true);
                                        
                                        $content['data']                       = $this->front_pages_share_market_model_portfolio_model->view($content['data'],'облигации рынок рф');
                                    break;
                                    case 'share_market_daily_comments':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                                                             
                                    case 'debt_market':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_debt_market_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                    
                                    case 'treaties_market_daily_reviews':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                     
                                    case 'stock_market_advice':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration no_sidebar_right';
                                        $content['data']->content_editor_class=false;
                                        
										$this->data['trade_recommendations_script']=false;
                                       // $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);
                                        
                                        $content['data']->sub_content=$this->front_pages_recommendations_model->view();
                                        
                                        //$this->data['body_content_sub_in_section_content']=$this->front_pages_recommendations_model->view();										
                                         //add5.04
										 
                                        //$sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        //$this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                     
                                    case 'stock_market_strategy':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                    
                                    case 'debt_market_trading_ideas':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                     
                                    case 'debt_market_specific_comments':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;                                    
                                    case 'monthly_reviews_debt_market':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'share_market_reviews_issuers':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        //$content['data']=$this->front_pages_share_market_reviews_issuers_model->view($content['data']);
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'share_market_special_comments':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        //$content['data']=$this->front_pages_share_market_special_comments_model->view($content['data']);
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'share_market_trading_ideas':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        $content['data']=$this->front_pages_share_market_trading_ideas_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'issuers_debt_market':
                                        $this->data['body_css_class']='inner emitenti-dolgovogo-rinka';
                                        $content['data']->content_editor_class=true;
                                        
                                        $this->data['issuers_debt_market_script']=true;
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);
                                        $this->data['body_content_sub_in_section_content']=$this->front_pages_issuers_debt_market_model->view();
                                        
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'trade_ideas': 
                                        $this->data['body_css_class']='inner emitenti-dolgovogo-rinka no_sidebar_right';
                                        $content['data']->content_editor_class=false;
                                        
                                        $this->data['trade_ideas_script']=false;
                                        //$content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);
                                        
                                        $content['data']->sub_content=$this->front_pages_trade_ideas_model->view();
                                        
                                        //$this->data['body_content_sub_in_section_content']=$this->front_pages_trade_ideas_model->view();
										

                                       // $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                       // $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'planned_placements': 
                                        $this->data['body_css_class']='inner emitenti-dolgovogo-rinka no_sidebar_right';
                                        $content['data']->content_editor_class=false;
                                        
                                        $this->data['trade_ideas_script']=false;
                                        
                                        $content['data']->sub_content=$this->front_pages_planned_placements_model->view();
                                    break;
                                    case 'analytics_calendar_statistics':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration calendar_statistics';
                                        $content['data']->content_editor_class=true;
                                        
                                        
                                        $content['data']=$this->front_pages_analytics_calendar_statistics_model->view($content['data']);
                                        
                                        $this->data['share_market_script']=$this->load->view('body_share_market_script','',true);
                                    break;
                                    case 'debt_market_surveys_on_emitter':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        
                                        //$content['data']=$this->front_pages_debt_market_surveys_on_emitter_model->view($content['data']);
                                        $content['data']=$this->front_pages_analytics_reviews_model->view($content['data']);

                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    break;
                                    case 'questions':
                                        $this->front_pages_questions_model->com_add();
                                        
                                        $this->data['body_css_class']='inner konferencii questions';
                                        $sidebar_right['data']->held=$this->front_pages_questions_model->get('held');
                                        $sidebar_right['data']->upcoming=$this->front_pages_questions_model->get('upcoming');
                                        $sidebar_right['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_conferencies',$sidebar_right,true);
                                        
                                        $content['data']=$this->front_pages_questions_model->view($content['data']);
                                    break;                                     
                                    case 'share_market_technical_research':
                                    	if (sizeOf($this->uri->segments)>5) {
                                          $this->data['body_css_class']='inner sub-inner analitika no_illustration no_sidebar_right';
                                          $this->data['content_editor_class']=false;
                                        
                                          $content['data']->content=$this->front_pages_technical_research_model->view();
                                        
                                    	} else {
                                    		$this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                    		$this->data['content_editor_class']=true;
                                    		
                                    		$content['data']->content=$this->front_pages_technical_research_model->view();
                                    		
                                    		$sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                    		$this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                    	}  
                                    break;                                     
                                    case 'commodities':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration no_sidebar_right';
                                        $content['data']->content_editor_class=false;
                                        
										$this->data['trade_recommendations_script']=false;
                                        
                                        $content['data']->sub_content=$this->front_pages_commodities_model->view();
                                        
                                    break;                                     
                                    case 'quotes':
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration no_sidebar_right';
                                        $content['data']->content_editor_class=false;
                                        
										$this->data['trade_recommendations_script']=false;
                                        
                                        $content['data']->sub_content=$this->front_pages_quotes_model->view();
                                        
                                    break;                                     
                                    default:
                                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                                        $content['data']->content_editor_class=true;
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                }
                            break;
                            case 'services_in_the_stock_market':
                                switch($res->sub_page_type){
                                    case 'brokerage':
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $sidebar_right['brokerage']=true;
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                        $this->data['body_css_class'].=' no_illustration';
                                    break;
                                    case 'feedback_brokerage':
                                    	$this->data['body_css_class']='inner';
                                    	$content['data']->content_editor_class=true;
                                    	$content['data']=$this->front_pages_feedback_brokerage_model->view($content['data']);
                                    	$sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                    	$this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                    	$this->data['about_companu_feedback_script']=true;
                                    break;
                                    default:
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                        $this->data['body_css_class'].=' no_illustration';
                                }
                            break;
                            case 'about_company':
                                switch($res->sub_page_type){
                                    case 'contact':
                                        $this->data['body_css_class']='inner content-right-sidebar contact';
                                    break;
                                    case 'news':
                                        $this->data['body_css_class']='inner konferencii';
                                        $content['data']->content_editor_class=true;
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        $content['data']=$this->front_pages_news_model->view($content['data']);
                                        $content['data']->no_head_suburl=true;
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                    break;
                                    case 'press_about_us':
                                        $this->data['body_css_class']='inner konferencii';
                                        $content['data']->content_editor_class=true;
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        $content['data']=$this->front_pages_press_about_us_model->view($content['data']);
                                        $content['data']->no_head_suburl=true;
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                    break;
                                    case 'conferencies':
                                        $this->front_pages_conferencies_model->com_add();
                                        
                                        $this->data['body_css_class']='inner konferencii';
                                        $sidebar_right['data']->held=$this->front_pages_conferencies_model->get('held');
                                        $sidebar_right['data']->upcoming=$this->front_pages_conferencies_model->get('upcoming');
                                        $sidebar_right['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_conferencies',$sidebar_right,true);
                                        
                                        $content['data']=$this->front_pages_conferencies_model->view($content['data']);
                                    break; 
                                    case 'about_company':
                                        $this->data['body_css_class']='inner sub-inner o-kompanii';
                                        $tmp['img']='body-illustration-1.jpg';
                                        $this->data['illustration']=$this->load->view('body_illustration',$tmp,true);
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                        $this->front_pages_about_company_model->view();
                                    break;
                                    case 'feedback':
                                        $this->data['body_css_class']='inner';   
                                        $content['data']->content_editor_class=true;
                                        $content['data']=$this->front_pages_feedback_model->view($content['data']);
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                        $this->data['about_companu_feedback_script']=true;
                                    break;
                                    case 'about_company_team':
                                        $this->data['body_css_class'].=' no_illustration';
                                        $content['data']->page_url=$this->page_url.'/'.$cat_url.'/'.$page_url;
                                        $content['data']=$this->front_pages_analytics_team_model->view($content['data'],'о компании');
                                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                       /* $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);*/
                                    break;

                                    default:
                                    	$sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                    	$this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                        $this->data['body_css_class'].=' no_illustration';
                                }
                            break;
                        
                            default:
                                switch($res->sub_page_type){
                                    case 'feedback_brokerage':
                                    	$this->data['body_css_class']='inner';
                                    	$content['data']->content_editor_class=true;
                                    	$content['data']=$this->front_pages_feedback_brokerage_model->view($content['data']);
                                    	$sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                    	$this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                    	$this->data['about_companu_feedback_script']=true;
                                    break;
                                    default:
                            	        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right',$sidebar_right,true);
                                        $this->data['body_css_class'].=' no_illustration';
                                }        
                        }
                }
                else{
                	if($page_url=="dolgovoyi-runok"){
			            $sql=$this->db->query('select `t`.`url`, 
			                                          `t`.`name`, 
			                                          `t`.`content`, 
			                                          `t`.`meta_title`, 
			                                          `t`.`meta_keywords`, 
			                                          `t`.`meta_description`, 
			                                          `t`.`timestamp`,
			                                          `t`.`sub_page_type`,
			                                          `t`.`cat_id`,
			                                          `t`.`link_to_page`,
			                                          `t`.`indexes_box`,
			            		                      `t`.`contact_id`,  
			                                          
			                                          `m`.`sub_page_type` as `page_type`, 
			                                          `m`.`url` as `parent_url`,
			                                          `m`.`name` as `parent_name`,
			                                          `m`.`link_to_page` as `m_link_to_page`,
			                                          
			                                          `st`.`url` as `s_url`,
			                                          `st`.`name` as `s_name`,
			                                          `st`.`content` as `s_content`, 
			                                          `st`.`meta_title` as `s_meta_title`, 
			                                          `st`.`meta_keywords` as `s_meta_keywords`, 
			                                          `st`.`meta_description` as `s_meta_description`,
			                                          `st`.`timestamp` as `s_timestamp`,
			                                          `st`.`sub_page_type` as `s_sub_page_type`,
			                                          `st`.`cat_id`as `s_cat_id`,
			                                          `st`.`link_to_page` as `s_link_to_page`
			                                          
			                                   from `'.$this->page_name.'` as `t` 
			                                   left join `'.$this->page_name.'_menu` as `m`
			                                   on `m`.`id`=`t`.`cat_id`
			                                   left join `'.$this->page_name.'` as `st`
			                                   on `st`.`id`=`t`.`parent_id`
			                                   where `t`.`lang`="'.$this->site_lang.'" 
			                                   and `t`.`is_home`="no"
			                                   and `m`.`lang`="'.$this->site_lang.'" 
			                                   and `m`.`is_home`="no"
			                                   and `m`.`url`="'.$cat_url.'"
			                                   and `t`.`url`="dolgovoyi-runok"
			                                   order by `t`.`sort_id` desc
			                                   limit 1;');   
                if($sql->num_rows()>0){
                    $res=$sql->row();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->date_format($res);
                        /*********************************/

                    $sidebar_left['menu']=self::sub_menu($res->cat_id,$page_url);
                    
                    $res->header=$res->name;
                    $sidebar_right['indexes_box']=$res->indexes_box;
                    
                    if(!empty($s_page_url) and $s_page_url==$res->url)
                    {
                        $res=$this->global_model->date_format($res,'','s_timestamp','s_timestamp');
                        $res->url=$res->s_url;
                        $res->name=$res->s_name;
                        $res->timestamp=$res->s_timestamp;  
                        
                        if($res->s_sub_page_type=='brokerage')
                        {
                            $res->sub_page_type=$res->s_sub_page_type;
                        }
                    }

                    $content['data']=$res;
                    
                        if(!empty($res->link_to_page)){
                            header('Location: '.$res->link_to_page);
                            die();
                        }
                        if(!empty($res->s_link_to_page)){
                            header('Location: '.$res->s_link_to_page);
                            die();
                        }
                        if(!empty($res->m_link_to_page)){
                            header('Location: '.$res->m_link_to_page);
                            die();
                        }
                        
                        
                        $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                        $this->data['keywords']=$res->meta_keywords;
                        $this->data['description']=$res->meta_description;
                        $this->data['body_css_class']='inner sub-inner analitika no_illustration';
                        
                        $content['data']->content_editor_class=true;                                        
                        $content['data']=$this->front_pages_debt_market_model->view($content['data']);
                        
                        $sidebar_right['contacts']=$this->front_contacts_model->get_contacts($res->contact_id);
                        $this->data['body_sidebar_right']=$this->load->view('body_sidebar_right_analytics',$sidebar_right,true);                          
                }			                         	
             		
                	}
                }
                
        $this->data['body_sidebar_left']=$this->load->view('body_sidebar_left',$sidebar_left,true);
        
        $content['right_adv_caption']= $this->front_right_adv_model->get_caption();
        $content['right_adv_menu']   = $this->front_right_adv_model->get_menu();
        
                
        return $this->load->view('view_'.$this->page_name,$content,true);
    }
    
    
##########################################################################
    
    private function first_cat()
    {
       $ret='';
        
        $sql=$this->db->query('select `m`.`url`
                               from `'.$this->page_name.'_menu` as `m` 
                               where `m`.`lang`="'.$this->site_lang.'" 
                               and `m`.`is_home`="no"
                               and `m`.`is_hide`="no" 
                               order by `m`.`sort_id` desc
                               limit 1;');

        if($sql->num_rows()>0){
            $res=$sql->row();
            $ret='/'.$res->url;
        }
 
      return $ret;
    }
    
    private function one_page($sub_page_type,$stripcontent=false)
    {
       $ret='';
        
        $sql=$this->db->query('select `t`.`url`, `t`.`name`, `t`.`content`
                               from `'.$this->page_name.'` as `t` 
                               where `t`.`lang`="'.$this->site_lang.'" 
                               and `t`.`sub_page_type`="'.$sub_page_type.'"
                               order by `t`.`sort_id` desc
                               limit 1;');

        if($sql->num_rows()>0){
            $res=$sql->row();
                /*********************************/
                 $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                /*********************************/
                 
            if($stripcontent==true){
                $res->content=character_limiter(strip_tags($res->content));
            }
            $ret=$res;
        }
 
      return $ret; 
    }
    
    private function get_page_type($page_url)
    {
       $ret='';
        
        $sql=$this->db->query('select `t`.`sub_page_type`
                               from `'.$this->page_name.'` as `t` 
                               where `t`.`lang`="'.$this->site_lang.'" 
                               and `t`.`url`="'.$page_url.'"
                               order by `t`.`sort_id` desc
                               limit 1;');

        if($sql->num_rows()>0){
            $res=$sql->row();
            $ret=$res->sub_page_type;
        }
 
      return $ret; 
    }
    
    private function sub_menu($cat_id,$page_url)
    {
       $ret='';
        
        $sql=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`
                               from `'.$this->page_name.'` 
                               where `lang`="'.$this->site_lang.'" 
                               and `is_home`="no"
                               and `is_hide`="no" 
                               and `cat_id`="'.$cat_id.'"
                               and `parent_id`="0"
                               and company="UFS"
                               order by `sort_id` desc;');

        if($sql->num_rows()>0){
            $res=$sql->result();

            for($i=0;$i<count($res);$i++){
               $res[$i]->select=($page_url==$res[$i]->url)?' class="selected"':'';
               
                $sql_s=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`
                                         from `'.$this->page_name.'` 
                                         where `lang`="'.$this->site_lang.'" 
                                         and `is_home`="no"
                                         and `is_hide`="no" 
                                         and `cat_id`="'.$cat_id.'"
                                         and `parent_id`="'.$res[$i]->id.'"
                                         and company="UFS"  
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
