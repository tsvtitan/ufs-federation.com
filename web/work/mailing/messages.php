<?php

require_once 'init.php';
require_once 'mailing/consts.php';

require_once 'libs/log.php';
require_once 'libs/utils.php';
require_once 'libs/mysql.php';
require_once 'libs/MessageGate.php';

define ('DEBUG_LOG',true); 

$log = new Log(LOG_MESSAGES,DEBUG_LOG,true,true);
if ($log) {

  $stamp = microtime(true);

  $log->writeInfo(str_repeat('-',50));
  try {
    $log->delete = true;
    
    $log->writeInfo('Connect to Database ...');

    $db = new Mysql($log,DB_MAILING_HOST,DB_MAILING_USER,DB_MAILING_PASS,DB_MAILING_NAME);
    if ($db) {

      $log->writeInfo('Connected.');
      $db->setCharset('utf8'); 
      
      $messages = $db->getRecords('select message_id
                                     from analytics_reviews_messages
                                    where all_count>0
                                      --and (sent_count+error_count)<all_count
                                      and created>=adddate(current_timestamp, interval -1 hour)
                                      and canceled is null
                                      and interrupted is null');
      
      if (is_array($messages) && sizeOf($messages)>0) {
        
        $log->writeInfo(sprintf('Found %d messages.',sizeOf($messages)));
        
        $log->delete = false;
      
        $gate = new MessageGate();
        if ($gate->connect()) {
          
          $messageIds = array();
          
          foreach ($messages as $message) {
            $messageIds[] = $message['message_id'];
            $log->writeInfo(sprintf('Message id is %s.',$message['message_id']));
          }
         
          $r = $gate->getStatus($messageIds);
          
          if (is_array($r) && sizeOf($r)>0) {
            
            $log->writeInfo(sprintf('Found %d statuses.',sizeOf($r)));
            
            foreach ($r as $status) {
               
              $data = array('all_count'=>$status->allCount,
                            'sent_count'=>$status->sentCount,
                            'delivered_count'=>$status->deliveredCount,
                            'error_count'=>$status->errorCount);
              
              $where = array('message_id'=>$status->messageId);
              
              $db->updateRecord('analytics_reviews_messages',$data,$where);
            }
            
          } else {
            $log->writeInfo('There are not statuses.');
          }
        } else {
          $log->writeError('Could not connect to gate.');
        }
      }
    }
    
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>