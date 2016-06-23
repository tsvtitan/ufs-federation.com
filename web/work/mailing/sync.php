<?php

require_once 'init.php';
require_once 'mailing/consts.php';

require_once 'libs/log.php';
require_once 'libs/utils.php';
require_once 'libs/mysql.php';
require_once 'libs/Zend/Loader.php';

define ('DEBUG_LOG',true); 

function member_in_list($log,$member,$list,&$memberId,&$index) {
  
  $ret = false;
  $email = false;
  $memberIdName = strtolower('memberId');
  $memberId = false;
  
  foreach($member->property as $p) {
    
    $n = strtolower($p->name);
    if ($n==$memberIdName) {
      $memberId = $p->value;
      $email = strtolower($memberId);
      break;
    }
    //$log->writeInfo($n."=".$p->value);
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

function unsubscribe($db,$email,$section_id) {
  
  $sql = sprintf('update mailing_subscriptions
                     set finished=current_timestamp
                   where email=%s 
                     and mailing_section_id=%d
                     and started is not null
                     and finished is null',
                 $db->quote(trim($email)),$section_id);
  $db->query($sql);
}

function sync_google_group($log,$db,$group,$email,$list,$section_id) {
  
  $ret = false;
  
  if (is_array($list)) {

    try {

      $client = Zend_Gdata_ClientLogin::getHttpClient(GOOGLE_USER,GOOGLE_PASS,Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
      if ($client) {
      
        $log->writeInfo('Client is available. Getting access ...');
      
        $gdata = new Zend_Gdata_Gapps($client,GOOGLE_DOMAIN);
        if ($gdata) {
      
          $log->writeInfo(sprintf('Searching the group %s...',$group));
      
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
                unsubscribe($db,$l['EMAIL'],$section_id);
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

$log = new Log(LOG_SYNC,DEBUG_LOG,true,true);
if ($log) {

  $stamp = microtime(true);

  $log->writeInfo(str_repeat('-',50));
  try {

    $log->writeInfo('Connect to Database ...');

    $db = new Mysql($log,DB_MAILING_HOST,DB_MAILING_USER,DB_MAILING_PASS,DB_MAILING_NAME);
    if ($db) {

      $log->writeInfo('Connected.');
      $db->setCharset('utf8');
      
      $sections = $db->getRecords('select `group` as NAME, max(mailing_section_id) as ID
                                     from mailing_sections
                                    where `group` is not null
                                      and trim(`group`)!=""
                                     -- and lang="en"
                                     -- and `group`="analytics1"
                                    group by `group`');
      if (is_array($sections) && sizeOf($sections)>0) {
        
        $log->writeInfo(sprintf('There are %d groups. Loading google api...',sizeOf($sections)));
        
        Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
        Zend_Loader::loadClass('Zend_Gdata_Gapps');
        
        $log->writeInfo('Google api is loaded. Processing...');
        
        foreach ($sections as $section) {
          
          $log->writeInfo(sprintf('Sync the %s group (id=%d) with google...',$section['NAME'],$section['ID']));
          
          
          $sql = sprintf('select email as EMAIL
                            from mailing_subscriptions
                           where mailing_section_id=%d 
                             and email is not null
                             and trim(email)!=""
                             and started is not null and started<=current_timestamp
                             and (finished is null or (finished is not null and finished>current_timestamp))
                           group by email',$section['ID']);
          
          $emails = $db->getRecords($sql);
          $r = sync_google_group($log,$db,$section['NAME'],sprintf('%s@%s',$section['NAME'],GOOGLE_DOMAIN),$emails,$section['ID']);
          if ($r) {
            $log->writeInfo(sprintf('Sync the %s group is successfully completed.',$section['NAME']));
          } else {
            $log->writeError(sprintf('Could not sync the group %s.',$section['NAME']));
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