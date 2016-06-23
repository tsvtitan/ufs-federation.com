<?php
class front_pages_feedback_brokerage_model extends Model{

	function front_pages_feedback_brokerage_model()
	{
		parent::Model();
	}
    
    function view($ret)
    {  
       $data['error']=self::add();
       
       $data['date']='';

       $ret->sub_content=$this->load->view('view_pages_feedback_brokerage',$data,true);
        
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

//                if($_REQUEST['kapcha_code']!=$_SESSION['captcha_keystring']){
//                   $ret['error'][]=dictionary('Проверочный код');
//                }
            
            if(isset($ret['error'])){
                return $ret;
            }else{
                $ret['lang']=$this->site_lang;
                $body = sprintf('Посетитель: %s '.
                		'Email: %s '.
                		'Сообщение: %s',
                		$ret['name'],$ret['email'],$ret['query']);
            $emails = $this->global_model->data_to_class(array(array('email'=>'dbd@ufs-federation.com')));
            $r = $this->global_model->send_emails($emails,'Внимание! Вопрос с сайта.',$body,null,'Web-site');
                
                
            
                
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
