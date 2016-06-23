<?php

set_include_path(':/install/php/work:'.get_include_path());

require_once 'gate/consts.php';

require_once 'libs/log.php';
require_once 'libs/utils.php';
require_once 'libs/package.php';
require_once 'libs/mysql.php';
require_once 'libs/Zend/Loader.php';

define ('DEBUG_LOG',true);

function member_in_list($log,$member,$list,&$memberId,&$index) {
  
  $ret = false;
  $email = false;
  $s = strtolower('memberId');
  
  foreach($member->property as $p) {
    if (strtolower($p->name)==$s) {
      $memberId = $p->value;
      $email = strtolower($memberId);
      break; 
    }
  }
  if ($email) {
    foreach($list as $k=>$l) {
      $e = strtolower($l['EMAIL']);
      if ($e==$email) {
        $index = $k;
        $ret = true;
        break;
      }
    }
  }
  return $ret;
}

function sync_google_group($log,$group,$email,$list) {
  
  $ret = false;
  
  if (is_array($list) && sizeOf($list)) {

    try {

      $client = Zend_Gdata_ClientLogin::getHttpClient(GOOGLE_USER,GOOGLE_PASS,Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
      if ($client) {
      
        $log->writeInfo('Client is available. Getting access ...');
      
        $gdata = new Zend_Gdata_Gapps($client,GOOGLE_DOMAIN);
        if ($gdata) {
      
          $log->writeInfo(sprintf('Searching a group %s...',$group));
      
          $groupId = $email;
      
          $g = $gdata->retrieveGroup($groupId);
          if ($g) {
            $log->writeInfo(sprintf('Group %s is found. Retrieving members...',$group));
      
            $members = $gdata->retrieveAllMembers($groupId);
            if ($members) {
      
              $log->writeInfo(sprintf('Group %s contains %d members. Sync with list...',$group,sizeOf($members)));
              
              foreach($members as $m) {
                $memberId = 'unknown';
                $found = member_in_list($log,$m,$list,$memberId,$index);
                if (!$found) {
                  $log->writeInfo(sprintf('Member %s is not found in list. Removing from group...',$memberId));
                  $gdata->removeMemberFromGroup($memberId,$groupId);
                } else {
                  $log->writeInfo(sprintf('Member %s is found in list. Keep using it.',$memberId));
                  unset($list[$index]);
                }
              }
            }
          } else {
            $log->writeInfo(sprintf('Group %s is not found. Creating...',$group));
      
            $g = $gdata->createGroup($groupId,$group);
            if ($g) {
              $log->writeInfo(sprintf('Group %s is created. Adding members...',$group));
            }
          }
      
          if ($g) {
          
            $log->writeInfo(sprintf('Rest of the list has %d emails.',sizeOf($list)));
            
            $counter = 1;
            foreach ($list as $l) {
              
              try {
                $log->writeInfo(sprintf('Adding new %d member %s into group %s...',$counter,$l['EMAIL'],$group));
                $counter++; 

                $m = $gdata->addMemberToGroup($l['EMAIL'],$groupId);
                if ($m) {
                  $log->writeInfo(sprintf('New member %s has added into group %s successfully.',$l['EMAIL'],$group));
                } else {
                  $log->writeInfo(sprintf('Could not add member %s into group %s.',$l['EMAIL'],$group));
                }

              } catch (Exception $e) {
                $log->writeError(sprintf('Could not add new member %s. Error: %s',$l['EMAIL'],$e->getMessage()));  
              }
            }
          }
          $ret = true;
        } else {
          $log->writeError(sprintf('Could not get access to %s.',GOOGLE_DOMAIN));
        }
      
        unset($client);
      } else {
        $log->writeError('Could not get google client.');
      }
      
    } catch (Exception $e) {
      $log->writeException($e);
    }
  }
  return $ret;
}

function get_accounts_in_role($log,$db,$role_id) {
  
  $ret = false;
  $accounts = $db->getRecords(sprintf('SELECT A.* '.
                                        'FROM ACCOUNT_ROLES AR '.
                                        'JOIN ACCOUNTS A ON A.ACCOUNT_ID=AR.ACCOUNT_ID '.
                                       'WHERE AR.ROLE_ID=%s '.
                                         'AND A.LOCKED IS NULL '.
                                       'ORDER BY A.EMAIL',
                                      $db->quote($role_id)));
  if (is_array($accounts) && sizeOf($accounts)>0) {
    $ret = $accounts;
  }
  return $ret;
}

function lock_mailing($db,$mailing_id) {
  
  $ret = false;
  $flag = true;

  $data = $db->getRecord(sprintf('SELECT LOCKED, SENT FROM MAILING WHERE MAILING_ID=%s LIMIT 1',$db->quote($mailing_id)));
  if (is_array($data) && sizeOf($data)>0) {
    
    if (!is_null($data['LOCKED']) || !is_null($data['SENT'])) {
      $flag = false;
    }
  }
  if ($flag) {
    $ret = $db->updateRecord('MAILING',array('LOCKED'=>$db->currentTimestamp()),array('MAILING_ID'=>$mailing_id));
  }
  return $ret;
}

$log = new Log (LOG_MAILING,DEBUG_LOG,true,false);
if ($log) {

  $stamp = microtime(true);

  $log->writeInfo(str_repeat('-',50));
  try {

    $log->writeInfo('Connect to Database ...');

    $db = new Mysql($log,DB_GATE_HOST,DB_GATE_USER,DB_GATE_PASS,DB_GATE_NAME);
    if ($db) {

      $log->writeInfo('Connected.');
      $db->setCharset('utf8');
      
      $mailings = $db->getRecords('SELECT T.* 
                                    FROM (SELECT A.LOGIN, A.NAME, A.EMAIL, A.IS_ROLE,
                                                 M.MAILING_ID, M.SUBJECT, M.BODY, M.HEADERS,
                                                 M.CREATED, M.PRIORITY, M.ACCOUNT_ID,
                                                 (CASE WHEN A.LOGIN LIKE "analytics%" THEN 1 ELSE 0 END) AS NEED_NOTIFY
                                            FROM MAILING M
                                            JOIN ACCOUNTS A ON A.ACCOUNT_ID=M.ACCOUNT_ID
                                           WHERE M.SENT IS NULL
                                             AND (M.BEGIN IS NULL OR M.BEGIN<=CURRENT_TIMESTAMP)
                                             AND (M.END IS NULL OR M.END>=CURRENT_TIMESTAMP)
                                             AND A.EMAIL IS NOT NULL
                                             AND TRIM(A.EMAIL)<>""
                                             AND A.LOCKED IS NULL
                                             AND M.LOCKED IS NULL
                                             AND A.IS_ROLE=1
                                           LIMIT 1
                                           UNION ALL
                                          SELECT A.LOGIN, A.NAME, A.EMAIL, A.IS_ROLE,
                                                 M.MAILING_ID, M.SUBJECT, M.BODY, M.HEADERS,
                                                 M.CREATED, M.PRIORITY, M.ACCOUNT_ID,
                                                 (CASE WHEN A.LOGIN LIKE "analytics%" THEN 1 ELSE 0 END) AS NEED_NOTIFY
                                            FROM MAILING M
                                            JOIN ACCOUNTS A ON A.ACCOUNT_ID=M.ACCOUNT_ID
                                           WHERE M.SENT IS NULL
                                             AND (M.BEGIN IS NULL OR M.BEGIN<=CURRENT_TIMESTAMP)
                                             AND (M.END IS NULL OR M.END>=CURRENT_TIMESTAMP)
                                             AND A.EMAIL IS NOT NULL
                                             AND TRIM(A.EMAIL)<>""
                                             AND A.LOCKED IS NULL
                                             AND M.LOCKED IS NULL
                                             AND A.IS_ROLE=0
                                           LIMIT 25) T
                                   ORDER BY T.CREATED, T.PRIORITY');
      if (is_array($mailings) && sizeOf($mailings)>0) {
        
        $log->writeInfo(sprintf('There are %d mailing. Loading google api...',sizeOf($mailings)));
        
        Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
        Zend_Loader::loadClass('Zend_Gdata_Gapps');
        
        $log->writeInfo('Google api is loaded. Processing...');

        foreach ($mailings as $m) {

          $r = lock_mailing($db,$m['MAILING_ID']);
          if ($r) {
          
            try {
              $email = $m['EMAIL'];

              $r = false;

              $is_role = ($m['IS_ROLE']=='1')?true:false;
              if ($is_role) {

                $list = get_accounts_in_role($log,$db,$m['ACCOUNT_ID']);
                if ($list) {
                  $log->writeInfo(sprintf('There are %d accounts in role %s. Sync...',sizeOf($list),$m['LOGIN']));
                  
                  $sync = sync_google_group($log,$m['LOGIN'],$m['EMAIL'],$list);
                  if ($sync) {

                    $r = send_mail($m['NAME'],$m['EMAIL'],$m['SUBJECT'],$m['BODY'],$m['HEADERS']);
                  } else {
                    $log->writeError(sprintf('Could not sync the google group %s',$m['LOGIN']));
                  }
                } else {
                  $log->writeInfo(sprintf('There are nobody in the role %s',$m['LOGIN']));
                }
              } else {
                $r = send_mail($m['NAME'],$m['EMAIL'],$m['SUBJECT'],$m['BODY'],$m['HEADERS']);
              }

              $sent = null;
              $result = null;
              if ($r) {
                $sent = $db->currentTimestamp();
                $log->writeInfo(sprintf('Mailing %s has sent successfully at %s',$m['MAILING_ID'],date(DB_DATE_TIME_FMT)));
              } else {
                $result = $r;
                $log->writeError(sprintf('Mailing %s has not sent. Error is %s',$m['MAILING_ID'],$r));
              }

              $where['MAILING_ID'] = $m['MAILING_ID'];
              $data['SENT'] = $sent;
              $data['RESULT'] = $result;
              $data['LOCKED'] = null;
              $r = $db->updateRecord('MAILING',$data,$where);
              
              if ($sent && $r && ($m['NEED_NOTIFY']=='1')) {

                $s = sprintf('REPORT: %s',$m['SUBJECT']);
                $s = sprintf('=?utf-8?B?%s?=',base64_encode($s));
                $body = sprintf('Date & time: %s',date(DB_DATE_TIME_FMT));
                
                $headers ='From: UFS IC Research <research@ufs-federation.com>'; 
                
                @mail('sbscrb@ufs-federation.com',$s,$body,$headers);
              }
              
            } catch (Exception $e) {
              $db->updateRecord('MAILING',array('LOCKED'=>null),array('MAILING_ID'=>$m['MAILING_ID']));
            }
          } else {
            $log->writeInfo('Could not lock mailing.');
          }
        }
      } else {
        $log->writeInfo('There is nothing to worry about');
        $log->delete = true;
      }
    }
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>