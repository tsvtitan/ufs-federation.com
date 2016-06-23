<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/strutils.php');

class front_pages_subscribe_model extends Model {

  private $auto_days = 2;
  
  function front_pages_subscribe_model() {
		
    parent::Model();
        
    $this->load->model('maindb_model');
  }
	
  function send_emails($emails,$subject,$body,$subject_as_title=false) {

    return $this->global_model->send_emails($emails,$subject,$body,($subject_as_title)?$subject:null,'UFS IC Mailer');
  }
		
  function get_hash($email,$lang) {

    $ret = $lang.':'.$email;
    return md5($ret);
  }
	
  function sections_to_html($tree,$ids,$pid='') {
    //echo '<pre>', print_r($ids); exit;
    $ret = '';
    if (is_array($tree) && sizeOf($tree)>0) {
      $ret.= '<ul>'."\n";
      foreach ($tree as $item) {
        $ret.= '<li>'."\n";
        $level = $item->level;
        $id = 'subscribe_section_'.$item->mailing_section_id;
        $name = $id;
        $found = in_array($item->mailing_section_id,$ids);
        $value = ($found)?'on':'off';
        $checked = ($found)?' checked ':' ';

        $image = (trim($item->description)!='') ? sprintf('<a class="image-off" onclick="subscribe_image_click(this);"><div></div><span>%s</span></a>',$item->description) : '';

        $ret.= sprintf('<table><tr><td><input type="checkbox" level="%s" pid="%s"%sid="%s" name="%s" value="%s" onclick="subscribe_section_click(this);"/>'.
                       '<a class="section" onclick="subscribe_section_click(this); return false;">%s</a></td><td>%s</td></tr></table>',
                       $level,$pid,$checked,$id,$name,$value,$item->name,$image)."\n";
        $r = $this->sections_to_html($item->items,$ids,$id);
        $ret.= $r.'</li>'."\n";
      }
      $ret.= '</ul>'."\n";
    }
    return $ret;		
  }

  function subscribe_params($s) {

    $ret = '';
    $ret.= dictionary($s);
    $l = strlen($ret)/2 + 1;
    $ret.= ':<br>'."\n";
    $ret.= str_repeat('=',$l).'<br>'."\n";
    return $ret;
  }
	
  function subscribe_insert($hash,$secs,$ids,$name,$email,$ip,$agent,$lang,$auto) {

    $ret = '';
    
    $this->db->query(sprintf('update mailing_subscriptions
                                 set finished=current_timestamp
                               where md5(concat(lang,":",email))=%s
                                 and finished is null;',
                                "'".$hash."'"));
    
    foreach ($secs as $s) {
      $found = in_array($s->mailing_section_id,$ids);
      if ($found) {
        $s_name = "'".$this->db->escape_str($name)."'";
        $s_email = "'".$this->db->escape_str($email)."'";
        $s_ip = "'".$this->db->escape_str($ip)."'";
        $s_agent = "'".$this->db->escape_str($agent)."'";
        $s_lang = "'".$this->db->escape_str($lang)."'";
        $s_started = ($auto)?'current_timestamp':(!is_null($this->auto_days))?sprintf('date_add(current_timestamp,interval +%d day)',$this->auto_days):'null';
        $found = $this->db->query(sprintf('insert into mailing_subscriptions (mailing_section_id,name,email,ip,agent,lang,started) 
                                          values (%d,%s,%s,%s,%s,%s,%s);',
                                          $s->mailing_section_id,$s_name,$s_email,$s_ip,$s_agent,$s_lang,$s_started));
      }
      if ($found) {
        //$ret.= '<b>'.$s->prefix.$s->name.'</b><br>';
        $ret.= $s->prefix.$s->name.'<br>';
      } else {
        $ret.= '<span style="color:silver">'.$s->prefix.$s->name.'</span><br>';
      }
    }

    return $ret;
  } 
	
  function build_subscribe_body($ip,$agent,$lang,$type,$section_ids,$name,$email,$auto,&$subject,&$info) {
    
    $secs = array();
    $sql = $this->db->query(sprintf('select * 
                                       from mailing_sections 
                                      where lang=%s
                                        and type%s
                                        and company="UFS"
                                      order by level, priority',
                                    "'".$lang."'",
                                    (is_null($type)?' is null':'='."'".$type."'")));
    if ($sql->num_rows()>0) {
      $secs = $sql->result();
      $secs = $this->global_model->make_tree($secs,'mailing_section_id');
      $secs = $this->global_model->tree_list($secs,str_repeat(chr(194).chr(160),3));
    }
      
    $hash = $this->get_hash($email,$lang); 
    
    $body = '<p>'.dictionary('Здравствуйте').', '.$name.'.</p>'."\n";
    $body.= '<p>'.sprintf(dictionary('Ваш e-mail %s был указан при подписке на аналитические материалы UFS Investment Company'),$email).'.</p>'."\n";
    
    $link = sprintf('%s?confirm=%s',$this->phpself.'subscribe'.$this->urlsufix,$hash);
        
    $sql = $this->db->query(sprintf('select *
                                       from mailing_subscriptions
                                      where md5(concat(lang,":",email))=%s
                                       and started is not null and finished is null',
                                    "'".$hash."'"));
    if ($sql->num_rows()>0) {
      $subs = $sql->result();
      $ids = array();
      foreach ($subs as $s) {
        $ids[] = $s->mailing_section_id;
      }
      $subject = dictionary('Изменение подписки на рассылку');
      /*$body.= '<p>';
      $body.= $this->subscribe_params('Параметры текущей Вашей подписки следующие');
      foreach ($secs as $s) {
        $found = in_array($s->mailing_section_id,$ids);
        if ($found) {
          $body.= '<b>'.$s->prefix.$s->name.'</b><br>';
        } else {
          $body.= '<span>'.$s->prefix.$s->name.'</span><br>';
        }
      }
      $body.= '</p><br>'."\n";*/
      $body.= $this->subscribe_params('Вы подписались на следующие разделы');
      $body.= $this->subscribe_insert($hash,$secs,$section_ids,$name,$email,$ip,$agent,$lang,$auto);
      $body.= '</p>'."\n";
      $body.= '<p><a href="'.$link.'">'.dictionary('Если Вы действительно хотите изменить содержание подписки, то перейдите по этой ссылке').'</a></p>'."\n";
      $body.= '<p>'.dictionary('В случае, если изменение подписки было оформлено не Вами или Вы не желаете менять её содержание, просто проигнорируйте данное письмо.').'</p>'."\n";
    
      //$info = sprintf(dictionary('Для подтверждения адреса получателя, мы направили Вам письмо. Пожалуйста, перейдите на свой почтовый ящик и следуйте инструкциям в письме.'),$email);
      $s = sprintf('<font style="color:red">%s</font>',$email);
      $info = sprintf(dictionary('На адрес %s выслано письмо для подтверждения подписки на аналитику. Пожалуйста, перейдите на свой почтовый ящик и следуйте инструкциям в письме.'),$s);      
      
    } else {
      $subject = dictionary('Подтверждение подписки на рассылку');
      $body.= '<p>';
      $body.= $this->subscribe_params('Вы подписались на следующие разделы');
      $body.= $this->subscribe_insert($hash,$secs,$section_ids,$name,$email,$ip,$agent,$lang,$auto);
      $body.= '</p>'."\n";
      $body.= '<p><a href="'.$link.'">'.dictionary('Если Вы подтверждаете, что Вы хотите получать рассылку, перейдите по этой ссылке').'</a></p>'."\n";
      $body.= '<p>'.dictionary('В случае, если подписка было оформлена не Вами или Вы не желаете получать письма по ней, просто проигнорируйте данное письмо.').'</p>'."\n";
       
/*     $info = sprintf(dictionary('Для подтверждения адреса получателя, мы направили Вам письмо. Пожалуйста, перейдите на свой почтовый ящик и следуйте инструкциям в письме.'),$email); */
//       $info = '<b>'.sprintf(dictionary('На Ваш почтовый ящик направлено письмо для подтверждения подписки на аналитику. Пожалуйста, зайдите в свой почтовый ящик и перейдите по ссылке.').'<b>',$email);
      $s = sprintf('<font style="color:red">%s</font>',$email);
      $info = sprintf(dictionary('На адрес %s выслано письмо для подтверждения подписки на аналитику. Пожалуйста, перейдите на свой почтовый ящик и следуйте инструкциям в письме.'),$s);      
    }

    $body.= '<p>'.dictionary('С уважением').',<br>'."\n";
    $body.= dictionary('UFS Investment Company').'</p>'."\n";
    
    return $body;
  }
	
  function subscribe($type=null) {

    $data = array();
    $error = '';
    $info = '';
    $completed = false;
    $section_ids = array();

    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $lang = isset($_REQUEST['lang'])?$_REQUEST['lang']:'';
    if ($lang=='') {
      $lang = $this->site_lang;
    }
    $name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
    $email = isset($_REQUEST['email'])?$_REQUEST['email']:'';
    $captcha_word = isset($_SESSION['captcha_word'])?$_SESSION['captcha_word']:'';
    $word = isset($_REQUEST['word'])?$_REQUEST['word']:'';
    $submit = isset($_REQUEST['submit'])?true:false;
    $terms_checked = false;
    $terms = isset($_REQUEST['terms'])?$_REQUEST['terms']:'';
    if ($terms=='on') {
      $terms_checked = true;
      $completed = $submit;
    }
    $auto = isset($_REQUEST['auto'])?true:false;

    if (sizeOf($_REQUEST)>0) {
      $l = strlen('subscribe_section_');
      foreach ($_REQUEST as $k=>$v) {
        $s = substr($k,0,$l);
        if ($s=='subscribe_section_') {
          $id = substr($k,$l,strlen($k)-$l);
          if (is_integer_ex($id)) {
            if ($v="on") {
              $section_ids[] = $id;
            }  
          }
        }
      }	
    }

    if ($completed) {
      if (sizeOf($section_ids)==0) {
        $error = dictionary('Не выбрано ни одного раздела.');
        $completed = false;
      }
    }

    /*if ($completed) {
      if (trim($name)=='') {
        $error = dictionary('Не указан логин.');
        $completed = false;
      }
    }*/

    if ($completed) {
      $email = trim($email);
      $email = strtolower($email);
      if (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/",$email)) {
        $error = dictionary('Неправильно указан email.');
        $completed = false;
      }
    }
    
    if ($completed) {
      $domain = array_pop(explode('@',$email));
      $arrHost = @gethostbynamel($domain);
      if (!$arrHost) {
        $error = dictionary('Неправильно указан email.');
        $completed = false;
      }
    }

    if ($completed) {
      if ($word!=$captcha_word) {
        $error = dictionary('Неправильно указано число с картинки.');
        $completed = false;
      }
    }

    if ($completed) {

      $body = $this->build_subscribe_body($ip,$agent,$lang,$type,$section_ids,$name,$email,$auto,$subject,$info);

      $sent = true;
      if (!$auto) {
        $sent = $this->send_emails($email,$subject,$body,true);
      }
      if ($sent) {
        unset ($_SESSION['captcha_word']);
        
        $emails = array();

        $emails[] = 'vpa@ufs-finance.com';
        $emails[] = 'tsv@ufs-financial.ch';
        $emails[] = 'subscribe-notify@ufs-federation.com'; //vpa@

        if ($auto) {
          $subject = sprintf('Новый подписчик %s на сайте (автоматическая подписка)',$email);
          $body = '<h3>'.sprintf('Новый подписчик %s (%s) на сайте (автоматическая подписка).',$email,$name).'</h3>';
        } else {
          
          if (is_null($this->auto_days)) {
            $subject = sprintf('Потенциальный подписчик %s на сайте',$email);
            $body = '<h3>'.sprintf('Потенциальный подписчик %s (%s) на сайте.',$email,$name).'</h3>';
          } else {
            $subject = sprintf('Новый подписчик %s на сайте (автоматическая подписка)',$email);
            $body = '<h3>'.sprintf('Новый подписчик %s (%s) на сайте (автоматическая подписка).',$email,$name).'</h3>';
          }
        }

        $this->send_emails($emails,$subject,$body);
        
      } else {
        $error = dictionary('Не удалось отправить сообщение на электронную почту. Попробуйте ещё раз.');
        $completed = false;
      }
    }

    if (!$completed) {

      $this->load->helper('captcha');
      $captcha_word = random_number(5);
      $params = array ('word'=>$captcha_word,
                       'img_path'=>$_SERVER['DOCUMENT_ROOT'].'/images/captcha/',
                       'img_url'=>$this->phpself.'/images/captcha/',
                       'img_width'=>100,
                       'img_height'=>30,
                       'expiration'=>3600);
      $captcha = create_captcha($params);
      if ($captcha) {
        $_SESSION['captcha_word'] = $captcha['word'];
        $data['captcha_image'] = $captcha['image'];
      }

      $sections = '';
      $sql = $this->db->query(sprintf('select * 
                                         from mailing_sections
                                        where lang=%s
                                          and type%s  
                                          and company="UFS"
                                        order by level, priority',
                                        "'".$lang."'",
                                        (is_null($type)?' is null':'='."'".$type."'")));
      if ($sql->num_rows()>0) {
        $res = $sql->result();
        $res = $this->global_model->make_tree($res,'mailing_section_id');
        $sections = $this->sections_to_html($res,$section_ids);
      }


      $data['lang'] = $lang;
      $data['sections'] = $sections;
      $data['name'] = $name;
      $data['email'] = $email;
      $data['terms'] = ($terms_checked)?'on':'off';
      $data['terms_checked'] = ($terms_checked)?'checked':'';
      $data['submit_disabled'] = ($terms_checked)?'':'disabled';
      $data['submit_fontweight'] = ($terms_checked)?'bold':'normal';
      //$data['action'] = $this->phpself.$_SERVER['REQUEST_URI'];
      $data['action'] = '';
    }

    $data['error'] = $error;
    $data['info'] = $info;
    $data['completed'] = $completed;
    $data['auto'] = $auto;

    return $data;
  }
	
  function confirm() {

    $data = array();
    $info = '';
    $error = '';
    $hash = isset($_REQUEST['confirm'])?$_REQUEST['confirm']:'';
    if ($hash!='') {

      $sql = $this->db->query(sprintf('select *
                                         from mailing_subscriptions
                                        where md5(concat(lang,":",email))=%s
                                          and (started is null or started>=current_timestamp) and finished is null',
                                        "'".$hash."'"));
      if ($sql->num_rows()>0) {

        $flag = $this->db->query(sprintf('update mailing_subscriptions
                                             set finished=current_timestamp
                                           where md5(concat(lang,":",email))=%s
                                             and started is not null and started<current_timestamp and finished is null;',
                                            "'".$hash."'"));
        $res = $sql->result();
        if (sizeOf($res)>0) {
          $email = '';
          foreach ($res as $r) {
            $email = $r->email;
            $f = $this->db->query(sprintf('update mailing_subscriptions
                                              set started=current_timestamp
                                            where mailing_subscription_id=%d;',$r->mailing_subscription_id));
            $flag = $flag && $f; 
          }
          if ($flag) {
            $res = $res[0];

            $emails = array();
            $emails[] = 'vpa@ufs-finance.com';
            $emails[] = 'subscribe-notify@ufs-federation.com';
            //$emails[] = 'zrv@ufs-financial.ch';

            $subject = sprintf('Новый подписчик %s на сайте',$res->email);
            $body = '<h3>'.sprintf('Новый подписчик %s (%s) на сайте.',$res->email,$res->name).'</h3>';

            $this->send_emails($emails,$subject,$body);

            $info = dictionary('Спасибо, Вы успешно подписаны на нашу рассылку.');
            $info = $info.'<br><br>'.sprintf(dictionary('Ваш e-mail: %s'),$email);
            $info = $info.'<br><br>'.sprintf(dictionary('Пожалуйста, добавьте адрес %s в исключения спам-листов своего почтового клиента, чтобы гарантированно получать от нас отправляемые письма.'),'mailer@ufs-financial.ch');

          } else {
            $error = dictionary('Произошла ошибка. Попробуйте повторить.');
          }
        }  
      } else {
        $error = dictionary('Спасибо, но Вы уже всё подтвердили ранее.');
      }
    }

    $data['info'] = $info; 
    $data['error'] = $error;
    $data['completed'] = true;
    return $data;
  }
	
  function remove() {

    $data = array();
    $info = '';
    $error = '';
    $hash = isset($_REQUEST['remove'])?$_REQUEST['remove']:'';
    $accepted = isset($_REQUEST['accepted']);
    $remove_link = '';

    if ($hash!='') {

      if ($accepted) { 

        $sql = $this->db->query(sprintf('select *
                                           from mailing_subscriptions
                                          where md5(concat(lang,":",email))=%s
                                            and finished is null',
                                          "'".$hash."'"));
        if ($sql->num_rows()>0) {

          $subs = $sql->result();
          $sub = $subs[0];
          $email = $sub->email;
          $lang = $sub->lang;

          $flag = $this->db->query(sprintf('update mailing_subscriptions
                                               set finished=current_timestamp
                                             where md5(concat(lang,":",email))=%s
                                               and finished is null;',
                                              "'".$hash."'"));
          if ($flag) {

            $flag = $this->db->query(sprintf('delete from analytics_reviews_mailing
                                               where mailing_id in (select mailing_id from mailing where email=%s and lang=%s);',
                                              "'".$email."'","'".$lang."'"));
            if ($flag) {
              $flag = $this->db->query(sprintf('delete from mailing where email=%s and lang=%s;',"'".$email."'","'".$lang."'"));
            }
          }	

          if ($flag) {
            $info = dictionary('Вы успешно отписаны от рассылки.');
          } else {
            $error = dictionary('Произошла ошибка. Попробуйте повторить.');
          }
        } else {
          $error = dictionary('Рассылка не найдена.');
        }
      } else {
        $sql = $this->db->query(sprintf('select count(*) as cnt
                                           from mailing_subscriptions
                                          where md5(concat(lang,":",email))=%s
                                            and finished is null',
                                          "'".$hash."'"));
        if ($sql->num_rows()>0) {
          $count = $sql->result();
          $count = $count[0];
          if ($count->cnt>0) {
            $info = dictionary('Если Вы хотите отписаться, нажмите кнопку ниже.');
            $remove_link ='&accepted';
          } else {
            $error = dictionary('Рассылка не найдена.');
          }
        } else {
          $error = dictionary('Произошла ошибка. Попробуйте повторить.');
        }
      }
    }

    $data['info'] = $info; 
    $data['error'] = $error;
    $data['completed'] = true;
    $data['remove_link'] = $remove_link;
    return $data;
  }
  
  
  function unsubscribe() {

    $data = array();
    $info = '';
    $error = '';
    $completed = true;
    $email = isset($_REQUEST['unsubscribe'])?$_REQUEST['unsubscribe']:'';
    $accepted = isset($_REQUEST['accepted']);
    $remove_link = '';
    $feedback = isset($_REQUEST['comments'])?true:false;
    $started = '';

    if ($email!='' && !$feedback) {
        
      $sql = $this->db->query(sprintf('select *
                                         from mailing_subscriptions
                                        where email=%s
                                          and finished is null',
                                        "'".$email."'"));
      if ($sql->num_rows()>0) {

        $subs = $sql->result();
        $sub = $subs[0];
        $email = $sub->email;
        $lang = $sub->lang;
        $started = $sub->started;

        $flag = $this->db->query(sprintf('update mailing_subscriptions
                                             set finished=current_timestamp
                                           where email=%s
                                             and finished is null;',
                                            "'".$email."'"));
        if ($flag) {
          //$info = dictionary('Вы успешно отписаны от рассылки.');
          //unset($_REQUEST['unsubscribe']);
          $feedback = true;
          
          $emails = array();

          //$emails[] = 'subscribe-notify@ufs-federation.com';
          $emails[] = 'vpa@ufs-finance.com';
          $emails[] = 'mas@ufs-federation.com';

          $subject = sprintf('Уведомление об отписке');
          $body = '<b>'.$email.'</b> отписался от подписки.';
          if($started!='') {
            $body .= '<br>Дата подписки: '.date('d.m.Y',strtotime ($started));
          }

          $this->send_emails($emails,$subject,$body);

        } else {
          $error = dictionary('Произошла ошибка. Попробуйте повторить.');
        }
      } else {
        $error = dictionary('Подписка не найдена.');
        //$feedback = false;
      }
    } else if ($feedback) {
      
      $info = dictionary('Желаем Вам удачных инвестиционных решений!');
      $comments = $_REQUEST['comments'];
      $feedback = false;

      if($email!='') {
        $emails = array();

        //$emails[] = 'subscribe-notify@ufs-federation.com';
        $emails[] = 'vpa@ufs-finance.com';
        $emails[] = 'mas@ufs-federation.com';

        $subject = sprintf('Комментарий к отписке');
        $body = '<b>'.$email.'</b> отписался от подписки.<br><p>Причина/пожелание:<br><i>'.$comments.'</i></p>';

        $this->send_emails($emails,$subject,$body);
        $email='';
      }

    } else {
      $info = '';//dictionary('Если Вы хотите отписаться, введите свой e-mail ниже и нажмите кнопку "Отменить рассылку"');
      $remove_link ='&accepted';
    }

    $data['info'] = $info; 
    $data['error'] = $error;
    $data['completed'] = $completed;
    $data['remove_link'] = $remove_link;
    $data['feedback'] = $feedback;
    $data['email'] = $email;
    return $data;
  }
	
  function view($type=null) {

    $simple = isset($_REQUEST['simple'])?true:false;

    $actions = array(0=>'subscribe',1=>'confirm',2=>'remove');
    $action = 0;
    $data = array();

    $temp = isset($_REQUEST['confirm'])?$_REQUEST['confirm']:'';
    if (trim($temp)!='') {
      $action = 1;
    } else {
      $temp = isset($_REQUEST['remove'])?$_REQUEST['remove']:'';
      if (trim($temp)!='') {
        $action = 2;
      } else {
        if (isset($_REQUEST['unsubscribe'])) {
          $action = 3;
        }
      }
    }

    switch ($action) {
      case 0: default: {
        $data = $this->subscribe($type);
        break;
      } 
      case 1: {
        $data = $this->confirm();
        break;
      }
      case 2: {
        $data = $this->remove();
        break;
      }
      case 3: {
        $data = $this->unsubscribe();
        if ($data && $simple) {
          
          header('Content-type: text/plain');
          header('Content-length: '.strlen($data['error']));
          header('Access-Control-Allow-Origin: *');
           
          echo ($data['error']);
          die();
          
        }
      }
    }
    
    if (!$simple) {
      return $this->load->view('view_pages_subscribe',$data,true);
    }
    
  }
    
}
?>