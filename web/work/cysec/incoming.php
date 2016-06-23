<?php

require_once 'consts.php'; 
require_once 'log.php';
require_once 'utils.php';

$log = new Log (LOG_INCOMING);
if ($log) {

  $stamp = microtime(true);
  $log->writeInfo(str_repeat('-',50));	
  try {
	
    $log->writeInfo('Receiving Ins ...');
  	
    $ins = array();
    
    if (receive_ins($log,$ins)) {

      $log->writeInfo('Ins have received. Sending report ...');

      if (send_report_by_ins($log,$ins)) {

        $log->writeInfo('Report has sent.');

      } else
        $log->writeError('Could not send report. There are some errors');

    } else 
      $log->writeError('Could not receive Ins. There are some errors');
   
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}


?> 