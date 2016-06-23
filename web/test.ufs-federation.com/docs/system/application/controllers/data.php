<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(':/var/www/ufs-federation.com/docs/system:'.get_include_path());

require_once $GLOBALS['application_folder'].'/libraries/controller.php';
require_once $GLOBALS['application_folder'].'/libraries/provider.php';
require_once $GLOBALS['application_folder'].'/libraries/utils.php';

define ('HEADER_404','HTTP/1.1 404 Not Found');
define ('DEFAULT_OUT_TYPE','json');

define ('ERROR_LACK_OF_PARAMS','Lack of parameters');
define ('ERROR_DB_ERROR','Database error');

class Data extends ControllerEx {

  private $provider = null;
  
  public function __construct() {
    
    parent::__construct();
    
      $this->provider = new Provider(new MySql('localhost','ufs-federation.com','www','bN4FAWQZrRSJNuNU','utf8'));
  }
  
  public function __destruct() {
    
    if (isset($this->provider)) {
      unset($this->provider);
    }
    parent::__destruct();
  }
  
  private function make_out() {
    
    $out = new stdClass();
    $out->data = new stdClass();
    $out->data->error = '';
    return $out;
  }
  
  private function get_value($name='') {
   
    $ret = isset($_REQUEST[$name])?$_REQUEST[$name]:null;
    if (isset($ret)) {
      if ($ret=='') {
        $ret = null;
      } elseif ((string)(int)$ret==$ret) {
        $ret = intval($ret);
      }
    }
    return $ret;
  }
  
  private function get_params($list=array()) {
    
    $ret = false;
    if (sizeOf($list)>0) {
      $new = new stdClass();  
      $counter = 0;
      foreach($list as $k=>$n) {
        
        $v = $this->get_value($n);
        if (is_int($k) && (intval($k)==$counter)) {
          $k = $n;
          $counter++;
        }
        $new->{$k} = $v;
      }
      if (sizeof($new)>0) {
        $ret = $new;
      }
    }
    return $ret;
  }
  
  private function param_exists($name) {
    return isset($_REQUEST[$name]);    
  }
  
  private function read($location='php://input') {
    
    $ret = false;
    $r = @file_get_contents($location);
    if ($r!='') {
      $ret = $r;
    }
    return $ret;
  }
  
  private function write($data) {
    
    $ret = false;
    if ($data) {
      outv($data);
      $ret = true;
    }
    return $ret;
  }
  
  private function reply($out,$type=DEFAULT_OUT_TYPE,&$data) {
    
    if (isset($out) && isset($out->data) && (is_array($out->data) || (is_object($out->data)))) {
      switch($type) {
        case 'json': {
          header('Content-type: application/json');
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
    return $this->write($data);
  }
  
  private function error($message) {
    
    $ret = false;
    if (isset($message)) {
      $out = $this->make_out();
      $out->data->error = $message;
      $data = false;
      $ret = $this->reply($out,DEFAULT_OUT_TYPE,$data);
    }
    return $ret;
  }
  
   private function exception($exception) {
    
    $ret = false;
    if (isset($exception) && ($exception instanceof Exception)) {
      $ret = $this->error($exception->getMessage());
    }
    return $ret;
  }
  
  private function success($out) {
    
    return $this->reply($out,DEFAULT_OUT_TYPE,$data);
  }
  
  private function not_found() {
    header(HEADER_404);  
  }
  
  public function index() {
    $this->not_found();
  }
  
  public function subscription() {
    
    try {
      $out = $this->make_out();
      
      $params = $this->get_params(array('lang'));
      if ($params) {
        
        $count = $this->param_exists('count')?true:false;
        $lang = isset($params->lang)?$params->lang:'ru';
        
        $sql = sprintf('select contact, name
                          from (select email as contact, name, mailing_section_id, count(*) as sub_count
                                  from mailing_subscriptions
                                 where started is not null
                                   and finished is null
                                   and lang = %s
                                 group by 1,2,3) t
                          where t.sub_count=1
                          group by 1,2
                          order by 1',
                       "'".$lang."'"); 
        if ($count) {
          
          $sql = sprintf('select count(*) from (%s) t',$sql);
          $out->data->count = $this->provider->first_value($sql,null,0);
          
        } else {
          
          $out->data->count = 0;
          $subscriptions = $this->provider->query($sql);
          if ($subscriptions) {
            $out->data->count = sizeof($subscriptions);
            $out->data->recipients = $subscriptions;
            foreach ($out->data->recipients as &$r) {
              if (is_null($r->name)) {
                unset($r->name);
              }
            }
          }
        }
        
        $this->success($out);
        
      } else throw new Exception(ERROR_LACK_OF_PARAMS);
            
    } catch (Exception $e) {
      $this->exception($e);
    }
  }
 
}

?> 