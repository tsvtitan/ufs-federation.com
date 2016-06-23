<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_item_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_item_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_type_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_tool_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_level_value_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_level_type_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_data_type_model.php');

class front_pages_trade_ideas_model_new extends Model{

  var $group_name =
                array('ru' => array ('Еврооблигации' => 'евро', 'Локальный рынок' => 'рубл'),
                      'en' => array ('Eurobonds' => 'евро', 'Local market' => 'рубл'),
                      'de' => array ('Eurobonds' => 'евро', 'Lokale markt' => 'рубл')
        );
  
  function front_pages_trade_ideas_model() {
    
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
  
  function default_view($order_id=null) {
    
    $ret = false;
    $last_update = '';
    /*$groups = array();
    
    $trade_ideas = array();
    $r = $this->global_model->get_table_data('order_trade_ideas',array('trade_idea_id'),array('order_id'=>$order_id));
    if ($r) {
      $r = $this->global_model->data_to_class($r);
      foreach($r as $item) {
        $trade_ideas[] = $item->trade_idea_id; 
      }
    }
    
    $r = $this->global_model->get_table_data('trade_ideas',null,array('lang'=>$this->site_lang,'finished'=>null),array('priority'));
    if ($r) {
      $r = $this->global_model->data_to_class($r);
    
      $old_group_name = '';
      $counter = 0;
      foreach($r as $item) {
    
        if ($item->group_name!=$old_group_name) {
          $group = new stdClass();
          $group->counter = $counter;
          $group->name = $item->group_name;
          $group->items = array();
          $groups[] = $group;
          $counter++;
          $old_group_name = $group->name;
        }
        if (isset($group)) {
          $item->checked = in_array($item->trade_idea_id,$trade_ideas);
          $group->items[] = $item;
        }
      }
    }
    
    $data['groups'] = $groups;
    */    
    $r = $this->global_model->get_query_data(sprintf('select max(created) as date '.
                                                       'from trade_idea_items '.
                                                      'where finished is null '.
                                                        'and language=%s ',
                                                     $this->global_model->quote($this->site_lang)));
    if ($r) {
      $r = $this->global_model->data_to_class($r);
      $r = $this->global_model->date_format($r,'d #_n_# Y','date','date');
      $last_update = $r[0]->date;
    }
    $data['last_update'] = $last_update;
    
    $lang = isset($_REQUEST['lang'])?$_REQUEST['lang']:$this->site_lang;
    
    $trade_idea_item_currency = new trade_idea_item();
    $trade_idea_item_currency->select('group_name, language')->distinct()->where(array('language' => $lang,
                                           'finished is null' => null))->get();
    $data['data']=$trade_idea_item_currency; 
    $data['trade_idea_group_name'] = $this->group_name;
    return $this->load->view('body_content_sub_trade_ideas_new',$data,true);  
  }
  
  function view() {

    $ret = false;
    
    $trade_idea_id = isset($_REQUEST['trade_idea_id'])?$_REQUEST['trade_idea_id']:false;
    
    $order = isset($_REQUEST['order'])?true:false;
    //$order_id = null;
    if ($order || $trade_idea_id) {
    
      $data = array();
      
      $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
      $agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null;
      $lang = isset($_REQUEST['lang'])?$_REQUEST['lang']:null;
      if ($lang=='') {
        $lang = $this->site_lang;
      }
    
      $submit = isset($_REQUEST['submit'])?true:false;
      
      $table_orders = 'orders';
      $table_order_trade_ideas = 'order_trade_ideas_new';
      $table_trade_ideas = 'trade_ideas';
      $desc = $this->global_model->get_table_desc($table_orders);
      
      $new = $this->global_model->get_request($_REQUEST);
      $trade_ideas = isset($_REQUEST['trade_ideas'])?$_REQUEST['trade_ideas']:false;
      
      if (!$trade_ideas && $trade_idea_id) {
        $trade_ideas[] = $trade_idea_id;
      }
    
      $order_id = isset($new['order_id'])?$new['order_id']:false;
      if (!$order_id) {
        if (isset($_SESSION['trade_ideas_order_id'])) {
          $order_id = $_SESSION['trade_ideas_order_id'];
        } else {
          $order_id = $this->global_model->get_unique_id();
          $_SESSION['trade_ideas_order_id'] = $order_id;
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
                                                            $table_order_trade_ideas,$this->global_model->quote($order_id)));
            if ($success) {
              $pattern = 'trade_idea_';
              $pattern2 = 'trade_idea2_';
              $l = strlen($pattern);
              $trade_ideas = array();
              if(isset($_REQUEST['trade_idea_id'])) unset($_REQUEST['trade_idea_id']);
              if(isset($_REQUEST['order'])) unset($_REQUEST['order']);
              foreach ($_REQUEST as $k=>$v) {
                $s = substr($k,0,$l);
                if (strtolower($s)==$pattern) {
                  $s = substr($k,$l);
                  /*if (strlen($s)==32)*/ {
                    $trade_ideas[] = $s;
                    $newti['order_id'] = $order_id;
                    $newti['trade_idea_id'] = $s;
                    if ($v=='other') {
                      $v = isset($_REQUEST[$pattern2.$s])?$_REQUEST[$pattern2.$s]:'';
                    }
                    $newti['volume'] = $v;
                    $this->global_model->insert($table_order_trade_ideas,$newti);
                  }
                }
              }
            }
          }
        }
      } else {
        if (!$exists) {
          $new['order_id'] = $order_id;
          $new['num'] = $this->get_num($table_orders,$table_trade_ideas);
          $new['ip'] = $ip;
          $new['agent'] = $agent;
          $new['lang'] = $lang;
          $new['type'] = $table_trade_ideas;
          $success = $this->global_model->insert($table_orders,$new);
          if ($success && is_array($trade_ideas)) {
            foreach($trade_ideas as $ti) {
              $newti['order_id'] = $order_id;
              $newti['trade_idea_id'] = $ti;
              $this->global_model->insert($table_order_trade_ideas,$newti);
            }
          }
        } else {
          $success = $this->global_model->execute(sprintf('delete from %s where order_id=%s;',
                                                          $table_order_trade_ideas,$this->global_model->quote($order_id)));
          if ($success && is_array($trade_ideas)) {
            foreach($trade_ideas as $ti) {
              $newti['order_id'] = $order_id;
              $newti['trade_idea_id'] = $ti;
              $this->global_model->insert($table_order_trade_ideas,$newti);
            }
          }
        }
      }

      $tiids = array();
      if (is_array($trade_ideas)) {
      
        $where = array();
        foreach($trade_ideas as $ti) {
          $where[] = $this->global_model->quote($ti);
        }
        if (sizeOf($where)>0) {
          $where = implode(',',$where);
          $r = $this->global_model->get_query_data(sprintf(
                  'select i.id trade_idea_id, t.operation, t.name, i.group_name,'.
                  '       ot.volume, t.isin '.
                  'from trade_idea_items i '.
                  '     join trade_idea_tools t on i.id=t.item_id'.
                  '     join %s ot on ot.trade_idea_id=i.id '.
                  'where i.id in (%s) and ot.order_id=%s',
                  $table_order_trade_ideas,
                  $where,$this->global_model->quote($order_id)));
          if ($r) {
            $tiids = $this->global_model->data_to_class($r);
          }
        }
      }
      $trade_ideas = $tiids;
      
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
          $emails[] = array('email'=>'tsv@ufs-federation.com');
          $emails[] = array('email'=>'imv@ufs-federation.com');
          $emails[] = array('email'=>'clientservice@ufs-federation.com');
          
          $emails = $this->global_model->data_to_class($emails);
          
          $fields = array();
          $subject = sprintf('%s%s заполнил заявку%s на покупку торговых идей',
                             (isset($data['is_customer'])&&($data['is_customer']==1)?'Потенциальный клиент':'Клиент'),
                             (isset($data['first_name'])?' '.$data['first_name']:''),
                             (isset($data['num'])?' #'.$data['num']:''));
          $body = '<u>Основные данные:</u><br><br>'."\n";
          $counter = 0;
          foreach($desc as $k=>$d) {
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
          if ($trade_ideas && sizeOf($trade_ideas)>0) {
            $body.= '<br><u>Выбранные торговые идеи:</u><br><br>'."\n";
            foreach($trade_ideas as $tid) {
              $body.= '<span>'.$tid->group_name.': <b>'.$tid->name.'</b> '.$tid->isin.' / объем = <b>'.$tid->volume.'</b></span><br>'."\n";
            }
          }
          $finished = $this->global_model->send_emails($emails,$subject,$body,$subject,'Торговые идеи');
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
            unset($_SESSION['trade_ideas_order_id']);
          }
        }
      }
      
      $data['trade_ideas'] = $trade_ideas;
      $data['finished'] = $finished; 
      $data['error'] = $error;
      $data['message'] = $message;
      $data['order_id'] = $order_id;
      
      $ret = $this->load->view('body_content_sub_order_trade_ideas_new',$data,true);
      
    } else {

      $order_id = null;
      if (isset($_SESSION['trade_ideas_order_id'])) {
        $order_id = $_SESSION['trade_ideas_order_id'];
      }
      $ret = $this->default_view($order_id); 
    }
    
    return $ret;
  }

}
?>