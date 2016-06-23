<?php

require_once 'consts.php'; 
require_once 'log.php';
require_once 'mssql.php';

$log = new Log (LOG_QUOTES);

if ($log) {
	
  $log->writeInfo(str_repeat('-',50));	

  try {
	
  	$log->writeInfo('Starting ...');
  	
  	$bloomberg = new MSSql($log, BLOOMBERG_HOST, BLOOMBERG_DB, BLOOMBERG_DB_USER, BLOOMBERG_DB_PASS);
  	
  	if ($bloomberg) {
  		
  		$bloomberg->setAnsiWarningsOn();
  		$bloomberg->setAnsiNullsOn();
  		
  		$log->writeInfo('Bloomberg database is connected. Selecting data ...');
  		
  		
  		$quotes = $bloomberg->getRecords('SELECT * FROM [BLOOMBERG_XLS]...[FI$]');
  		if (is_array($quotes)) {
  			
  			$log->writeInfo(sprintf('There are %d record(s)',sizeOf($quotes)));

  			$doc = new DOMDocument('1.0','UTF-8');
  			$doc->preserveWhiteSpace = false;
  			$doc->formatOutput = true;
  			$root = $doc->createElement('data');
  			$doc->appendChild($root);
  			
  			foreach ($quotes as $q) {
  				
  			  $node = $doc->createElement('item');
  			  $root->appendChild($node);
  			  
  			  $node->appendChild($doc->createElement('short_name',$q['SHORT_NAME']));
  			  $node->appendChild($doc->createElement('security_name',$q['SECURITY_NAME']));
  			  $node->appendChild($doc->createElement('id_isin',$q['ID_ISIN']));
  			  $node->appendChild($doc->createElement('bid',$q['BID']));
  			  $node->appendChild($doc->createElement('ask',$q['ASK']));
  			  $node->appendChild($doc->createElement('px_close_1d',$q['PX_CLOSE_1D']));
  				
  			}
  			
  			$data = $doc->saveXML();
  			
  			$log->writeInfo(sprintf('Data is %s',$data));
  			
  			echo $data;
  			
  		} else
  			$log->writeError('Quotes data is not found');
  		
  			
  		unset($bloomberg);

  	} else
  		$log->writeError('Could not connect to Bloomberg database');
  	
  } catch (Exception $e) {
  	
  	$log->writeException($e);
  	
  }

  $log->writeInfo('Game over :-)');
  
  unset($log);
  
}


?> 