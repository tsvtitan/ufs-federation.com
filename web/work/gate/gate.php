<?php

set_include_path(':/install/php/work:'.get_include_path());

require_once 'gate/consts.php';

require_once 'libs/log.php';
require_once 'libs/utils.php';
require_once 'libs/package.php';
require_once 'libs/mysql.php';
require_once 'libs/consts.php';
              
define ('R_TYPE_SUCCESS','success');
define ('R_TYPE_FAILED','failed');
define ('R_TYPE_INTERNAL_ERROR','internal_error');
define ('PACKAGE_RESULT','result');
define ('MAX_RND_LENGTH',3*1024);
define ('LOCAL_KEY','1DE1E46E5B48C7065F6FE64936DFAE91');
define ('REMOTE_KEY','79C5868D7808345E524DF0587021BDDB');

define ('DEBUG_FILE',false);
define ('DEBUG_LOG',true);

function get_content() {
  
  if (DEBUG_FILE) {
   // $ret = @file_get_contents('/var/www/gate/logs/gate_input.xml');
  } else {
    $ret = @file_get_contents('php://input');
    @file_put_contents('/var/www/gate/logs/input.data', $ret);
    $ret = decode_string(REMOTE_KEY,$ret);
  }
  return $ret;
}

function read($log) {

  $ret = false;
  $xml = get_content();
  if ($xml) {
    $ret = new Package();
    $ret->loadXml($xml);
    if ($ret->method=='') {
      $ret = false;
    }
  } else
    $log->writeError(sprintf('Could not read from %s',$_SERVER['REMOTE_ADDR']));
  return $ret;
}

function reply($package) {

  if ($package) {
    $package->add('rnd')->value = base64_encode(random_string(MAX_RND_LENGTH));
    $xml = $package->saveXml();
    if ($xml) {
      $xml = encode_string(LOCAL_KEY,$xml);
      echo $xml;
    }
  }
}

function change_params($log,$value,$params) {

  $ret = $value;
  if (trim($params)!='') {
    $arr = explode("\n",$params);
    foreach ($arr as $a) {
      $temp = explode(':',$a,2);
      if (sizeof($temp)>0) {
        $ret = str_replace($temp[0],$temp[1],$ret);
      }  
    }
  }  
  return $ret;
}

function set_mailing($log,$db,$mailing_id=null,$account_id,$priority=null,$subject=null,
                     $headers=null,$body=null,$begin=null,$end=null,$sent=null,$result=null) {
  $ret = false;
  
  $where = false;
  if (is_null($mailing_id)) {
    $mailing_id = $db->getUniqueId();
  } else {
    $where['MAILING_ID'] = $mailing_id;
  }
  
  $mailing['MAILING_ID'] = $mailing_id; 
  $mailing['ACCOUNT_ID'] = $account_id;
  $mailing['PRIORITY'] = (trim($priority)!='')?$priority:null;
  $mailing['SUBJECT'] = (trim($subject)!='')?$subject:null;
  $mailing['HEADERS'] = (trim($headers)!='')?$headers:null;
  $mailing['BODY'] = (trim($body)!='')?$body:null;
  $mailing['BEGIN'] = (trim($begin)!='')?$begin:null;
  $mailing['END'] = (trim($end)!='')?$end:null;
  $mailing['SENT'] = (trim($sent)!='')?$sent:null;
  $mailing['RESULT'] = (trim($result)!='')?$result:null;
  
  $r = false;
  if ($where) {
    $r = $db->updateRecord('MAILING',$mailing,$where);
  } else {
    $r = $db->insertRecord('MAILING',$mailing);
  } 
    
  if ($r) {
    $ret = $mailing;
  }
  return $ret;
}

function lock_account($db,&$account) {
  
  $ret = false;
  if (is_null($account['LOCKED'])) {

    $date = date(DB_DATE_TIME_FMT);
    
    $where['ACCOUNT_ID'] = $account['ACCOUNT_ID'];
    $data['LOCKED'] = $date; 
    $data['LOCK_REASON'] = 'Gate processing system';
  
    $ret = $db->updateRecord('ACCOUNTS',$data,$where);
    if ($ret) {
      $account['LOCKED'] = $date;
      if ($account['IS_ROLE']==1) {
        $db->query(sprintf('DELETE FROM ACCOUNT_ROLES WHERE ROLE_ID=%s;',$db->quote($account['ACCOUNT_ID'])));
      }
    }
  }
  return $ret;
}

function unlock_account($db,&$account) {
  
  $ret = false;
  if (!is_null($account['LOCKED'])) {
    
    $where['ACCOUNT_ID'] = $account['ACCOUNT_ID'];
    $data['LOCKED'] = null;
    $data['LOCK_REASON'] = null; 
  
    $ret = $db->updateRecord('ACCOUNTS',$data,$where);
    if ($ret) {
      $account['LOCKED'] = null;
    }
  }
  return $ret;
}

function get_account_by_email($db,$email) {
  
  $ret = false;
  $account = $db->getRecord(sprintf('SELECT * '.
                                      'FROM ACCOUNTS '.
                                     'WHERE UPPER(EMAIL)=UPPER(%s) '.
                                       'AND IS_ROLE=0 '.
                                     'LIMIT 1',
                                     $db->quote($email)));
  
  if (is_array($account) && sizeOf($account)>0) {
    $ret = $account;
  } 
  return $ret;
}

function set_account($db,$name,$email) {
  
  $ret = false;
  
  $account['ACCOUNT_ID'] = $db->getUniqueId();
  $account['IS_ROLE'] = '0';
  $account['LOGIN'] = $email;
  $account['NAME'] = (trim($name)!='')?$name:null;
  $account['EMAIL'] = $email;
  $account['LOCKED'] = null;
  
  $r = $db->insertRecord('ACCOUNTS',$account);
  if ($r) {
    $ret = $account;
  }
  return $ret;
}

function check_account($log,$db,$name,$email,$role_id=null) {
  
  $ret = false;
  if (trim($email)!='') {

    $account = get_account_by_email($db,$email);
    if (!$account) {
      $ret = set_account($db,$name,$email);
    } else {
      if (is_null($account['LOCKED'])) {
        $ret = $account;
      }
    }
    if ($ret && (!is_null($role_id))) {

      $r = $db->getRecord(sprintf('SELECT COUNT(*) AS CNT '.
                                    'FROM ACCOUNT_ROLES AR '.
                                    'JOIN ACCOUNTS A1 ON A1.ACCOUNT_ID=AR.ACCOUNT_ID '.
                                    'JOIN ACCOUNTS A2 ON A2.ACCOUNT_ID=AR.ROLE_ID '.
                                   'WHERE AR.ACCOUNT_ID=%s '.
                                     'AND AR.ROLE_ID=%s',
                                  $db->quote($ret['ACCOUNT_ID']),
                                  $db->quote($role_id)));

      if (is_array($r) && sizeOf($r)>0) {

        if ($r['CNT']==0) {

          $account_roles['ACCOUNT_ID'] = $ret['ACCOUNT_ID'];
          $account_roles['ROLE_ID'] = $role_id;
          $r = $db->insertRecord('ACCOUNT_ROLES',$account_roles);
          if (!$r) {
            $ret = false;
          }
        }
      } else {
        $ret = false;
      }
    }
  }
  return $ret;
}

function get_role_by_login($db,$login) {
  
  $ret = false;
  $role = $db->getRecord(sprintf('SELECT * '.
                                   'FROM ACCOUNTS '.
                                  'WHERE UPPER(LOGIN)=UPPER(%s) '.
                                    'AND IS_ROLE=1 '.
                                  'LIMIT 1',
                                 $db->quote($login)));

  if (is_array($role) && sizeOf($role)>0) {
    $ret = $role;
  }
  return $ret;
}

function set_role($db,$login) {
  
  $ret = false;
  
  $role['ACCOUNT_ID'] = $db->getUniqueId();
  $role['IS_ROLE'] = '1';
  $role['LOGIN'] = $login;
  $role['EMAIL'] = $login.'@ufs-federation.com';
  $role['LOCKED'] = null;
  
  $r = $db->insertRecord('ACCOUNTS',$role);
  if ($r) {
    $ret = $role;
  }
  return $ret;
}

function check_role($log,$db,$login) {

  $ret = false;
  if (trim($login)!='') {

    $role = get_role_by_login($db,$login);
    if (!$role) {
      $ret = set_role($db,$login);
    } else {
      $ret = $role;
    }
  }
  return $ret;
}

function sendMail($log,$db,$in,&$out) {
  
  $ret = R_TYPE_FAILED;
  if ($in) {
    $data = $in->find('data');
    if ($data) {
      
      $name = $data->attributes['name'];  
      $email = $data->attributes['email'];
      $begin = $data->attributes['begin'];
      $end = $data->attributes['end'];
      $async = ($data->attributes['async']=='1')?true:false;
      
      $account = check_account($log,$db,$name,$email,null);
      if ($account) {
        
        $locked = lock_account($db,$account);
        if ($locked) {
          try {
            $params = base64_decode($data->childs->find('params')->value);
            
            $subject = base64_decode($data->childs->find('subject')->value);
            $subject = change_params($log,$subject,$params);
            
            $body = base64_decode($data->childs->find('body')->value);
            $body = change_params($log,$body,$params);
            
            $headers = base64_decode($data->childs->find('headers')->value);
            $headers = change_params($log,$headers,$params);
            
            $mailing = set_mailing($log,$db,null,$account['ACCOUNT_ID'],null,
                                   $subject,$headers,$body,$begin,$end,null,null);
            if ($mailing) {
            
              $outdata = $out->add('data');
              $outdata->attributes['remote_id'] = $mailing['MAILING_ID'];
              $outdata->attributes['transferred'] = null;
              $outdata->attributes['sent'] = null;
              $result = $outdata->childs->add('result');
              $result->value = null;
               
              $r = false;
              $sent = null;
              $res = null;
              if (!$async) {
                
                $r = send_mail($name,$email,$subject,$body,$headers);
                if ($r) {
                  $sent = date(DB_DATE_TIME_FMT);
                  $outdata->attributes['sent'] = $sent;
                  $ret = R_TYPE_SUCCESS;
                } else {
                  $res = $r;
                  $result->value = base64_encode($r);
                }
                set_mailing($log,$db,$mailing['MAILING_ID'],$account['ACCOUNT_ID'],null,
                            $subject,$headers,$body,$begin,$end,$sent,$res);
              } else {
                $ret = R_TYPE_SUCCESS;
                $outdata->attributes['transferred'] = date(DB_DATE_TIME_FMT);
              }
            } else {
              throw new Exception('Could not set the mailing.');
            }
            unlock_account($db,$account);
            
          } catch (Exception $e) {
            unlock_account($db,$account);
          }
        }
      } else {
        throw new Exception('Could not check the email.');
      }
    } else {
      throw new Exception('Data is not found.');
    }
  }
  return $ret;
}

function checkMails($log,$db,$in,&$out) {
  
  $ret = R_TYPE_FAILED;
  if ($in) {
    $data = $in->find('data');
    if ($data) {
      
      $log->writeInfo(sprintf('Data has %d childs.',sizeOf($data->childs)));
      
      $outdata = $out->add('data');
      $mailing_ids = array();

      foreach ($data->childs as $c) {
        if ($c->name=='item') {
          $remote_id = (trim($c->attributes['remote_id'])!='')?$c->attributes['remote_id']:false;
          if ($remote_id) {
            $v = $db->quote($remote_id);
            if (!in_array($v,$mailing_ids)) {
              $mailing_ids[] = $v;
            }
          }
        }
      }
      
      if (sizeOf($mailing_ids)>0) {
      
        $mailing_ids = implode(',',$mailing_ids);
        $mailing = $db->getRecords(sprintf('SELECT * FROM MAILING WHERE MAILING_ID IN (%s)',$mailing_ids));
        
        if (is_array($mailing) && sizeOf($mailing)>0) {
          
          $log->writeInfo(sprintf('There are %d records in the mailing.',sizeOf($mailing)));
          
          foreach($mailing as $m) {
            
            $item = $outdata->childs->add('item',false);
            $item->attributes['remote_id'] = $m['MAILING_ID'];
            $item->attributes['sent'] = $m['SENT'];
            $result = $item->childs->add('result');
            $result->value = base64_encode($m['RESULT']);
            
          }
          $ret = R_TYPE_SUCCESS;
        }
      } else {
        throw new Exception('Data does not have items.');
      }
    } else {
      throw new Exception('Data is not found.');
    }
  }
  return $ret;
}

function sendMails($log,$db,$in,&$out) {
  
  $ret = R_TYPE_FAILED;
  if ($in) {
    $data = $in->find('data');
    if ($data) {

      $group = (trim($data->attributes['group'])!='')?$data->attributes['group']:null;
      $async = ($data->attributes['async']=='1')?true:false;
      
      $outdata = $out->add('data');
      
      $subject = base64_decode($data->childs->find('subject')->value);
      $body = base64_decode($data->childs->find('body')->value);
      $headers = base64_decode($data->childs->find('headers')->value);

      $role = check_role($log,$db,$group);
      if (!$role) {

        $log->writeInfo('Processing emails...');
        
        $log->writeInfo(sprintf('Data has %d childs.',sizeOf($data->childs)));
        
        foreach ($data->childs as $c) {
        
          if ($c->name=='item') {

            $priority = $c->attributes['priority'];
            $name = $c->attributes['name'];
            $email = $c->attributes['email'];
            $begin = $c->attributes['begin'];
            $end = $c->attributes['end'];
          
            $item = $outdata->childs->add('item',false);
            $item->attributes['mailing_id'] = $c->attributes['mailing_id'];
            $item->attributes['remote_id'] = null;
            $item->attributes['transferred'] = null;
            $item->attributes['sent'] = null;
          
            $result = $item->childs->add('result');
            $result->value = null;
                    
            $account = check_account($log,$db,$name,$email,null);
            if ($account) {
              
              $locked = lock_account($db,$account);
              if ($locked) {
                try {
                  
                  $params = base64_decode($c->childs->find('params')->value);
                  
                  $new_subject = change_params($log,$subject,$params);
                  $new_body = change_params($log,$body,$params);
                  $new_headers = change_params($log,$headers,$params);
                  
                  $mailing = set_mailing($log,$db,null,$account['ACCOUNT_ID'],$priority,
                                         $new_subject,$new_headers,$new_body,$begin,$end,null,null);
                  if ($mailing) {
                  
                    $item->attributes['remote_id'] = $mailing['MAILING_ID'];
                    
                    $r = false;
                    $sent = null;
                    $res = null;
                    if (!$async) {
                      $r = send_mail($name,$email,$new_subject,$new_body,$new_headers);
                      if ($r) {
                        $sent = date(DB_DATE_TIME_FMT);
                        $item->attributes['sent'] = $sent;
                      } else {
                        $res = $r;
                        $result->value = base64_encode($r);
                      }
                      set_mailing($log,$db,$mailing['MAILING_ID'],$account['ACCOUNT_ID'],$priority,
                                  $new_subject,$new_headers,$new_body,$begin,$end,$sent,$res);
                    } else {
                      $item->attributes['transferred'] = date(DB_DATE_TIME_FMT);
                    }
                  } else {
                    $result->value = base64_encode('Could not set the mailing.');
                  }
                  
                  unlock_account($db,$account);
                  
                } catch (Exception $e) {
                  unlock_account($db,$account);
                }
              } else {
                $log->writeError('Account is locked.');
              }
            } else {
              $result->value = base64_encode('Could not check the email.');
            }
          }
        }
      } else {
        
        $locked = lock_account($db,$role);
        if ($locked) {
          try {
            
            $min_priority = null;
            $min_begin = null;
            $max_end = null;
            
            $log->writeInfo(sprintf('Data has %d childs.',sizeOf($data->childs)));
            
            foreach ($data->childs as $c) {
            
              if ($c->name=='item') {
            
                $name = $c->attributes['name'];
                $email = $c->attributes['email'];
            
                $priority = (trim($c->attributes['priority'])!='')?$c->attributes['priority']:false;
                if ($priority) {
                  if (is_null($min_priority)) {
                    $min_priority = $priority;
                  } elseif (intval($min_priority)>intval($priority)) {
                    $min_priority = $priority;
                  }
                }
            
                $begin = (trim($c->attributes['begin'])!='')?$c->attributes['begin']:false;
                if ($begin) {
                  if (is_null($min_begin)) {
                    $min_begin = $begin;
                  } elseif (strtotime($min_begin)>strtotime($begin)) {
                    $min_begin = $begin;
                  }
                }
            
                $end = (trim($c->attributes['end'])!='')?$c->attributes['end']:false;
                if ($end) {
                  if (is_null($max_end)) {
                    $max_end = $end;
                  } elseif (strtotime($max_end)<strtotime($end)) {
                    $max_end = $end;
                  }
                }
            
                check_account($log,$db,$name,$email,$role['ACCOUNT_ID']);
              }
            }
            
            $mailing = set_mailing($log,$db,null,$role['ACCOUNT_ID'],$min_priority,
                                   $subject,$headers,$body,$min_begin,$max_end,null,null);
            if ($mailing) {
            
              $transferred = null;
              $sent = null;
              $res = null;
            
              if (!$async) {
                $r = send_mail($role['NAME'],$role['EMAIL'],$subject,$body,$headers);
                if ($r) {
                  $sent = date(DB_DATE_TIME_FMT);
                  $log->writeInfo(sprintf('Sent at %s.',$sent));
                } else {
                  $res = $r;
                }
                set_mailing($log,$db,$mailing['MAILING_ID'],$role['ACCOUNT_ID'],$min_priority,
                            $subject,$headers,$body,$min_begin,$max_end,$sent,$res);
              } else {
                $transferred = date(DB_DATE_TIME_FMT);
                $log->writeInfo(sprintf('Transferred at %s.',$transferred));
              }
            
              foreach ($data->childs as $c) {
            
                if ($c->name=='item') {
            
                  $item = $outdata->childs->add('item',false);
                  $item->attributes['mailing_id'] = $c->attributes['mailing_id'];
                  $item->attributes['remote_id'] = $mailing['MAILING_ID'];
                  $item->attributes['transferred'] = $transferred;
                  $item->attributes['sent'] = $sent;
            
                  $result = $item->childs->add('result');
                  $result->value = base64_encode($res);
                  
                  $log->writeInfo(sprintf('Item id %s has %s.',
                                          $c->attributes['mailing_id'],
                                          (trim($transferred)!='')?'transferred':'sent'));
            
                }
              }
            }
            unlock_account($db,$role);
            
          } catch (Exception $e) {
            unlock_account($db,$role);
          }
        } else {
          $log->writeError('Role is locked.');
        }
      }
      $ret = R_TYPE_SUCCESS;
    }
  }
  return $ret;
}  

$log = new Log (LOG_GATE,DEBUG_LOG,false,false);
if ($log) {
  
  $stamp = microtime(true);
  
  $log->writeInfo(str_repeat('-',50));
  try {

    $log->writeInfo('Reading data ...');

    $in = read($log);
    if ($in) {
      $out = new Package(PACKAGE_RESULT);
      $type = $out->add('type');
      $error = $out->add('error');
      try {
         
        $ret = R_TYPE_INTERNAL_ERROR;
        if (isset($in->method)) {

          $log->writeInfo('Connect to Database ...');
           
          $db = new Mysql($log,DB_GATE_HOST,DB_GATE_USER,DB_GATE_PASS,DB_GATE_NAME);
          if ($db) {

            $log->writeInfo('Connected.');
            $db->setCharset('utf8');

            $ret = R_TYPE_SUCCESS;
            $log->writeInfo(sprintf('Method is %s',$in->method));

            switch(strtolower($in->method)) {
              case 'sendmail': {
                $ret = sendMail($log,$db,$in,&$out);
                break;
              }
              case 'checkmails': {
                $ret = checkMails($log,$db,$in,&$out);
                break;
              }
              case 'sendmails': {
                $ret = sendMails($log,$db,$in,&$out);
                break;
              }
              default: {
              }
            }
            unset($db);
          }
        }
        $type->value = $ret;
        
      } catch (Exception $e) {
        
        $type->value = R_TYPE_INTERNAL_ERROR;
        $error->value = base64_encode($e->getMessage());
        $log->writeException($e);
      }
      reply($out);
    }
    
    
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>