<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function out($s) {
  echo ($s);
}

function get_controller() {

  $ret = false;
  $obj = isset($GLOBALS['CONTROLLER_REF'])?$GLOBALS['CONTROLLER_REF']:null;
  if (isset($obj) && ($obj instanceof Controller)) {
    $ret = $obj;
  }
  return $ret;
}

function getv($s,$l='',$r='') {

  $ret = $s;
  $ingores = array('');
  $controller = get_controller();
  if ($controller) {
    //$vars = $controller->load->getVars();
    $vars = array();
    foreach ($vars as $n=>$v) {
      if (!in_array($n,$ingores)) {
        if (!is_object($v)) {
          $ret = str_replace($l.$n.$r,$v,$ret);
        }  
      }
    }
  }
  return $ret;
}

function outv($s,$l='',$r='') {
  
  out(getv($s,$l,$r));
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

function get_trace_info($index=0) {
  
  $ret = false;
  $x = debug_backtrace();
  if ($x && sizeOf($x)>$index) {
    $ret = new stdClass();
    $ret->class = isset($x[$index]['class'])?$x[$index]['class']:null;
    $ret->type = isset($x[$index]['type'])?$x[$index]['type']:null;
    $ret->function = isset($x[$index]['function'])?$x[$index]['function']:null;
    $ret->line = isset($x[$index]['line'])?$x[$index]['line']:null;
    $ret->file = isset($x[$index]['file'])?$x[$index]['file']:null;
  }
  return $ret;
}

function log_write($message,$type='debug',$index=0,$echo=false,$html=false) {

  $ret = false;
  if (isset($message) && $message) {
    $i = get_trace_info($index);
    if ($i) {
      $s = sprintf('[%s%s%s] %s',$i->class,$i->type,$i->function,$message);
      log_message($type,$s);
      if ($echo) {
        if ($html) {
          outv($s.'<br>');
        } else {
          outv($s."\n");
        }
      }
      $ret = true;
    }
  }
  return $ret;
}

function copy_props($from,$to=null,$props=array()) {

  $ret = false;
  if (isset($from) && $from instanceof stdClass) {

    if (!isset($to) || !($to instanceof stdClass)) {
      $to = new stdClass();
    }
    if (isset($props) && is_array($props)) {

      foreach($from as $fk=>$fv) {

        $name = isset($props[$fk])?$props[$fk]:$fk;
        $to->{$name} = $fv;

      }
      $ret = $to;
    }
  }
  return $ret;
}

function is_integer_ex($s) {
  return preg_match("/^[0-9]+$/",$s);
}

?>