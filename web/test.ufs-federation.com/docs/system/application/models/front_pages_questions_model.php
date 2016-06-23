<?php
class front_pages_questions_model extends Model{

	function front_pages_questions_model()
	{
		parent::Model();
	}
    
    function last()
    {
      $ret=array();
        
            $sql=$this->db->query('select `url`,`name`,`timestamp` 
                                   from `questions` 
                                   where `lang`="'.$this->site_lang.'" 
                                   and `timestamp` < now()
                                   order by `timestamp` desc 
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'questions');
                         $res=$this->global_model->date_format($res,'d #_n_# Y / H:i');
                        /*********************************/
                         
                        $res->page_url=$this->global_model->pages_url('questions');

                    $ret=$res;
                }
                
      return $ret;
    }

    function get($time='held')
    {
        $ret='';
        
            if($time=='held'){
                $now='<';
                $sort='desc';
            }else{
                $now='>';
                $sort='asc';
            }
        
            $sql=$this->db->query('select `url`, `name`, `timestamp`
                                   from `questions`
                                   where `lang`="'.$this->site_lang.'"
                                   and `timestamp` '.$now.' now()
                                   order by `timestamp` '.$sort.'
                                   limit 4;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'questions');
                         $res=$this->global_model->date_format($res,'d #_n_# Y / H:i');
                        /*********************************/

                   $ret=$res;
                         
                }
        
        return $ret;
    }
    
    function view($ret)
    {
        $url=mysql_string($this->uri->segment(5));
        
            if($url=='all' or empty($url)){
                $ret->no_head_suburl=true;
                $ret->content_editor_class=true;
                
                $sql=$this->db->query('select *
                                       from `questions`
                                       where `lang`="'.$this->site_lang.'"
                                       order by `timestamp` desc;');

                    if($sql->num_rows()>0){
                        $res=$sql->result();

                            /*********************************/
                             $res=$this->global_model->adjustment_of_results($res,'questions');
                             $res=$this->global_model->date_format($res,'d #_n_# Y / H:i');
                            /*********************************/

                            $data['data_arr']=$res;
                            $ret->content=$this->load->view('view_pages_questions',$data,true);     
                    }
                
            }else{
        
                $sql=$this->db->query('select `t`.`id`, `t`.`name`, `c`.`id` as `com_id`, `c`.`status`, 
                                              `c`.`name` as `user`, `c`.`query`, `c`.`answer`, `t`.`timestamp`, `t`.`is_moderation`
                                       from `questions` as `t`
                                       left join `questions_comment` as `c`
                                       on `c`.`c_id`=`t`.`id`
                                       where `t`.`lang`="'.$this->site_lang.'"
                                       and `t`.`url`="'.$url.'"
                                       order by `t`.`timestamp` desc, `c`.`timestamp` desc;');

                    if($sql->num_rows()>0){
                        $res=$sql->result();

                            /*********************************/
                             $res=$this->global_model->adjustment_of_results($res,'questions');
                             $res=$this->global_model->adjustment_of_results($res,'questions_comment');
                             $res=$this->global_model->date_format($res,'d #_n_# Y');
                            /*********************************/

                            $ret->header=$res[0]->name;
                            $data['time']=$res[0]->timestamp;
                            $data['c_id']=$res[0]->id;
                            $data['data']=$res;
                            $ret->content=$this->load->view('view_pages_questions',$data,true);     
                    }
                    
            }
        
        return $ret;
    }
    
    function com_add()
    {
        if(isset($_REQUEST['form_submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
            $ret['lang']=$this->site_lang;
            
            $sql=$this->db->query('select `id` from `questions` 
                                   where `id`="'.$ret['c_id'].'" 
                                   and `lang`="'.$this->site_lang.'" 
                                   and `is_moderation`="yes" 
                                   limit 1;');
                if($sql->num_rows()>0){	
                    $ret['status']='hide';
                }else{
                    $ret['status']='show';
                }
            
              $this->db->insert('questions_comment',$ret);
              
              // send question to email
              $body = 'Здравствуйте, поступил новый вопрос в раздел &laquo;<a href="http://ru.ufs-federation.com/backoffice/questions" target="_blank">Вопросы аналитикам</a>&raquo;<br>'.$ret['name'].' ('.$ret['email'].')<br><br>'.$ret['query'];
              $emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'),array('email'=>'vpa@ufs-finance.com'),array('email'=>'cey@ufs-financial.ch')));
              $this->global_model->send_emails($emails,'Вопросы аналитикам — новый вопрос',$body,null,null);
              
              $_SESSION['conf_query']=$ret['query'];

            return header('Location: '.$_SERVER['REQUEST_URI']);
        }
        
        if(isset($_SESSION['conf_query'])){
            $popup='';
            $popup['query']=$_SESSION['conf_query'];
            $this->data['body_popup']=$this->load->view('body_popup',$popup,true);
            
            unset($_SESSION['conf_query']);
        }
    }

}
?>
