<?php

set_include_path(':/var/www/work/mailing:'.get_include_path());

require_once 'consts.php';

set_include_path(':/var/www/work/libs:'.get_include_path());

require_once 'log.php';
require_once 'mysql.php';
require_once 'utils.php';
require_once 'connection.php';

define ('DEBUG_LOG',true);

function connection_sendmail($log,$name,$email,$begin,$end,
                             $params,$subject,$body,$headers,
                             &$remote_id,&$transferred,&$sent,&$result) {

  $ret = false;
  $c = new Connection($log,GATE_URL);
  try {
    $ret = $c->sendMail($name,$email,$begin,$end,
                        $params,$subject,$body,$headers,true,
                        $remote_id,$transferred,$sent,$result);
  } catch (Exception $e) {
    $result = $e->getMessage();
    $log->writeException($e);
  }
  unset($c);
  return $ret;
}

function connection_sendmails($log,&$emails,$group,$subject,$body,$headers,&$result) { 

  $ret = false;
  $c = new Connection($log,GATE_URL);
  try {
    $ret = $c->sendMails($emails,$group,$subject,$body,$headers,true,$result);
  } catch (Exception $e) {
    $result = $e->getMessage();
    $log->writeException($e);
  }
  unset($c);
  return $ret;
}

function change_params($log,$value,$params) {

  $ret = $value;
  $arr = explode("\n",$params);
  foreach ($arr as $a) {
    $temp = explode(':',$a,2);
    $ret = str_replace($temp[0],$temp[1],$ret);
  }
  return $ret;
}

function sendmail($log,$name,$email,$begin,$end,
                  $params,$subject,$body,$headers,$method=0,
                  &$remote_id,&$transferred,&$sent,&$result) {

  $ret = false;
  if ($method==0) {
    $ret = connection_sendmail($log,$name,$email,$begin,$end,
                               $params,$subject,$body,$headers,
                               $remote_id,$transferred,$sent,$result);
  } else {
    $name = sprintf('=?utf-8?B?%s?=',base64_encode($name));
    $subject = sprintf('=?utf-8?B?%s?=',base64_encode($subject));
    $s = sprintf('%s <%s>',$name,$email);
    
    $s1 = change_params($log,$subject,$params); 
    $s2 = change_params($log,$body,$params); 
    $s3 = change_params($log,$headers,$params); 
    
    $ret = @mail($s,$s1,$s2,$s3);
  }
  return $ret;
}

function sendmails($log,$db,&$emails,$group,$subject,$body,$headers,$method=0,&$result) {

  $ret = false;
  if ($method==0) {
    $r = connection_sendmails($log,&$emails,$group,$subject,$body,$headers,&$result);
    $ret = true;
  } else {
    
    
    foreach ($emails as &$e) {
    
      $r = sendmail($log,$e['name'],$e['email'],$subject,$body,$headers,1);
      if ($r) { 
        $e['sent'] = $db->currentTimestamp();
        $e['result'] = null;
      } else {
        $e['sent'] = null;
        $e['result'] = $r;
      }
    }
    $ret = true;
  }
  return $ret;
}

function connection_checkmails($log,&$emails,&$result) {
  
  $ret = false;
  $c = new Connection($log,GATE_URL);
  try {
    $ret = $c->checkMails($emails,$result);
  } catch (Exception $e) {
    $result = $e->getMessage();
    $log->writeException($e);
  }
  unset($c);
  return $ret;
}

$method = 0; // 0-via gate, 1-via mail
$no_pattern_limit = 10; // record's limit for single-messages
$pattern_limit = 3000; // record's limit for pattern-messages

$log = new Log (LOG_MAILING,DEBUG_LOG,true,true,false);
if ($log) {

  $stamp = microtime(true);
  
  $log->writeInfo(str_repeat('-',50));
  try {
    $delete = true;
    
    $log->writeInfo('Connect to Database ...');
     
    $db = new Mysql($log,DB_MAILING_HOST,DB_MAILING_USER,DB_MAILING_PASS,DB_MAILING_NAME);
    if ($db) {

      $log->writeInfo('Connected.');
      $db->setCharset('utf8');
      
      $db->query('DELETE FROM analytics_reviews_mailing '.
                  'WHERE mailing_id IN (SELECT mailing_id FROM mailing WHERE created<=ADDDATE(CURRENT_TIMESTAMP, INTERVAL -5 DAY));');
      
      $db->query('DELETE FROM mailing '.
                  'WHERE created<=ADDDATE(CURRENT_TIMESTAMP, INTERVAL -5 DAY);');

      $db->query('DELETE FROM mailing_patterns '.
                  'WHERE mailing_pattern_id NOT IN (SELECT DISTINCT(mailing_pattern_id) FROM mailing);');
      
      if ($method==0) {
        
        $mailing = $db->getRecords('SELECT mailing_id, remote_id '.
                                     'FROM mailing '.
                                    'WHERE (begin IS NULL OR begin<=CURRENT_TIMESTAMP) '.
                                      'AND (end IS NULL OR end>=CURRENT_TIMESTAMP) '.
                                      'AND remote_id IS NOT NULL '.
                                      'AND transferred IS NOT NULL '.
                                      'AND sent IS NULL '.
                                    'ORDER BY transferred');
        
        if (is_array($mailing) && sizeOf($mailing)>0) {
          
          $delete = false;
          
          $log->writeInfo(sprintf('There are %d emails. Checking...',sizeOf($mailing)));
          
          $emails = array();
          foreach($mailing as $m) {
            
            $r = new stdClass();
            $r->mailing_id = $m['mailing_id'];
            $r->remote_id = $m['remote_id'];
            $r->sent = null;
            $r->result = null;
            $emails[] = $r;
          }
          $result = null;
          $r = connection_checkmails($log,$emails,$result);
          if ($r) {
            
            $inc = 0;
            foreach($emails as $e) {

              $where['mailing_id'] = $e->mailing_id;
              $where['remote_id'] = $e->remote_id;
              $data['sent'] = $e->sent;
              $data['result'] = $e->result;
              $inc++;
            
              if (!is_null($e->sent)) {
                $log->writeInfo(sprintf('Mailing %d with id %d has sent successfully at %s...',$inc,$e->mailing_id,$e->sent));
              } else {
                $log->writeError(sprintf('Mailing %d with id %d has not sent. Error is %s.',$inc,$e->mailing_id,$e->result));
              }
              
              $r = $db->updateRecord('mailing',$data,$where);
              if ($r) {
                $log->writeInfo(sprintf('Mailing %d with id %d has updated successfully ...',$inc,$e->mailing_id));
              } else {
                $log->writeError(sprintf('Mailing %d with id %d has not updated. Error is %s.',$inc,$e->mailing_id,$r));
              }
            }
          } else {
            $log->writeError(sprintf('Checking has failed because of %s',$result));
          }
        }
      }

      $patterns = $db->getRecords('SELECT mp.*, t.rest_count, t.min_created, t.min_priority '.
                                    'FROM (SELECT mailing_pattern_id, COUNT(*) as rest_count, '.
                                                 'MIN(created) as min_created, MIN(priority) as min_priority '.
                                            'FROM mailing '.
                                           'WHERE (begin IS NULL OR begin<=CURRENT_TIMESTAMP) '.
                                             'AND (end IS NULL OR end>=CURRENT_TIMESTAMP) '.
                                             'AND transferred IS NULL '.
                                             'AND sent IS NULL '.
                                           'GROUP BY 1) t '. 
                                    'LEFT JOIN mailing_patterns mp on mp.mailing_pattern_id=t.mailing_pattern_id '.
                                   'ORDER BY t.min_created, t.min_priority');
      
      if (is_array($patterns) and sizeOf($patterns)>0) {
      
        $delete = false;
        
        $log->writeInfo(sprintf('There are %d patterns.',sizeOf($patterns)));
        
        $counter = 0;
        $max_counter = 0;
        foreach ($patterns as $p) {
          
          $flag = is_null($p['mailing_pattern_id']);
          $increment = ($flag) ? $p['rest_count']*10 : $p['rest_count'];
          $max_counter = $max_counter + $increment;
        }
        
        if ($max_counter>$pattern_limit) {
          $max_counter = $pattern_limit;
        }
        
        foreach($patterns as $p) {
        
          $flag = is_null($p['mailing_pattern_id']);
          
          if ($max_counter>=$counter) {
          
            $increment = ($flag) ? $p['rest_count']*10 : $p['rest_count'];
          
            $mpid = ($flag) ? 'NULL' : $p['mailing_pattern_id'];
          
            $log->writeInfo(sprintf('Process pattern %s / %d emails.',$mpid,$p['rest_count']));
          
            $mpid_where = ($flag) ? ' IS NULL' : '='.$p['mailing_pattern_id'];
          
            $emails = array();
            $max_limit = ($flag) ? $no_pattern_limit : $pattern_limit;
          
            $mailing = $db->getRecords(sprintf('SELECT * '.
                                                 'FROM mailing '.
                                                'WHERE (begin IS NULL OR begin<=CURRENT_TIMESTAMP) '.
                                                  'AND (end IS NULL OR end>=CURRENT_TIMESTAMP) '.
                                                  'AND transferred IS NULL '.
                                                  'AND sent IS NULL '.
                                              //    'AND mailing_id=36000 '.
                                                  'AND mailing_pattern_id%s '.
                                                'ORDER BY created, priority '.
                                                'LIMIT 0,%d',$mpid_where,$max_limit));
            if (is_array($mailing) && sizeOf($mailing)>0) {
          
              $log->writeInfo(sprintf('There are %d emails. Sending / transferring ...',sizeOf($mailing)));
             
              if ($flag) {

                $where = array();
                $data = array();
                $inc = 0;
                
                foreach ($mailing as $m) {
                  
                  $remote_id = null;
                  $transferred = null;
                  $sent = null;
                  $result = null;
                  $inc++;
                  
                  $r = sendmail($log,$m['name'],$m['email'],$m['begin'],$m['end'],
                                $m['params'],$m['subject'],$m['body'],$m['headers'],$method,
                                $remote_id,$transferred,$sent,$result); 
                  $where['mailing_id'] = $m['mailing_id'];
                  $data['remote_id'] = $remote_id;
                  $data['transferred'] = $transferred;
                  $data['sent'] = $sent;
                  $data['result'] = $result;
                  if ($r) {
                    $d = date(DB_DATE_TIME_FMT);
                    $log->writeInfo(sprintf('Mailing %d with id %d has %s successfully at %s...',
                                            $inc,$m['mailing_id'],($method==0)?'transferred':'sent',$d));
                  } else {
                    $log->writeError(sprintf('Mailing %d with id %d has not %s. Error is %s.',
                                             $inc,$m['mailing_id'],($method==0)?'transferred':'sent',$r));
                  }
                  
                  $r = $db->updateRecord('mailing',$data,$where);
                  if ($r) {
                    $counter = $counter + $increment;
                    $log->writeInfo(sprintf('Mailing %d with id %d has updated successfully ...',$inc,$m['mailing_id']));
                  } else {
                    $log->writeError(sprintf('Mailing %d with id %d has not updated. Error is %s.',$inc,$m['mailing_id'],$r));
                  } 
                }

              } else {
 
                $emails = array();
              
                foreach ($mailing as $m) {
                  
                  $r = new stdClass();
                  $r->mailing_id = $m['mailing_id'];
                  $r->remote_id = $m['remote_id'];
                  $r->priority = $m['priority'];
                  $r->name = $m['name'];
                  $r->email = $m['email'];
                  $r->params = $m['params'];
                  $r->begin = $m['begin'];
                  $r->end = $m['end'];
                  $r->transferred = null;
                  $r->sent = null;
                  $r->result = null;
                  $emails[] = $r;
                } 
              
                $r = sendmails($log,$db,$emails,$p['group'],$p['subject'],$p['body'],$p['headers'],$method,$result);
                if ($r) { 
                  $where = array();
                  $data = array();
                  $inc = 0;
                   
                  foreach ($emails as $e) {
                    
                    $where['mailing_id'] = $e->mailing_id;
                    $data['remote_id'] = $e->remote_id;
                    $data['transferred'] = $e->transferred;
                    $data['sent'] = $e->sent;
                    $data['result'] = $e->result;
                    $inc++;
                    
                    $date = ($method==0)?$e->transferred:$e->sent;
                    if (!is_null($date)) {
                      $log->writeInfo(sprintf('Mailing %d with id %d has %s successfully at %s...',
                                              $inc,$e->mailing_id,($method==0)?'transferred':'sent',$date));
                    } else {
                      $log->writeError(sprintf('Mailing %d with id %d has not %s. Error is %s.',
                                               $inc,$e->mailing_id,($method==0)?'transferred':'sent',$e->result));
                    }
                    
                    $r = $db->updateRecord('mailing',$data,$where);
                    if ($r) {
                      $counter = $counter + $increment;
                      $log->writeInfo(sprintf('Mailing %d with id %d has updated successfully ...',$inc,$e->mailing_id));
                    } else {
                      $log->writeError(sprintf('Mailing %d with id %d has not updated. Error is %s.',$inc,$e->mailing_id,$r));
                    } 
                  }
                } else {
                  $log->writeError(sprintf('Sending / transferring has failed because of %s',$result));
                } 
              }
            } else {
              $log->writeInfo('There are no emails');
            }
          }
        }
        
        $log->writeInfo(sprintf('MaxCounter=%d Counter=%d',$max_counter,$counter));
      } else {
        $log->writeInfo('There is nothing to worry about');
      }
      unset ($db);
    }
    $log->delete = $delete; 
    
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>
