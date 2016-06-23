<?php

define('GATE_WSDL','http://dev1.ufs-federation.com:7000/MessageGate/WebService?wsdl');
//define('GATE_WSDL','http://app.ufs-federation.com:8080/MessageGate/WebService?wsdl');
define('GATE_DATETIME_FMT','Y-m-d H:i:s');
define('GATE_TIMEOUT',10000);
 
define('GATE_EMAIL','EMAIL');
define('GATE_SMS','SMS');

class Recipient {
  public $contact = null;
  public $name = null;
  public $priority = null;
  
  public function __construct($contact,$name=null,$priority=null) {
    
    $this->contact = $contact;
    $this->name = $name;
    $this->priority = $priority;
  }
}

class Keyword {
  public $keyword = null;
  public $deliveryType = null;
  
  public function __construct($keyword,$deliveryType=GATE_EMAIL) {
    
    $this->keyword = $keyword;
    $this->deliveryType = $deliveryType;
  }
}

class Pattern {
  public $pattern = null;
  public $deliveryType = null;
}

class Value {
  public $name = null;
  public $value = null;
}

class Var_ {
  public $contact = null;
  public $values = null;
 
  public function add($name,$value) {
    
    $ret = new Value();
    $ret->name = $name;
    $ret->value = $value;
    
    $this->values[] = $ret;
    
    return $ret;
  }
}

class Attachment {
  public $name = null;
  public $extension = null;
  public $data = null;
  public $contentType = null;
  public $contentId = null;
  public $size = null;
}

class Header extends Value {
  
  public function __construct($name,$value) {
    
    $this->name = $name;
    $this->value = $value;
  }
}

class Message {

  public $channelId = null;
  public $senderName = null;
  public $senderContact = null;
  public $recipients = null; 
  public $subject = null;
  public $body = null;
  public $begin = null;
  public $end = null;
  public $priority = null;
  public $keywords = null;
  public $patterns = null;
  public $vars = null;
  public $attachments = null;
  public $headers = null;
  public $unique = null;
      
  public function addRecipient($contact,$name=null,$priority=null) {
    
    $ret = new Recipient($contact,$name,$priority);
    $this->recipients[] = $ret;
    return $ret;
  }
  
  public function addKeyword($keyword,$deliveryType=GATE_EMAIL) {
    
    $ret = new Keyword($keyword,$deliveryType);
    $this->keywords[] = $ret;
    return $ret;
  }
  
  public function addPattern($pattern,$deliveryType=GATE_EMAIL) {
    
    $ret = new Pattern();
    $ret->pattern = $pattern;
    $ret->deliveryType = $deliveryType;
     
    $this->patterns[] = $ret;
    
    return $ret;
  }
  
  public function addVar($contact) {
    
    $ret = new Var_();
    $ret->contact = $contact; 
    
    $this->vars[] = $ret;
    
    return $ret;
  }
  
  public function addAttachment($name,$extension,$data,$contentType=null,$contentId=null) {
    
    $ret = new Attachment();
    $ret->name = $name;
    $ret->extension = $extension;
    $ret->data = $data;
    $ret->contentType = $contentType;
    $ret->contentId = $contentId;
    $ret->size = strlen($data);
    
    $this->attachments[] = $ret;
    
    return $ret;
  }
  
  public function addHeader($name,$value) {
    
    $ret = new Header($name,$value);
    $this->headers[] = $ret;
    return $ret;
  }
}

class SendResult {
  
  public $messageId = null;
  public $queueLength = 0;
}


class StatusResult {

  public $messageId = null;
  public $allCount = 0;
  public $sentCount = 0;
  public $deliveredCount = 0;
  public $errorCount = 0;
}

class MessageGate {
   
  private $params = array('cache_wsdl' => WSDL_CACHE_NONE,
                          'trace' => false);
  private $client = null;
  
  public function __construct($params=null) {

    if (!isset($params) || is_null($params)) {
      $params = array();
    }
  
    $this->params = array_merge($this->params,$params);
    ini_set('default_socket_timeout',GATE_TIMEOUT);
  }
  
  public function connect() {
  
    $ret = !is_null($this->client);
    if (!$ret) {
      try {
        $this->client = @new SoapClient(GATE_WSDL,$this->params);
        $ret = !is_null($this->client);
      } catch (SoapFault $e) {
        $ret = false;
      }  
    }
    return $ret;
  }
  
  private function prepareTimestamp($timestamp) {
    
    $ret = null;
    if (is_string($timestamp)) {
      $ret = date(GATE_DATETIME_FMT,strtotime($timestamp));
    } 
    return $ret;
  }
  
  private function prepareBlob($blob) {
    
    $ret = null;
    if (!is_null($blob)) {
      $ret = base64_encode($blob);
    }
    return $ret;
  }
  
  private function asArray($result) {
    
    $ret = false;
    if (isset($result->return)) {
      if (is_array($result->return)) {
        $ret = $result->return;
      } else {
        $ret = array($result->return);
      }
    }
    return $ret;
  }
  
  private function asStatusArray($result) {
    
    $ret = false;
    $r = $this->asArray($result);
    if (is_array($r)) {
      foreach ($r as $v) {
        $status = new StatusResult();
        $status->messageId = $v->messageId;
        $status->allCount = $v->allCount;
        $status->sentCount = $v->sentCount;
        $status->deliveredCount = $v->deliveredCount;
        $ret[] = $status;
      }
    }
    return $ret;
  }

  private function asBoolean($result) {
    
    $ret = false;
    if (isset($result->return)) {
      if (is_bool($result->return)) {
        $ret = $result->return;
      }
    }
    return $ret;
  }
  
  private function asSendResult($result) {
    $ret = false;
    if (isset($result->return) && is_object($result->return)) {
      $ret = new SendResult();
      $ret->messageId = (isset($result->return->messageId))?$result->return->messageId:null;
      $ret->queueLength = (isset($result->return->queueLength))?$result->return->queueLength:0;
    }
    return $ret;
  }

  public function sendMessage($message) {
  
    $ret = false;
    if (!is_null($this->client) && $message instanceof Message) {
      
      $message->body = $this->prepareBlob($message->body);
      $message->begin = $this->prepareTimestamp($message->begin);
      $message->end = $this->prepareTimestamp($message->end);
      
      if (is_array($message->attachments)) {
        
        foreach ($message->attachments as $attachment) {
          
          $attachment->data = $this->prepareBlob($attachment->data);
        }
      }
      
      $params = new stdClass();
      $params->message = $message;
      
      $r = $this->client->send($params);
      if ($r) {
        $ret = $this->asSendResult($r);
      }
    }
    return $ret;
  }
  
  public function send($senderName,$senderContact,$subject,$body,
                       $recipients=null,$begin=null,$end=null,
                       $priority=null,$keywords=null,
                       $patterns=null,$vars=null,
                       $attachments=null,$headers = null,
                       $channelId=null,$unique=null) {
  
    $ret = false;
    if (!is_null($this->client)) {
      
      $message = new Message();
      $message->channelId = $channelId;
      $message->senderName = $senderName;
      $message->senderContact = $senderContact;
      $message->recipients = $recipients;
      $message->subject = $subject;
      $message->body = $body;
      $message->begin = $begin;
      $message->end = $end;
      $message->priority = $priority;
      $message->keywords = $keywords;
      $message->patterns = $patterns;
      $message->vars = $vars;
      $message->attachments = $attachments;
      $message->headers = $headers;
      $message->unique = $unique;
      
      $ret = $this->sendMessage($message);
    }
    return $ret;
  }
  
  public function cancel($messageIds) {
    
    $ret = false;
    if (!is_null($this->client)) {
      
      $params = new stdClass();
      
      if (is_array($messageIds)) {
        $params->messageIds = $messageIds;
      } else {
        $params->messageIds = array($messageIds);
      }
      
      $r = $this->client->cancel($params);
      if ($r) {
        $ret = $this->asBoolean($r);
      }
    }
    return $ret;
  }
  
  public function accelerate($messageIds) {
    
    $ret = false;
    if (!is_null($this->client)) {
      
      $params = new stdClass();
      
      if (is_array($messageIds)) {
        $params->messageIds = $messageIds;
      } else {
        $params->messageIds = array($messageIds);
      }
      
      $r = $this->client->accelerate($params);
      if ($r) {
        $ret = $this->asBoolean($r);
      }
    }
    return $ret;
  }
  
  public function getStatus($messageIds) {
    
    $ret = false;
    if (!is_null($this->client)) {
      
      $params = new stdClass();
      
      if (is_array($messageIds)) {
        $params->messageIds = $messageIds;
      } else {
        $params->messageIds = array($messageIds);
      }
      
      $r = $this->client->getStatus($params);
      if ($r) {
        $ret = $this->asStatusArray($r);
      }
    }
    return $ret;
  }
  
}

?>