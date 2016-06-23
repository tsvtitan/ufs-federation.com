<?php

require_once 'log.php';

class MSSql
{
	private $connection;
	private $log;

	public function __construct($log, $host, $database, $user_name, $password) {
	
		$this->connection = @mssql_connect($host, $user_name, $password);
		if (! $this->connection)
			throw new Exception("Could not connect to $host");
		
		$ret = mssql_select_db($database,$this->connection);
		if (!$ret)
			throw new Exception("Could not select $database");
		
		$this->log = $log;		
	}
	
	public function __destruct() {
		
	   @mssql_close($this->connection);
	   unset($this->connection);	
	}
	
	private function setQuery($query) {
		
		$this->log->writeInfo(sprintf('[MSSql->Query] %s',$query));
		$r = @mssql_query($query, $this->connection);
		if (!$r) 
			$this->log->writeError(sprintf('[MSSql->Query] Error: %s',mssql_get_last_message()));
	}
	
	public function setAnsiNullsOn() {
		
		$this->setQuery('SET ANSI_NULLS ON');
	}
	
	public function setAnsiWarningsOn() {
	
		$this->setQuery('SET ANSI_WARNINGS ON');
	}
	
	public function getRecords($query) {
		
		$ret='';
		
		$this->log->writeInfo(sprintf('[MSSql->getRecords] %s',$query));
		
		$r = @mssql_query($query, $this->connection);
		
		if ($r) {
			
			while ($row = @mssql_fetch_assoc($r)) {
				$ret[] = $row;
			}
			
			@mssql_free_result($r);
			
		} else
			$this->log->writeError(sprintf('[MSSql->getRecords] Error: %s',mssql_get_last_message()));
		
		return $ret;		
	}
	
	public function lastError() {
	
		return @ibase_errmsg($this->connection);
	}
	
}


?>