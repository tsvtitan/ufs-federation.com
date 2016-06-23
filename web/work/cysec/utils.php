<?php

require_once 'consts.php';
require_once 'firebird.php';
require_once 'mysql.php';
require_once 'sftp.php';
require_once 'log.php';

function collect_trans ($LOG) {

  $ret = false;

  $LOG->writeInfo('Connecting to Fansy database ...');

  $fansy_db = new Firebird ($LOG,FANSY_DB,FANSY_DB_USER,FANSY_DB_PASS);

  if ($fansy_db) {

    $LOG->writeInfo('Fansy database has connected. Connecting to CySec database ...');

    $cysec_db = new Mysql ($LOG,CYSEC_DB_HOST,CYSEC_DB_USER,CYSEC_DB_PASS,CYSEC_DB);

    if ($cysec_db) {

      $LOG->writeInfo('CySec database has connected. Selecting data from Fansy ...'); 

      $dt = $GLOBALS['REPORT_DATE'];
      date_add($dt, date_interval_create_from_date_string('-1 days'));
      $dt = date_format($dt,FANSY_DB_DATE_FMT); 

      $reestr = $fansy_db->getRecords(sprintf('SELECT * FROM UFS_CYSEC_DEALS_BY_DATE(%s)',"'".$dt."'"));
      if (is_array($reestr)) {

        $LOG->writeInfo(sprintf('There are %d record(s)',sizeOf($reestr)));

        foreach ($reestr as $r) {

          $LOG->writeInfo(sprintf('Checking Tran %s ...',$r['TRANSACTION_REFERENCE_NUMBER']));

          $rec = $cysec_db->getRecord(sprintf('SELECT count(*) as cnt 
                                                 FROM trans 
                                                WHERE TransactionReferenceNumber=%s',
                                              "'".$r['TRANSACTION_REFERENCE_NUMBER']."'"));
          if (is_array($rec) && ($rec['cnt']==0)) {

            $tran['tran_type'] = 0;
            $tran['client_name'] = iconv('windows-1251', 'UTF-8', $r['CLIENT_NAME']);
            $tran['instrument_name'] = iconv('windows-1251', 'UTF-8', $r['INSTRUMENT_NAME']);

            $tran['ReportingFirmIdentification'] = 'EXIVCY21XXX';

            list($date,$time) = explode(' ',$r['TRADE_DATE']);
            $tran['TradingDay'] = $date; 
            $tran['TradingTime'] = $time;
            $tran['TimeIdentifier'] = substr(date('P'),0,3);
            $tran['BuySellIndicator'] = $r['BUY_SELL_INDICATOR'];
            $tran['TradingCapacity'] = $r['TRADING_CAPACITY'];

            $tran['InstrumentIdentification'] = $r['INSTRUMENT_IDENTIFICATION'];

            $tran['AIIExchangeCode'] = NULL;
            $tran['AIIProductCode'] = NULL;
            $tran['AIIDerivativeType'] = NULL;
            $tran['AIIPutCallIdentifier'] = NULL;
            $tran['AIIExpiryDate'] = NULL;
            $tran['AIIStrikePrice'] = NULL;

            $tran['PriceCurrency'] = $r['PRICE_CURRENCY'];
            $tran['PricePercentage'] = NULL;

            $tran['PriceNotation'] = $r['PRICE_NOTATION'];
            $tran['Quantity'] = $r['QUANTITY'];

            $tran['CounterpartyIdentificationBIC'] = NULL;
            $tran['CounterpartyIdentificationMIC'] = NULL;
            $tran['CounterpartyIdentificationCustomerInternal'] = $r['COUNTER_PARTY_INTERNAL']; 

            $tran['ClientBIC'] = NULL;
            $tran['ClientInternal'] = $r['CLIENT_INTERNAL'];

            $tran['TradingVenueCodeBIC'] = $r['TRADING_VENUE_CODE_BIC'];
            $tran['TradingVenueCodeMIC'] = $r['TRADING_VENUE_CODE_MIC'];
            $tran['TradingVenueCodeXOFF'] = $r['TRADING_VENUE_CODE_XOFF'];

            $tran['TransactionReferenceNumber'] = $r['TRANSACTION_REFERENCE_NUMBER'];

            $tran['CancelledTransactionFlag'] = NULL;
            $tran['CancelledInstrumentIdentifier'] = NULL;

            $LOG->writeInfo('Inserting Tran into database ...');

            if ($cysec_db->insertRecord('trans', $tran)) {

              $tran_id = $cysec_db->lastId();

              $LOG->writeInfo(sprintf('Tran has inserted (%d)',$tran_id));
            } else 
              $LOG->writeError(sprintf('Could not insert Tran. Error: %s ',$cysec_db->lastError()));

          } else
            $LOG->writeError(sprintf('Tran %s already exists',$r['TRANSACTION_REFERENCE_NUMBER']));
        }
        $ret = true;

      } else
        $LOG->writeError('Fansy data has not found');

      unset($cysec_db);

    } else
      $LOG->writeError('Could not connect to CySec database');

    unset($fansy_db);

  }  else
    $LOG->writeError('Could not connect to Fansy databases');

  return $ret;
}

function create_out($LOG) {
	
  $ret = false;

  $LOG->writeInfo('Connecting to CySec database ...');

  $cysec_db = new Mysql($LOG,CYSEC_DB_HOST,CYSEC_DB_USER,CYSEC_DB_PASS,CYSEC_DB);

  if ($cysec_db) {

    $LOG->writeInfo('CySec database has connected. Selecting Trans ...');

    $trans = $cysec_db->getRecords('SELECT * FROM trans 
                                     WHERE processed IS NULL 
                                     ORDER BY TradingDay, TradingTime, tran_type');
    if (is_array($trans)) {

      $LOG->writeInfo(sprintf('There are %d tran(s). Creating out xml-file ...',sizeof($trans)));

      $sequnce = 0;
      $r = $cysec_db->getRecord('SELECT sequence 
                                   FROM outs 
                                  ORDER BY sequence desc, created desc LIMIT 1');
      if (is_array($r))
        $sequnce = $r['sequence'];

      $sequnce++;

      $stamp = time();

      $out['created'] = date(CYSEC_DB_DATE_TIME_FMT,$stamp); 
      $out['sequence'] = $sequnce;

      $s = sprintf('%06d',$sequnce);
      $out['name'] = sprintf(CYSEC_SFTP_FILE_IN_FMT,$s,date('y',$stamp));

      $LOG->writeInfo(sprintf('Name of file is %s',$out['name']));

      $doc = new DOMDocument('1.0','UTF-8');
      $doc->preserveWhiteSpace = false;
      $doc->formatOutput = true;

      $root = $doc->createElement('MiFIDTransactionReporting');
      $root->setAttribute('xsi:noNamespaceSchemaLocation','CYSEC_DATTRA2.1.xsd');
      $root->setAttribute('AuthorityKey','EX'); 
      $root->setAttribute('CreationDate',date('Y-m-d',$stamp));
      $root->setAttribute('CreationTime',date('H:i:s',$stamp));
      $root->setAttribute('CreationTimeOffset',substr(date('P',$stamp),0,3));
      $root->setAttribute('Version','2.1');
      $doc->appendChild($root);

      foreach ($trans as $d) {

        if ($d['tran_type']==0) { 

          $LOG->writeInfo(sprintf('Transaction %s has detected (id => %d). Creating node ...',$d['TransactionReferenceNumber'],$d['tran_id']));

          $node = $doc->createElement('TransactionRecordInfo');
          $root->appendChild($node);

          $node->appendChild($doc->createElement('ReportingFirmIdentification',$d['ReportingFirmIdentification']));
          $node->appendChild($doc->createElement('TradingDay',$d['TradingDay']));

          $n1 = $doc->createElement('TradingTime');
          $node->appendChild($n1);

          $n1->appendChild($doc->createElement('TradingTime',$d['TradingTime']));
          $n1->appendChild($doc->createElement('TimeIdentifier',$d['TimeIdentifier']));

          $node->appendChild($doc->createElement('BuySellIndicator',$d['BuySellIndicator']));
          $node->appendChild($doc->createElement('TradingCapacity',$d['TradingCapacity']));

          if (isset($d['InstrumentIdentification'])) {

            $node->appendChild($doc->createElement('InstrumentIdentification',$d['InstrumentIdentification']));
          } else {
            
            $n1 = $doc->createElement('AIIInstrumentIdentification');
            $node->appendChild($n1);

            $n1->appendChild($doc->createElement('AIIExchangeCode',$d['AIIExchangeCode']));
            $n1->appendChild($doc->createElement('AIIProductCode',$d['AIIProductCode']));
            $n1->appendChild($doc->createElement('AIIDerivativeType',$d['AIIDerivativeType']));
            $n1->appendChild($doc->createElement('AIIPutCallIdentifier',$d['AIIPutCallIdentifier']));
            $n1->appendChild($doc->createElement('AIIExpiryDate',$d['AIIExpiryDate']));
            $n1->appendChild($doc->createElement('AIIStrikePrice',$d['AIIStrikePrice']));
          }

          $n1 = $doc->createElement('UnitPrice');
          $node->appendChild($n1);

          if (isset($d['PriceCurrency'])) 
            $n1->appendChild($doc->createElement('PriceCurrency',$d['PriceCurrency']));
          else
            $n1->appendChild($doc->createElement('PricePercentage',$d['PricePercentage']));

          $node->appendChild($doc->createElement('PriceNotation',$d['PriceNotation']));
          $node->appendChild($doc->createElement('Quantity',$d['Quantity']));

          $n1 = $doc->createElement('Counterparty');
          $node->appendChild($n1);

          if (isset($d['CounterpartyIdentificationBIC']))
            $n1->appendChild($doc->createElement('CounterpartyIdentificationBIC',$d['CounterpartyIdentificationBIC']));
          else if (isset($d['CounterpartyIdentificationMIC']))
            $n1->appendChild($doc->createElement('CounterpartyIdentificationMIC',$d['CounterpartyIdentificationMIC']));
          else {
            
            $n2 = $doc->createElement('CounterpartyIdentificationCustomer');
            $n1->appendChild($n2);

            $n2->appendChild($doc->createElement('CounterpartyIdentificationCustomerInternal',$d['CounterpartyIdentificationCustomerInternal']));
          }	

          $n1 = $doc->createElement('Client');
          $node->appendChild($n1);

          if (isset($d['ClientBIC']))
            $n1->appendChild($doc->createElement('ClientBIC',$d['ClientBIC']));
          else
            $n1->appendChild($doc->createElement('ClientInternal',$d['ClientInternal']));

          $n1 = $doc->createElement('TradingVenueCode');
          $node->appendChild($n1);

          if (isset($d['TradingVenueCodeBIC']))
            $n1->appendChild($doc->createElement('TradingVenueCodeBIC',$d['TradingVenueCodeBIC']));
          else if (isset($d['TradingVenueCodeMIC']))
            $n1->appendChild($doc->createElement('TradingVenueCodeMIC',$d['TradingVenueCodeMIC']));
          else 
            $n1->appendChild($doc->createElement('TradingVenueCodeXOFF',$d['TradingVenueCodeXOFF']));

          $node->appendChild($doc->createElement('TransactionReferenceNumber',$d['TransactionReferenceNumber']));

        } else if ($d['tran_type']==1) {

          $LOG->writeInfo(sprintf('Cancellation %s has detected (id => %d). Creating node ...',$d['TransactionReferenceNumber'],$d['tran_id']));

          $node = $doc->createElement('CancellationRecordInfo');
          $root->appendChild($node);

          $node->appendChild($doc->createElement('CancelledTransactionUniqueIdentifier',$d['TransactionReferenceNumber']));
          $node->appendChild($doc->createElement('CancelledTransactionFlag',$d['CancelledTransactionFlag']));
          $node->appendChild($doc->createElement('CancelledInstrumentIdentifier',$d['CancelledInstrumentIdentifier']));
        }
      }		

      $out['data'] = $doc->saveXML(); 

      $LOG->writeInfo('Inserting Out into database ...');
      if ($cysec_db->insertRecord('outs', $out)) {

        $out_id = $cysec_db->lastId();

        $LOG->writeInfo(sprintf('Out has inserted (%d). Inserting Tran_Outs ...',$out_id));

        foreach ($trans as $d) {
          $temp = array ('tran_id'=>$d['tran_id'], 'out_id'=>$out_id);
          $cysec_db->insertRecord('tran_outs', $temp);
        }

        $ret = true;
      } else {
        $LOG->writeError(sprintf('Could not insert Out. Error: %s ',$cysec_db->lastError()));
      } 	

      unset($doc);

    } else
      $LOG->writeInfo('Trans have not found');

    unset ($cysec_db);

  } else
    $LOG->writeError('Could not connect to CySec database');

  return $ret;
}

function send_out($LOG, &$OUT, $upload = true) {
	
  $ret = false;

  $LOG->writeInfo('Connecting to CySec database ...');
  $cysec_db = new Mysql($LOG,CYSEC_DB_HOST,CYSEC_DB_USER,CYSEC_DB_PASS,CYSEC_DB);
  if ($cysec_db) {

    $LOG->writeInfo('CySec database has connected. Selecting Out ...');

    $out = $cysec_db->getRecord('SELECT out_id, name, data, created, sent
                                   FROM outs
                                  WHERE sent IS NULL
                                  ORDER BY sequence desc, created desc LIMIT 1');
    if (is_array($out)) {

      $LOG->writeInfo(sprintf('Out has selected (id => %d). Trying to uppload ...',$out['out_id']));
      $where['out_id'] = $out['out_id'];
      try {

        if ($upload) {
          
          $LOG->writeInfo('Connecting to CySec ftp ...');
          
          $cysec_ftp = new SFTP(CYSEC_SFTP_HOST,CYSEC_SFTP_PORT);
          $cysec_ftp->login(CYSEC_SFTP_USER,CYSEC_SFTP_PASS);

          $LOG->writeInfo(sprintf('CySec ftp has connected. Uploading file %s ...',$out['name']));

          $remote = sprintf('%s%s%s',CYSEC_SFTP_INCOMING,DIRECTORY_SEPARATOR,$out['name']);
          $cysec_ftp->uploadData($out['data'],$remote);

          unset($cysec_ftp);

          $LOG->writeInfo(sprintf('File %s has uploaded. Updating Out ...',$out['name']));
          
        } else {
          
          $LOG->writeInfo('Upload flag is false. Updating Out ...');
        }  

        $data['sent'] = date(CYSEC_DB_DATE_TIME_FMT);
        $ret = $cysec_db->updateRecord('outs',$data,$where);
        if (!$ret) {
          $LOG->writeError(sprintf('Could not update Out (error: %s) ',$cysec_db->lastError()));
        } else {
          $out['sent'] = $data['sent'];
          $OUT = $out;
        }		
      } catch (Exception $e) {
        $LOG->writeException($e);
        $data['error'] = $e->getMessage(); 
        $cysec_db->updateRecord('outs',$data,$where);
      }
    } else
      $LOG->writeError('Could not find Out'); 

    unset ($cysec_db);

  } else
    $LOG->writeError('Could not connect to CySec database');

  return $ret;
}

function trim_utf8($s) {

  return trim(preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$s));
}

function receive_ins($LOG, &$INS) {
	
  $ret = false;

  $LOG->writeInfo('Connecting to CySec database ...');

  $cysec_db = new Mysql($LOG,CYSEC_DB_HOST,CYSEC_DB_USER,CYSEC_DB_PASS,CYSEC_DB);

  if ($cysec_db) {

    try {

      $LOG->writeInfo('CySec database has conneced. Connectig to CySec secure ftp ...');

      $cysec_ftp = new SFTP(CYSEC_SFTP_HOST,CYSEC_SFTP_PORT);
      $cysec_ftp->login(CYSEC_SFTP_USER,CYSEC_SFTP_PASS);

      $LOG->writeInfo('Scanning remote directory ...');

      $files = $cysec_ftp->getFiles(CYSEC_SFTP_OUTGOING,false);

      if (is_array($files)) { 

        $LOG->writeInfo(sprintf('There are %d file(s)',sizeof($files)));

        foreach ($files as $f) {

          $b = basename($f);

          $LOG->writeInfo(sprintf('Checking name of file %s ...',$b));

          if (preg_match(CYSEC_SFTP_FILE_OUT_EXP,$b)) {

            $LOG->writeInfo(sprintf('Name of file %s is valid. Searching in database ...',$b));

            $rec = $cysec_db->getRecord(sprintf('SELECT count(*) as cnt FROM ins WHERE name=%s',"'".$b."'"));
            if (is_array($rec) && ($rec['cnt']==0)) {

              $in['name'] = $b;

              $LOG->writeInfo(sprintf('Downloading file %s ...',$b));

              $in['data'] = trim_utf8($cysec_ftp->receiveData($f));

              $LOG->writeInfo(sprintf('File %s has downloaded. Insert into database ...',$b));

              $in['received'] = date(CYSEC_DB_DATE_TIME_FMT);
              $in['created'] = NULL;
              $in['error'] = NULL;
              $in['out_id'] = NULL;

              if ($cysec_db->insertRecord('ins',$in)) {

                $where['in_id'] = $cysec_db->lastId();
                $in['in_id'] = $where['in_id'];

                try {

                    $LOG->writeInfo(sprintf('Parsing file %s ...',$b));

                    $doc = new DOMDocument;
                    $doc->preserveWhiteSpace = false;
                    $doc->loadXML($in['data']);

                    if (preg_match('/[0-9]{6}/',$b,$matches)) {
                      $data['sequence'] = (int)$matches[0];
                    }

                    $FeedBackFileInfo = $doc->getElementsByTagName('FeedBackFileInfo');
                    if (isset($FeedBackFileInfo)) {

                      $FeedBackFileInfo = $FeedBackFileInfo->item(0); 

                      $cd = $FeedBackFileInfo->getAttribute('CreationDate');
                      $ct = $FeedBackFileInfo->getAttribute('CreationTime');
                      $cto = $FeedBackFileInfo->getAttribute('CreationTimeOffset');

                      $dt = strtotime(sprintf('%s %s %s hours',$cd,$ct,$cto)); 
                      $dt = date(CYSEC_DB_DATE_TIME_FMT,$dt);
                      /*$dt = strtotime(sprintf('%s %s hours',$dt,substr(date('P'),0,3)));
                      $dt = date(CYSEC_DB_DATE_TIME_FMT,$dt);*/

                      $data['created'] = $dt;

                      $OriginalFileName = $FeedBackFileInfo->getElementsByTagName('OriginalFileName');
                      if (isset($OriginalFileName)) {

                        $OriginalFileName = $OriginalFileName->item(0);
                        $fn = $OriginalFileName->nodeValue;
                        $ext = pathinfo($fn,PATHINFO_EXTENSION);
                        if (empty($ext)) {
                          $fn = sprintf('%s.xml',$fn);
                        }

                        $out = $cysec_db->getRecord(sprintf('SELECT * FROM outs WHERE name=%s',"'".$fn."'"));
                        if (is_array($out)) {

                          $data['out_id'] = $out['out_id'];
                          $in['out_id'] = $out['out_id'];

                          $NoErrors = $FeedBackFileInfo->getElementsByTagName('NoErrors');

                          if (isset($NoErrors) && ($NoErrors->length>0)) {

                            $NoErrors = $NoErrors->item(0);
                            if ($NoErrors->nodeValue=='OK') {

                              $tran_ids = $cysec_db->getRecords(sprintf('SELECT tos.tran_id
                                                                           FROM tran_outs tos
                                                                           JOIN trans t on t.tran_id=tos.tran_id
                                                                          WHERE t.processed is null and tos.out_id=%d',
                                                                        $out['out_id']));
                              if (is_array($tran_ids)) {

                                foreach ($tran_ids as $tid) {

                                  $tran_where['tran_id'] = $tid['tran_id'];
                                  $tran_data['processed'] = $data['created'];
                                  $tran_data['error'] = NULL;

                                  $cysec_db->updateRecord('trans', $tran_data, $tran_where);
                                }
                              }	 
                            } else 
                              $LOG->writeError(sprintf('Invalid NoErrors value => %s ',$node->nodeValue));

                          } else {

                            $out_data['error'] = NULL;
                            $out_where['out_id'] = $data['out_id'];

                            $cysec_db->updateRecord('outs',$out_data,$out_where);

                            $FileError = $FeedBackFileInfo->getElementsByTagName('FileError');

                            if (isset($FileError) && ($FileError->length>0)) {

                              $errors = array();
                              $FileError = $FileError->item(0);
                              foreach ($FileError->childNodes as $child) {
                                $errors[] = $child->nodeValue;
                              }

                              $out_data['error'] = implode(' | ',$errors);
                              $out_where['out_id'] = $data['out_id'];

                              if ($cysec_db->updateRecord('outs',$out_data,$out_where)) {
                                // nothing
                              }
                            }

                            $TransactionError = $FeedBackFileInfo->getElementsByTagName('TransactionError');
                            if (isset($TransactionError) && ($TransactionError->length>0)) {

                              foreach ($TransactionError as $te) {

                                $errors = array();
                                $tran_id = null;

                                foreach ($te->childNodes as $child) {

                                  if ($child->nodeName=='TransactionCAUniqueId') {

                                    $tran = $cysec_db->getRecord(sprintf('SELECT tos.tran_id
                                                                            FROM tran_outs tos
                                                                            JOIN trans t on t.tran_id=tos.tran_id
                                                                           WHERE /*t.processed is null
                                                                             AND*/ t.TransactionReferenceNumber=%s
                                                                             AND out_id=%d',
                                                                         "'".$child->nodeValue."'",$out['out_id']));
                                    if (is_array($tran)) {
                                      $tran_id = $tran['tran_id'];
                                    }	

                                  } else {
                                    $errors[] = $child->nodeValue;
                                  }
                                }

                                if (!is_null($tran_id)) {
                                  $tran_data['error'] = implode(' | ',$errors);
                                  $tran_where['tran_id'] = $tran_id;
                                  $cysec_db->updateRecord('trans',$tran_data,$tran_where);
                                }  

                              }
                            }
                          }
                        }
                    }
                  }

                  $ret = $cysec_db->updateRecord('ins',$data,$where);
                  if ($ret) {
                    $LOG->writeInfo(sprintf('File %s has parsed',$b));
                    $in['created']=$data['created'];
                    $INS[] = $in;
                  } else 	
                    $LOG->writeError(sprintf('Could not update In (error: %s) ',$cysec_db->lastError()));

                } catch (Exception $e) {

                  $LOG->writeException($e);

                  $data['error'] = $e->getMessage();
                  if ($cysec_db->updateRecord('ins',$data,$where)) {
                    $in['error']=$data['error'];
                    $INS[] = $in;
                  }
                }

              } else 
                $LOG->writeError(sprintf('Could not insert In. Error: %s ',$cysec_db->lastError()));
            } else
              $LOG->writeError(sprintf('File %s already exists',$b));
          } else 
            $LOG->writeError(sprintf('File %s has invalid name',$b));
        } 
      }
      unset($cysec_ftp);

    } catch (Exception $e) {
      $LOG->writeException($e);
    }
    unset ($cysec_db);

  } else
    $LOG->writeError('Could not connect to CySec database');

  return $ret;
}

function send_report_by_trans($LOG, $NAME, $TRANS, $ERROR, $FILES) {

  $ret = false;
  $flag=false;

  $subject=$NAME;

  $emails = $GLOBALS['REPORT_EMAILS'];

  foreach ($emails as $m) {

    $to = $m;

    $LOG->writeInfo(sprintf('Trying to send to %s ...',$to));

    $random_hash = md5(date('r', time()));

    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers.='Content-Type: multipart/mixed; boundary="REPORT-mixed-'.$random_hash.'"'."\r\n";
    $headers.='From: CySEC <mailer@ufs-financial.ch>';

    $message ='--REPORT-mixed-'.$random_hash."\n";
    $message.='Content-Type: multipart/alternative; boundary="REPORT-alt-'.$random_hash.'"'."\n\n";

    $message.='--REPORT-alt-'.$random_hash."\n";
    $message.='Content-Type: text/html; charset="utf-8"'."\n\n";

    $message.='<html>'."\n";
    $message.='<head>'."\n";
    $message.='<meta charset="utf-8">'."\n";
    $message.='<meta http-equiv=Content-Type content="text/html; charset=utf-8">'."\n";
    $message.='</head>'."\n";
    $message.='<body>'."\n";
    $message.='<h2>'.$subject.'</h2>'."\n";

    if (isset($ERROR)) {
      $message.=sprintf('<h3 style="color: red">%s</h3>'."\n",$ERROR);
    }

    $counter = 1;
    if (is_array($TRANS)) {
      
      foreach ($TRANS as $t) {

        $message.=sprintf('<span style="color: gray"># %s: </span>',$counter);
        $message.=sprintf('<b>%s</b> (<span style="color: blue">%s</span>)<br>'."\n",$t['client_name'],$t['TransactionReferenceNumber']);
        $message.=sprintf('<span style="color: gray">Issuer:</span> %s (%s)<br>'."\n",$t['instrument_name'],$t['InstrumentIdentification']);
        $message.=sprintf('<span style="color: gray">Buy/Sell indicator:</span> %s<br>'."\n",$t['BuySellIndicator']);
        $message.=sprintf('<span style="color: gray">Trading date:</span> %s %s (%s)<br>'."\n",$t['TradingDay'],$t['TradingTime'],$t['TimeIdentifier']);
        $message.=sprintf('<span style="color: gray">Quantity:</span> %s<br>'."\n",$t['Quantity']);
        $message.=sprintf('<span style="color: gray">Price:</span> %s (%s)<br>'."\n",$t['PriceCurrency'],$t['PriceNotation']);
        if (!is_null($t['processed'])) {
          $message.=sprintf('<span style="color: gray"><b>Processed at</b>: <span style="color: green">%s</span><br>'."\n",$t['processed']);
        }
        if (!is_null($t['error'])) {
          $message.=sprintf('<span style="color: gray"><b>Error</b>: <span style="color: red">%s</span><br>'."\n",$t['error']);
        }
        $message.='<br>';
        $counter++;
      }
      $message.=sprintf('<h3>Transactions in total: %d</h3><br>'."\n",sizeof($TRANS));
      
    } else {
      $message.='<h3>Transactions have not found</h3><br>'."\n";
    }	

    $message.='</body>'."\n";
    $message.='</html>';
    $message.="\n";
    $message.='--REPORT-alt-'.$random_hash.'--'."\n";

    foreach ($FILES as $f) {

      $attachment = chunk_split(base64_encode($f['data']));

      $message.='--REPORT-mixed-'.$random_hash."\n";
      $message.='Content-Type: text/xml; charset="utf-8" name="'.$f['name'].'"'."\n";
      $message.='Content-Disposition: attachment; filename="'.$f['name'].'"'."\n";
      $message.='Content-Transfer-Encoding: base64'."\n\n";
      $message.=$attachment."\n";
    }
    $message.='--REPORT-mixed-'.$random_hash.'--'."\n";

    $LOG->writeInfo($message);

    $sent=@mail($to,$subject,$message,$headers);
    if ($sent) {
      $LOG->writeInfo('Message has sent');
    } else
      $LOG->writeError(sprintf('Could not send to %s',$to));

    if ($flag)
      $ret=$ret & $sent;
    else
      $ret=$sent;

    $flag=true;
  }
  return $ret;
}

function send_report_by_out($LOG, $OUT) {

  $ret = false;

  $LOG->writeInfo('Connecting to CySec database ...');

  $cysec_db = new Mysql($LOG,CYSEC_DB_HOST,CYSEC_DB_USER,CYSEC_DB_PASS,CYSEC_DB);
  if ($cysec_db) {

    try {

      $LOG->writeInfo('CySec database has connected. Sending report ...');

      $files = array();
      $files[] = array('name'=>$OUT['name'],'data'=>$OUT['data']);

      $trans = $cysec_db->getRecords(sprintf('SELECT * 
                                                FROM trans t 
                                                LEFT JOIN tran_outs tos on tos.tran_id=t.tran_id 
                                               WHERE tos.out_id=%s 
                                               ORDER BY TradingDay, TradingTime, tran_type',
                                             $OUT['out_id']));
      if (is_array($trans)) {
        $LOG->writeInfo(sprintf('There are %d tran(s). Sending trans and file ...',sizeof($trans)));
        $s = sprintf(SUBJECT_OUT,$OUT['created'],$OUT['sent']);
        $ret = send_report_by_trans($LOG,$s,$trans,NULL,$files);
      }
    } catch (Exception $e) {
      $LOG->writeException($e);
    }
    unset ($cysec_db);
    
  } else
    $LOG->writeError('Could not connect to CySec database');

  return $ret;
}

function send_report_by_ins ($LOG, $INS) {
	
  $ret = false;
  $flag=false;

  $LOG->writeInfo('Connecting to CySec database ...');

  $cysec_db = new Mysql($LOG,CYSEC_DB_HOST,CYSEC_DB_USER,CYSEC_DB_PASS,CYSEC_DB);
  if ($cysec_db) {

    try {

      $LOG->writeInfo('CySec database has connected. Processing ins ...');

      foreach ($INS as $in) {

        $LOG->writeInfo(sprintf('In name (%s) in_id (%d) ...',$in['in_id'],$in['name']));

        $files = array();
        $files[] = array('name'=>$in['name'],'data'=>$in['data']);

        $error = "";
        $out = $cysec_db->getRecord(sprintf('SELECT * FROM outs WHERE out_id=%s',$in['out_id']));
          
        if (is_array($out)) {

          $LOG->writeInfo('Out has a response');

          $trans = $cysec_db->getRecords(sprintf('SELECT * 
                                                    FROM trans t 
                                                    LEFT JOIN tran_outs tos on tos.tran_id=t.tran_id 
                                                   WHERE tos.out_id=%s 
                                                   ORDER BY TradingDay, TradingTime, tran_type',
                                                 $in['out_id']));
          if (is_array($trans)) {
            $LOG->writeInfo(sprintf('There are %d tran(s). Sending trans and file ...',sizeof($trans)));
          } else {
            $LOG->writeInfo('Trans have not found. Sending only file ...');
          }
          
          $error = $out['error'];
          
        } else {
          $LOG->writeError('There is no out. Sending only file ...');
        }

        $s = sprintf(SUBJECT_IN,$in['created'],$in['received']);
        $sent = send_report_by_trans($LOG,$s,$trans,$error,$files);

        if ($flag)
          $ret=$ret & $sent;
        else
          $ret=$sent;

        $flag = true;
          
      }

    } catch (Exception $e) {
      $LOG->writeException($e);
    }
    unset ($cysec_db);
    
  } else
    $LOG->writeError('Could not connect to CySec database');

  return $ret;
}
?>