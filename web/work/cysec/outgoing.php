<?php

require_once 'consts.php'; 
require_once 'log.php';
require_once 'utils.php';

$log = new Log (LOG_OUTGOING);

if ($log) {
	
  $stamp = microtime(true);
  $log->writeInfo(str_repeat('-',50));	
  try {
	
    $log->writeInfo('Collecting Trans  ...');
  	
    if (collect_trans($log)) {
      
      $log->writeInfo('Trans have collected. Trying to create Out ...');

      if (create_out($log)) {

        $log->writeInfo('Out has created. Trying to send Out ...');

        $out = NULL;

        if (send_out($log,$out,false)) {

          $log->writeInfo('Out has sent. Trying to send report ...');

          if (send_report_by_out($log,$out)) {

            $log->writeInfo('Report has sent');

          } else 
            $log->writeError('Could not send Report');

        } else 
          $log->writeError('Could not send Out');

      } else
        $log->writeWarn('Could not create Out.');
    	
    } else 
      $log->writeError('Could not collect Trans');
    
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>