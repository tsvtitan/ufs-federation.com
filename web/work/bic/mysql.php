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
			$this->current_timestamp_ident = random_string(32);
		}	
		
		$this->log = $log;
	}

	public function __destruct() {

		unset($this->database);
		mysql_close($this->connection);
		unset($this->connection);
	}
	
	public function current_timestamp() {
		
		return $this->current_timestamp_ident;
	}
	
	public function quoted($s) {
		$ret = mysql_real_escape_string($s,$this->connection);
		$ret = "'".$ret."'";
		return $ret;
	}
	
	public function getRecords($query) {

		$ret = false;

		$this->log->writeInfo(sprintf('[Mysql->getRecords] %s',$query));
		
		$r = mysql_query($query,$this->connection);
		
		if ($r) {
		  
		  while ($row = mysql_fetch_assoc($r)) {
			 $ret[] = $row;
		  }
		  
		  mysql_free_result($r);
		}  
		
		return $ret;
	}
	
	public function getRecord($query, $index=0) {
		
		$d = $this->getRecords($query);
		
		if (is_array($d)) {
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
		  	switch ($v) {
		  		case $this->current_timestamp_ident: $v = 'CURRENT_TIMESTAMP'; break;
		  		default: $v = $this->quoted($v);
		  	}		  	
		  } else 
		  	$v = 'NULL';  
		  $values[] = $v;	
		}
		
		$query = sprintf('INSERT INTO `%s` (%s) VALUES (%s)',$table,implode(',',$names),implode(',',$values));
		
		$this->log->writeInfo(sprintf('[Mysql->insertRecord] %s',$query));
		
		return mysql_query($query,$this->connection);

	}
	
	public function updateRecord($table, $data, $where) {
		
		$sets = '';
		
		foreach ($data as $d => $v) {
			$n = "`".$d."`";
			if (isset($v)) {
			  switch ($v) {
		  		case $this->current_timestamp_ident: $v = 'CURRENT_TIMESTAMP'; break;
		  		default: $v = $this->quoted($v);  
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
			 	switch ($v) {
		  		case $this->current_timestamp_ident: $v = 'CURRENT_TIMESTAMP'; break;
		  		default: $v = $this->quoted($v);  
		  	}
			} else
				$v = 'NULL';
			$wheres[] = sprintf('%s=%s',$n,$v);
		}
		
		if (is_array($wheres))
			$ws = sprintf(' WHERE %s',implode('AND',$wheres));
		
		
		$query = sprintf('UPDATE `%s` SET %s%s',$table,implode(',',$sets),$ws);
		
		$this->log->writeInfo(sprintf('[Mysql->updateRecord] %s',$query));
				
		return mysql_query($query,$this->connection);
		
	}
	
	public function setCharset($charset) {
	
		return mysql_query('set names '.$charset,$this->connection);
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
	
}


?>