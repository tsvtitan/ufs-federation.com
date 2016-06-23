<?php  

function echox($s) {
  if (isset($s)) {
    echo ($s);
  }
}

function random_string($length = 0, $chars="abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?") {
  
  $validCharacters = $chars;

  $validCharNumber = strlen($validCharacters);
  $result = "";

  for ($i = 0; $i < $length; $i++) {
    $index = mt_rand(0, $validCharNumber - 1);
    $result .= $validCharacters[$index];
  }

  return $result;
}

function random_number($length = 0) {

  return random_string($length, "0123456789");
  
}

function trim_string($s) {

  return trim(preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$s));
}

function encode_string($key,$s) {
  
  try {
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_CBC);
    $iv = str_repeat("\0",$iv_size);
    $new = strtoupper(md5($key));
    $s = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$new,$s,MCRYPT_MODE_CBC,$iv);
    return $s;
  } catch (Exception $e) {
    //
  }
}

function decode_string($key,$s) {
  
  try {
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_CBC);
    $iv = str_repeat("\0",$iv_size);
    $new = strtoupper(md5($key));
    $s = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$new,$s,MCRYPT_MODE_CBC,$iv);
    return $s;
  } catch (Exception $e) {
    //
  }
}

function get_class_method($index=0) {
  
  $x = debug_backtrace();
  $c = $x[$index]['class'];
  $t = $x[$index]['type'];
  $f = $x[$index]['function'];
  return $c.$t.$f;
}

function ip_exists($ip,$list) {
  
  require_once 'ipfilter.php';
  
  $ret = false;
  $filter = new IpFilter($list);
  if ($filter) {
    $ret = $filter->check($ip);
    unset ($filter);  
  }
  return $ret;
}

function data_to_class($data) {
   
  $ret = false;
  if (is_array($data)) {

    foreach($data as $k=>$v) {
          
      if (is_array($v)) {

        $r = new stdClass();
        foreach($v as $k1=>$v1) {
          $r->{$k1} = $v1;
        }
        $ret[] = $r;
      }
    }
  }
  return $ret;
}
 
function send_mail($name,$email,$subject,$body,$headers) {
  
  if (trim($name)!='') {
    $name = sprintf('=?utf-8?B?%s?=',base64_encode($name));
  }
  if (trim($subject)!='') {
    $subject = sprintf('=?utf-8?B?%s?=',base64_encode($subject));
  }
  $s = trim(sprintf('%s <%s>',$name,$email));

  return @mail($s,$subject,$body,$headers);
}

function get_xlsx_data($file,$maxsheets=50,$maxrows=1000,$maxcols=20) {

  require_once 'excel/simplexlsx.class.php';
  
  $ret = false;

  $obj = new SimpleXLSX($file);

  for ($sheet=1;$sheet<=$maxsheets;$sheet++) {

    $rows = $obj->rows($sheet);
    $row_count = count($rows);
    if ($row_count>$maxrows) {
      $row_count = $maxrows;
    }

    list($col_count,$rc) = $obj->dimension($sheet);
    if ($col_count>$maxcols) {
      $col_count = $maxcols;
    }

    if ($row_count>0) {

      $i = $sheet-1;
      for ($r=0;$r<=($row_count-1);$r++) {

        for ($c=0;$c<=($col_count-1);$c++) {

          $o = new stdClass();
          $o->data = $rows[$r][$c];

          $ret[$i][$r][$c] = $o;
        }
      }
    }
  }
  return $ret;
}

?>