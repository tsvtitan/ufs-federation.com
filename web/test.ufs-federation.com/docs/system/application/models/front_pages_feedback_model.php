<?php
class front_pages_feedback_model extends Model{

	function front_pages_feedback_model()
	{
		parent::Model();
	}
    
    function view($ret)
    {  
       $data['error']=self::add();
       
       $data['date']='';

            $sql=$this->db->query('select `id`, `name`, `query`, `answer`, `timestamp`
                                   from `feedback`
                                   where `lang`="'.$this->site_lang.'"
                                   order by `timestamp` desc;');

                if($sql->num_rows()>0){
                    $res=$sql->result();

                        /*********************************/
                         $res=$this->global_model->adjustment_of_results($res,'feedback');
                         $res=$this->global_model->date_format($res,'d #_n_# Y');
                        /*********************************/

                        $data['data']=$res;

                }
                    
          $ret->sub_content=$this->load->view('view_pages_feedback',$data,true);
        
      return $ret;
    }
    
    private function add()
    {
        if(isset($_REQUEST['form_submit'])){
            $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
              
                if(empty($ret['name'])){
                    $ret['error'][]=dictionary('Ваше имя');
                }

                if(valid_email($ret['email'])==FALSE){
                    $ret['error'][]=dictionary('Ваша электронная почта');
                }

                if(empty($ret['query'])){
                    $ret['error'][]=dictionary('Ваш вопрос');
                }

                if($_REQUEST['kapcha_code']!=$_SESSION['captcha_keystring']){
                    $ret['error'][]=dictionary('Проверочный код');
                }
            
            if(isset($ret['error'])){
                return $ret;
            }else{
                $ret['lang']=$this->site_lang;

                  $this->db->insert('feedback',$ret);

                  $_SESSION['conf_query']=$ret['query'];

                return header('Location: '.$_SERVER['REQUEST_URI']);   
            }
        }       
                
        if(isset($_SESSION['conf_query'])){
            $popup='';
            $popup['query']=$_SESSION['conf_query'];
            $this->data['body_popup']=$this->load->view('body_popup',$popup,true);
            
            unset($_SESSION['conf_query']);
        }
        
       return '';
    }

}
?>
