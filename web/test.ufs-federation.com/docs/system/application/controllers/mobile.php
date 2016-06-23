<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define ('WORK_OR_TEST',false); // !!!!

if (WORK_OR_TEST) {
  set_include_path(':/var/www/ufs-federation.com/docs/system:'.get_include_path());
} else {  
  set_include_path(':/mnt/www/html/website/ufs-federation.com/docs/system:'.get_include_path());
}

require_once $GLOBALS['application_folder'].'/libraries/controller.php';
require_once $GLOBALS['application_folder'].'/libraries/provider.php';
require_once $GLOBALS['application_folder'].'/libraries/utils.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/system/libraries/simple_html_dom.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/system/libraries/class.html2text.inc';

define ('DB_DATE_TIME_FMT','Y-m-d H:i:s');
define ('ROOT_PATH','mobile');
define ('DEFAULT_OUT_TYPE','json');
define ('MAX_COUNT',PHP_INT_MAX);
define ('MAX_CACHE_SIZE',1024*1024*3);

define ('ERROR_TRAN_NOT_FOUND','Transaction is not found.');
define ('ERROR_MENU_NOT_FOUND','Menu is not found.');
define ('ERROR_PROMOTION_NOT_FOUND','Promotion is not found.');
define ('ERROR_LACK_OF_PARAMS','Lack of parameters.');
define ('ERROR_DB_ERROR','Database error.');
define ('ERROR_EXPIRED','Expired.');
define ('ERROR_LOCKED','Locked.');

define ('CODE_ERROR_LOCKED',100);
define ('CODE_ERROR_EXPIRED',101);

define ('HEADER_404','HTTP/1.1 404 Not Found');

define ('CONTENT_TYPE_JPG','image/jpg');
define ('CONTENT_TYPE_PNG','image/png');
define ('CONTENT_TYPE_GIF','image/gif');
define ('CONTENT_TYPE_PDF','application/pdf');
define ('CONTENT_TYPE_XLS','application/xsl'); 


class Mobile extends ControllerEx {
  
  private $provider = null;
  private $content_types = array('jpg'=>CONTENT_TYPE_JPG,
                                 'jpeg'=>CONTENT_TYPE_JPG,
                                 'png'=>CONTENT_TYPE_PNG,
                                 'gif'=>CONTENT_TYPE_GIF,
                                 'pdf'=>CONTENT_TYPE_PDF);
  
  public function __construct() {
    
    parent::__construct();
    
      if (WORK_OR_TEST) {
        $this->provider = new Provider(new MySql('localhost','ufs-federation.com','www','bN4FAWQZrRSJNuNU','utf8'));
      } else {
        $this->provider = new Provider(new MySql('localhost','ufs-federation.com','root','1qsc2wdv3efb','utf8'));
      }
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
    $out->data->result = new stdClass();
    $out->data->error = new stdClass();
    $out->data->error->code = '';
    $out->data->error->message = '';
    return $out;
  }
  
  private function get_protocol($location) {
    
    $ret = false;
    if (isset($location)) {
      $a = explode(':',$location);
      $l = sizeOf($a);
      if ($l>1) {
        $ret = $a[0];
      }
    }
    return $ret;
  }
  
  private function get_server_protocol() {
    
    $ret = false;
    $protocol = isset($_SERVER['SERVER_PROTOCOL'])?$_SERVER['SERVER_PROTOCOL']:false;
    if ($protocol) {
      list($protocol) = explode('/',$protocol);
      $ret = strtolower($protocol);
    }
    return $ret;
  }
  
  private function get_host() {
    
    return isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:false;
  }
  
  private function get_server_path() {
    
    $ret = false;
    $protocol = $this->get_server_protocol();
    $host = $this->get_host();
    if ($protocol && $host) {
      $ret = sprintf('%s://%s',$protocol,$host);
    }
    return $ret;
  }
  
  private function is_test() {
    
    $ret = false;
    $l = sizeOf($this->uri->segments);
    if ($l>1) {
      $last = $this->uri->segments[$l];
      if ($last=='test') {
        $ret = true;
      }
    }
    return $ret;
  }
  
  private function is_work() {
    return !$this->is_test();
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
  
  private function reply_error($message,$code=null) {
    
    $ret = false;
    if (isset($message)) {
      $out = $this->make_out();
      $out->data->result = array();
      $out->data->error->message = $message;
      if (isset($code)) {
        $out->data->error->code = $code;
      } else {
        $out->data->error->code = '';
      }
      $data = false;
      $ret = $this->reply($out,DEFAULT_OUT_TYPE,$data);
    }
    return $ret;
  }
  
  private function reply_not_found() {
    header(HEADER_404);  
  }
  
  private function get_lang() {
    
    $ret = false;
    $host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:false;
    if ($host) {
      $arr = explode('.',$host);
      $l = sizeof($arr);
      if ($l>0) {
        $lang = $arr[0];
        if (trim($lang)!='') {
          $ret = $this->provider->select('lang',null,array('lang'=>$lang),null,true);
        }
      }
    }
    return $ret; 
  }
  
  private function get_device_type_id() {
    
    $ret = null;
    $params = $this->get_params(array('manufacturer'=>'madeBy','model'=>'deviceModel','os','id'));
    if ($params && isset($params->manufacturer)) {
      
      //$type = $this->provider->select('device_types',null,$params,null,true);
      $type = $this->provider->select('device_types',null,array('id'=>$params->id),null,true);
      if (!$type) {
        $r = $this->provider->insert('device_types',$params);
        if ($r) {
          $type = $this->provider->select('device_types',null,$params,null,true);
        }
      }
      if ($type) {
        $ret = $type->device_type_id;
      }
    }
    return $ret;
  }
  
  private function get_relative_path() {
  
    $ret = null;
    $l = sizeOf($this->uri->segments);
    if ($l>0) {
      $a = $this->uri->segments;
      if ($a[1]==ROOT_PATH) {
        unset($a[1]);
      }
      $ret = '/'.implode('/',$a);
    }
    return $ret;
  }
  
  private function tran_begin($make_session=false) {
    
    $ret = false;
    $stamp = microtime(true);
    $session = false;
    
    $msid = $this->get_value('token');
    if (!isset($msid) && $make_session) {
      
      $lang = $this->get_lang();
      if ($lang) {
        $s = new stdClass();
        $s->mobile_session_id = $this->provider->unique_id();
        $s->expired = date(DB_DATE_TIME_FMT,strtotime('now + 1 days'));
        $s->lang_id = $lang->id;
        $s->device_type_id = $this->get_device_type_id();
        $s->screen_size = $this->get_value('screenSize');
        if (trim($s->screen_size)!='') {
          if (preg_match('/(\d+)\D+(\d+)/',$s->screen_size,$matches)) {
            if (sizeOf($matches)==3) {
              $s->screen_height = (trim($matches[1])!='')?trim($matches[1]):null;
              $s->screen_width = (trim($matches[2])!='')?trim($matches[2]):null;
            }
          }
        }
        $s->ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;

        $r = $this->provider->insert('mobile_sessions',$s);
        if ($r) {
          $msid = $s->mobile_session_id;
        }
      }
    }
    
    if (isset($msid)) {
      
      $session = $this->provider->select('mobile_sessions',null,array('mobile_session_id'=>$msid),null,true);
      if ($session) {
        $ret = new stdClass();
        $ret->session = $session;
        $ret->lang = $this->provider->select('lang',null,array('id'=>$session->lang_id),null,true);
        if (!is_null($session->locked)) {
          $ret->error = new StdClass();
          $ret->error->code = CODE_ERROR_LOCKED;
          $ret->error->message = (!is_null($session->lock_reason))?$session->lock_reason:ERROR_LOCKED;
        } elseif (!is_null($session->expired) && (strtotime($session->expired)<=strtotime('now'))) {
          $ret->error = new StdClass();
          $ret->error->code = CODE_ERROR_EXPIRED;
          $ret->error->message = ERROR_EXPIRED;
        }
      } 
    }
    
    if ($ret) {
      $c = new stdClass();
      $c->mobile_comm_id = $this->provider->unique_id();
      $c->mobile_session_id = $ret->session->mobile_session_id;
      $in_data = $this->read();
      if ($in_data) {
        $c->in_data = $in_data;
      } else {
        $arr = parse_url($_SERVER['REQUEST_URI']);
        $c->in_data = $arr['query'];
      }
      $c->path = $this->get_relative_path();
      $c->method = $_SERVER['REQUEST_METHOD'];
      
      $r = $this->provider->insert('mobile_comms',$c);
      if ($r) {
        $comm = $this->provider->select('mobile_comms',null,array('mobile_comm_id'=>$c->mobile_comm_id),null,true);
        if ($comm) {
          $ret->comm = $comm;
          $ret->begin = $stamp;
        }
      }
    }
    return $ret;
  }
  
  private function tran_end($tran) {
    
    $ret = false;
    if (isset($tran) && isset($tran->comm)) {
      
      $c = new StdClass();
      $c->finished = date(DB_DATE_TIME_FMT);
      if ($tran->begin) {
        $c->duration = microtime(true) - $tran->begin;
      }
      $c->out_data = $tran->comm->out_data;
      
      $r = $this->provider->update('mobile_comms',$c,array('mobile_comm_id'=>$tran->comm->mobile_comm_id));
      if ($r) {
        $ret = true;
      }
    }
    return $ret;
  }
  
  private function tran_reply($tran=false,$out) {
    
    $ret = false;
    $data = false;
    $r = $this->reply($out,DEFAULT_OUT_TYPE,$data);
    if ($r && isset($tran) && is_object($tran) && isset($tran->comm)) {
      $tran->comm->out_data = ($data)?$data:null;
      $ret = true;
    }
    return $ret;
  }

  private function tran_reply_error($tran=false,$message,$code=null) {
    
    $ret = false;
    if (isset($message)) {
      $out = $this->make_out();
      $out->data->result = array();
      $out->data->error->message = $message;
      if (isset($code) && is_int($code) && ($code!=0)) {
        $out->data->error->code = $code;
      } else {
        $out->data->error->code = '';
      }
      $ret = $this->tran_reply($tran,$out);
    }
    return $ret;
  }
  
  private function tran_reply_exception($tran=false,$exception) {
    
    $ret = false;
    if (isset($exception) && ($exception instanceof Exception)) {
      $ret = $this->tran_reply_error($tran,$exception->getMessage(),$exception->getCode());
    }
    return $ret;
  }
  
  private function view_test($params=array()) {
  
    $i = get_trace_info(2);
    if ($i) { 
      $data['func'] = $i->function;
      $data['params'] = $params;
      $this->load->view('mobile_test_view',$data);
    }
  }
  
  public function index() {
    $this->reply_not_found();
  }
  
  private function get_file_from_table($location) {
    
    $ret = false;
    $a = explode('/',$location);
    $l = sizeOf($a);
    if ($l>1) {
      $type = $a[2];
      $next1 = isset($a[3])?$a[3]:null;
      $next2 = isset($a[4])?$a[4]:null;
      switch ($type) {
        case 'menu': {
          if (isset($next1) && isset($next2)) {
            $menu = $this->provider->select('mobile_menu',null,array('mobile_menu_id'=>$next1),null,true);
            if ($menu) {
              $ret = $menu->{'image_'.$next2};
            }
          }
          break;
        }
        case 'activities': {
          if (isset($next1)) {
            $activity = $this->provider->select('mobile_activities',null,array('mobile_activity_id'=>$next1),null,true);
            if ($activity) {
              $ret = $activity->image;
            }
          }
          break;
        }
        case 'product_types': {
          if (isset($next1)) {
            $product_type = $this->provider->select('promotion_product_types',null,array('promotion_product_type_id'=>$next1),null,true);
            if ($product_type) {
              $ret = $product_type->image;
            }
          }
          break;
        }
      }
    }
    return $ret;
  }
  
  private function get_cache($cache_id) {
    
    $ret = false;
    if (isset($cache_id)) {
      $r = $this->provider->select('cache',null,array('cache_id'=>$cache_id),null,true);
      if ($r) {
        if (isset($r->expired)) {
          if (strtotime($r->expired)<=strtotime('now')) {
            $ret = $r;
          }
        } else {
          $ret = $r;
        }
      }
    }
    return $ret;
  }
  
  private function set_cache($data,$expired=null) {
    
    $ret = false;
    if (isset($data)) {
      $cache_id = strtoupper(md5($data));
      $r = $this->provider->select('cache',array('expired'),array('cache_id'=>$cache_id),null,true);
      if ($r) {
        $ret = $cache_id;
        if (isset($expired)) {
          if (isset($r->expired) && strtotime($r->expired)>=strtotime($expired)) {
            $expired = $r->expired;
          }
          $this->provider->update('cache',array('expired'=>$expired),array('cache_id'=>$cache_id));
        }
      } else {
        $r = $this->provider->insert('cache',array('cache_id'=>$cache_id,'data'=>$data,'expired'=>$expired));
        if ($r) {
          $ret = $cache_id;
        }
      }
    }
    return $ret;
  }
  
  private function prepare_location($location) {
    
    $ret = $location;
    $protocol = $this->get_protocol($location);
    if (!$protocol) {
      if (!file_exists($location)) {
        $path = $this->get_server_path();
        if ($path) {
          $ret = $path.$location;
        }
      }
    }
    return $ret;
  }
  
  private function load_file_from_location($location) {
    
    $ret = false;
    $r = $this->get_file_from_table($location);
    if (!$r) {
      $location = $this->prepare_location($location);
      $ret = $this->read($location);
    } else {
      $ret = $r;
    }
    return $ret;
  }
  
  public function files() {
    
    $exists = false;
    $a = $this->uri->segments;
    $l = sizeOf($a);
    if ($l>1) {
      $a = explode('.',$a[$l]);
      $mobile_file_id = isset($a[0])?$a[0]:null;
      $file = $this->provider->select('mobile_files',null,array('mobile_file_id'=>$mobile_file_id),null,true);
      if ($file) {
        $r = false;
        $cache = $this->get_cache($file->cache_id);
        if ($cache) {
          $r = $cache->data;
        } else {
          $r = $this->load_file_from_location($file->location);
          if ($r && sizeOf($r)<=MAX_CACHE_SIZE) {
            $cache_id = $this->set_cache($r);
            if ($cache_id) {
              $this->provider->update('mobile_files',array('cache_id'=>$cache_id),array('mobile_file_id'=>$mobile_file_id));
            }
          }
        }
        if ($r) {
          if (isset($file->content_type)) {
            header('Content-Type: '.$file->content_type);
          }
          if (isset($file->name)) {
            $name = $file->name.(isset($file->extension)?'.'.$file->extension:'');
            header(sprintf('Content-Disposition: attachment; filename="%s"',$name));
            header('Content-Transfer-Encoding: binary');
          }
          header('Content-Length: '.strlen($r));
          echo $r;
          $exists = true;
        }
      }
    }
    if (!$exists) {
      $this->reply_not_found();
    }
  }
  
  public function auth() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin(true);
      if ($tran) {
        try {
          
          //$this->provider->insert('test_del',array('id'=>1,'name'=>'sdfsdf'));
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            $out->data->result->token = $tran->session->mobile_session_id;
            $out->data->result->expired = sprintf('%d',strtotime($tran->session->expired));
          }
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  private function get_parent_menu($menu,$id) {
    
    $ret = null;
    foreach ($menu as $m) {
      if ($m->id==$id) {
        $ret = &$m;
        break;
      }
      if (!isset($ret) && isset($m->subcategories)) {
        $ret = $this->get_parent_menu($m->subcategories,$id);
        if (isset($ret)) {
          break;
        }
      }
    }
    return $ret;
  }
  
  private function get_location_size($location) {
  
    $ret = @filesize($location);
    if (!$ret) {
      $location = $this->prepare_location($location);
      $ch = @curl_init($location);
      if ($ch) {
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
          if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
            $ret = (float)$matches[1];
          }
        }
      }
    }
    return $ret;
  }
  
  private function get_cache_id_by_location($location,&$size) {
  
    $ret = null;
    $size = $this->get_location_size($location);
    if (!$size || ($size && ($size<=MAX_CACHE_SIZE))) {
      $r =  $this->load_file_from_location($location);
      if ($r && sizeOf($r)<=MAX_CACHE_SIZE) {
        $ret = $this->set_cache($r);
        $size = strlen($r);
      }
    }
    return $ret;
  }
  
  private function get_file_url($tran,$location,$name=null,$extension=null,$content_type=null,&$file_size=false,$prev_delete=false) {
    
    $ret = '';
    if (isset($tran) && isset($tran->session)) {
      
      $loc = isset($location)?$location:null;
      
      if ($prev_delete) {
        $this->provider->delete('mobile_files',array('mobile_session_id'=>$tran->session->mobile_session_id,'location'=>$loc));
      }
      
      $r = false;
      
      $f = $this->provider->select('mobile_files',null,array('mobile_session_id'=>$tran->session->mobile_session_id,'location'=>$loc),null,true);
      if (!$f) {
      
        $f = new stdClass();
        $f->mobile_file_id = $this->provider->unique_id();
        $f->mobile_session_id = $tran->session->mobile_session_id;
        $f->mobile_comm_id = $tran->comm->mobile_comm_id;
        $f->cache_id = $this->get_cache_id_by_location($location,$file_size);
        $f->name = isset($name)?$name:null;
        $f->extension = isset($extension)?$extension:null;
        $f->location = $loc;
        $f->content_type = isset($content_type)?$content_type:null;
        $f->size = ($file_size)?$file_size:0;

        $r = $this->provider->insert('mobile_files',$f);
        
      } else {
        $file_size = $f->size;
        $r = $this->provider->update('mobile_files',array('mobile_comm_id'=>$tran->comm->mobile_comm_id),array('mobile_file_id'=>$f->mobile_file_id));
      }
      
      if ($r) {
        
        //$ret = $f->mobile_file_id.(isset($f->extension)?'.'.$f->extension:'');
        $ret = $f->mobile_file_id;
        $ret = '/files/'.$ret;
      }
      
    }
    return $ret;
  }
  
  private function get_content_type($ext) {
    
    return isset($this->content_types[$ext])?$this->content_types[$ext]:null;
  }
  
  public function getCategories() {
    
    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
        
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $account_id = (isset($tran->session->account_id)?'='.$this->provider->quote($tran->session->account_id):' is null');
            $sql = sprintf('select mm.*, unix_timestamp(date_add(current_timestamp, interval 30 day)) as expired
                              from mobile_menu_rights mmr
                              join mobile_menu mm on mm.mobile_menu_id=mmr.mobile_menu_id
                             where mm.lang_id=%d
                               and mmr.account_id%s
                             order by mm.level, mm.priority',
                           $tran->session->lang_id,$account_id);
            
            $menu = $this->provider->query($sql);
            if ($menu) {
              
              $new = array();
              
              foreach ($menu as $m) {
                
                $item = new stdClass();
                $item->id = $m->mobile_menu_id;
                $item->title = $m->name;
                $item->type = $m->menu_type;
                $item->expired = sprintf('%s',$m->expired);
                if ($m->level>0) {

                  $fsize = false;
                  $item->imgURL = '';
                  if (trim($m->image_default)!='') { 
                    $item->imgURL = $this->get_file_url($tran,'/images/menu/'.$m->mobile_menu_id.'/default',null,'png',$this->get_content_type('png'),$fsize);
                  }
                  $item->h_imgURL = '';
                  if (trim($m->image_highlight)!='') {
                    $item->h_imgURL = $this->get_file_url($tran,'/images/menu/'.$m->mobile_menu_id.'/highlight',null,'png',$this->get_content_type('png'),$fsize);
                  }
                }
        
                if ($item->type==1) {

                  $item->allNewsCount = $this->provider->first_value(str_replace(array('$SQL','$LANG','$MOBILE_MENU_ID','$PARENT_ID'),
                                                                                 array($m->sql_news,$this->provider->quote($tran->lang->lang),
                                                                                       $this->provider->quote($m->mobile_menu_id),$this->provider->quote($m->parent_id)),
                                                                                 $m->sql_news_all_count),null,'0');

                  $item->actualNewsCount = $this->provider->first_value(str_replace(array('$SQL','$LANG','$MOBILE_MENU_ID','$PARENT_ID'),
                                                                                    array($m->sql_news,$this->provider->quote($tran->lang->lang),
                                                                                          $this->provider->quote($m->mobile_menu_id),$this->provider->quote($m->parent_id)),
                                                                                    $m->sql_news_actual_count),null,'0');
                }
                if ($item->type==2) {
                  
                  $sql2 = str_replace('$LANG',$this->provider->quote($tran->lang->lang),$m->sql_activities);
                  $sql2 = sprintf('select count(*) from (%s) t',$sql2);
                  
                  $item->allActivityCount = $this->provider->first_value($sql2,null,'0');
                }
                
                
                $parent = $this->get_parent_menu($new,$m->parent_id);
                
                if (isset($parent)) {
                  $parent->subcategories[] = $item;
                } else {
                  $new[] = $item;
                }
              }
              
              $out->data->result = $new;
              
            } else {
              throw new Exception(ERROR_MENU_NOT_FOUND);
            }
          }
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }

  private function get_text_from_html($default) {
  
    $ret = $default;
    if (trim($ret)!='') {
      $html = new html2text($ret);
      if ($html) {
        $ret = trim($html->get_text());
      }
    } else {
      $ret = null;
    }
    return $ret;
  }
  
  private function get_file_ext($location) {
    
    $ret = null;
    if (trim($location)!='') {
      $ret = pathinfo($location,PATHINFO_EXTENSION);
      if (trim($ret)=='') {
        $ret = null;
      }
    }
    return $ret;
  }

  private function normalize_str($str) {
    
    return str_replace('\"','',$str);
  }
  
  private function normalize_location($location) {
    
    $ret = $this->normalize_str($location);
    return filter_var($ret,FILTER_SANITIZE_URL);
  }
  
  private function get_images_from_html($tran,$htmls) {
    
    $ret = null;
    if (isset($htmls) && ((is_array($htmls) && sizeOf($htmls)>0) || is_string($htmls))) {
      
      $items = array();
      if (is_array($htmls)) {
        $items = array_merge($items,$htmls);
      } else {
        $items[] = $htmls;
      }
      $images = array();
      
      foreach ($items as $h) {
        
        $html = str_get_html($h);
        if ($html) {
          
          foreach($html->find('img') as $img) {
            
            $location = $this->normalize_location(trim($img->src));
            if (trim($location)!='') {
              
              $image = new stdClass();
              $image->name = $this->normalize_str($img->alt);
              $ext = $this->get_file_ext($location);
              $fsize = false;
              $image->url = $this->get_file_url($tran,$location,null,$ext,$this->get_content_type($ext),$fsize);
              $image->extension = $ext;
              $image->size = ($fsize)?sprintf('%s',$fsize):'0';
              $images[] = $image;
            }
          }
        }
      }
      $ret = $images;
    }
    return $ret;
  }
  
  private function get_files_by_sql($tran,$sql_files,$id) {
    
    $ret = array();
    if (trim($sql_files)!='') {

      $sql = str_replace(array('$ID','$LANG'),
                         array($this->provider->quote($id),
                               $this->provider->quote($tran->lang->lang)),
                         $sql_files);
      
      $r = $this->provider->query($sql);
      if ($r) {
        
        foreach ($r as $f) {
          
          $name = isset($f->name)?$f->name:null;
          $file = isset($f->file)?$f->file:null;
          
          if (isset($name) && isset($file)) {
          
            $location = $_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$file;
            if (file_exists($location)) {
              $ext = $this->get_file_ext($location);

              $file = new stdClass();
              $file->name = $name;
              $fsize = false;
              $file->url = $this->get_file_url($tran,$location,$name,$ext,$this->get_content_type($ext),$fsize);
              $file->extension = $ext;
              $file->size = ($fsize)?sprintf('%s',$fsize):'0';
              $ret[] = $file;
            }
          }
        }
      }
    }
    return $ret;
  }
  
  private function get_links_by_sql($tran,$sql_links,$id) {

    $ret = array();
    if (trim($sql_links)!='') {

      $sql = str_replace(array('$ID','$LANG'),
                         array($this->provider->quote($id),
                               $this->provider->quote($tran->lang->lang)),
                         $sql_links);
      
      $r = $this->provider->query($sql);
      if ($r) {
        
        foreach ($r as $l) {
          
          $id = isset($l->id)?$l->id:null;
          $name = isset($l->name)?$l->name:null;
          $date = isset($l->date)?$l->date:null;
          $mobile_menu_id = (isset($l->mobile_menu_id) && (trim($l->mobile_menu_id!='')))?$l->mobile_menu_id:null;
          $parent_id = (isset($l->parent_id) && trim($l->parent_id)!='')?$l->parent_id:null;
          
          if (isset($id) && isset($name) && isset($date)) {
          
            $link = new stdClass();
            $link->id = $id;
            $link->name = $name; 
            $link->date = strtotime($date);
            
            $categoryID = $parent_id;
            $subcategoryID = $mobile_menu_id;
            if (!isset($categoryID)) {
              $categoryID = $subcategoryID;
              $subcategoryID = '';
            }
            $link->categoryID = $categoryID;
            $link->subcategoryID = $subcategoryID;
            
            $ret[] = $link;
          }
        }
      }
    }
    return $ret;
  }
  
  private function get_news_by_sql($tran,$sql_news,$sql_files,$sql_links,$begin,$end,$count,$id=null) {
    
    $ret = false;
    if (trim($sql_news)!='') {
      
      $rep = str_replace(array('$LANG','$BEGIN','$END'),
                         array($this->provider->quote($tran->lang->lang),
                               $this->provider->quote($begin),
                               $this->provider->quote($end)),
                         $sql_news);
      
      $sql = sprintf('select t.* 
                        from (%s) t 
                       where t.date>=%s 
                         and t.date<=%s
                         and t.id%s
                       order by t.date desc 
                       limit 0, %d',
                     $rep,
                     $this->provider->quote($begin),
                     $this->provider->quote($end),
                     (isset($id)?'='.$this->provider->quote($id):' is not null'),
                     $count);
      
      $r = $this->provider->query($sql);
      if ($r) {
        
        $new = array();
        foreach ($r as $n) {

          $id = isset($n->id)?$n->id:null;
          $actual = isset($n->actual)?$n->actual:null;
          $title = $this->get_text_from_html(isset($n->title)?$n->title:null);
          $text = $this->get_text_from_html(isset($n->text)?$n->text:null);
          $date = isset($n->date)?$n->date:null;
          $expired = isset($n->expired)?$n->expired:null;
          $mobile_menu_id = (isset($n->mobile_menu_id) && (trim($n->mobile_menu_id!='')))?$n->mobile_menu_id:null;
          $parent_id = (isset($n->parent_id) && trim($n->parent_id)!='')?$n->parent_id:null;
          
          if (isset($id) && isset($actual) && isset($date) && isset($mobile_menu_id)) {
            
            $item = new stdClass();
            $item->id = $id;
            $item->actual = $actual;
            $item->title = $title;
            $item->text = $text;
            $item->date = isset($date)?sprintf('%s',strtotime($date)):'';
            
            $categoryID = $parent_id;
            $subcategoryID = $mobile_menu_id;
            if (!isset($categoryID)) {
               $categoryID = $subcategoryID;
               $subcategoryID = '';
            }
            $item->categoryID = $categoryID;
            $item->subcategoryID = $subcategoryID;
            
            $item->expired = isset($expired)?sprintf('%s',strtotime($expired)):'';
            $item->imageUrls = $this->get_images_from_html($tran,array($n->title,$n->text));
            
            $s = null;
            if (isset($sql_files)) {
              if (is_array($sql_files)) {
                $s = isset($sql_files[$mobile_menu_id])?$sql_files[$mobile_menu_id]:null;
              } else {
                $s = $sql_files;
              }
            }
            $item->fileUrls = $this->get_files_by_sql($tran,$s,$id); 
            
            $s = null;
            if (isset($sql_links)) {
              if (is_array($sql_links)) {
                $s = isset($sql_links[$mobile_menu_id])?$sql_links[$mobile_menu_id]:null;
              } else {
                $s = $sql_links;
              }
            }
            $item->relatedLinks = $this->get_links_by_sql($tran,$s,$id);
            
            $new[] = $item;
          }
        }
        $ret = $new;
      }
    }
    return $ret;
  }
  
  public function getNews() {
    
    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('parent_id'=>'categoryID','mobile_menu_id'=>'subcategoryID','id'=>'newsID',
                                              'end'=>'timestamp','begin'=>'limitDateTime','count'=>'offset'));
            
            if ($params) {

              $params->begin = isset($params->begin)?date(DB_DATE_TIME_FMT,$params->begin):'1971-01-01 00:00:00'; 
              $params->end = isset($params->end)?date(DB_DATE_TIME_FMT,$params->end):date(DB_DATE_TIME_FMT);
              $params->count = (isset($params->count) && ($params->count!=''))?$params->count:MAX_COUNT;
              $params->id = (isset($params->id) && ($params->id!=''))?$params->id:null;
              
              $news = array();
              
              $sql = '';
              $parent_exists = isset($params->parent_id) && ($params->parent_id!='');
              $account_id = (isset($tran->session->account_id)?'='.$this->provider->quote($tran->session->account_id):' is null');
              
              if (isset($params->mobile_menu_id) && ($params->mobile_menu_id!='')) {
                
                $sql = sprintf('select m1.mobile_menu_id, m1.parent_id, m1.sql_news, m1.sql_files, m1.sql_links
                                  from mobile_menu m1
                                  left join mobile_menu m2 on m2.mobile_menu_id=m1.parent_id
                                 where m1.sql_news is not null
                                   and m1.mobile_menu_id in (select mobile_menu_id from mobile_menu_rights where account_id%s)
                                   and trim(m1.sql_news)<>""%s 
                                   and m1.mobile_menu_id=%s',
                               $account_id,
                               ($parent_exists)?' and m1.parent_id='.$this->provider->quote($params->parent_id):'',
                               $this->provider->quote($params->mobile_menu_id));
              } else {
                $parent_id_str = ($parent_exists)?'='.$this->provider->quote($params->parent_id):' is null';
                $mobile_menu_id_str = $parent_id_str;
                
                $sql = sprintf('select m1.mobile_menu_id, m1.parent_id, m1.sql_news, m1.sql_files, m1.sql_links
                                  from mobile_menu m1
                                  left join mobile_menu m2 on m2.mobile_menu_id=m1.parent_id
                                 where m1.sql_news is not null
                                   and m1.mobile_menu_id in (select mobile_menu_id from mobile_menu_rights where account_id%s)
                                   and trim(m1.sql_news)<>""
                                   and (m1.parent_id%s or m1.mobile_menu_id%s)',
                               $account_id,$parent_id_str,$mobile_menu_id_str);
              }
              
              $r = $this->provider->query($sql);
              if ($r) {
                
                $sql_news = '';
                $t = array();
                $sql_files = array();
                $sql_links = array();
                
                foreach ($r as $m) {
                  $t[] = str_replace(array('$MOBILE_MENU_ID','$PARENT_ID'),
                                     array($this->provider->quote($m->mobile_menu_id),
                                           $this->provider->quote($m->parent_id)),
                                     $m->sql_news);
                  $sql_files[$m->mobile_menu_id] = $m->sql_files;
                  $sql_links[$m->mobile_menu_id] = $m->sql_links;
                }
                
                $sql_news.= implode("\n".'union all'."\n",$t);
                
                $r = $this->get_news_by_sql($tran,$sql_news,$sql_files,$sql_links,
                                            $params->begin,$params->end,$params->count,$params->id);
                if ($r) {
                  $news = $r;
                }
              }
              
              $out->data->result = $news;
              
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  private function get_dates_of_news_by_sql($tran,$sql_news) {
    
    $ret = false;
    if (trim($sql_news)!='') {
      
      $rep = str_replace('$LANG',$this->provider->quote($tran->lang->lang),$sql_news);
      
      $sql = sprintf('select cast(t.date as date) as date, count(*) as cnt
                        from (%s) t 
                       group by 1 
                       order by t.date desc',
                     $rep);
      
      $r = $this->provider->query($sql);
      if ($r) {
        
        $dates = array();
        foreach ($r as $d) {

          $date = isset($d->date)?$d->date:null;
          if (isset($date)) {
            
            $dates[] = strtotime($date);
          }
        }
        $ret = $dates;
      }
    }
    return $ret;
  }
  
  public function getDatesOfNews() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('parent_id'=>'categoryID','mobile_menu_id'=>'subcategoryID'));
            if ($params) {

              $dates = array();
              
              $sql = '';
              $parent_exists = isset($params->parent_id) && ($params->parent_id!='');
              
              if (isset($params->mobile_menu_id) && ($params->mobile_menu_id!='')) {
                
                $sql = sprintf('select m1.mobile_menu_id, m1.parent_id, m1.sql_news
                                  from mobile_menu m1
                                  left join mobile_menu m2 on m2.mobile_menu_id=m1.parent_id
                                 where m1.sql_news is not null
                                   and trim(m1.sql_news)<>""%s 
                                   and m1.mobile_menu_id=%s',
                               ($parent_exists)?' and m1.parent_id='.$this->provider->quote($params->parent_id):'',
                               $this->provider->quote($params->mobile_menu_id));
              } else {
                $parent_id_str = ($parent_exists)?'='.$this->provider->quote($params->parent_id):' is null';
                $mobile_menu_id_str = $parent_id_str;
                
                $sql = sprintf('select m1.mobile_menu_id, m1.parent_id, m1.sql_news
                                  from mobile_menu m1
                                  left join mobile_menu m2 on m2.mobile_menu_id=m1.parent_id
                                 where m1.sql_news is not null
                                   and trim(m1.sql_news)<>""
                                   and (m1.parent_id%s or m1.mobile_menu_id%s)',
                               $parent_id_str,$mobile_menu_id_str);
              }
              
              $r = $this->provider->query($sql);
              if ($r) {
                
                $sql_news = '';
                $t = array();
                
                foreach ($r as $m) {
                  $t[] = str_replace(array('$MOBILE_MENU_ID','$PARENT_ID'),
                                     array($this->provider->quote($m->mobile_menu_id),
                                           $this->provider->quote($m->parent_id)),
                                     $m->sql_news);
                }
                
                $sql_news.= implode("\n".'union all'."\n",$t);
                
                $dates = $this->get_dates_of_news_by_sql($tran,$sql_news);
              }
              
              $out->data->result = $dates;
              
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  public function getTableView() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('mobile_menu_id'=>'subcategoryID'));
            if ($params) {

              $tables = array();
              $sql = sprintf('select mt.name, mt.description, mt.columns, mt.alignments, mt.sql
                                from mobile_menu_tables mmt
                                join mobile_tables mt on mt.mobile_table_id=mmt.mobile_table_id
                               where mmt.mobile_menu_id=%s
                                 and mt.sql is not null
                                 and trim(mt.sql)<>""
                               order by mmt.priority',
                             $this->provider->quote($params->mobile_menu_id));
              
              $r = $this->provider->query($sql);
              if ($r) {
                
                foreach ($r as $t) {
                  
                  $sql = str_replace('$LANG',$this->provider->quote($tran->lang->lang),$t->sql);
                  $r1 = $this->provider->query($sql);
                  if ($r1) {
                    
                    $table = new stdClass();
                    $table->name = $t->name;
                    $table->about = isset($t->description)?$t->description:'';
                    $table->expired = sprintf('%s',strtotime(date(DB_DATE_TIME_FMT,strtotime('now + 1 days'))));
                    
                    $columns = '';
                    if (trim($t->columns)!='') {
                      $columns = explode(',',$t->columns);
                    }
                    $table->columns = ($columns!='')?$columns:false;
                    if (!$table->columns) {
                      $columns = array();
                    }
                    $need_columns = is_array($columns) && sizeOf($columns)==0;
                    
                    $alignments = array();
                    if (trim($t->alignments)!='') {
                      $alignments = explode(',',$t->alignments);
                    }
                    $table->alignments = $alignments;
                    
                    $table->values = array();
                            
                    $avoids = array('url');
                    
                    foreach ($r1 as $t1) {
                    
                      $value = array();
                      
                      foreach ($t1 as $k=>$v) {
        
                        if ($need_columns) {
                          if (!in_array($k,$columns) && !in_array($k,$avoids)) {
                            $columns[] = $k;
                          }
                        }
                        
                        if (!in_array(strtolower($k),$avoids)) {
                          $value[] = isset($v)?$v:'';
                        }
                      }
                      $table->values[] = $value;
                    }
                    
                    if (!$table->columns) {
                      $table->columns = $columns;
                    }
        
                    $links = array();
                    $path = $this->get_server_path();
                    
                    foreach ($r1 as $t1) {
                      
                      $exists = false;
                      foreach ($t1 as $k=>$v) {
                        
                        if (!$exists && (strtolower($k)=='url')) {
                          
                          $v = isset($v)?$v:'';
                          $v = str_replace('$PATH',$path,$v);
                          $links[] = $v;
                          $exists = true;
                        }
                      }
                      
                      if (!$exists) {
                        $links[] = '';
                      }
                    }
                    $table->buyUrls = $links;
                            
                    $tables[] = $table;
                  }
                }
              }

              $v = $this->provider->select('mobile_menu',array('description'),array('mobile_menu_id'=>$params->mobile_menu_id),null,true);
              $out->data->result->subcategoryID = $params->mobile_menu_id;
              $out->data->result->about = ($v)?$v:'';
              $out->data->result->tables = $tables;
              
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  private function get_contact($text) {
   
    $ret = false;
    if (trim($text)!='') {
      $temp = explode(':',$text,2);
      if (sizeof($temp)>0) {
        $ret = new stdClass();
        if (sizeof($temp)>1) {
          $ret->title = trim($temp[0]);
          $ret->value = trim($temp[1]);
        } else {
          $ret->title = '';
          $ret->value = trim($temp[0]);
        }
      }
    }
    return $ret;
  }
  
  public function getBranches() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $branches = array();

            $r = $this->provider->select('branches',null,array('lang_id'=>$tran->session->lang_id,'locked'=>null),array('priority'));
            if ($r) {

              foreach ($r as $b) {
                
                $branch = new stdClass();
                $branch->id = $b->branch_id; 
                //$branch->id = strtoupper(md5($b->branch_id));
                $branch->name = isset($b->name)?$b->name:'';
                $branch->region = isset($b->region)?$b->region:'';
                $branch->city = isset($b->city)?$b->city:'';
                $branch->address = isset($b->address)?$b->address:'';
                $branch->latitude = isset($b->latitude)?$b->latitude:'';
                $branch->longitude = isset($b->longitude)?$b->longitude:'';
                $branch->expired = sprintf('%s',strtotime(date(DB_DATE_TIME_FMT,strtotime('now + 1 months'))));
                  
                $contacts = array();

                $c = $this->get_contact($b->contact1); if ($c) { $contacts[] = $c; }
                $c = $this->get_contact($b->contact2); if ($c) { $contacts[] = $c; }
                $c = $this->get_contact($b->contact3); if ($c) { $contacts[] = $c; }
                $c = $this->get_contact($b->contact4); if ($c) { $contacts[] = $c; }
                $c = $this->get_contact($b->contact5); if ($c) { $contacts[] = $c; }
                $c = $this->get_contact($b->contact6); if ($c) { $contacts[] = $c; }

                $branch->contacts = $contacts;
                $branches[] = $branch;
              }
            }

            $out->data->result = $branches;
          }
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  private function get_parent_group($groups,$id) {
    
    $ret = null;
    foreach ($groups as $g) {
      if ($g->id==$id) {
        $ret = &$g;
        break;
      }
      if (!isset($ret) && isset($g->items)) {
        $ret = $this->get_parent_group($g->items,$id);
        if (isset($ret)) {
          break;
        }
      }
    }
    return $ret;
  }
  
  public function getGroups() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('mobile_menu_id'=>'subcategoryID'));
            if ($params) {
             
              $groups = array();

              $sql = sprintf('select mobile_menu_id, sql_groups 
                                from mobile_menu
                               where mobile_menu_id=%s
                                 and sql_groups is not null
                                 and trim(sql_groups)<>""',
                             $this->provider->quote($params->mobile_menu_id));
              
              $r = $this->provider->query($sql,true);
              if ($r) {
                
                $sql = str_replace('$LANG',$this->provider->quote($tran->lang->lang),$r->sql_groups);
                $r1 = $this->provider->query($sql);
                if ($r1) {
                  
                  foreach ($r1 as $g) {
                    
                    $id = isset($g->id)?$g->id:null;
                    $name = isset($g->name)?$g->name:null;
                    
                    if (isset($id) && (trim($name)!='')) {
                      
                      $item = new stdClass();
                      $item->id = $id;
                      $item->name = $name;
                      
                      $parent = $this->get_parent_group($groups,$g->parent_id);
                      
                      if (isset($parent)) {
                        $item->date = isset($g->date)?strtotime($g->date):'';
                        $item->new = isset($g->new)?$g->new:'0';
                        $item->type = isset($g->type)?$g->type:'0';
                        $item->linkID = isset($g->link_id)?$g->link_id:'';
                        $item->actual = isset($g->actual)?$g->actual:'0';
                        $parent->items[] = $item; 
                      } else {
                        $item->items = array();
                        $groups[] = $item;
                      }
                    }
                  }
                }
              }
              
              $out->data->result = $groups;
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  private function replace_image_links($tran,$html) {
  
    $ret = $html;
    $html = str_get_html($ret);
    if ($html) {

      foreach($html->find('img') as $img) {

        $location = trim($img->src);
        if (trim($location)!='') {

          $ext = $this->get_file_ext($location);
          $fsize = false;
          $img->src = $this->get_file_url($tran,$location,$img->alt,$ext,$this->get_content_type($ext),$fsize);
        }
      }
      $ret = $html->save();
    }
    return $ret;
  }
  
  public function getActivities() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('mobile_menu_id'=>'categoryID'));
            if ($params) {
             
              $activities = array();

              $sql = sprintf('select mobile_menu_id, sql_activities 
                                from mobile_menu
                               where mobile_menu_id=%s
                                 and sql_activities is not null
                                 and trim(sql_activities)<>""',
                             $this->provider->quote($params->mobile_menu_id));
              
              $r = $this->provider->query($sql,true);
              if ($r) {
                
                $sql = str_replace('$LANG',$this->provider->quote($tran->lang->lang),$r->sql_activities);
                $r1 = $this->provider->query($sql);
                if ($r1) {
                  
                  foreach ($r1 as $a) {
                    
                    $id = isset($a->id)?$a->id:null;
                    $name = isset($a->name)?$a->name:null;
                    $image_ext = isset($a->image_ext)?$a->image_ext:null;
                    $html = isset($a->html)?$a->html:null;
                    $screen_width = isset($a->screen_width)?$a->screen_width:null;
                    $widths = array();
                    if (preg_match_all('~(\d+)~',$screen_width,$matches)) {
                      $widths = $matches[0];
                    }
                    
                    //if (isset($id) && (trim($html)!='') && (sizeOf($widths)==0 || (sizeOf($widths)>0 && in_array($tran->session->screen_width,$widths)))) {
                    if (isset($id)) {
                      
                      $item = new stdClass();
                      $item->id = $id;
                      $item->name = isset($name)?$name:'';
                      $item->expired = sprintf('%s',strtotime(date(DB_DATE_TIME_FMT,strtotime('now + 1 months'))));
                      $fsize = false;
                      $item->mainImg = $this->get_file_url($tran,'/images/activities/'.$id.'/image',null,$image_ext,$this->get_content_type($image_ext),$fsize);
                      $item->url = isset($a->url)?$a->url:'';
                      //$item->text = $this->replace_image_links($tran,$html);
                      $item->text = $html;
                      
                      $activities[] = $item;
                    }
                  }
                }
              }
              
              $out->data->result = $activities;
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  function getHtml() {
    
    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('parent_id'=>'categoryID','mobile_menu_id'=>'subcategoryID'));
            if ($params) {
           
              $htmls = array();

              $sql = '';
              $parent_exists = isset($params->parent_id) && ($params->parent_id!='');
              $account_id = (isset($tran->session->account_id)?'='.$this->provider->quote($tran->session->account_id):' is null');
              
              if (isset($params->mobile_menu_id) && ($params->mobile_menu_id!='')) {
                
                $sql = sprintf('select m1.mobile_menu_id, m1.parent_id, m1.html
                                  from mobile_menu m1
                                  left join mobile_menu m2 on m2.mobile_menu_id=m1.parent_id
                                 where m1.html is not null
                                   and m1.mobile_menu_id in (select mobile_menu_id from mobile_menu_rights where account_id%s)
                                   and trim(m1.html)<>""%s 
                                   and m1.mobile_menu_id=%s',
                               $account_id,
                               ($parent_exists)?' and m1.parent_id='.$this->provider->quote($params->parent_id):'',
                               $this->provider->quote($params->mobile_menu_id));
              } else {
                $parent_id_str = ($parent_exists)?'='.$this->provider->quote($params->parent_id):' is null';
                $mobile_menu_id_str = $parent_id_str;
                
                $sql = sprintf('select m1.mobile_menu_id, m1.parent_id, m1.html
                                  from mobile_menu m1
                                  left join mobile_menu m2 on m2.mobile_menu_id=m1.parent_id
                                 where m1.html is not null
                                   and m1.mobile_menu_id in (select mobile_menu_id from mobile_menu_rights where account_id%s)
                                   and trim(m1.html)<>""
                                   and (m1.parent_id%s or m1.mobile_menu_id%s)',
                               $account_id,$parent_id_str,$mobile_menu_id_str);
              }
              
              $r = $this->provider->query($sql);
              if ($r) {

                foreach ($r as $m) {

                  $item = new stdClass();
                  
                  if (is_null($m->parent_id)) {
                    $item->categoryID = $m->mobile_menu_id;
                    $item->subcategoryID = '';
                  } else {
                    $item->categoryID = $m->parent_id;
                    $item->subcategoryID = $m->mobile_menu_id;
                  }
                  $item->html = $m->html;
                  
                  $htmls[] = $item;
                }
              }
              
              $out->data->result = $htmls;
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  private function get_promotion_company($app_link) {
    
    $ret = false;
    
    if ($app_link) {
      
      $sql = sprintf('select *
                        from promotion_companies 
                       where lower(app_link)=lower("%s")
                         and locked is null',
                     $app_link);
      
      $r = $this->provider->query($sql);
      if ($r) {
        
        foreach($r as $company) {
          
          $ret = $company;
          break;
        }
      }
    }
    return $ret;
  }
  
  private function get_promotion_products($tran,$device_type_id,$app_link,&$company) {
  
    $ret = false;
    
    if ($device_type_id) {
      
      $company = $this->get_promotion_company($app_link);
      if ($company) {

        $sql = sprintf('select t.*
                          from (select pp.promotion_product_id, pp.begin, pp.end, pp.locked, pp.timeout,
                                       ppt.promotion_product_type_id, ppt.name, ppt.description, ppt.agreement,
                                       (case when length(ppt.image) is null then 0 else length(ppt.image) end) as image_length,
                                       ppt.image_ext,
                                       (case 
                                          when p2.status is null
                                            then case 
                                                   when pp.locked is null 
                                                     then case 
                                                            when ((pp.begin is null) or (pp.begin is not null and pp.begin<=current_timestamp)) and
                                                                 ((pp.end is null) or (pp.end is not null and pp.end>=current_timestamp))
                                                              then "started"
                                                            when (pp.begin is not null and pp.begin>=current_timestamp)
                                                              then "prepared"
                                                            when (pp.end is not null and pp.end<=current_timestamp)
                                                              then "finished"
                                                            else "finished" end  
                                                   else "disabled" end
                                          else p2.status end) as status,
                                       p2.promotion_id, p2.created, p2.expired, p2.accepted, p2.rejected,
                                       (case when p1.cnt is null then 0 else p1.cnt end) as promotion_count,
                                       pp.priority, current_timestamp as stamp
                                  from promotion_products pp
                                  join promotion_product_types ppt on ppt.promotion_product_type_id=pp.promotion_product_type_id
                                  left join (select promotion_product_id, max(promotion_id) as last_promotion_id, count(*) as cnt
                                               from promotions
                                              where device_type_id=%s
                                              group by 1) p1 on p1.promotion_product_id=pp.promotion_product_id
                                  left join (select promotion_id, created, expired, accepted, rejected,
                                                    (case 
                                                       when (accepted is null)
                                                          then case 
                                                                when (rejected is null)
                                                                  then case 
                                                                         when (expired is null) or (expired is not null and expired>=current_timestamp)
                                                                           then null
                                                                         else "expired" end  
                                                                else "rejected" end  
                                                       else "accepted" end) as status
                                               from promotions) p2 on p2.promotion_id=p1.last_promotion_id
                                 where pp.promotion_company_id=%s) t
                         where t.status<>"disabled"                      
                         order by t.priority',
                       $device_type_id,$company->promotion_company_id);
      
        $r = $this->provider->query($sql);
        if ($r) {

          $products = array();
          
          foreach($r as $product) {
          
            if (($product->promotion_count==0) && ($product->status=='started')) {
            
              $p = new stdClass();
              $p->promotion_product_id = $product->promotion_product_id;
              $p->device_type_id = $device_type_id;
              $p->created = $this->provider->current_timestamp();
              if (!is_null($product->timeout)) {
                
                $t = strtotime($p->created);
                $t = strtotime(sprintf('+%s seconds',$product->timeout),$t); 
                $p->expired = date(DB_DATE_TIME_FMT,$t);
              } else {
                $p->expired = null;
              }
                      
              $f = $this->provider->insert('promotions',$p);
              if ($f) {
                $product->promotion_id = (String)$this->provider->last_insert_id();
                $product->created = $p->created;
                $product->expired = $p->expired;
                $product->promotion_count = 1;
              }
            }
            
            $p = new stdClass();
            $p->name = $product->name;
            $p->description = $product->description;
            $p->agreement = $product->agreement;
            $p->imageURL = '';
            if ($product->image_length>0) {
              $fsize = false;
              $url = '/images/product_types/'.$product->promotion_product_type_id.'/image';
              $p->imageURL = $this->get_file_url($tran,$url,null,$product->image_ext,$this->get_content_type($product->image_ext),$fsize);
            }
            $p->promotionID = $product->promotion_id;
            $p->status = $product->status;
            
            $p->countdown = null;
            if (!is_null($product->expired)) {
              
              $d1 = strtotime($product->stamp);
              $d2 = strtotime($product->expired);
              
              $d3 = null;
              if (!is_null($product->accepted)) {
                $d3 = strtotime($product->accepted);
              } elseif (!is_null($product->rejected)) {
                $d3 = strtotime($product->rejected);
              }
              if (!is_null($d3)) {
                //
              } else {
                $p->countdown = $d2-$d1;
              }
            }
            $products[] = $p;
          }
          $ret = $products;
        }
      }
    }
    return $ret;
  }
  
  public function qrcode() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('text'));
            if ($params) {
              
              $kind = 'message';
              
              if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i',$params->text)) {
                
                
                $company = null;
                $products = $this->get_promotion_products($tran,$tran->session->device_type_id,$params->text,$company);
                if ($products) {
                  
                  $kind = 'promotion';
                  $out->data->result->promotion->title = $company->name;
                  $out->data->result->promotion->products = $products;
                  
                } else {
                  
                  $kind = 'redirection';
                  $out->data->result->redirection->url = $params->text;
                }
              } else {
                $out->data->result->message->text = 'Unknown QR-code.';
              }
              
              $out->data->result->kind = $kind;

            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
  public function promotion() {

    if ($this->is_work()) {
      
      $tran = $this->tran_begin();
      if ($tran) {
        try {
          
          $out = $this->make_out();
          if (isset($tran->error)) {
            $out->data->error = $tran->error;
          } else {
            
            $params = $this->get_params(array('promotion_id'=>'promotionID','accepted'));
            if ($params) {
              
              $sql = sprintf('select pc.name as company_name, ppt.name as type_name
                                from promotions p
                                join promotion_products pp on pp.promotion_product_id=p.promotion_product_id
                                join promotion_product_types ppt on ppt.promotion_product_type_id=pp.promotion_product_type_id
                                join promotion_companies pc on pc.promotion_company_id=pp.promotion_company_id
                               where p.promotion_id=%s
                                 and p.accepted is null
                                 and p.rejected is null
                                 and (p.expired is null or (p.expired is not null and p.expired>=current_timestamp))',
                             $params->promotion_id);
              
              $r = $this->provider->query($sql,true);
              if ($r) {
                
                $accepted_values = array('1','yes','ok','true');
                $stamp = $this->provider->current_timestamp();

                $p = new stdClass();
                $p->accepted = in_array($params->accepted,$accepted_values)?$stamp:null;
                $p->rejected = in_array($params->accepted,$accepted_values)?null:$stamp;

                $this->provider->update('promotions',$p,array('promotion_id'=>$params->promotion_id));
                
                $out->data->result->status = !is_null($p->accepted)?'accepted':'rejected';
                
                $out->data->result->publisher = sprintf('%s [%s. %s]',$out->data->result->status,$r->company_name,$r->type_name);
                
                
              } else {
                throw new Exception(ERROR_PROMOTION_NOT_FOUND);
              }
              
            } else {
              throw new Exception(ERROR_LACK_OF_PARAMS);
            }
          }
          
          $this->tran_reply($tran,$out);
          
        } catch (Exception $e) {
          $this->tran_reply_exception($tran,$e);
        }
        $this->tran_end($tran);
      } else {
        $this->reply_error(ERROR_TRAN_NOT_FOUND);
      }
    } else {
      $this->view_test();
    }
  }
  
}
?>