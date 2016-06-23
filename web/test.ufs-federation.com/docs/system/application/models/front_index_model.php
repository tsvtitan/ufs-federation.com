<?php
class front_index_model extends Model{

	function front_index_model()
	{
		parent::Model();
        
        $this->load->model('front_pages_news_model');
        $this->load->model('front_pages_press_about_us_model');
        $this->load->model('front_pages_conferencies_model');
        $this->load->model('front_contacts_model');
        $this->load->model('front_right_adv_model');
        $this->load->model('front_pages_analytics_reviews_model');
        
        $this->load->model('maindb_model');
	}
    
    function view()
    {  
        $this->data['body_css_class'] = 'index';
        $this->data['main_page']      = true;
        $this->global_model->ssl_check(false);

        $promo['data']   = '';   
        $content['data'] = '';

            $sql=$this->db->query('select * from `pages` 
                                   where `is_home`="yes" 
                                   and `lang`="'.$this->site_lang.'" 
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res = $sql->row();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'pages');
                        /*********************************/

                    $promo['data']              = $res;

                    $this->data['title']        = (empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                    $this->data['keywords']     = $res->meta_keywords;
                    $this->data['description']  = $res->meta_description;
                    
                    $this->data['slider_speed'] = $res->promo_slider_speed;
                    
                    $this->data['promo']        = $this->load->view('body_promo',$promo,true);
                }
                
          $content['news']               = $this->front_pages_news_model->last(7);
          $content['news_url']					 = $this->phpself.'pages/'.$this->global_model->pages_url('news').$this->urlsufix;
          
          $content['press_about_us']     = $this->front_pages_press_about_us_model->last(6);
          $content['press_about_us_url'] = $this->phpself.'pages/'.$this->global_model->pages_url('press_about_us').$this->urlsufix;
          
          $content['conferencies']       = $this->front_pages_conferencies_model->last();
          $content['contacts']           = $this->front_contacts_model->get_contacts($res->contact_id);
          $content['right_avd_caption']  = $this->front_right_adv_model->get_caption();
          $content['right_adv_menu']     = $this->front_right_adv_model->get_menu();

          if ($this->site_lang=='ru') {
          	$content['subscription_data']=array(array('name'=>'subscribe_section_1','value'=>'on'),
          			array('name'=>'subscribe_section_3','value'=>'on'),
          			array('name'=>'subscribe_section_10','value'=>'on'),
          			array('name'=>'subscribe_section_11','value'=>'on'),
          			array('name'=>'subscribe_section_12','value'=>'on'),
          			array('name'=>'subscribe_section_2','value'=>'on'),
          			array('name'=>'subscribe_section_4','value'=>'on'),
          			array('name'=>'subscribe_section_5','value'=>'on'),
          			array('name'=>'subscribe_section_14','value'=>'on'),
          			array('name'=>'subscribe_section_13','value'=>'on'),
                                array('name'=>'subscribe_section_35','value'=>'on'),
          			array('name'=>'subscribe_section_36','value'=>'on'),
          			array('name'=>'subscribe_section_39','value'=>'on'),
          			array('name'=>'subscribe_section_40','value'=>'on'),
          			array('name'=>'subscribe_section_44','value'=>'on'),
                                array('name'=>'subscribe_section_47','value'=>'on'),
          			array('name'=>'subscribe_section_43','value'=>'on'));
                                       } elseif($this->site_lang=='en') {
          	$content['subscription_data']=array(array('name'=>'subscribe_section_9','value'=>'on'),
          			array('name'=>'subscribe_section_15','value'=>'on'),
          			array('name'=>'subscribe_section_16','value'=>'on'),
          			array('name'=>'subscribe_section_17','value'=>'on'),
          			array('name'=>'subscribe_section_18','value'=>'on'),
          			array('name'=>'subscribe_section_19','value'=>'on'),
          			array('name'=>'subscribe_section_20','value'=>'on'),
          			array('name'=>'subscribe_section_21','value'=>'on'),
          			array('name'=>'subscribe_section_22','value'=>'on'),
          			array('name'=>'subscribe_section_24','value'=>'on'),
                                array('name'=>'subscribe_section_37','value'=>'on'),
          			array('name'=>'subscribe_section_38','value'=>'on'),
          			array('name'=>'subscribe_section_41','value'=>'on'),
                                array('name'=>'subscribe_section_42','value'=>'on'),
          			array('name'=>'subscribe_section_45','value'=>'on'));
                                        } elseif($this->site_lang=='de') {
          	$content['subscription_data']=array(array('name'=>'subscribe_section_25','value'=>'on'),
          			array('name'=>'subscribe_section_26','value'=>'on'),
          			array('name'=>'subscribe_section_27','value'=>'on'),
          			array('name'=>'subscribe_section_28','value'=>'on'),
          			array('name'=>'subscribe_section_29','value'=>'on'),
          			array('name'=>'subscribe_section_30','value'=>'on'),
          			array('name'=>'subscribe_section_31','value'=>'on'),
          			array('name'=>'subscribe_section_32','value'=>'on'),
          			array('name'=>'subscribe_section_33','value'=>'on'),
          			array('name'=>'subscribe_section_34','value'=>'on'));
          }
          
          $content['lase_debt_emmiter']  = '';
          $spes_comments                 = $this->maindb_model->select_table('pages','sub_page_type = "debt_market_specific_comments" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
          if(!empty($spes_comments )){
          	$lase_debt_emmiter             = $this->front_pages_analytics_reviews_model->last($spes_comments->id,3); 
          	$content['lase_debt_emmiter']  = reset($lase_debt_emmiter);
          }          
          
          $content['lase_dmarket_daily']  = '';
          $spes_comments_debt             = $this->maindb_model->select_table('pages','sub_page_type = "share_market_special_comments" and lang="'.$this->site_lang.'" ','timestamp',1,true,'itembg',array('name'),'','',true);
          if(!empty($spes_comments_debt )){
          	$lase_dmarket_daily            = $this->front_pages_analytics_reviews_model->last($spes_comments_debt->id,3); 
          	$content['lase_dmarket_daily'] = reset($lase_dmarket_daily);
          }

        return $this->load->view('view_'.$this->page_name,$content,true);
    }

}
?>