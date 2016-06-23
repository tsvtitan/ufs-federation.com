<?php

global $db;
global $amp_conf;

$autoincrement = (($amp_conf["AMPDBENGINE"] == "sqlite") || ($amp_conf["AMPDBENGINE"] == "sqlite3")) ? "AUTOINCREMENT":"AUTO_INCREMENT";

$sql = "CREATE TABLE IF NOT EXISTS miscdests (
	id INTEGER NOT NULL PRIMARY KEY $autoincrement,
	description VARCHAR( 100 ) NOT NULL ,
	destdial VARCHAR( 100 ) NOT NULL
)";

$check = $db->query($sql);
if(DB::IsError($check)) {
	die_freepbx("Can not create miscdests table\n");
}

?>
