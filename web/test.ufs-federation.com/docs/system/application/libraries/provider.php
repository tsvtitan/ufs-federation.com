<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once $GLOBALS['application_folder'].'/libraries/logger.php';

abstract class ProviderLink {
  
  protected $database = null;
  protected $user = null;
  protected $password = null;
  
  public function __construct($database,$user,$password) {
    
    $this->database = isset($database)?$database:$this->database;
    $this->user = isset($user)?$user:$this->user;
    $this->password = isset($password)?$password:$this->password;
  }
  
  public function __destruct() {
    
    $this->disconnect();
  }
  
  public function description() {
    
    return sprintf('%s (%s)',get_class($this),$this->database);
  }
  
  abstract protected function get_unique_id_sql($size);
  abstract protected function get_current_timestamp_sql();
  
  abstract protected function connect();
  abstract protected function disconnect();
  abstract protected function connected();
  abstract protected function escape($sql);
  abstract protected function query($sql,$execute=false);
  abstract protected function last_error();
  abstract protected function last_insert_id();
  
  
}

class MySql extends ProviderLink {
  
  protected $host = 'localhost';
  protected $charset = 'utf8';
  protected $connection;
  protected $db;
  
  public function __construct($host,$database,$user,$password,$charset) {
    
    parent::__construct($database,$user,$password);
    
    $this->host = isset($host)?$host:$this->host;
    $this->charset = isset($charset)?$charset:$this->charset;
    
  }
  
  public function get_unique_id_sql($size) {
    
    return sprintf('select upper(cast(md5(uuid()) as char(%d)))',$size);
  }
  
  public function get_current_timestamp_sql() {
    
    return 'select current_timestamp';
  }
  
  public function description() {
    
    return sprintf('%s (%s, %s)',get_class($this),$this->host,$this->database);
  }
  
  public function connect() {
    
    $ret = $this->connected();
    if (!$ret) {
      $this->connection = @mysql_connect($this->host,$this->user,$this->password);
      if ($this->connection) {
        $this->db = @mysql_select_db($this->database);
        if ($this->db) {
          @mysql_query('set names '.$this->charset,$this->connection);
        } else {
          $this->disconnect();
        }
      }
    }
    return $ret;
  }
  
  public function disconnect() {

    if ($this->connected()) {
      if (isset($this->db)) {
        unset($this->db);
      }
      if (isset($this->connection)) {
        @mysql_close($this->connection);
        unset($this->connection);
      }
    }
  }

  public function connected() {
    
    return (isset($this->db) && isset($this->connection));
  }
  
  public function query($sql,$execute=false) {

    $ret = false;
    if (isset($this->connection) && isset($sql)) {
      
      $r = @mysql_query($sql,$this->connection);
      if ($r) {
        
        if ($execute) {
          
          $ret = $r;
        } else {

          while ($row = @mysql_fetch_assoc($r)) {
            $ret[] = $row;
          }
          @mysql_free_result($r);
        }
      }
    }
    return $ret;
  }
  
  public function escape($s) {
    
    $ret = false;
    if (isset($this->connection) && isset($s)) {
      $ret = @mysql_real_escape_string($s,$this->connection);
    }
    return $ret;
  }
  
  public function last_error() {
    
    $ret = false;
    if (isset($this->connection)) {
      $ret = @mysql_error($this->connection);
      if ($ret=='') {
        $ret = false;
      }
    }
    return $ret;
  }
  
  public function last_insert_id() {
    
    $ret = false;
    if (isset($this->connection)) {
      $ret = @mysql_insert_id($this->connection);
      if ($ret=='') {
        $ret = false;
      }
    }
    return $ret;
  }
  
}

class Firebird extends ProviderLink {
  
  public function get_unique_id_sql($size) {
    return false;
  }
  
  public function get_current_timestamp_sql() {
    return false;
  }
  public function connect() {
    return false;
  }
  
  public function disconnect() {
  }
  
  public function connected() {
    return false;
  }
  
  public function query($sql,$execute=false) {
    return false;
  }
  
  public function escape($s) {
    return false;
  }
  
  public function last_error() {
    return false;
  }

  public function last_insert_id() {
    return false;
  }
 
 }

class Provider extends Logger {
  
  private $link;
  
  public function __construct($link) {
    
    parent::__construct();
    
    if (isset($link) && ($link instanceof ProviderLink)) {
      
      $link->connect();
      if (!$link->connected()) {
        
        $this->log('Cound not connect to '.$link->description());
      } else {
        
        $this->link = $link;
      }
      
    } else {
      $this->log('Link is invalid.');
    }
  }
  
  public function __destruct() {
    
    if (isset($this->link)) {
      unset($this->link);
    }
    parent::__destruct();
  }
  
  private function connected() {
    
    $ret = false;
    if (isset($this->link)) {
      $ret = $this->link->connected();
    }
    return $ret;
  }
  
  private function get_where($where) {
    
    $ret = '';
    $w = array();

    if (isset($where) && ((is_array($where) && sizeOf($where)>0) || is_object($where))) {
      
      foreach($where as $k=>$v) {
        $o = '=';
        $s = 'is null';
        if (!is_null($v)) {
          if (is_string($v)) {
            $s = $this->quote($v);
          } else {
            $s = $v;
          }  
        } else {
          $o = ' ';
        }
        $w[] = $k.$o.$s;
      }
    }
    if (sizeOf($w)>0) {
      $s = implode(' and ',$w);
      $ret = ' where '.$s;
    }
    return $ret;
  }
  
  private function get_order($orders) {
  
    $ret = '';
  
    if (isset($orders) && is_array($orders) && sizeOf($orders)>0) {
  
      $ret = ' order by '.implode(',',$orders);
    }
    return $ret;
  }
  
  private function get_limit($from,$count) {
  
    $ret = '';
    $f = null;
    if (isset($from) && is_int($from) && $from>0) {
      $f = $from;
    }
    $c = null;
    if (isset($count) && is_int($count) && $count>0) {
      $c = $count;
    }
    if (is_null($f) && !is_null($c)) {
      $f = 0;
    }
    if (!is_null($f)) {
      $ret = ' limit '.$f.(!is_null($c)?','.$c:'');
    }
    return $ret;
  }
  
  public function quote($s) {
  
    $ret = "'".$s."'";
    if ($this->connected()) {
      $r = $this->link->escape($s);
      if ($r) {
        $ret = "'".$r."'";
      } 
    }
    return $ret;
  }
  
  public function query($sql,$first=false,$array=false) {
   
    $ret = false;
    if ($this->connected()) {
      try {
        $r = $this->link->query($sql);
        if (!$r) {
          
          $this->log($this->link->last_error());
          
        } elseif (is_array($r) && sizeOf($r)>0) {
        
          if ($array) {
            
            $ret = $r;
          } else {
            
            foreach ($r as $k=>$v) {
            
              $new = new stdClass();
              foreach ($v as $k1=>$v1) {
            
                $new->{$k1} = $v1;
              }
              $ret[] = $new;
            }
          }
          
          if ($first) {
            $ret = $ret[0];
          }
        }
      } catch (Exception $e) {
        
      }
    }
    return $ret;
  }
  
  public function first_value($sql,$name=null,$default=null) {
    
    $ret = $default;
    if (isset($sql)) {
      $r = $this->query($sql,true);
      if ($r) {
        if (isset($name)) {
          $ret = isset($r->{$name})?isset($r->{$name}):$default;
        } else {
          foreach ($r as $v) {
            $ret = $v;
            break;
          }
        }
      }
    }
    return $ret;
  }
  
  public function execute($sql) {
    
    $ret = false;
    if ($this->connected()) {
      try {
        $r = $this->link->query($sql,true);
        if ($r) {
          $ret = $r;
        } else {
          $this->log($this->link->last_error());
        }
      } catch(Exception $e) {
        
      }
    }
    return $ret;
  }
  
  public function select($table,$fields=null,$where=null,$orders=null,$first=false,$array=false,$count=null,$from=null) {
    
    $ret = false;
    if ($this->connected()) {
      
      $f = isset($fields)?implode(',',$fields):'*';
      $w = $this->get_where($where);
      $o = $this->get_order($orders);
      $l = $this->get_limit($from,$count);
      $ret = $this->query(sprintf('select %s from %s%s%s%s',$f,$table,$w,$o,$l),$first,$array);
    }
    return $ret;
  }
  
  protected function get_insert_sql($table,$data,$exclude=array()) {
  
    $ret = '';
    if (isset($data) && (is_array($data) || is_object($data))) {
      $f = array();
      $v = array();
      foreach($data as $k=>$s) {
  
        if (!in_array($k,$exclude)) {
          
          unset($s1);
          
          if (!is_null($s)) {
            
            if (is_string($s)) {
              $s1 = $this->quote($s); 
            } elseif(is_integer($s)) {
              $s1 = strval($s);
            } else {
              $s1 = $s;
            }
          } else {
            $s1 = 'null';
          }
          
          if (isset($s1)) {
            $f[] = $k;
            $v[] = $s1;
          }
        }
      }
      if (sizeOf($f)>0) {
        $fields = implode(',',$f);
        $values = implode(',',$v);
        $ret = sprintf('insert into %s (%s) values (%s);',$table,$fields,$values);
      }
    }
    return $ret;
  }
  
  public function insert($table,$data,$exclude=array()) {
  
    $ret = false;
    if ($this->connected()) {
      try {
        $sql = $this->get_insert_sql($table,$data,$exclude);
        if (trim($sql)!='') {
          $ret = $this->execute($sql);
        }
      } catch(Exception $e) {
        
      }
    }
    return $ret;
  }

  protected function get_update_sql($table,$data,$where,$exclude=array()) {
    
    $ret = '';
    if (isset($data) && (is_array($data) || is_object($data))) {

      $fv = array();
      foreach($data as $k=>$v) {
        
        if (!in_array($k,$exclude)) {
          $s = 'null';
          if (!is_null($v)) {
            if (is_string($v)) {
              $s = $this->quote($v); 
            } else {
              $s = $v;
            }
          }
          $fv[] = $k.'='.$s;
        }  
      }
      $field_values = '';
      if (sizeOf($fv)>0) {
        $field_values = ' set '.implode(',',$fv);
      }
      $w = $this->get_where($where);
      $ret = sprintf('update %s%s%s;',$table,$field_values,$w);
    }
    return $ret;
  }

  public function update($table,$data,$where,$exclude=array()) {
     
    $ret = false;
    if ($this->connected()) {
      try {
        $sql = $this->get_update_sql($table,$data,$where,$exclude);
        if (trim($sql)!='') {
          $ret = $this->execute($sql);
        }
      } catch(Exception $e) {
           
      }
    }
    return $ret;
  }
  
  protected function get_delete_sql($table,$where) {
  
    $ret = '';
    if (trim($table)!='') {
      $w = $this->get_where($where);
      $ret = sprintf('delete from %s%s;',$table,$w);
    }
    return $ret;
  }

  function delete($table,$where) {
    
    $ret = false;
    if ($this->connected()) {
      try {
        $sql = $this->get_delete_sql($table,$where);
        if (trim($sql)!='') {
          $ret = $this->execute($sql);
        }
      } catch(Exception $e) {

      }
    }
    return $ret;
  }

  function unique_id() {
    
    $ret = $this->first_value($this->link->get_unique_id_sql(32));
    return $ret;
  }
  
  function get_unique_id() {
    
    return $this->unique_id();
  }
  
  function last_insert_id() {
    
    return $this->link->last_insert_id();
  }
  
  function current_timestamp() {
    
    $ret = $this->first_value($this->link->get_current_timestamp_sql());
    return $ret;
  }
  
}

?>