<?php

global $db;

$autoincrement = (($amp_conf["AMPDBENGINE"] == "sqlite") || ($amp_conf["AMPDBENGINE"] == "sqlite3")) ? "AUTOINCREMENT":"AUTO_INCREMENT";
$sql = "CREATE TABLE IF NOT EXISTS queueprio (
	queueprio_id INTEGER NOT NULL PRIMARY KEY $autoincrement,
	queue_priority VARCHAR( 50 ) ,
	description VARCHAR( 50 ) ,
	dest VARCHAR( 255 )
)";

$check = $db->query($sql);
if(DB::IsError($check)) {
	die_freepbx("Can not create queueprio table\n");
}

?>
