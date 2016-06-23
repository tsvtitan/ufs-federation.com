<?php
class front_pages_crossword_model extends Model{

  function front_pages_crossword_model() {
    
    parent::Model();
    
  }

  function view($ret) {

    $data = array();
    $submit = isset($_REQUEST['submit'])?true:false;
    if ($submit) {
      
      $success = false;
      
      $email = isset($_REQUEST['email'])?$_REQUEST['email']:false;
      if ($email) {

        //$q1 = isset($_REQUEST['email'])?$_REQUEST['email']:false;
        $name = isset($_REQUEST['name'])?$_REQUEST['name']:false;
        $phone = isset($_REQUEST['phone'])?$_REQUEST['phone']:false;
        $start_timestamp = isset($_REQUEST['start-time'])?$_REQUEST['start-time']:false;
        $data_control = isset($_REQUEST['data-control'])?$_REQUEST['data-control']:false;
        
        //$q4 = isset($_REQUEST['q4'])?$_REQUEST['q4']:false;
        //$q5 = isset($_REQUEST['q5'])?$_REQUEST['q5']:false;
        
        if ($start_timestamp) {
          $dt_start_time = new DateTime(date("Y-m-d H:i:s", $start_timestamp));
          $dt_end_time = new DateTime(date("Y-m-d H:i:s", time()));
          $solve_interval = date_diff($dt_start_time, $dt_end_time);
          
          $start_time = date("Y-m-d H:i:s", $start_timestamp);
          $end_time = date("Y-m-d H:i:s", time());
          /* $solve_interval_string =
                $solve_interval['h'] . ' часов ' . 
                $solve_interval['i'] . ' минут ' . 
                $solve_interval['s'] . ' секунд'; */
            
          
          //[y] => 0 [m] => 0 [d] => 0 [h] => 0 [i] => 12 [s] => 33 [invert] => 0 [days] => 0
            
            
          
          //print_r($solve_interval);
          
          //die();
          
          $subject = sprintf('Вариант решения кроссворда от: %s',$email);
          $body = sprintf('Вариант решения кроссворда от: %s<br><br>',$email)."\n";
          
          $body.= sprintf('<span>Имя: <b>%s</b></span><br>',$name)."\n";
          $body.= sprintf('<span>Телефон: <b>%s</b></span><br>',$phone)."\n";
          
          $body.= sprintf('<span>Старт: <b>%s</b></span><br>',$start_time)."\n";
          $body.= sprintf('<span>Отправлен: <b>%s</b></span><br>',$end_time)."\n";
          $body.= sprintf('<span>Контрольное поле: <b>%s</b></span><br>',$data_control)."\n";
          //$body.= sprintf('<span>Затрачено времени: <b>%s</b></span><br>',$solve_interval_string)."\n";
          
                  
          /* $body.= sprintf('<span>Вопрос #3: <b>%s</b></span><br>',$q3)."\n";
          $body.= sprintf('<span>Вопрос #4: <b>%s</b></span><br>',$q4)."\n";
          $body.= sprintf('<span>Вопрос #5: <b>%s</b></span><br>',$q5)."\n"; */

          $emails = array();
          $emails = array(array('name'=>'Кроссворд','email'=>'mov@ufs-federation.com'),
                            array('name'=>'Кроссворд','email'=>'mas@ufs-federation.com'),
                          array('name'=>'Кроссворд','email'=>'cey@ufs-financial.ch'));

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
    
    $ret->content = $this->load->view('body_content_sub_crossword',$data,true);
    
    return $ret;
  }

}
?>