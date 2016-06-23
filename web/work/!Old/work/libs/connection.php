<?php 

require_once 'package.php';
require_once 'log.php';
require_once 'utils.php';
require_once 'consts.php';

define ('R_TYPE_SUCCESS','success');
define ('R_TYPE_FAILED','failed');
define ('R_TYPE_INTERNAL_ERROR','internal_error');
define ('PACKAGE_RESULT','result');
define ('MAX_RND_LENGTH',3*1024);
define ('LOCAL_KEY','79C5868D7808345E524DF0587021BDDB');
define ('REMOTE_KEY','1DE1E46E5B48C7065F6FE64936DFAE91');

class Connection  {

  public $url = '';
  private $log = null;
  private $local_key = LOCAL_KEY;
  private $remote_key = REMOTE_KEY;
  
  public function __construct($log,$url) {
    
    $this->url = $url;
    $this->log = $log; 
  }
  
  private function log($message) {
    if (isset($this->log))
      $this->log->writeInfo($message);  
  }
  
  private function send($package) {
    
    $ret=false;
    try {
      if ($package) {
      $package->add('rnd')->value = base64_encode(random_string(MAX_RND_LENGTH));
      $xml = $package->saveXml();
      if ($xml) {
        
        //@file_put_contents('/home/ufs-federa/work/mailing/logs/output.xml',$xml);
         
        $xml = encode_string($this->local_key,$xml);
        $options = array ('http'=>array('method'=>'POST',
                                        'content'=>$xml,
                                        'header'=>'Content-type: text/xml'));
        $context = @stream_context_create($options);
        $url = $this->url;
        $response = @file_get_contents($url,false,$context);
        if ($response) {
          @file_put_contents('/home/ufs-federa/work/mailing/logs/input.data',$response); 
          
          $xml = decode_string($this->remote_key,$response); 
          $ret = new Package(PACKAGE_RESULT);
          $ret->loadXml($xml);
        } else
          $this->log(sprintf('Could not send to %s',$url));
        }
      }    
    } catch (Exception $e) {
      $ret = false; 
      $this->log($e->getMessage());
    }
    return $ret;
  }
  
  public function sendMail($name,$email,$begin,$end,
                           $params,$subject,$body,$headers,$async=false,
                           &$remote_id,&$transferred,&$sent,&$result) {
    
    $ret = false;
    $out = new Package(__FUNCTION__);
    
    $a = $out->add('data');
    $a->attributes['name'] = $name;
    $a->attributes['email'] = $email;
    $a->attributes['begin'] = $begin;
    $a->attributes['end'] = $end;
    $a->attributes['async'] = $async;

    $a->childs->add('params')->value = base64_encode($params);
    $a->childs->add('subject')->value = base64_encode($subject);
    $a->childs->add('body')->value = base64_encode($body);
    $a->childs->add('headers')->value = base64_encode($headers);
    
    $in = $this->send($out);
    if ($in) {
      
      $diff = $in->stamp - $out->stamp;
      
      $type = $in->find('type');
      if (($type) && (isset($type->value))) {
        
        switch ($type->value) {
          case R_TYPE_SUCCESS: {
            $data = $in->find('data');
            if ($data) {
              $remote_id = (trim($data->attributes['remote_id'])!='')?$data->attributes['remote_id']:null;

              $transferred = (trim($data->attributes['transferred'])!='')?$data->attributes['transferred']:null;
              if ($transferred) {
                $t = strtotime($transferred);
                $transferred = date(DB_DATE_TIME_FMT,$t - $diff);
              }
              
              $sent = (trim($data->attributes['sent'])!='')?$data->attributes['sent']:null;
              if ($sent) {
                $t = strtotime($sent);
                $sent = date(DB_DATE_TIME_FMT,$t - $diff);
              }
              
              $result = $data->childs->find('result');
              if ($result) {
                $result = base64_decode($result->value);
                $result = (trim($result)!='')?$result:null;
              }
              
              $ret = !is_null($remote_id);
            }
            break;
          }
          case R_TYPE_FAILED:
          case R_TYPE_INTERNAL_ERROR: {
            $e = $in->find('error');
            if ($e) {
              $result = base64_decode($e->value);
              $result = (trim($result)!='')?$result:null;
            }
            break;
          }
        }
      }
    }
    return $ret;
  }
  
  public function checkMails(&$emails,&$result) {

    $ret = false;
    $out = new Package(__FUNCTION__);

    $a = $out->add('data');
    
    foreach($emails as $e) {
      $i = $a->childs->add('item',false);
      $i->attributes['remote_id'] = $e->remote_id;
    }
    
    $in = $this->send($out);
    if ($in) {
      
      $diff = $in->stamp - $out->stamp;
      
      $type = $in->find('type');
      if (($type) && (isset($type->value))) {
      
        switch ($type->value) {
          case R_TYPE_SUCCESS: {
            $data = $in->find('data');
            if ($data) {

              foreach ($data->childs as $d) {
                if ($d->name='item') {

                  foreach ($emails as &$e) {
                    if ($d->attributes['remote_id']==$e->remote_id) {
                      
                      $sent = (trim($d->attributes['sent'])!='')?trim($d->attributes['sent']):null;
                      if ($sent) {
                        $t = strtotime($sent);
                        $e->sent = date(DB_DATE_TIME_FMT,$t - $diff);
                      }
                      
                      $result = $d->childs->find('result');
                      if ($result) {
                        $result = base64_decode($result->value);
                        $e->result = (trim($result)!='')?$result:null;
                      }
                    }
                  }
                }
              }
              $ret = true;
            }
            break;
          }
          case R_TYPE_FAILED:
          case R_TYPE_INTERNAL_ERROR: {
            $e = $in->find('error');
            if ($e) {
              $result = base64_decode($e->value);
              $result = (trim($result)!='')?$result:null;
            }
            break;
          }
        }
      }
    }
    return $ret;
  }
  
  public function sendMails(&$emails,$group,$subject,$body,$headers,$async=false,&$result) {
    
    $ret = false;
    $out = new Package(__FUNCTION__);
    
    $a = $out->add('data');
    $a->attributes['async'] = $async;
    $a->attributes['group'] = $group;
    
    $a->childs->add('subject')->value = base64_encode($subject);
    $a->childs->add('body')->value = base64_encode($body);
    $a->childs->add('headers')->value = base64_encode($headers);
    
    foreach($emails as $e) {
      
      $i = $a->childs->add('item',false);
      $i->attributes['mailing_id'] = $e->mailing_id;
      $i->attributes['priority'] = $e->priority;
      $i->attributes['name'] = $e->name; 
      $i->attributes['email'] = $e->email;
      $i->attributes['begin'] = $e->begin;
      $i->attributes['end'] = $e->end;
      
      $i->childs->add('params')->value = base64_encode($e->params);
    }

    $in = $this->send($out);
    if ($in) {
      
      $diff = $in->stamp - $out->stamp;
      
      $type = $in->find('type');
      if (($type) && (isset($type->value))) {
        
        switch ($type->value) {
          case R_TYPE_SUCCESS: {
            $data = $in->find('data');
            if ($data) {
              foreach ($data->childs as $d) {
                if ($d->name='item') {
                  foreach ($emails as &$e) {
                    if ($d->attributes['mailing_id']==$e->mailing_id) {
                      
                      $e->remote_id = (trim($d->attributes['remote_id'])!='')?trim($d->attributes['remote_id']):null;
                      
                      $transferred = (trim($d->attributes['transferred'])!='')?trim($d->attributes['transferred']):null;
                      if ($transferred) {
                        $t = strtotime($transferred);
                        $e->transferred = date(DB_DATE_TIME_FMT,$t - $diff);
                      }
                      
                      $sent = (trim($d->attributes['sent'])!='')?trim($d->attributes['sent']):null;
                      if ($sent) {
                        $t = strtotime($sent);
                        $e->sent = date(DB_DATE_TIME_FMT,$t - $diff);
                      }
                      
                      $result = $d->childs->find('result');
                      if ($result) {
                        $result = base64_decode($result->value);
                        $e->result = (trim($result)!='')?$result:null;
                      }
                    }
                  }
                }
              }
              $ret = true;
            }
            break;
          }
          case R_TYPE_FAILED: 
          case R_TYPE_INTERNAL_ERROR: {
            $e = $in->find('error');
            if ($e) {
              $result = base64_decode($e->value);
              $result = (trim($result)!='')?$result:null;
            }
            break;
          }
        }
      }
    }
    return $ret;
  }
  
}

?>