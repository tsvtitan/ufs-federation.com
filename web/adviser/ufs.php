<?php

require_once('utils.php');
require_once('libs/common.php');
require_once('libs/chat.php');
require_once('libs/operator.php');

define ('TIME_FORMAT','H:i:s');
//define ('ONLINE_TIMEOUT',60*60*8);
//define ('LAST_MESSAGE_TIMEOUT',60*15);
//define ('THREAD_TIMEOUT',60*1);
//define ('ONLINE_TIMEOUT',60*10*1);
define ('LAST_MESSAGE_TIMEOUT',60*5);
define ('THREAD_TIMEOUT',10*1);

function read($location='php://input') {

  $ret = false;
  $r = @file_get_contents($location);
  if ($r!='') {
    $ret = $r;
  }
  return $ret;
}

function write($data) {

  $ret = false;
  if ($data) {
    echo($data);
    $ret = true;
  }
  return $ret;
}

function make_out() {

  $out = new stdClass();
  $out->data = new stdClass();
  $out->data->success = false;
  $out->data->message = '';
  return $out;
}

function reply_out($out,$type='json',&$data) {

  if (isset($out) && isset($out->data) && (is_array($out->data) || (is_object($out->data)))) {
    switch($type) {
      case 'json': {
        $default = JSON_HEX_QUOT | JSON_HEX_AMP;
        $data = @json_encode($out->data,isset($out->options)?$out->options:$default);
        if (isset($out->callback)) {
          $data = $out->callback.'('.$data.')';
        }
        break;
      }
      default: {
        $data = var_export($out,true);
        break;
      }
    }
  } else {
    $data = $out;
  }
  return write($data);
}

function reply($out) {
  
  $data = false;
  return reply_out($out,'json',$data);
}

function reply_error($message) {

  $ret = false;
  if (isset($message)) {
    $out = make_out();
    $out->data->message = $message;
    $data = false;
    $ret = reply($out);
  }
  return $ret;
}

function reply_not_found() {
  header(HEADER_404);  
}

function get_thread($conn) {
  
  $ret = false;
  
  if( isset($_SESSION['threadid']) ) {
    $r = thread_by_id_($_SESSION['threadid'],$conn);
    if ($r && is_array($r) && sizeOf($r)>0) {
      $ret = $r;
    }
  }
  
  return $ret;
}

function thread_expired($thread) {
  
  $ret = false;
  if ($thread) {
    global $state_closed;
    $s = sprintf('+%d minutes',THREAD_TIMEOUT);
    $t = strtotime($s,$thread['modified']);
    $ret = ($thread['istate']==$state_closed) && (time()>=$t);
  }
  return $ret;
}

function process_exists($conn,$in) {

  $out = make_out(); 
  $found = false;
  
  $thread = get_thread($conn); 
  if ($thread) {
    $found = !thread_expired($thread);
  }
  
  $out->data->found = $found;
  $out->data->success = true;
  return $out;
}

function get_adviser_query($online_seconds,$where) {

  global $mysqlprefix;
  
  $query = sprintf("select operatorid, vclocalename, vcavatar, istatus, vcemail, vcphone, vcoccupation, vcsex,
                           (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited)) as time
                      from ${mysqlprefix}chatoperator
                     where operatorid>1
                       and hour(CURRENT_TIMESTAMP)>=8 and hour(CURRENT_TIMESTAMP)<19
                       and (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited))<=%d
                       %s
                     order by vclogin",
                   $online_seconds,((trim($where)!='')?sprintf('and %s',$where):''));

  return $query;
}

function get_adviser_randomly($conn,$where) {

  $ret = false;
  //$online_seconds = ONLINE_TIMEOUT;
  global $settings;
  
  $online_seconds = $settings['online_timeout'];
  
  $query = get_adviser_query($online_seconds,$where);
  
  $operators = select_multi_assoc($query,$conn);
  if (isset($operators) && is_array($operators) && (sizeof($operators)>0)) {
    
    $index = rand(0,sizeof($operators)-1);
    $ret = $operators[$index];
  }
  return $ret;
}


function get_adviser($conn,$in,&$name,&$image,&$id,&$occupation) {
  
  $ret = false; 
  global $mysqlprefix, $settings;
  
  $need_random = true;
  //$online_seconds = ONLINE_TIMEOUT;
  $online_seconds = $settings['online_timeout'];
  
  $operator_id = false;
  
  $thread = get_thread($conn); 
  if ($thread) {
    
    $expired = thread_expired($thread);
    if (!$expired) {
    
      $operator_id = (trim($thread['agentId'])=='')?false:trim($thread['agentId']);
    }
  }

  if (!$operator_id && (trim($in->adviser_id)!='')) {
    $operator_id = trim($in->adviser_id);
  }

  if ($operator_id) {

    $query = sprintf("select operatorid, vclocalename, vcavatar, istatus, vcemail, vcphone, vcoccupation, vcsex,
                            (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited)) as time
                       from ${mysqlprefix}chatoperator
                      where operatorid=%s
                        and (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited))<=%d",
                     $operator_id,$online_seconds);

    $row = select_one_row($query,$conn);
    if ($row) {

      $name = $row['vclocalename'];
      $image = $row['vcavatar'];
      $id = $row['operatorid'];
      $occupation = $row['vcoccupation'];

      $need_random = false;
      $ret = true;
    }
  }
  
  if ($need_random) {
    
    $operator = get_adviser_randomly($conn,'');
    if ($operator) {

      $name = $operator['vclocalename'];
      $image = $operator['vcavatar'];
      $id = $operator['operatorid'];
      $occupation = $operator['vcoccupation'];

      $ret = true;
    }
  }
  return $ret;
  //return false;
}

function process_adviser($conn,$in) {

  $out = make_out(); 
  
  $r = get_adviser($conn,$in,$name,$image,$id,$occupation);
  if ($r) {
    $out->data->name = $name;
    $out->data->image = $image;
    $out->data->id = $id;
    $out->data->occupation = $occupation;
    $out->data->found = true;
  } else {
    $out->data->found = false;
  }
  $out->data->success = true;
  return $out;
}

function get_advisers($conn) {
  
  $ret = false; 
  $need_random = true;
  
  $thread = get_thread($conn); 
  if ($thread) {
    $need_random = thread_expired($thread);
  }
  
  if ($need_random) {

    $female = get_adviser_randomly($conn,'vcsex=0');
    $male = get_adviser_randomly($conn,'vcsex=1');
    
    if ($female && $male) {
      
      $ads = array();
      
      $flip = rand(0,1);
      if ($flip==0) {
        $ads[] = $female;
        $ads[] = $male;
      } else {
        $ads[] = $male;
        $ads[] = $female;
      }
      
      $advisers = array();
      
      foreach ($ads as $a) {
        
        $adviser = new stdClass();
        $adviser->id = $a['operatorid'];
        $adviser->name = $a['vclocalename'];
        $adviser->image = $a['vcavatar'];
        $adviser->occupation = $a['vcoccupation'];
                
        $advisers[] = $adviser;
      }
      
      $ret = $advisers;
    }
  }
  
  return $ret;
  //return false;
}

function process_advisers($conn,$in) {

  $out = make_out(); 
  
  $r = get_advisers($conn);
  if ($r) {
    $out->data->advisers = $r;
    $out->data->found = true;
  } else {
    $out->data->found = false;
  }
  $out->data->success = true;
  return $out;
}

function process_name($conn,$in) {

  $out = make_out(); 
  
  $thread = get_thread($conn); 
  if ($thread) {
    
  	global $kind_events;

    commit_thread($thread['threadid'], array('userName' => "'" . mysql_real_escape_string($in->user, $conn) . "'"), $conn);

    if ($thread['userName'] != $in->user) {
      post_message_($thread['threadid'], $kind_events,
                    getstring2_("chat.status.user.changedname", array($thread['userName'], $in->user), $thread['locale'], true), $conn);
    }
  }
  
  $out->data->success = true;
  
  return $out;
}

function get_empty_user($user,$lang) {
  
  $ret = $user;
  if (trim($ret)=='') {
    $ret = 'Guest';
    if ($lang=='ru') {
      $ret = 'Гость';
    }
  }
  return $ret;
}

function get_default_message($lang,$name,$sex) {
  
  $ret = false;
  
  //$morning = array('ru'=>array('Доброе утро!','Здравствуйте!','Добро пожаловать!'), 
  $morning = array('ru'=>array('Доброе утро!'),
                   'en'=>array('Good morning!','Hello!','Wellcome','Can I help you?'));
  
  //$noon = array('ru'=>array('Добрый день!','Здравствуйте!','Добро пожаловать!'),
  $noon = array('ru'=>array('Добрый день!'),
                'en'=>array('Good day!','Hello!','Wellcome','Can I help you?'));

  //$evening = array('ru'=>array('Добрый вечер!','Здравствуйте!','Добро пожаловать!'),
  $evening = array('ru'=>array('Добрый вечер!'),
                   'en'=>array('Good evening!','Hello!','Wellcome','Can I help you?'));

  $night = array('ru'=>array('К сожалению, в это время суток, мы не можем Вам ответить на вопросы'),
                 'en'=>array('Unfortunatly we can not answer for your questions at this time'));
  
  
  /*$extra = array('ru'=>sprintf('Спасибо что посетили нас! %sБуду рад%s Вам помочь найти необходимое и проконсультировать! Давайте знакомиться? Как Вас зовут?',
                               (trim($name)!='')?sprintf('Меня зовут %s. ',$name):'',($sex==0)?'а':''),
                 'en'=>'Thank you for paying attention!');*/

  list($name,$surname) = split(" ",trim($name));
  
  $extra = array('ru'=>sprintf('Рады видеть Вас на страницах нашего сайта! Мы поможем Вам найти необходимое с учетом ваших пожеланий. Меня зовут %s. А как я могу обращаться к Вам?',$name),
                 'en'=>'Thank you for paying attention!');
  
  $arr = false;
  $hour = intval(date('H'));
  
  if (($hour>=22) && ($hour<7)) {
    $arr = $night;
  } elseif (($hour>=7) && ($hour<12)) {
    $arr = $morning;
  } elseif (($hour>=12) && ($hour<17)) {
    $arr = $noon;
  } elseif (($hour>=17) && ($hour<22)) {
    $arr = $evening;
  }
  
  if ($arr) {
    $ret = new stdClass();
    $ret->time = date(TIME_FORMAT);
    
    $arr = $arr[$lang];
    $index = rand(0,sizeOf($arr)-1);
    
    $ret->text = sprintf('%s %s',$arr[$index],$extra[$lang]);
    $ret->out = true;
  }
  
  return $ret;
}

function clear_old_threads($conn) {

   $query = "delete from chatthread
              where DATE_ADD(dtmmodified,INTERVAL 1 HOUR)<=CURRENT_TIMESTAMP
                and istate = 3
                and threadid not in (select t.threadid
                                       from (select threadid, count(*) as cnt
                                               from chatmessage
                                              where ikind in (1)
                                              group by threadid) t
                                      where t.cnt!=0)";
   perform_query($query,$conn);
   
   $query = "delete from chatmessage
              where threadid not in (select threadid from chatthread)";
   
   perform_query($query,$conn);
  
}

function process_start($conn,$in) {

  $out = make_out();
  
  $userName = get_empty_user($in->user,$in->lang);
  
  $messages = array();
  $closed = false;
  $user = '';
  
  global $state_closed, $mysqlprefix;
  
  $thread = get_thread($conn); 
  if ($thread) {
    
    $closed = ($thread['istate']==$state_closed);

    $expired = thread_expired($thread);
    if ($expired) {
      $thread = false;
      $closed = false;
    }
  }

  $operator = false;
  $query = sprintf("select operatorid, vclocalename, vcsex
                       from ${mysqlprefix}chatoperator
                      where operatorid='%s'",
                    $in->adviser_id);

   $row = select_one_row($query,$conn);
   if ($row && (sizeOf($row)>0)) {
     $operator['operatorid'] = $row['operatorid'];
     $operator['vclocalename'] = $row['vclocalename'];
     $operator['vcsex'] = $row['vcsex'];
   }  
  
  if ($thread) {

    global $kind_info, $kind_agent, $kind_user;
    
    $out_kinds = array($kind_info,$kind_agent);
    
    $query = sprintf("select messageid,ikind,dtmcreated,tname,tmessage from ${mysqlprefix}chatmessage
                       where threadid = %s and ikind in (%s,%s,%s) order by messageid",
                     intval($thread['threadid']),$kind_info,$kind_agent,$kind_user);

    $msgs = select_multi_assoc($query,$conn);
    foreach ($msgs as $msg) {

      $message = new stdClass();
      $message->id = $msg['messageid'];
      $message->time = date(TIME_FORMAT,strtotime($msg['dtmcreated']));
      $message->text = $msg['tmessage'];
      $message->out = in_array($msg['ikind'],$out_kinds)?true:false;

      $messages[] = $message;
    }

    $name = $thread['userName'];
    $user = ($name!=$userName)?$name:'';
    
  } else {
    
    global $usercookie, $state_queue, $state_waiting;
    
    $groupid = "";

    $remoteHost = get_remote_host();
    $referrer = '';
    $locale = $in->lang;

    if (isset($_COOKIE[$usercookie])) {
      $userId = $_COOKIE[$usercookie];
    } else {
      $userId = uniqid('', TRUE);
      setcookie($usercookie, $userId, time() + 60 * 60 * 24 * 365);
    }

    $userbrowser = $_SERVER['HTTP_USER_AGENT'];

    $thread = create_thread($groupid,$userName,$remoteHost,$referrer,$locale,$userId,$userbrowser,$state_queue,$conn);
    $_SESSION['threadid'] = $thread['threadid'];
    
    
    if ($operator) {

      $thread['agentId'] = $operator['operatorid'];
      $thread['agentName'] = $operator['vclocalename'];
      
      commit_thread($thread['threadid'], array("istate" => intval($state_waiting),"nextagent" => 0,"agentId" => intval($row['operatorid']),
					                           "agentName" => "'" . mysql_real_escape_string($row['vclocalename'],$conn) . "'"),$conn);
      
    }
    
  }
  
  if ($thread && (sizeOf($messages)==0)) {

    global $kind_agent;
    
    clear_old_threads($conn);
            
    $message = get_default_message($in->lang,$thread['agentName'],($operator)?$operator['vcsex']:1);
    if ($message) {
      
			$postedid = post_message_($thread['threadid'],$kind_agent,$message->text,$conn,$thread['agentName']);
			if ($postedid) {
				commit_thread( $thread['threadid'], array('shownmessageid' => intval($postedid)),$conn);
			}
      $message->id = $postedid;
      $messages[] = $message;
      
      global $state_closed, $kind_events, $mysqlprefix;
      
      if (trim($in->referrer)!='') {
        $postedid = post_message_($thread['threadid'],$kind_events,sprintf('переход с %s',$in->referrer),$conn,$userName);
        if ($postedid) {
          commit_thread( $thread['threadid'], array('shownmessageid' => intval($postedid)), $conn);
        }
      }

      // close thread by default
      
      if ($thread['istate'] != $state_closed) {
        commit_thread($thread['threadid'], array( 'istate' => intval($state_closed),
                     'messageCount' => "(SELECT COUNT(*) FROM ${mysqlprefix}chatmessage WHERE ${mysqlprefix}chatmessage.threadid = t.threadid AND ikind = 1)" ), $conn);
      }

      $message =getstring2_("chat.status.user.left", array($thread['userName']), $thread['locale'], true);
      post_message_($thread['threadid'], $kind_events, $message, $conn);
      
    }
  }
  
  $out->data->messages = $messages;
  $out->data->closed = $closed;
  $out->data->success = true;
  $out->data->user = $user;
  
  $_SESSION['closed'] = $closed;
  
  return $out;
}

function process_stop($conn,$in) {

  $out = make_out();

  $thread = get_thread($conn); 
  if ($thread) {
    
    global $state_closed, $kind_events, $mysqlprefix;

    if ($thread['istate'] != $state_closed) {
      commit_thread($thread['threadid'], array( 'istate' => intval($state_closed),
                   'messageCount' => "(SELECT COUNT(*) FROM ${mysqlprefix}chatmessage WHERE ${mysqlprefix}chatmessage.threadid = t.threadid AND ikind = 1)" ), $conn);
    }

    $message =getstring2_("chat.status.user.left", array($thread['userName']), $thread['locale'], true);
    post_message_($thread['threadid'], $kind_events, $message, $conn);
  }
  
  $out->data->success = true;
  
  $_SESSION['closed'] = true;
  
  return $out;
}

function notify_advisers($conn,$thread,$user,$text) {
  
  if ($thread) {

    global $kind_agent, $mysqlprefix, $settings;
    
    $operators = array();

    //$online_seconds = ONLINE_TIMEOUT;
    $online_seconds = $settings['online_timeout'];
    
    if (trim($thread['agentId'])!='') {
      

      $query = sprintf("select cm.agentId, co.*
                          from ${mysqlprefix}chatmessage cm
                          left join ${mysqlprefix}chatoperator co on co.operatorId=cm.agentId
                         where cm.threadid = %s and cm.ikind in (%s) and cm.agentId>1
                           and (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(co.dtmlastvisited))<=%d
                         order by cm.messageid desc limit 1",
                       intval($thread['threadid']),$kind_agent,$online_seconds);

      $row = select_one_row($query,$conn);
      if ($row && (sizeOf($row)>0)) {
        
        $operators[] = $row;
      }
      
    }
    
    if (is_array($operators) && (sizeOf($operators)==0)) {
      
      $query = sprintf("select *,
                               (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited)) as time
                          from ${mysqlprefix}chatoperator
                         where operatorid>1
                           and (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited))<=%d
                         order by vclogin",
                       $online_seconds);
      $operators = select_multi_assoc($query,$conn);
    }
    
    if (is_array($operators) && sizeOf($operators)>0) {
      
      $emails = array();
      $phones = array();

      foreach ($operators as $operator) {
        
        if (trim($operator['vcemail'])!='') {
          $emails[] = $operator['vcemail'];
        }
        if (trim($operator['vcphone'])!='') {
          $phones[] = $operator['vcphone'];
        }
      }

      send_sms($phones,'Внимание! Потенциальный клиент на сайте');
      send_email($emails,'Внимание! Потенциальный клиент на сайте',sprintf('Потенцильный клиент %s на сайте начал диалог: %s',$user,$text));
    }
  }
}

function process_incoming($conn,$in) {
  
  $out = make_out();
  
  global $kind_user, $state_closed, $state_waiting, $mysqlprefix;
  
  $thread = get_thread($conn); 
  if ($thread) {

    if ($thread['istate']==$state_closed) {
      
      global $kind_events;
      
      commit_thread($thread['threadid'],
                    array("istate" => intval($state_waiting), "nextagent" => 0), $conn);
      
      post_message_($thread['threadid'], $kind_events, getstring_("chat.status.user.reopenedthread", $thread['locale'], true), $conn);
      
    }
    
    $notify = false;
    
    $states = array($state_closed,$state_waiting);
    if (in_array($thread['istate'],$states)) {
      
      if ($thread['istate']==$state_waiting) {
        
        $query = sprintf("select count(*) as cnt
                            from ${mysqlprefix}chatmessage cm
                           where cm.threadid = %s and cm.ikind in (%s)
                             and (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(cm.dtmcreated))<=%d",
                         intval($thread['threadid']),$kind_user,LAST_MESSAGE_TIMEOUT);

        $row = select_one_row($query,$conn);
        if (isset($row['cnt'])) {
          $notify = intval($row['cnt'])==0;
        }
        
      } else {
        $notify = true;
      }
    }

    $userName = get_empty_user($in->user,$in->lang);
    
    if ($notify) {
      notify_advisers($conn,$thread,$userName,$in->text);
    }

    $postedid = post_message_($thread['threadid'],$kind_user,$in->text,$conn,$userName);
    if ($postedid) {
      commit_thread( $thread['threadid'], array('shownmessageid' => intval($postedid)), $conn);

      $out->data->id = $postedid;
      $out->data->time = date(TIME_FORMAT);
      $out->data->text = $in->text;
    }
    $out->data->success = true;
    
  } else {
    $out->data->message = 'Thread is not found (process_incoming).';
  }
  return $out;
}

function process_outgoing($conn,$in) {
  
  $out = make_out();
  
  $adviser = false;
  $messages = array();
  $closed = false;
  
  $thread = get_thread($conn); 
  if ($thread) {

    global $kind_info, $kind_agent, $kind_events, 
           $state_closed, $mysqlprefix;
  
    // messages
    $query = sprintf("select messageid,ikind,dtmcreated,tname,tmessage
                        from ${mysqlprefix}chatmessage
                       where threadid = %s and ikind in (%s,%s) and messageid>%s order by messageid",
                     intval($thread['threadid']),$kind_info,$kind_agent,$in->last_id);

    $msgs = select_multi_assoc($query,$conn);
    foreach ($msgs as $msg) {

      $message = new stdClass();
      $message->id = $msg['messageid'];
      $message->time = date(TIME_FORMAT,strtotime($msg['dtmcreated']));
      $message->text = $msg['tmessage'];
      $message->out = true;

      $messages[] = $message;
    }
    
    //adviser

    $query = sprintf("select co.operatorid, co.vclocalename, co.vcavatar, co.vcoccupation,
                             (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(co.dtmlastvisited)) as time
                       from ${mysqlprefix}chatthread ct
                       left join ${mysqlprefix}chatoperator co on co.operatorid=ct.agentId
                      where ct.threadid = %s",
                     intval($thread['threadid']));
    
    $row = select_one_row($query,$conn);
    if ($row) {

      if (trim($row['vclocalename'])!='') {
      
        $adviser = new stdClass();
        $adviser->name = $row['vclocalename'];
        $adviser->image = $row['vcavatar'];
        $adviser->id = $row['operatorid'];
        $adviser->occupation = $row['vcoccupation'];
        
      } else {

        $query = sprintf("select co.operatorid, cm.messageid, cm.ikind, cm.dtmcreated, cm.tname, cm.tmessage, co.vclocalename, co.vcavatar, co.vcoccupation
                          from ${mysqlprefix}chatmessage cm
                          left join ${mysqlprefix}chatoperator co on co.operatorid = cm.agentid
                         where cm.threadid = %s 
                           and cm.agentid>1
                         order by cm.messageid desc limit 1",
                         intval($thread['threadid']));
        $row = select_one_row($query,$conn);
        if ($row) {

          $adviser = new stdClass();
          $adviser->name = $row['vclocalename'];
          $adviser->image = $row['vcavatar'];
          $adviser->id = $row['operatorid'];
          $adviser->occupation = $row['vcoccupation'];
          
        }
      }
    }
    
    //closed
    
    $closed = $thread['istate']==$state_closed;
    if ($closed) {

     /* $userName = get_empty_user($in->user,$in->lang);
      $text = ($in->lang=='ru')?'Консультант покинул диалог':'The adviser leaved this conversation';

      $postedid = post_message_($thread['threadid'],$kind_events,$text,$conn,$userName);
      if ($postedid) {
        commit_thread( $thread['threadid'], array('shownmessageid' => intval($postedid)), $conn);

        $message = new stdClass();
        $message->id = $postedid;
        $message->time = date(TIME_FORMAT);
        $message->text = $text;
        $message->out = true;

        $messages[] = $message;
      } */
    }
    
  } else {
    $out->data->message = 'Thread is not found (process_outgoing).';
  }
  
  $out->data->adviser = $adviser;
  $out->data->messages = $messages;
  $out->data->closed = $closed;
  $out->data->success = true;  
  
  $_SESSION['closed'] = $closed;
  
  return $out; 
}

function process_message($conn,$in) {

  $out = make_out(); 
  global $mysqlprefix;
  
  $m_success = array('ru'=>'Ваше сообщение принято.',
                     'en'=>'Your message has accepted.');
  $m_failed = array('ru'=>'Произошла ошибка, попробуйте ещё раз.',
                    'en'=>'An error has occured, try again.');
  
  $message = $m_failed[$in->lang];
  
  $query = "select *,
                   (unix_timestamp(CURRENT_TIMESTAMP)-unix_timestamp(dtmlastvisited)) as time
              from ${mysqlprefix}chatoperator
             where operatorid>1
             order by vclogin";
              
  $operators = select_multi_assoc($query,$conn);
    
  if (is_array($operators) && sizeOf($operators)>0) {

    $emails = array();

    foreach ($operators as $operator) {

      if (trim($operator['vcemail'])!='') {
        $emails[] = $operator['vcemail'];
      }
    }

    $r = send_email($emails,'Внимание! Потенциальный клиент оставил сообщение на сайте',sprintf('Email: %s, сообщение: %s',$in->email,$in->message));
    if ($r) {
      $message = $m_success[$in->lang];
    }
  }
      
  $out->data->success = true;
  $out->data->message = $message;
  return $out;
}


$input = read();
if ($input) {
      
  loadsettings();
  
  $remoteHost = get_remote_host();
  
  $conn = connect();
  /*if(!check_connections_from_remote($remoteHost, $conn)) {
    mysql_close($conn);
    reply_error('number of connections from your IP is exceeded, try again later');
    die();
  }*/
  
  $in = @json_decode($input);
  if ($in) {
    
    switch($in->method) {
      case 'exists': {
        reply(process_exists($conn,$in));
        break;
      }
      case 'adviser': {
        reply(process_adviser($conn,$in));
        break;
      }
      case 'advisers': {
        reply(process_advisers($conn,$in));
        break;
      }
      case 'name': {
        reply(process_name($conn,$in));
        break;
      }
      case 'start': {
        reply(process_start($conn,$in));
        break;
      }
      case 'stop': {
        reply(process_stop($conn,$in));
        break;
      }
      case 'incoming': {
        reply(process_incoming($conn,$in));
        break;
      }
      case 'outgoing': {
        reply(process_outgoing($conn,$in));
        break;
      }
      case 'message': {
        reply(process_message($conn,$in));
        break;
      }
      default: {
        reply_error('Method not found.');
      }
    }
  } else {
    reply_error('Could not decode json.');
  }
  
  mysql_close($conn);
  
} else {
  reply_error('Input is empty.');
}

?>