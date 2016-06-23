<?php

function Utf8ToCp1251($s)
{
	$s=iconv("UTF-8","CP1251",$s);
	return $s;
}

function decode($key,$s)
{
	try {
		$iv=str_repeat("\0",16);
		$s=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,$s,MCRYPT_MODE_CBC,$iv);
		return $s;
	} catch (Exception $e) {
		//
	}
}

$s="";

$fd=fopen("php://input","r");
#$fd=fopen("/install/php/work/cysec/1.dat","r");
while (!feof($fd)) {
	$s.=fread($fd,1024);
}
fclose($fd);

$s=trim($s);

if ($s!="") {

	$host = ''; 
	$key = 'test.ufs-federation.com';
	$key=strtoupper(md5($key));
	$s = decode($key,$s);

	if ($s!="") {
	
		list($rnd,$ip,$host,$process,$begin,$end,$data) = explode(chr(13).chr(10),$s);
		
		list($n,$ip) = explode('=',$ip);
		list($n,$host) = explode('=',$host);
		list($n,$process) = explode('=',$process);
		list($n,$begin) = explode('=',$begin);
		list($n,$end) = explode('=',$end);
		list($n,$data) = explode('=',$data);
		$data = base64_decode($data);

		if ($data!="") {
	      $db = mysql_connect('localhost','root','1qsc2wdv3efb');
      	  if ($db) {
		
       		$ret = mysql_select_db('cysec',$db);
       		if ($ret)  {

       			mysql_query('set names cp1251',$db);
    	    		
        		$ip = mysql_real_escape_string($ip,$db);
        		$key = md5($ip);
        		$ip = "'".$ip."'";
        		
        		$key = "'".$key."'";
        			
        		$host = mysql_real_escape_string($host,$db);
        		$host = "'".$host."'";
        			
        		$process = mysql_real_escape_string($process,$db);
        		$process = "'".$process."'";
        				        		
    	    	$begin = mysql_real_escape_string($begin,$db);
        		$begin = "'".$begin."'";
        		
        		$end = mysql_real_escape_string($end,$db);
        		$end = "'".$end."'";
        		
	        	$data = mysql_real_escape_string($data,$db);
    	    	$data = "'".$data."'";
    	    		
    	    	
        		$query = 'INSERT INTO `data` (`ip`,`host`,`process`,`begin`,`end`,`data`) '.
        		         'VALUES ('.$ip.','.$host.','.$process.','.$begin.','.$end.',AES_ENCRYPT('.$data.','.$key.'))';
        		$ret = mysql_query($query,$db);
        		
	            #echo $query;         		
        		
    	   	}
          }
		}   
	} 
}	

?>
