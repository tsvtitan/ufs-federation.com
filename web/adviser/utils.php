<?php

require_once ('Service.php');

function serviceSend($contacts,$subject,$body,$sender=true,$duration=2) {
  
  $ret = false;
  $service = new service\Service();
  if ($service->connect()) {
    
    $mailing = new service\Mailing();
    
    if ($sender) {
      $mailing->setSender('mailer@lists.ufs-financial.ch','UFS Online consultant');
    }
    
    foreach ($contacts as $contact) {
      $mailing->addRecipient($contact);
    }
    
    $mailing->subject = $subject;
    $mailing->body = $body;
    $mailing->test = false; 
    $mailing->pattern = 'EmptyPattern';
    $mailing->duration = $duration;
    $mailing->delay = 0;
    
    $r = $service->sendMailing($mailing);
    $ret = ($r && !$r->error);
  }
  return $ret;
}

/*function send_email($emails,$subject,$text) {
  
  $ret = false;
  if (isset($emails) && is_array($emails) && sizeOf($emails)>0) {
    
    $r = true;
    $headers = 'Content-type: text/plain; charset=utf-8'."\r\n";
    $headers.= 'From: UFS Online consultant <callback@ufs-federation.com>';
    foreach ($emails as $email) {
      try {
        $r = $r && @mail($email,$subject,$text,$headers);
      } catch (Exception $e) {
        //
      }
    }
    $ret = $r;
  }
  return $ret; 
}*/

function send_email($emails,$subject,$text) {
  
  $ret = false;
  if (isset($emails) && is_array($emails) && sizeOf($emails)>0) {
    
    $ret = serviceSend($emails,$subject,$text);
  }
  return $ret;
}

/*function send_sms($phones,$text) {

  $ret = false;
  if (isset($phones) && is_array($phones) && sizeOf($phones)>0) {
    
    $url = 'http://service.qtelecom.ru/public/http/?user=13492.3&pass=92463019&action=post_sms&target=%s&message=%s';
    
    foreach ($phones as $phone) {
      try {
        $s = sprintf($url,$phone,urlencode($text));
        $r = @file_get_contents($s);
        if ($r) {
          //
        }
      } catch (Exception $e) {
        //
      }
    }
  }
  return $ret;
}*/

function send_sms($phones,$text) {

  $ret = false;
  if (isset($phones) && is_array($phones) && sizeOf($phones)>0) {
    
    $ret = serviceSend($phones,null,$text,false,1);
  }
  return $ret;
}
 
?>