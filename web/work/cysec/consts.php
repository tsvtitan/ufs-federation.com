<?php

define ('LOG_FORMAT','%s %s: %s');
define ('LOG_OUTGOING','/install/php/work/cysec/logs/outgoing_%s.log');
define ('LOG_INCOMING','/install/php/work/cysec/logs/incoming_%s.log');

/*define ('LOG_OUTGOING','/var/www/cysec/logs/outgoing_%s.log');
define ('LOG_INCOMING','/var/www/cysec/logs/incoming_%s.log');*/


define ('FANSY_DB','10.1.1.105:E:\Firebird\BASES\SPECTRE_EXSCALE_DATA.FDB');
define ('FANSY_DB_USER','SYSDBA');
define ('FANSY_DB_PASS','masterkey');
define ('FANSY_DB_DATE_TIME_FMT','d.m.Y H:i:s');
define ('FANSY_DB_DATE_FMT','d.m.Y');

define ('CYSEC_DB_HOST','localhost');
define ('CYSEC_DB','cysec');
define ('CYSEC_DB_USER','root');
define ('CYSEC_DB_PASS','1qsc2wdv3efb');
define ('CYSEC_DB_DATE_TIME_FMT','Y-m-d H:i:s');

define ('CYSEC_SFTP_FILE_IN_FMT','EX_DATTRA_CY_%s_%s.xml');
define ('CYSEC_SFTP_FILE_OUT_EXP','(^CY_FDBTRA_EX_[0-9]{6}_[0-9]{2}.xml$)');

define ('CYSEC_SFTP_HOST','10.1.1.70');
define ('CYSEC_SFTP_PORT',22);
define ('CYSEC_SFTP_USER','root');
define ('CYSEC_SFTP_PASS','1qsc2wdv3efb');
define ('CYSEC_SFTP_INCOMING','/install/php/work/cysec/incoming');
define ('CYSEC_SFTP_OUTGOING','/install/php/work/cysec/outgoing');

/*define ('CYSEC_SFTP_HOST','212.31.100.75');
define ('CYSEC_SFTP_PORT',22);
define ('CYSEC_SFTP_USER','ex');
define ('CYSEC_SFTP_PASS','57gzs33');
define ('CYSEC_SFTP_INCOMING','/home/ex/incoming');
define ('CYSEC_SFTP_OUTGOING','/home/ex/outgoing');*/

define ('SUBJECT_OUT','Exscale: Outgoing report (created at %s, sent at %s)');
define ('SUBJECT_IN','Exscale: Incoming report (created at %s, received at %s)');

//$REPORT_DATE = date_create();
$REPORT_DATE = date_create('2013-10-01');
//$REPORT_EMAILS = array ('cysec@ufs-federation.com.cy');
$REPORT_EMAILS = array ('tsv@ufs-federation.com');




?>