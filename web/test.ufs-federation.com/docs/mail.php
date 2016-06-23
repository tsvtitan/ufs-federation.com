<?php

function send_emails($emails,$subject,$body,$files=null,$from='Mailer <mailer@ufs-financial.ch>') {

  $ret = false;
  $flag = false;

  if (!is_array($emails)) {
    $emails = array($emails);
  }

  foreach ($emails as $m) {

    $random_hash = md5(date('r', time()));

    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers.='Content-Type: multipart/mixed; boundary="REPORT-mixed-'.$random_hash.'"'."\r\n";
    $headers.=sprintf('From: %s',$from);

    $message ='--REPORT-mixed-'.$random_hash."\n";
    $message.='Content-Type: multipart/alternative; boundary="REPORT-alt-'.$random_hash.'"'."\n\n";

    $message.='--REPORT-alt-'.$random_hash."\n";
    $message.='Content-Type: text/html; charset="utf-8"'."\n\n";

    $message.='<html>'."\n";
    $message.='<head>'."\n";
    $message.='<meta charset="utf-8">'."\n";
    $message.='<meta http-equiv=Content-Type content="text/html; charset=utf-8">'."\n";
    $message.='</head>'."\n";
    $message.='<body>'."\n";
    $message.=$body."\n";

    $message.='</body>'."\n";
    $message.='</html>';
    $message.="\n";
    $message.='--REPORT-alt-'.$random_hash.'--'."\n";

    if (isset($files) && is_array($files)) {

      foreach ($files as $f) {

        $attachment = chunk_split(base64_encode($f['data']));

        $message.='--REPORT-mixed-'.$random_hash."\n";
        $message.='Content-Type: text/xml; charset="utf-8" name="'.$f['name'].'"'."\n";
        $message.='Content-Disposition: attachment; filename="'.$f['name'].'"'."\n";
        $message.='Content-Transfer-Encoding: base64'."\n\n";
        $message.=$attachment."\n";
      }
    }


    $message.='--REPORT-mixed-'.$random_hash.'--'."\n";

    $to = $m;		
    $new_subject = sprintf('=?utf-8?B?%s?=',base64_encode($subject));
    $sent=mail($to,$new_subject,$message,$headers);

    if ($flag)
      $ret=$ret & $sent;
    else
      $ret=$sent;

    $flag=true;
  }

  return $ret;
}


$emails = array();
$emails[] = 'tsv@ufs-financial.ch';

$subject = 'Тема письма';
$body = '<h3>Проверка тела письма</h3>';

if (send_emails($emails,$subject,$body)) {
  echo '<h1>Mail has sent.</h1>';
} else {
  echo '<h1 style="color:red">Mail has not sent. See error log.</h1>';
}

?>