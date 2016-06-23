<?php

require_once 'consts.php';
require_once 'log.php';
require_once 'firebird.php';
require_once 'utils.php';
require_once 'connection.php';

function get_absence_days($log,$db,$date,&$reports) {
	
  $ret = false; 

  $data = $db->getRecords(sprintf('SELECT FIO FROM UFS_GET_ABSENCE_DAYS(%s) 
                                    WHERE ABSENCE_DAYS>0 
                                    UNION ALL 
                                   SELECT FIO FROM UFS_GET_WORK_DAY_HOURS(%s) 
                                    WHERE OUTSIDE_TIME>DATEADD(HOUR,4,CAST(%s AS TIME)) 
                                    ORDER BY 1 ',
                                  "'".$date."'","'".$date."'","'00:00:00'")); 
  if ($data) {

    $style = array();
    $format = array();
    $transform = array();

    $reports[] = array('name'=>sprintf(REPORT_ABSENCE_DAYS_NAME,$date),
                       'style'=>$style, 
                       'format'=>$format, 
                       'transform'=>$transform,
                       'data'=>$data,
                       'as_file'=>true); 
    $ret = $data;
  } 
  return $ret;
}

function get_work_day_hours($log,$db,$date,&$reports) {
	
  $ret = false;

  $data = $db->getRecords(sprintf('SELECT FIO, INSIDE_TIME, OUTSIDE_TIME, FIRST_DATETIME, LAST_DATETIME 
                                     FROM UFS_GET_WORK_DAY_HOURS(%s) 
                                    ORDER BY 1',
                                  "'".$date."'"));
  if ($data) {

    $style = array('INSIDE_TIME'=>'text-align:center;',
                   'OUTSIDE_TIME'=>'text-align:center;',
                   'FIRST_DATETIME'=>'text-align:center;',
                   'LAST_DATETIME'=>'text-align:center;');

    $format = array('INSIDE_TIME'=>DB_TIME_WITHOUT_SECONDS_FMT,
                    'OUTSIDE_TIME'=>DB_TIME_WITHOUT_SECONDS_FMT,
                    'FIRST_DATETIME'=>DB_DATE_TIME_FMT,
                    'LAST_DATETIME'=>DB_DATE_TIME_FMT,);

    $transform = array('LAST_DATETIME'=>array('LAST_DATETIME'=>array('/23:59:59/'=>MATCH_NO_EXIT)),
                       'INSIDE_TIME'=>array('LAST_DATETIME'=>array('/23:59:59/'=>'00:00')),
                       'OUTSIDE_TIME'=>array('LAST_DATETIME'=>array('/23:59:59/'=>'00:00')));

    $reports[] = array('name'=>sprintf(REPORT_WORK_DAY_HOURS_NAME,$date),
                       'style'=>$style, 
                       'format'=>$format, 
                       'transform'=>$transform,
                       'data'=>$data,
                       'as_file'=>true); 
    $ret = $data;
  } 
  return $ret;
}

function get_work_day_begin($log,$db,$date,&$reports) {
	
  $ret = false;

  $data = $db->getRecords(sprintf('SELECT FIO, FIRST_DATETIME
                                     FROM UFS_GET_WORK_DAY_HOURS(%s) 
                                    ORDER BY 1',
                                  "'".$date."'"));
  if ($data) {

    $style = array('FIRST_DATETIME'=>'text-align:center;');

    $format = array('FIRST_DATETIME'=>TIME_FMT);

    $transform = array();

    $reports[] = array('name'=>sprintf(REPORT_WORK_DAY_BEGIN_NAME,$date),
                       'style'=>$style, 
                       'format'=>$format, 
                       'transform'=>$transform,
                       'data'=>$data,
                       'as_file'=>true); 
    $ret = $data;
  } 
  return $ret;
}

function get_work_week_hours($log,$db,$date1,$date2,&$reports) {
	
  $ret = false;

  $data = $db->getRecords(sprintf('SELECT FIO, FIRST_DATE, LAST_DATE, INSIDE_HOURS, OUTSIDE_HOURS 
                                     FROM UFS_GET_WORK_WEEK_HOURS(%s) ORDER BY 1 ',
                                  "'".$date2."'")); 
  if ($data) {
    
    $style = array('BULDING'=>'text-align:center;',
                   'LAST_DATE'=>'text-align:center;',
                   'FIRST_DATE'=>'text-align:center;',
                   'INSIDE_HOURS'=>'text-align:right;',
                   'OUTSIDE_HOURS'=>'text-align:right;');

    $format = array('FIRST_DATE'=>DB_DATE_FMT,
                    'LAST_DATE'=>DB_DATE_FMT); 

    $transform = array();

    $reports[] = array('name'=>sprintf(REPORT_WORK_WEEK_HOURS_NAME,$date1,$date2),
                       'style'=>$style, 
                       'format'=>$format, 
                       'transform'=>$transform,
                       'data'=>$data,
                       'as_file'=>true); 
    $ret = $data;
  } 
  return $ret;
}

function get_work_month_hours($log,$db,&$reports) {


  $ret = false;

  $data = $db->getRecords('SELECT ID, FIO, DAY_DATE, FIRST_DATETIME, LAST_DATETIME, INSIDE_TIME, OUTSIDE_TIME
                             FROM UFS_GET_WORK_MONTH_HOURS(NULL,NULL) /*WHERE ID IN (1394,1495) */
                            ORDER BY 2,3 '); 
  if ($data) {

    $min_date = false;
    $max_date = false;
    
    $row = array();
    $new_data = array();
    $oldID = false;
    
    $format = array(); 
    $transform = array();
    
    $style = array('FIO'=>'white-space: nowrap;',
               'IN_TIME_OK'=>'text-align:left;color:green;',
               'OUT_TIME_OK'=>'text-align:right;color:red;',
               'IN_TIME_ERROR'=>'text-align:left;color:silver;',
               'OUT_TIME_ERROR'=>'text-align:right;color:silver;',
               'HOURS'=>'text-align:center;');
    
    foreach ($data as $drow) {
      
      $date = $drow['DAY_DATE'];
      $d = strtotime($date);
      
      if (!$min_date) {
        $min_date = $d;
      } elseif ($d<$min_date) {
        $min_date = $d;
      }
      
      if (!$max_date) {
        $max_date = $d;
      } elseif ($d>$max_date) {
        $max_date = $d;
      }
      
      if ($oldID && ($oldID!=$drow['ID'])) {        
        $new_data[] = $row;
        $row = array();
      }
        
      $row['FIO'] = $drow['FIO'];
      
      $in_time = date(TIME_HOURS_AND_MINUTES_FMT,strtotime($drow['FIRST_DATETIME']));
      $out_time = date(TIME_HOURS_AND_MINUTES_FMT,strtotime($drow['LAST_DATETIME']));
                  
      $name = date(DATE_SHORT_FMT,$d);      
      
      //Обработка ситуации, когда сотрудник не вышел из офиса (время 23:59)      
      $d = date_parse_from_format('Y-m-d H:i:s', $drow['LAST_DATETIME']);
      if ($d['hour'] == 23 && $d['minute'] == 59){
          $row[$name] = array('IN_TIME_ERROR'=>ZERO_TIME,'OUT_TIME_ERROR'=>ZERO_TIME);
      } else {
          $row[$name] = array('IN_TIME_OK'=>$in_time,'OUT_TIME_OK'=>$out_time);          
      }
      
      $style[$name] = 'text-align:center;';
      
      if (!isset($row['HOURS'])) {
        $row['HOURS'] = 0;
      }
      
      $row['HOURS'] = $row['HOURS'] + (strtotime($drow['LAST_DATETIME'])-strtotime($drow['FIRST_DATETIME']))/(60*60);
      
      //подсчет количества времени INSIDE ведется в секундах
      $d = date_parse_from_format('H:i:s', $drow['INSIDE_TIME']);
      $row['HOURS'] = $row['HOURS'] + $d['hour']*60*60 + $d['minute'] * 60 + $d['second'];
      
      $oldID = $drow['ID'];
    }
    
    if ($oldID) {
      $new_data[] = $row;
    }
    
    $week_days = array(1=>'понед-ник',2=>'вторник',3=>'среда',4=>'четверг',5=>'пятница',6=>'суббота',0=>'воск-ние');
    
    $data = array();
    
    foreach ($new_data as $row) {
      
      $r['FIO'] = $row['FIO'];
      //$r['HOURS'] = round($row['HOURS']);
      $r['HOURS'] = round($row['HOURS']/(60*60));
      
      for ($i=$min_date; $i<=$max_date; $i=$i+(60*60*24)) {
        
        $name = date(DATE_SHORT_FMT,$i);
        $wd = date('w',$i);
        if(isset($row[$name]))
            $r[sprintf('%s %s',$name,$week_days[$wd])] = $row[$name];
        else
            $r[sprintf('%s %s',$name,$week_days[$wd])] = array('IN_TIME_OK'=>'','OUT_TIME_OK'=>'');
      }
      
      $data[] = $r;
    }
    
    $reports[] = array('name'=>sprintf(REPORT_WORK_MONTH_HOURS_NAME,date(DB_DATE_FMT,$min_date),date(DB_DATE_FMT,$max_date)),
                       'style'=>$style, 
                       'format'=>$format, 
                       'transform'=>$transform,
                       'data'=>$data,
                       'as_file'=>true,
                       'as_body'=>false); 
    $ret = $data;
    
  }
  return $ret;
}
       
function get_body_by_report($report,$old) {
	
  $ret = '<table width=auto cellspacing=0 cellpadding=3 border=1>';
  $first = true;

  foreach ($report['data'] as $data) {

    if ($first) {

      $ret.= '<tr>';
      foreach($data as $n=>$v) {
      
        $name = 'FIELD_'.$n;
        if (isset ($GLOBALS[$name])) {
          $name = $GLOBALS[$name];
        } else {
          $name = $n;
        }
        
        $td = $name;
        if (!is_array($v)) {
        
          $td = sprintf('<b>%s</b>',$name);
          
        } else {
          
          $td = '<table width=100% cellspacing=0 cellpadding=2 border=0 style="border: 0 1px 0 0">';
          $td.= sprintf('<tr><td style="text-align:center" colspan="%d"><b>%s</b></td></tr>',sizeOf($v),$name)."\n";
          $td1 = "";
          $flag = false;
          foreach ($v as $n1=>$v1) {

            $name1 = 'FIELD_'.$n1;
            if (isset ($GLOBALS[$name1])) {
              $name1 = $GLOBALS[$name1];
            } else {
              $name1 = $n1;
            }
            $td1.= sprintf('<td>%s</td>',$name1);
            $flag = true;
          }
          $td.= sprintf('<tr>%s</tr>',$td1)."\n";
          $td.= '</table>';
        }
        
        $ret.= '<td style="text-align:center;background-color:#eeeeee">'.$td.'</td>';
      }
      $ret.= '</tr>'."\n";
      $first = false;
    }

    $ret.= '<tr>';

    foreach($data as $n=>$v) {
        
      if (is_string($v)) {
        
        $v = ($old)?iconv('windows-1251','UTF-8',$v):$v;
        
      } elseif(is_array($v)) {
        
        foreach ($v as $n1=>$v1) {
          
          if (is_string($v1)) {
            $v1 = ($old)?iconv('windows-1251','UTF-8',$v1):$v1;
          }
          $v[$n1] = $v1;
        }
      }
      $data[$n] = $v;
    }

    foreach($data as $n=>$v) {

      if (!is_array($v)) { 
        $style = isset($report['style'][$n]) ? ' style="'.$report['style'][$n].'"' : '';
        if (!is_null($v)) {
          $v = isset($report['format'][$n]) ? date($report['format'][$n],strtotime($v)) : $v;
        }   

        $transform = isset($report['transform'][$n]) ? $report['transform'][$n] : false;
        if ($transform) {

          foreach ($data as $n1=>$v1) {

            if (isset($transform[$n1])) {

              foreach($transform[$n1] as $t=>$tv) {

                if (preg_match($t,$v1)) {
                  $v = $tv;
                }
              }
            }
          }
        } 
      } else {
        
        $td1 = '<table width=100% cellspacing=0 cellpadding=2 border=0><tr>';
        foreach ($v as $n1=>$v1) {
          
          $style1 = isset($report['style'][$n1]) ? ' style="'.$report['style'][$n1].'"' : '';
          $td1.= sprintf('<td%s>%s</td>',$style1,$v1)."\n";
        }
        $td1.= '<tr></table>';
        $v = $td1;
      }
      $ret.= sprintf('<td%s>%s</td>',$style,$v);
    }
    $ret.= '</tr>'."\n";
  }

  $ret.= '</table>';

  return $ret;
}

function get_body_and_headers($report,$old,&$headers) {

  $random_hash = md5(date('r', time()).random_string(32));

  $headers ='MIME-Version: 1.0'."\r\n";
  $headers.='Content-Type: multipart/mixed; boundary="REPORT-mixed-'.$random_hash.'"'."\r\n";
  $headers.='Return-Path: <mailer@ufs-financial.ch>'."\r\n";

  $name = sprintf('=?utf-8?B?%s?=',base64_encode('Система контроля рабочего времени'));
  $headers.=sprintf('From: %s <mailer@ufs-financial.ch>',$name);
  	

  $message ='--REPORT-mixed-'.$random_hash."\n";
  $message.='Content-Type: multipart/alternative; boundary="REPORT-alt-'.$random_hash.'"'."\n\n";

  $message.='--REPORT-alt-'.$random_hash."\n";
  $message.='Content-Type: text/html; charset="utf-8"'."\n\n";

  $html ='<html>'."\n";
  $html.='<head>'."\n";
  $html.='<meta charset="utf-8">'."\n";
  $html.='<meta http-equiv=Content-Type content="text/html; charset=utf-8">'."\n";
  $html.='</head>'."\n";
  $html.='<body>'."\n";
  $html.='<table width=100% cellspacing=0 cellpadding=0 border=0>'."\n";
  $html.='<tr><td style="padding-left:10px"><h3>'.$report['name'].'</h3></td></tr>'."\n";
  $html.='<tr><td><table width=90% cellspacing=0 cellpadding=0 border=0>'."\n";
  
  $body = get_body_by_report($report,$old); 
  
  $html.='<tr><td>'.$body.'</td></tr>'."\n";
  $html.='</table></td></tr>'."\n";
  $html.='</table>'."\n";
  $html.='</body>'."\n";
  $html.='</html>';
  
  if (isset($report['as_body'])) {
    if ($report['as_body']) {
      $message.=$html."\n";    
    }  else {
      $message.="\n"; 
    }
  } else {
    $message.=$html."\n"; 
  } 
  
  $message.='--REPORT-alt-'.$random_hash.'--'."\n";
  
  if (isset($report['as_file']) && ($report['as_file'])) {

    $attachment = chunk_split(base64_encode($html));

    $message.='--REPORT-mixed-'.$random_hash."\n";
    $message.='Content-Type: text/html; charset="utf-8" name="'.$report['name'].'"'."\n";
    $message.='Content-Disposition: attachment; filename="'.$report['name'].'.html"'."\n";
    $message.='Content-Transfer-Encoding: base64'."\n\n";
    $message.=$attachment."\n";
  }
  

  $message.='--REPORT-mixed-'.$random_hash.'--'."\n";

  return $message;	
}

$mode = 99; // everything (0), absence (1), day hours (2), week hours (3), absence + day hours (4), month hours (5) 
$old = false;

if (isset($_REQUEST['mode'])) {
  $mode = intval($_REQUEST['mode']);
} else {
  $mopt = getopt('m:');
  if (isset($mopt['m'])) {
    $mode = intval($mopt['m']);
  }
  $oopt = getopt('o:');
  $old = isset($oopt['o'])?true:false;  
}

$log = new Log (sprintf(LOG_MAILING,$mode,'%s'),true); 
if ($log) {

  $stamp = microtime(true);
  $log->writeInfo(str_repeat('-',50));
  try {

    $current = date(DATE_FMT,time());
    $today = date(DB_DATE_FMT,time());

    $date0 = date_create($current);
    date_add($date0, date_interval_create_from_date_string('-7 days'));
    $date0 = date_format($date0,DB_DATE_FMT);

    $date = date_create($current);
    date_add($date, date_interval_create_from_date_string('-1 days'));
    $date = date_format($date,DB_DATE_FMT);

    $log->writeInfo('Connecting to Database ...');
    
    $db_name = ($old)?DB_NAME_OLD:DB_NAME;
    $db = new Firebird($log,$db_name,DB_USER,DB_PASS);
    if ($db) {
        
      $log->writeInfo(sprintf('Connected. Mode is %d',$mode));

      $emails = array(); 

      // need to replace sdfsdf
      $emails[] = array('mailing_id'=>0,'name'=>'Сергей','email'=>'tsv@ufs-financial.ch','params'=>'');
      //$emails[] = array('mailing_id'=>100,'name'=>'Руслан','email'=>'zrv@ufs-financial.ch','params'=>'');
      $emails[] = array('mailing_id'=>200,'name'=>'Boris','email'=>'kba@ufs-financial.ch','params'=>'');
      //$emails[] = array('mailing_id'=>200,'name'=>'Boris','email'=>'kba@ufs-federation.com','params'=>'');
      $emails[] = array('mailing_id'=>300,'name'=>'Andrey','email'=>'lan@ufs-financial.ch','params'=>'');

      $reports = array();

      switch ($mode) {
        case 0: {
          $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>'');

          get_absence_days($log,$db,$date,$reports);
          get_work_day_hours($log,$db,$date,$reports);
          get_work_week_hours($log,$db,$date0,$date,$reports); 
          break;
        }
        case 1: {
          $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>'');
          $emails[] = array('mailing_id'=>11,'name'=>'','email'=>'hr@ufs-federation.com','params'=>'');
          $emails[] = array('mailing_id'=>21,'name'=>'','email'=>'zvk@ufs-federation.com','params'=>'');

          get_absence_days($log,$db,$date,$reports);
          break;
        }
        case 2: {
          $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>'');
          $emails[] = array('mailing_id'=>11,'name'=>'','email'=>'hr@ufs-federation.com','params'=>'');
          $emails[] = array('mailing_id'=>21,'name'=>'','email'=>'zev@ufs-federation.com','params'=>'');

          get_work_day_hours($log,$db,$date,$reports);
          break;
        }
        case 3: {
          $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>''); 
          $emails[] = array('mailing_id'=>11,'name'=>'','email'=>'hr@ufs-federation.com','params'=>'');

          get_work_week_hours($log,$db,$date0,$date,$reports);
          break;
        }
        case 4: {
          get_absence_days($log,$db,$date,$reports);
          get_work_day_hours($log,$db,$date,$reports);
          break;
        }
        case 5: {
          $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>''); 
          $emails[] = array('mailing_id'=>11,'name'=>'','email'=>'hr@ufs-federation.com','params'=>'');
          
          get_work_month_hours($log,$db,$reports);
          break;
	    }
        case 6: {
          $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>''); 
          $emails[] = array('mailing_id'=>11,'name'=>'','email'=>'hr@ufs-federation.com','params'=>'');
          
          get_work_day_begin($log,$db,$today,$reports);
          break;
        }
        case 99: {
         // $emails[] = array('mailing_id'=>1,'name'=>'Miki','email'=>'imv@ufs-financial.ch','params'=>''); 
         /* get_absence_days($log,$db,$date,$reports);
          get_work_day_hours($log,$db,$date,$reports);
          get_work_week_hours($log,$db,$date0,$date,$reports); 
          get_work_month_hours($log,$db,$reports);*/
          
          //get_work_day_begin($log,$db,$today,$reports);
          
          break;
        }
      }

      $log->writeInfo(sprintf('Email count=%d / Report count=%d',sizeof($emails),sizeof($reports)));

      if (sizeof($emails)>0 && sizeof($reports)>0) {

        foreach ($reports as $report) {

          $connection = new Connection($log,GATE_URL);
          try {
            $headers = '';
            $body = get_body_and_headers($report,$old,$headers);
            if ($body) {
              $error = '';
              $ret = $connection->sendmails($emails,$report['name'],$body,$headers,$error);
              if (!$ret) {
                $log->writeError($error);
              }
            } 
          } catch (Exception $e) {
                  $log->writeException($e);
          }
          unset($connection);
        }
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
