<?php


set_include_path(':/var/www/work/mailing:'.get_include_path());

require_once 'consts.php';

set_include_path(':/var/www/work/libs:'.get_include_path());

require_once 'log.php';
require_once 'utils.php';
require_once 'mysql.php';
require_once 'MessageGate.php';

define ('DEBUG_LOG',true); 

function send_message($senderName,$senderContact,
                      $subject,$body,$subscribers,
                      $begin=null,$end=null,$replyTo=null,$copies=null) {
  
  $ret = false;
  $gate = new MessageGate();
  if ($gate->connect()) {

    $message = new Message();
    $message->senderName = $senderName;
    $message->senderContact = $senderContact;

    foreach ($subscribers as $subscriber) {
      if (is_array($subscriber)) {
        $message->addRecipient($subscriber['email'],$subscriber['name'],$subscriber['priority']);
      } else {
        $message->addRecipient($subscriber->email,$subscriber->name,$subscriber->priority);
      }
    }
    
    $message->subject = $subject;
    $message->body = $body;

    $message->begin = $begin;
    $message->end = $end; 
    $message->priority = 10;
    $message->unique = true;

    $message->addPattern('EmailResearchPattern');

    if (!is_null($replyTo)) {
      $message->addHeader('Reply-To',$replyTo);
    }
    
    if (!is_null($copies)) {
      $message->addHeader('Cc',$copies);
    }
    
    $r = $gate->sendMessage($message);
    if (is_object($r) && $r instanceof SendResult) {

      if (!is_null($r->messageId)) {
        $ret = $r->messageId;
      } else {
        $ret = 'No message.';
      }
    } else {
      $ret = 'Could not send.';
    } 
    
  } else {
    $ret = 'Could not connect.';
  }
  return $ret;
}

function make_subscriber($email,$name=null,$priority=null) {
    
  $ret = new stdClass();
  $ret->email = $email;
  $ret->name = $name;
  $ret->priority = $priority;
  return $ret;
}

$log = new Log(LOG_MAIL,DEBUG_LOG,true,true);
if ($log) {

  $stamp = microtime(true);

  $log->writeInfo(str_repeat('-',50));
  try {
    $log->delete = true;
    
    $log->writeInfo('Connect to Database ...');

    $db = new Mysql($log,DB_MAILING_HOST,DB_MAILING_USER,DB_MAILING_PASS,DB_MAILING_NAME);
    if ($db) {

      $log->writeInfo('Connected.');
      $db->setCharset('utf8'); 
      
      //$subscribers = array(); 
      //$subscribers[] = make_subscriber('tsv@ufs-financial.ch');
      
      $subscribers = $db->getRecords('select distinct(email) as email, name, null as priority from emails');
      
      if (is_array($subscribers) && sizeOf($subscribers)>0) {
        
        $log->writeInfo(sprintf('Found %d subscribers.',sizeOf($subscribers)));
        
        $log->delete = false;
        
        $time = time();
        $begin = date(GATE_DATETIME_FMT,strtotime('+10 minutes',$time));
        $end = date(GATE_DATETIME_FMT,strtotime('+120 minutes',$time));
        
        $subject = 'Поздравляем с новым годом!';
        $body = '<p>Уважаемые коллеги и партнеры!<br/><br/>'.
                'Поздравляем Вас с Новым годом!<br/><br/>'.
                'Желаем Вам богатырского здоровья, чудесного праздничного настроения, удачи во всех делах и начинаниях, исполнения заветных желаний и достижения новых целей!<br/><br/>'.
                'С наилучшими пожеланиями,<br/>'.
                'команда UFS IC и<br/>'.
                'Управляющий Партнер Елена Железнова</p>'.
                '<br><img src="http://ru.ufs-federation.com/banners/2015.jpg">';
        
        $replyTo = 'USF IC <research@ufs-federation.com>';
        
        $copies = '';
        
        /*$copies = sprintf('%s <zev@ufs-federation.com>, %s <vpa@ufs-finance.com>, %s <mvi@ufs-federation.com>',
                          sprintf('=?utf-8?B?%s?=',base64_encode('Елена Железнова')),
                          sprintf('=?utf-8?B?%s?=',base64_encode('Павел Василиади')),
                          sprintf('=?utf-8?B?%s?=',base64_encode('Мария Исаева')));*/
        
        $ret = send_message('UFS IC','mailer@lists.ufs-financial.ch',
                            $subject,$body,$subscribers,
                            $begin,$end,$replyTo,$copies);
        
        $log->writeInfo($ret);
      }
    }
    
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>
