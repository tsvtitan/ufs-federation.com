<?php

define ('LOG_FORMAT','%s %s: %s');
define ('LOG_MAILING','/var/www/acs/logs/mailing_%s_%s.log'); // work
//define ('LOG_MAILING','/install/php/work/acs/logs/mailing_%s.log'); //test

//define ('DB_NAME','192.168.6.165:c:\program files\forsec\forsec 2.3 sql\db\forsec.gdb');
define ('DB_NAME_OLD','10.1.2.1:c:\forsec\forsec.gdb');
define ('DB_NAME','192.168.31.2:c:\SCD17K.FDB');
define ('DB_USER','SYSDBA');
define ('DB_PASS','masterkey');
define ('DB_DATE_TIME_FMT','d.m.Y H:i:s');
define ('DB_DATE_FMT','d.m.Y');
define ('DB_TIME_FMT','H:i:s');
define ('DB_TIME_WITHOUT_SECONDS_FMT','H:i');

define ('REPORT_ABSENCE_DAYS_NAME','Список сотрудников, отсутствующих в здании на %s');
define ('REPORT_WORK_DAY_HOURS_NAME','Список сотрудников, присутствующих в здании на %s');
define ('REPORT_WORK_DAY_BEGIN_NAME','Список сотрудников, пришедших в здание на %s');
define ('REPORT_WORK_WEEK_HOURS_NAME','Список сотрудников, присутствующих в здании за 7 дней (%s - %s)');
define ('REPORT_WORK_MONTH_HOURS_NAME','Список сотрудников, присутствующих в здании за месяц (%s - %s)');

define ('MATCH_NO_EXIT','Не отмечен выход');

define ('DATE_FMT','Y-m-d');
define ('TIME_FMT','H:i:s');
define ('TIME_HOURS_AND_MINUTES_FMT','H:i');
define ('DATE_SHORT_FMT','d.m.y');
define ('ZERO_TIME','00:00');

$FIELD_FIO='Сотрудник';
$FIELD_BUILDING='Здание';
$FIELD_LAST_DATE='Последний день';
$FIELD_FIRST_DATE='Первый день';
$FIELD_ABSENCE_DAYS='Отсутствие (дней)';
$FIELD_IN_DATE='Дата';
$FIELD_INSIDE_HOURS='Внутри (час)';
$FIELD_OUTSIDE_HOURS='Снаружи (час)';
$FIELD_INSIDE_TIME='Время внутри';
$FIELD_OUTSIDE_TIME='Время снаружи';
$FIELD_FIRST_DATETIME='Время прихода';
$FIELD_LAST_DATETIME='Время ухода';
$FIELD_HOURS='Часов';
$FIELD_IN_TIME_OK='Вход';
$FIELD_IN_TIME_ERROR = $FIELD_IN_TIME_OK;
$FIELD_OUT_TIME_OK='Вых.';
$FIELD_OUT_TIME_ERROR = $FIELD_OUT_TIME_OK;

define ('GATE_URL','http://localhost/mailing/gate.php'); // work
//define ('GATE_URL','http://10.1.1.106/mailing/gate.php'); // test

?>
