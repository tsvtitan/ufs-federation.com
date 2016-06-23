<?

namespace service;

//define('MAILINGS_HOST_PORT','192.168.34.2:1337');
define('MAILINGS_HOST_PORT','app1.ufs-federation.com:1338');
define('MAILINGS_URL',sprintf('http://%s/service/api/mailings',MAILINGS_HOST_PORT));
define('MAILINGS_SEND_URL',sprintf('%s/send',MAILINGS_URL));
define('MAILINGS_CANCEL_URL',sprintf('%s/cancel',MAILINGS_URL));
define('MAILINGS_ACCELERATE_URL',sprintf('%s/accelerate',MAILINGS_URL));

define('JOBS_HOST_PORT','192.168.34.2:1337');
//define('JOBS_HOST_PORT','app1.ufs-federation.com:1340');

define('JOBS_URL',sprintf('http://%s/service/api/jobs',JOBS_HOST_PORT));
define('JOBS_START_URL',sprintf('%s/start',JOBS_URL));

define('AUTH_LOGIN','www');
define('AUTH_PASS','');

class Auth {
  
  public $login = null;
  public $pass = null;
   
  public function __construct($login,$pass) {
    
    $this->login = $login;
    $this->pass = $pass;
  }
}

class Sender {
  
  public $contact = null;
  public $name = null;
  
  public function __construct($contact,$name) {
    
    $this->contact = $contact;
    $this->name = $name;
  }
}

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

class Header {
  
  public $name = null;
  public $value = null;
  
  public function __construct($name,$value) {
    
    $this->name = $name;
    $this->value = $value;
  }
}

class Attachment {
  
  public $id = null;
  public $name = null;
  public $extension = null;
  public $data = null;
  public $size = null;
  public $contentType = null;
  public $contentId = null;
  
}


class Mailing {

  public $sender = null;
  public $subject = null;
  public $body = null;
  public $recipients = null;
  public $headers = null;
  public $attachments = null;
  public $keywords = null;
  
  public $priority = null;
  public $channel = null;
  public $pattern = null;
  public $test = true;
  
  public $delay = 0;
  public $duration = 60;
  
  public function setSender($contact,$name=null) {
    
    $ret = new Sender($contact,$name);
    $this->sender = $ret;
    return $ret;
  }
  
  public function addRecipient($contact,$name=null,$priority=null) {
    
    $ret = new Recipient($contact,$name,$priority);
    $this->recipients[] = $ret;
    return $ret;
  }
  
  public function addHeader($name,$value) {
    
    $ret = new Header($name,$value);
    $this->headers[] = $ret;
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
  
  public function addKeyword($keyword) {
    
    $this->keywords[] = $keyword;
  }
  
}

class ErrorResult {
  
  public $error = false;
}

class SendResult {
  
  public $error = false;
  public $messageId = null;
  public $mailingId = null;
  public $allCount = 0;
}

class CancelResult {
  
  public $error = false;
  public $canceled = null;
}

class AccelerateResult {
  
  public $error = false;
  public $accelerated = null;
}

class Service {
   
  private $params = array();
  public $auth = null;
  
  public function __construct($params=null) {

    if (!isset($params) || is_null($params)) {
      $params = array();
    }
  
    $this->params = array_merge($this->params,$params);
  }
  
  public function setAuth($login,$pass=null) {
    
    $ret = new Auth($login,$pass);
    $this->auth = $ret;
    return $ret;
  }
  
  private function base64Encode($data) {
    
    $ret = null;
    if (!is_null($data)) {
      $ret = base64_encode($data);
    }
    return $ret;
  }
  
  private function getAuth() {
    
    $ret = $this->auth;
    if (is_null($ret)) {
      $ret = new Auth(AUTH_LOGIN,AUTH_PASS);
    }
    return $ret;
  }
  
  private function curl_post($url,$data) {
    
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');                                                                     
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);                                                                  
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    
    $headers = array('Content-Type: application/json',
                     'Content-Length: '.strlen($data));
    
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);        
    
    $ret = curl_exec($ch);
    if (!$ret) {
      //echo curl_error($ch);
    }
      
    return $ret;
  }

  private function postJson($url,$data,&$response) {
    
    $ret = false;
    try {
      
      $default = JSON_HEX_QUOT | JSON_HEX_AMP;
      
      $content = json_encode($data,$default);
      
      $response = $this->curl_post($url,$content);
      if ($response) {
        $ret = json_decode($response,true);
      }
    
    } catch (Exception $ex) {
      $ret = false;
    }

    return $ret;
  }
  
  
  public function connect() {
    
    $ret = false;
    
    $data = new \stdClass();
    $data->auth = $this->getAuth();

    $response = '';
    $r = $this->postJson(MAILINGS_URL,$data,$response);
    if ($r && is_array($r)) {
      $ret = isset($r['error'])?!$r['error']:false;
    }
    
    return $ret;
  }
  
  private function asErrorResult($result) {
    $ret = false;
    if (isset($result) && is_array($result)) {
      $ret = new ErrorResult();
      $ret->error = isset($result['error'])?$result['error']:false;
    }
    return $ret;
  }

  private function asSendResult($result) {
    $ret = false;
    if (isset($result) && is_array($result)) {
      $ret = new SendResult();
      $ret->error = isset($result['error'])?$result['error']:false;
      $ret->messageId = isset($result['messageId'])?$result['messageId']:null;
      $ret->mailingId = isset($result['mailingId'])?$result['mailingId']:null;
      $ret->allCount = isset($result['allCount'])?$result['allCount']:0;
    }
    return $ret;
  } 
  
  public function sendMailing($mailing) {
  
    $ret = false;
    if ($mailing instanceof Mailing) {
      
      $data = new \stdClass();
      $data->auth = $this->getAuth();
      $data->mailing = $mailing;
      
      $data->mailing->body = $this->base64Encode($data->mailing->body);
      if (!is_null($data->mailing->body)) {
        $data->mailing->bodyEncoding = 'base64';
      }
      
      if (is_array($data->mailing->attachments)) {
        
        $counter = 0;
        foreach ($data->mailing->attachments as $attachment) {
          
          $attachment->id = is_null($attachment->id)?''.$counter:$attachment->id;
          $attachment->data = $this->base64Encode($attachment->data);
          $counter++;
        }
      }
      
      $response = '';
      $r = $this->postJson(MAILINGS_SEND_URL,$data,$response);
      if ($r) {
        $ret = $this->asSendResult($r);
      } else {
        $ret = new SendResult();
        $ret->error = is_bool($response)?!$response:$response;
      }
    }
    return $ret;
  }
  
  public function send($subject,$body,
                       $senderContact=null,$senderName=null,
                       $recipients=null,$headers =null,
                       $attachments=null,$keywords=null,
                       $priority=null,$channel=null,
                       $pattern=null,$test=null,
                       $delay=null,$duration=null) {
  
    $mailing = new Mailing();
    $mailing->subject = $subject;
    $mailing->body = $body;
    $mailing->setSender($senderContact,$senderName);
    $mailing->recipients = $recipients;
    $mailing->headers = $headers;
    $mailing->attachments = $attachments;
    $mailing->keywords = $keywords;
   
    $mailing->priority = $priority;
    $mailing->channel = $channel;
    $mailing->pattern = $pattern;
    $mailing->test = $test;
  
    $mailing->delay = isset($delay)?$delay:0;
    $mailing->duration = isset($duration)?$duration:60;
    
    return $this->sendMailing($mailing);
  }
  
  private function asAccelerateResult($result) {
    $ret = false;
    if (isset($result) && is_array($result)) {
      $ret = new AccelerateResult();
      $ret->error = isset($result['error'])?$result['error']:false;
      $ret->accelerated = isset($result['accelerated'])?$result['accelerated']:false;
    }
    return $ret;
  }
  
  public function accelerate($mailingId) {
    
    $ret = false;
    
    if (isset($mailingId)) {
      $data = new \stdClass();
      $data->auth = $this->getAuth();
      $data->id = $mailingId;

      $response = '';
      $r = $this->postJson(MAILINGS_ACCELERATE_URL,$data,$response);
      if ($r) {
        $ret = $this->asAccelerateResult($r);
      } else {
        $ret = new AccelerateResult();
        $ret->error = is_bool($response)?!$response:$response;
        $ret->accelerated = false;
      }
    }
    return $ret;
  }

  private function asCancelResult($result) {
    $ret = false;
    if (isset($result) && is_array($result)) {
      $ret = new CancelResult();
      $ret->error = isset($result['error'])?$result['error']:false;
      $ret->canceled = isset($result['canceled'])?$result['canceled']:false;
    }
    return $ret;
  }
  
  public function cancel($mailingId) {
    
    $ret = false;
    
    if (isset($mailingId)) {
      $data = new \stdClass();
      $data->auth = $this->getAuth();
      $data->id = $mailingId;

      $response = '';
      $r = $this->postJson(MAILINGS_CANCEL_URL,$data,$response);
      if ($r) {
        $ret = $this->asCancelResult($r);
      } else {
        $ret = new CancelResult();
        $ret->error = is_bool($response)?!$response:$response;
        $ret->canceled = false;
      }
    }
    return $ret;
  }
  
  public function startJob($name) {
    
    $ret = false;
    
    if (isset($name)) {
      $data = new \stdClass();
      $data->auth = $this->getAuth();
      $data->name = $name;

      $response = '';
      $r = $this->postJson(JOBS_START_URL,$data,$response);
      if ($r) {
        $ret = $this->asErrorResult($r);
      } else {
        $ret = new ErrorResult();
        $ret->error = is_bool($response)?!$response:$response;
      }
    }
    return $ret;
  }
}

?>