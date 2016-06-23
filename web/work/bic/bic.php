<?php

require_once 'consts.php';
require_once 'log.php';
require_once 'mysql.php';
require_once 'utils.php';

$log = new Log (LOG_BIC,true);
if ($log) {

	$stamp = microtime(true);
	$log->writeInfo(str_repeat('-',50));
	try {

		$log->writeInfo('Connecting to Database ...');
			
	  $db = new Mysql($log,DB_HOST,DB_USER,DB_PASS,DB);
		if ($db) {

		  $log->writeInfo('Connected.');
			$db->setCharset('utf8');

			$file = FILE_BIC;
			if (file_exists($file)) {
				
				$log->writeInfo(sprintf('Loading bic file %s ...',$file));
				
				$dbf = dbase_open($file,0);
				if ($dbf) {
				
					
					$rcount = @dbase_numrecords($dbf);
					if ($rcount) {
						
						$log->writeInfo(sprintf('There are %s records.',$rcount));
						
					  for ($r=1;$r<=$rcount;$r++) {
								
						  $row = @dbase_get_record_with_names($dbf,$r);
							if ($row) {
								
								$bank = $db->getRecord(sprintf('select * from banks where vkey=%s','"'.$row['VKEY'].'"'));
								if ($bank) {

									$where['bank_id'] = $bank['bank_id'];
									
									foreach($row as $k=>$v) {
									
										$k = strtolower($k);
										$v = iconv('CP866','UTF-8',trim($v));
										$bank[$k] = (trim($v)=='')?null:$v;
									}
									
									if (!$db->updateRecord('banks',$bank,$where)) {
									
										$log->writeError(sprintf('Could not update. Error is %s',$db->lastError()));
									}
										
								} else {
									
									$bank = array();
									foreach($row as $k=>$v) {
										
										$k = strtolower($k);
										$v = iconv('CP866','UTF-8',trim($v));
										$bank[$k] = (trim($v)=='')?null:$v;
									}
									
									if (!$db->insertRecord('banks',$bank)) {
										
										$log->writeError(sprintf('Could not insert. Error is %s',$db->lastError()));
									} 
								}
							} 
						}
					}
					@dbase_close($dbf);
				} else {
					$log->writeError('Could not open bic file.');
				}
			} else {
				$log->writeError('Bic file is not found.');
			}

			unset ($db);
		}
			
	} catch (Exception $e) {
		$log->writeException($e);
	}
	$log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
	unset($log);
}

?>