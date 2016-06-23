<?php
//This file is part of FreePBX.
//
//    FreePBX is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 2 of the License, or
//    (at your option) any later version.
//
//    FreePBX is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with FreePBX.  If not, see <http://www.gnu.org/licenses/>.
//
//    Copyright 2006 Greg MacLellan
//
if (! function_exists("out")) {
	function out($text) {
		echo $text."<br />";
	}
}

if (! function_exists("outn")) {
	function outn($text) {
		echo $text;
	}
}

global $db;
global $amp_conf;

$autoincrement = (($amp_conf["AMPDBENGINE"] == "sqlite") || ($amp_conf["AMPDBENGINE"] == "sqlite3")) ? "AUTOINCREMENT":"AUTO_INCREMENT";
$sql = "CREATE TABLE IF NOT EXISTS announcement (
	announcement_id integer NOT NULL PRIMARY KEY $autoincrement,
	description VARCHAR( 50 ),
	recording_id INTEGER,
	allow_skip INT,
	post_dest VARCHAR( 255 ),
	return_ivr TINYINT(1) NOT NULL DEFAULT 0,
	noanswer TINYINT(1) NOT NULL DEFAULT 0,
	repeat_msg VARCHAR(2) NOT NULL DEFAULT ''
)";
$check = $db->query($sql);
if(DB::IsError($check)) {
	die_freepbx("Can not create announcement table");
}

// Version 0.3 adds auto-return to IVR
$sql = "SELECT return_ivr FROM announcement";
$check = $db->getRow($sql, DB_FETCHMODE_ASSOC);
if(DB::IsError($check)) {
	// add new field
    $sql = "ALTER TABLE announcement ADD return_ivr TINYINT(1) NOT NULL DEFAULT 0;";
    $result = $db->query($sql);
    if(DB::IsError($result)) { die_freepbx($result->getDebugInfo()); }
}

// Version 0.4 adds auto-return to IVR
$sql = "SELECT noanswer FROM announcement";
$check = $db->getRow($sql, DB_FETCHMODE_ASSOC);
if(DB::IsError($check)) {
	// add new field
    $sql = "ALTER TABLE announcement ADD noanswer TINYINT(1) NOT NULL DEFAULT 0;";
    $result = $db->query($sql);
    if(DB::IsError($result)) { die_freepbx($result->getDebugInfo()); }
}

// Version 0.8 upgrade
$repeat = (($amp_conf["AMPDBENGINE"] == "sqlite") || ($amp_conf["AMPDBENGINE"] == "sqlite3")) ? "repeat":"`repeat`";
$sql = "SELECT $repeat FROM announcement";
$check = @$db->getRow($sql, DB_FETCHMODE_ASSOC);
if(! DB::IsError($check)) {
    // Change field name because php5 was not happy with repeat
    //
    $sql = "ALTER TABLE announcement CHANGE $repeat repeat_msg VARCHAR( 2 ) NOT NULL DEFAULT '' ;"; 
    $result = $db->query($sql);
    if(DB::IsError($result)) {
            die_freepbx($result->getDebugInfo());
    }
}

// Version 0.6 adds repeat_msg
$sql = "SELECT repeat_msg FROM announcement";
$check = $db->getRow($sql, DB_FETCHMODE_ASSOC);
if(DB::IsError($check)) {
	// add new field
    $sql = "ALTER TABLE announcement ADD repeat_msg VARCHAR(2) NOT NULL DEFAULT '';";
    $result = $db->query($sql);
    if(DB::IsError($result)) { die_freepbx($result->getDebugInfo()); }
}

$results = array();
$sql = "SELECT announcement_id, post_dest FROM announcement";
$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
if (!DB::IsError($results)) { // error - table must not be there
	foreach ($results as $result) {
		$old_dest  = $result['post_dest'];
		$announcement_id    = $result['announcement_id'];

		$new_dest = merge_ext_followme(trim($old_dest));
		if ($new_dest != $old_dest) {
			$sql = "UPDATE announcement SET post_dest = '$new_dest' WHERE announcement_id = $announcement_id  AND post_dest = '$old_dest'";
			$results = $db->query($sql);
			if(DB::IsError($results)) {
				die_freepbx($results->getMessage());
			}
		}
	}
}

// Version 2.5 migrate to recording ids
//
outn(_("Checking if recordings need migration.."));
$sql = "SELECT recording_id FROM announcement";
$check = $db->getRow($sql, DB_FETCHMODE_ASSOC);
if(DB::IsError($check)) {
	//  Add recording_id field
	//
	out(_("migrating"));
	outn(_("adding recording_id field.."));
  $sql = "ALTER TABLE announcement ADD recording_id INTEGER";
  $result = $db->query($sql);
  if(DB::IsError($result)) {
		out(_("fatal error"));
		die_freepbx($result->getDebugInfo()); 
	} else {
		out(_("ok"));
	}

	// Get all the valudes and replace them with recording_id
	//
	outn(_("migrate to recording ids.."));
  $sql = "SELECT `announcement_id`, `recording` FROM `announcement`";
	$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	if(DB::IsError($results)) {
		out(_("fatal error"));
		die_freepbx($results->getDebugInfo());	
	}
	$migrate_arr = array();
	$count = 0;
	foreach ($results as $row) {
		if (trim($row['recording']) != '') {
			$rec_id = recordings_get_or_create_id($row['recording'], 'announcement');
			$migrate_arr[] = array($rec_id, $row['announcement_id']);
			$count++;
		}
	}
	if ($count) {
		$compiled = $db->prepare('UPDATE `announcement` SET `recording_id` = ? WHERE `announcement_id` = ?');
		$result = $db->executeMultiple($compiled,$migrate_arr);
		if(DB::IsError($result)) {
			out(_("fatal error"));
			die_freepbx($result->getDebugInfo());	
		}
	}
	out(sprintf(_("migrated %s entries"),$count));

	// Now remove the old recording field replaced by new id field
	//
	outn(_("dropping recording field.."));
  $sql = "ALTER TABLE `announcement` DROP `recording`";
  $result = $db->query($sql);
  if(DB::IsError($result)) { 
		out(_("no recording field???"));
	} else {
		out(_("ok"));
	}

} else {
	out(_("already migrated"));
}

?>
