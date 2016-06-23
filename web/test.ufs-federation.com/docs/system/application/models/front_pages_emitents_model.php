<?php
class front_pages_emitents_model extends Model{

  function front_pages_emitents_model() {
    
    parent::Model();
    
    $this->load->model('front_pages_subscribe_model');
  }

  function get_num($table,$type) {
    
    $ret = 1;
    $r = $this->global_model->get_query_data(sprintf('select max(num) as num from %s where type=%s',$table,$this->global_model->quote($type)));
    if ($r && sizeOf($r)>0) {
      $r = $this->global_model->data_to_class($r);
      $ret = $r[0]->num;
      $ret++;
    }
    return $ret;
  }
  
  function default_view($order_id=null,$emitent_id=false) {
    
    $ret = false;
    $items = array();
    
    $emitents = array();
    if (!$emitent_id) {
      $r = $this->global_model->get_table_data('order_emitents',array('emitent_id'),array('order_id'=>$order_id));
      if ($r) {
        $r = $this->global_model->data_to_class($r);
        foreach($r as $item) {
          $emitents[] = $item->emitent_id; 
        }
      }
    } else {
      $emitents[] = $emitent_id;
    }
    
    $r = $this->global_model->get_table_data('emitents',null,array('lang'=>$this->site_lang,'finished'=>null),array('priority'));
    if ($r) {
      $r = $this->global_model->data_to_class($r);
      foreach($r as $item) {
        $item->checked = in_array($item->emitent_id,$emitents);
        $items[] = $item;
      }
    }
    
    $data['items'] = $items;
    
    $last_update = '';
    $r = $this->global_model->get_query_data(sprintf('select max(created) as date '.
                                                       'from emitents '.
                                                      'where finished is null '.
                                                        'and lang=%s ',
                                                     $this->global_model->quote($this->site_lang)));
    if ($r) {
      $r = $this->global_model->data_to_class($r);
      $r = $this->global_model->date_format($r,'d #_n_# Y','date','date');
      $last_update = $r[0]->date;
    }
    $data['last_update'] = $last_update;
    $data['order_id'] = $order_id;
    
    return $this->load->view('body_content_sub_emitents',$data,true);  
  }
  
  function view() {

    $ret = false;
    
    $emitent_id = isset($_REQUEST['emitent_id'])?$_REQUEST['emitent_id']:false;
    $action = isset($_REQUEST['action'])?true:false;
    
    $order = isset($_REQUEST['order'])?true:false;
    if ($order || ($emitent_id && $action)) {
    
      $data = array();
      
      $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
      $agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null;
      $lang = isset($_REQUEST['lang'])?$_REQUEST['lang']:null;
      if ($lang=='') {
        $lang = $this->site_lang;
      }
    
      $submit = isset($_REQUEST['submit'])?true:false;
      $is_client = isset($_REQUEST['is_client'])?true:false;
      
      $table_orders = 'orders';
      $table_order_emitents = 'order_emitents';
      $table_emitents = 'emitents';
      $desc = $this->global_model->get_table_desc($table_orders);
      
      $new = $this->global_model->get_request($_REQUEST);
      $emitents = isset($_REQUEST['emitents'])?$_REQUEST['emitents']:false;
      
      if (!$emitents && $emitent_id) {
        $emitents[] = $emitent_id;
      }
    
      $order_id = isset($new['order_id'])?$new['order_id']:false;
      if (!$order_id) {
        if (isset($_SESSION['emitents_order_id'])) {
          $order_id = $_SESSION['emitents_order_id'];
        } else {
          $order_id = $this->global_model->get_unique_id();
          $_SESSION['emitents_order_id'] = $order_id;
        }
      }
      
      $exists = false;
      $old = array();
      if ($order_id) {
        $old = $this->global_model->get_table_record($table_orders,null,array('order_id'=>$order_id));
        $exists = is_array($old) && (sizeOf($old)>0);
      }
      
      $finished = false;
      $error = false;
      $message = null;
      $success = ($new!=false);
      
      if ($success) {
        if ($exists) {
          $success = $this->global_model->update($table_orders,$new,array('order_id'=>$order_id),array('order_id'));
          if ($success) {
            $success = $this->global_model->execute(sprintf('delete from %s where order_id=%s;',
                                                            $table_order_emitents,$this->global_model->quote($order_id)));
            if ($success) {
              $pattern = 'emitent_';
              $pattern2 = 'emitent2_';
              $l = strlen($pattern);
              $emitents = array();
              foreach ($_REQUEST as $k=>$v) {
                $s = substr($k,0,$l);
                if (strtolower($s)==$pattern) {
                  $s = substr($k,$l);
                  if (strlen($s)==32) {
                    $emitents[] = $s;
                    $newe['order_id'] = $order_id;
                    $newe['emitent_id'] = $s;
                    if ($v=='other') {
                      $v = isset($_REQUEST[$pattern2.$s])?$_REQUEST[$pattern2.$s]:'';
                    }
                    $newe['volume'] = $v;
                    $this->global_model->insert($table_order_emitents,$newe);
                  }
                }
              }
            }
          }
        }
      } else {
        if (!$exists) {
          $new['order_id'] = $order_id;
          $new['num'] = $this->get_num($table_orders,$table_emitents);
          $new['ip'] = $ip;
          $new['agent'] = $agent;
          $new['lang'] = $lang;
          $new['type'] = $table_emitents;
          $new['is_client'] = ($is_client)?1:null;
          $success = $this->global_model->insert($table_orders,$new);
          if ($success && is_array($emitents)) {
            foreach($emitents as $e) {
              $newe['order_id'] = $order_id;
              $newe['emitent_id'] = $e;
              $this->global_model->insert($table_order_emitents,$newe);
            }
          }
        } else {
          $success = $this->global_model->execute(sprintf('delete from %s where order_id=%s;',
                                                          $table_order_emitents,$this->global_model->quote($order_id)));
          if ($success && is_array($emitents)) {
            foreach($emitents as $e) {
              $newe['order_id'] = $order_id;
              $newe['emitent_id'] = $e;
              $this->global_model->insert($table_order_emitents,$newe);
            }
          }
        }
      }

      $eids = array();
      if (is_array($emitents)) {
      
        $where = array();
        foreach($emitents as $e) {
          $where[] = $this->global_model->quote($e);
        }
        if (sizeOf($where)>0) {
          $where = implode(',',$where);
          $r = $this->global_model->get_query_data(sprintf('select t1.*, t2.volume '.
                                                             'from %s t1 '.
                                                             'join %s t2 on t2.emitent_id=t1.emitent_id '.
                                                            'where t1.emitent_id in (%s) and t2.order_id=%s',
                                                           $table_emitents,$table_order_emitents,
                                                           $where,$this->global_model->quote($order_id)));
          if ($r) {
            $eids = $this->global_model->data_to_class($r);
          }
        }
      }
      $emitents = $eids; 
      
      if (!$success) {
        if (!isset($message)) {
          $message = $this->db->last_error();
        }
        $error = true;
      } else {
       
        $r = $this->global_model->get_table_record($table_orders,null,array('order_id'=>$order_id));
        if ($r) {
          foreach($r as $k=>$v) {
            $data[$k] = $v;
          }
        }
        
        if ($submit) {
          
          $emails = array();
          $emails[] = array('email'=>'tsv@ufs-financial.ch');
          //$emails[] = array('email'=>'imv@ufs-federation.com');
          //$emails[] = array('email'=>'clientservice@ufs-federation.com');
          
          $emails = $this->global_model->data_to_class($emails);
          
          $fields = array('num','created','lang','last_name','first_name','middle_name','email','phone','subscription');
          
          $subject = sprintf('%s%s заполнил заявку%s на покупку акций',
                             (isset($data['is_customer'])&&($data['is_customer']==1)?'Потенциальный клиент':'Клиент'),
                             (isset($data['first_name'])?' '.$data['first_name']:''),
                             (isset($data['num'])?' #'.$data['num']:''));
          $body = '<u>Основные данные:</u><br><br>'."\n";
          $counter = 0;
          foreach($fields as $k) {
            $d = $desc[$k];
            if (isset($d)) {
              $v = $data[$k];
              if (trim($d->comment)!='') {
                $name = $d->comment;
                if (!is_null($v)) {
                  $style = 'background: #A9F5A9; margin-bottom: 2px; ';
                } else {
                  $style = 'background: #F5BCA9; margin-bottom: 2px; ';
                }
                $body.= '<span style="'.$style.'">'.dictionary($name).': <b>'.$v.'</b></span><br>'."\n";
                $counter++;
              }
            }
          }
          if ($emitents && sizeOf($emitents)>0) {
            $body.= '<br><u>Выбранные акции:</u><br><br>'."\n";
            foreach($emitents as $e) {
              $body.= '<span><b>'.$e->name.'</b> / объем = <b>'.$e->volume.'</b></span><br>'."\n";
            }
          }
          $finished = $this->global_model->send_emails($emails,$subject,$body,$subject,'Акции');
          if ($finished) {
            
            if (isset($data['subscription'])&&($data['subscription']==1)) {
              
              $type = 'analytics';
              $section_ids = array();
              $sections = $this->global_model->get_table_data('mailing_sections',array('mailing_section_id'),array('type'=>$type,'lang'=>$lang));
              foreach ($sections as $v) {
                $section_ids[] = $v['mailing_section_id'];
              }
              
              $name = (isset($data['first_name']))?$data['first_name']:'';
              $email = (isset($data['email']))?$data['email']:'';
              $subject = '';
              $info = '';
              // function build_subscribe_body($ip,$agent,$lang,$type,$section_ids,$name,$email,$auto,&$subject,&$info)
              $body = $this->front_pages_subscribe_model->build_subscribe_body($ip,$agent,$lang,$type,$section_ids,$name,$email,false,$subject,$info);
              
              $emails = array();
              $emails = array(array('name'=>$name,'email'=>$email));
              $emails = $this->global_model->data_to_class($emails);
              
              $r = $this->global_model->send_emails($emails,$subject,$body);
              if ($r) {
                $data['info'] = $info;
              }
            }
            unset($_SESSION['emitents_order_id']);
          }
        }
      }
      
      $data['emitents'] = $emitents;
      $data['finished'] = $finished; 
      $data['error'] = $error;
      $data['message'] = $message;
      $data['order_id'] = $order_id;
      
      $ret = $this->load->view('body_content_sub_order_emitents',$data,true);
      
    } else {

      $order_id = null;
      if (isset($_SESSION['emitents_order_id'])) {
        $order_id = $_SESSION['emitents_order_id'];
      }
      $ret = $this->default_view($order_id,$emitent_id); 
    }
    return $ret;
  }

}
?>