<?php
class front_pages_inquirer_model extends Model{

  function front_pages_inquirer_model() {
    
    parent::Model();
    
  }

  function view($ret) {

    $data = array();
    $lang = $this->site_lang;
    
    $submit = isset($_REQUEST['submit'])?true:false;
    if ($submit) {
      
      $success = false;
      
      $email = isset($_REQUEST['email'])?$_REQUEST['email']:false;
      if ($email) {

        $q1 = isset($_REQUEST['q1'])?$_REQUEST['q1']:false;
        $q2 = isset($_REQUEST['q2'])?$_REQUEST['q2']:false;
        $q3 = isset($_REQUEST['q3'])?$_REQUEST['q3']:false;
        $q4 = isset($_REQUEST['q4'])?$_REQUEST['q4']:false;
        $q5 = isset($_REQUEST['q5'])?$_REQUEST['q5']:false;
        
        if ($q1 && $q2 && $q3 && $q4 && $q5) {
        
          $subject = sprintf('Ответы на вопросы конкурса от: %s',$email);
          $body = sprintf('Ответы на вопросы от: %s<br><br>',$email)."\n";
          
          $body.= sprintf('<span>Вопрос #1: <b>%s</b></span><br>',$q1)."\n";
          $body.= sprintf('<span>Вопрос #2: <b>%s</b></span><br>',$q2)."\n";
          $body.= sprintf('<span>Вопрос #3: <b>%s</b></span><br>',$q3)."\n";
          $body.= sprintf('<span>Вопрос #4: <b>%s</b></span><br>',$q4)."\n";
          $body.= sprintf('<span>Вопрос #5: <b>%s</b></span><br>',$q5)."\n";

          $emails = array();
          $emails = array(/*array('name'=>'Конкурс','email'=>'dbd@ufs-federation.com'),
                            array('name'=>'Конкурс','email'=>'events@ufs-federation.com'),*/
                          array('name'=>'Конкурс','email'=>'dd@ufs-federation.com'));

          $emails = $this->global_model->data_to_class($emails);

          $r = $this->global_model->send_emails($emails,$subject,$body);
          $success = $r;
        } else {
          $submit = false;
        }
      } else {
        $submit = false;
      }
      
      $data['success'] = $success;
    }
    
    $data['submit'] = $submit;
    
    $ret->content = $this->load->view('body_content_sub_inquirer',$data,true);
    
    return $ret;
  }

}
?>