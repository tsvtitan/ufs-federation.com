<?php
class front_pages_share_market_special_comments_model extends Model{

	function front_pages_share_market_special_comments_model()
	{
		parent::Model();
	}
    
    function last($limit=3)
    {
      $ret=array();
        
            $sql=$this->db->query('select `url`,`name`,`short_content`,`timestamp` 
                                   from `share_market_special_comments` 
                                   where `lang`="'.$this->site_lang.'" 
                                   order by `timestamp` desc 
                                   limit '.$limit.';');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'share_market_special_comments');
                         $res=$this->global_model->date_format($res,'j #_n_# Y');
                        /*********************************/
                         
                    $page_url='pages/'.$this->global_model->pages_url('share_market_special_comments');
                         
                    for($i=0;$i<count($res);$i++){
                        $res[$i]->page_url=$page_url;
                    }
                         
                    $ret=$res;
                }
                
      return $ret;
    }
    
    function view($ret)
    {
        $url=mysql_string($this->uri->segment(6));
        
        if(!empty($url) and $url!='all'){
            $where='and `url`="'.$url.'"';
            $res_func='row';
            $res_data='data';
        }else{
            $where='';
            $res_func='result';
            $res_data='data_arr';
        }
            
        
            $sql=$this->db->query('select *
                                   from `share_market_special_comments` 
                                   where `lang`="'.$this->site_lang.'"
                                   '.$where.'
                                   order by `timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->$res_func();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'share_market_special_comments');
                         $res=$this->global_model->date_format($res,'d #_n_# Y','date','timestamp');
                        /*********************************/
                         
                        $data['page_url']=$ret->page_url='pages/'.$this->global_model->pages_url('share_market_special_comments');
                        
                        if($res_data=='data'){
                            $this->data['title']=(empty($res->meta_title)?'':$res->meta_title.' - ').$this->data['title'];
                            $this->data['keywords']=$res->meta_keywords;
                            $this->data['description']=$res->meta_description;
                        }
                         
                        $data[$res_data]=$res;
                        $ret->content=$this->load->view('view_pages_share_market_special_comments',$data,true);  
                }
        
        return $ret;
    }

}
?>
