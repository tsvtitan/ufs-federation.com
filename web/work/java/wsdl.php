<?php

set_include_path(':/var/www/work:'.get_include_path());
require_once 'libs/MessageGate.php';


$gate = new MessageGate();
if ($gate->connect()) { 

  /*$r = true;
  
  $body = '<h1>Body</h1><br/><br/><h2>США</h2><br/><br/><h3 th:utext="${contact}"/><br/><br/><div th:replace="Test"/>';
  
  
  $message = new Message();
  $message->senderName = 'Sergei';
  $message->senderContact = 'tsv@ufs-financial.ch';
  
  $message->addRecipient('tsv@nextsoft.ru'); 
   * $message->addRecipient('tsv@nextsoft.ru'); 
   * $message->addRecipient('tsv@nextsoft.ru'); 
   * $message->addRecipient('tsv@nextsoft.ru'); 
  
  $message->subject = 'Проверка темы письма';
  $message->body = $body;
  $message->begin = null;
  $message->end = null;
  $message->priority = 10; 
  
  $message->addKeyword('США','EMAIL');
  $message->addKeyword('КНР');
  $message->addKeyword('ZZZ',GATE_SMS);
  
  $message->addPattern('EmailDefaultPattern');
  
  $var = $message->addVar('tsv@ufs-financial.ch');
  $var->add('contact','-=Контакт=-');
  $var->add('name','-=Имя=-');
  
  $message->addAttachment('Вложение #1','html','Привет поцанчики','text/html');
  
  $message->addHeader('Reply-To','info@ufs-financial.ch');
  
  $r = $gate->sendMessage($message);
  if ($r) {

    if ($gate->cancel(array($r->parentId))) {
      
      $r = $gate->getStatus(array($r->parentId)); 
      foreach ($r as $v) {
        
        if ($v instanceof StatusResult) {
          
          echo sprintf('parentId: %s<br/>',$v->parentId);
          echo sprintf('allCount: %s<br/>',$v->allCount);
          echo sprintf('sentCount: %s<br/>',$v->sentCount);
          echo sprintf('deliveredCount: %s<br/>',$v->deliveredCount);
          
        }
      }
    }
    
  } else {
    echo 'Messages are not found.'; 
  }*/
  
  $gate->cancel(array('123','456'));
  
  
} else {
  echo 'MessageGate is not found.';
}


/*$client = new SoapClient("http://dev1.ufs-federation.com:8080/MessageGate/WebService?wsdl",
                         array('cache_wsdl' => WSDL_CACHE_NONE,
                               'trace' => 1));


$params->channelId = null;
$params->senderName = 'Sergei';
$params->senderContact = 'tsv@ufs-financial.ch';

$recipient1->contact = 'tsv@ufs-federation.com';
$recipient1->name = 'Sergei';

$recipient2->contact = 'tsv@nextsoft.ru';
$recipient2->name = 'Sergei';

$params->recipients = array($recipient1,$recipient2);

$params->subject = 'Subject';
$params->body = 'Body США';

$params->begin = '';
$params->end = '';
$params->priority = 10;

$params->pattern = null;
$params->keywords = array('США','КНР');

$parameter->name = 'link';
$parameter->value = 'http://ru.ufs-federation.com/subscribe.html?unsubscribe';

$params->vars = array($parameter);

$response = $client->queue($params); 
var_dump($response); */ 


?>