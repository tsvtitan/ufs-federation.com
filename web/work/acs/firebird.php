<?php

require_once 'log.php';

class Firebird {
	
	private $connection;
	private $log;

	public function __construct($log, $database, $user_name, $password) {
	
		$this->connection = @ibase_connect($database, $user_name, $password);
		if (! $this->connection)
			throw new Exception("Could not connect to $database");
		
		$this->log = $log;		
	}
	
	public function __destruct() {
		
	   @ibase_close($this->connection);
	   unset($this->connection);	
	}
	
	public function getRecords($query) {
		
		$ret = false;
		
		$this->log->writeInfo(sprintf('[Firebird->getRecords] %s',$query));
		
		$r = @ibase_query($this->connection, $query);
		
		if ($r) {
			
			while ($row = @ibase_fetch_assoc($r)) {
				$ret[] = $row;
			}
			
			@ibase_free_result($r);
			
		} else
			$this->log->writeError(sprintf('[Firebird->getRecords] Error: %s',$this->lastError()));
		
		return $ret;		
	}
	
	public function lastError() {
	
		return @ibase_errmsg();
	}
	
}


?>