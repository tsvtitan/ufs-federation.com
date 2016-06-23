<?php

require_once 'log.php';
require_once 'utils.php';

class Google  {
	
	private $log = null;
	
	public function __construct($log) {
	
		$this->log = $log;
	}
	
	private function log($message) {
		if (isset($this->log))
			$this->log->writeInfo($message);
	}
	
}

?>