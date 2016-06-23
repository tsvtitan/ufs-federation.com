<?php

require_once 'log.php';
require_once 'utils.php';

class Mysql
{
  private $connection;
  private $database;
  private $log;
  private $current_timestamp_ident = '';

  public function __construct($log, $host, $user_name, $password, $database) {

    $this->connection = mysql_connect($host, $user_name, $password);
    if (! $this->connection)
      throw new Exception("Could not connect to $host");
      
    $this->database = mysql_select_db($database);
    if (! $this->database) {
      throw new Exception("Could not connect to $database");
    } else {
      $this->current_timestamp_ident = 'CURRENT_TIMESTAMP_'.random_string(32);
    }  
    
    $this->log = $log;
  }

  public function __destruct() {

    unset($this->database);
    mysql_close($this->connection);
    unset($this->connection);
  }
   
  public function currentTimestamp() {
    
    return $this->current_timestamp_ident;
  }
  
  public function quote($s) {
    
    $ret = mysql_real_escape_string($s,$this->connection);
    $ret = "'".$ret."'";
    return $ret;
  }
  
  public function query($sql) {
    
    $ret = false;
    $this->log->writeInfo(sprintf('[Mysql->query] %s',$sql));
    
    $ret = mysql_query($sql,$this->connection);
    if (!$ret) {
      $this->log->writeError(sprintf('[Mysql->query] %s',$this->lastError()));
    } else {
      $info = $this->lastInfo();
      if (trim($info)!='') {
        $this->log->writeInfo(sprintf('[Mysql->query] %s',$info));
      } else {
//        $this->log->writeInfo(sprintf('[Mysql->query] %s records',sizeOf($ret)));
      }
    }
    return $ret;
  }
  
  public function getRecords($sql) {

    $ret = false;

    $r = $this->query($sql);
    
    if ($r) {
      
      $ret = array();
      
      while ($row = mysql_fetch_assoc($r)) {
        $ret[] = $row;
      }
      
      mysql_free_result($r);
    }  
    
    return $ret;
  }
  
  public function getRecord($sql, $index=0) {
    
    $d = $this->getRecords($sql);
    
    if (is_array($d) && sizeOf($d)>$index) {
      $d = $d[$index];
    }
    
    return $d;
  }

  public function insertRecord($table, $data) {
    
    $names = '';
    $values = '';
    
    foreach ($data as $d => $v) {
      $names[] = "`".$d."`";
      if (isset($v)) {
        if (is_string($v) && $v==$this->current_timestamp_ident) {
          $v = 'CURRENT_TIMESTAMP';
        } else {
          switch ($v) {
            default: $v = $this->quote($v);
          }       
        }
      } else 
        $v = 'NULL';  
      $values[] = $v;  
    }
    
    $sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)',$table,implode(',',$names),implode(',',$values));
    
    return $this->query($sql);

  }
  
  public function updateRecord($table, $data, $where) {
    
    $sets = '';
    
    foreach ($data as $d => $v) {
      $n = "`".$d."`";
      if (isset($v)) {
        if (is_string($v) && $v==$this->current_timestamp_ident) {
          $v = 'CURRENT_TIMESTAMP';
        } else {
          switch ($v) {
            default: $v = $this->quote($v);  
          }  
        }
      } else
        $v = 'NULL';
      $sets[] = sprintf('%s=%s',$n,$v);
    }
    
    $ws = '';
    $wheres ='';
    
    foreach ($where as $w => $v) {
      $n = "`".$w."`";
      if (isset($v)) {
        if (is_string($v) && $v==$this->current_timestamp_ident) {
          $v = 'CURRENT_TIMESTAMP';
        } else {
          switch ($v) {
            default: $v = $this->quote($v);  
          }
        }
      } else
        $v = 'NULL';
      $wheres[] = sprintf('%s=%s',$n,$v);
    }
    
    if (is_array($wheres))
      $ws = sprintf(' WHERE %s',implode(' AND ',$wheres));
    
    
    $sql = sprintf('UPDATE `%s` SET %s%s',$table,implode(',',$sets),$ws);
    
    return $this->query($sql);
    
  }
  
  public function setCharset($charset) {
  
    return $this->query('set names '.$charset);
  }
    
  public function lastId() {
    
    return mysql_insert_id($this->connection);
  }
  
  public function lastError() {
    
    return mysql_error($this->connection);
  }
  
  public function lastInfo() {
    
    return mysql_info($this->connection);
  }
  
  public function getUniqueId() {

    $ret = $this->getRecord('SELECT UPPER(CAST(MD5(RAND()) AS CHAR(32))) AS ID');
    
    if (is_array($ret) && sizeOf($ret)>0) {
      $ret = $ret['ID'];
    }
    return $ret;
  }
  
}
?>
