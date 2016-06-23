<?php
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

$sql = "
CREATE TABLE IF NOT EXISTS `parkinglot` 
(
	`id` VARCHAR( 20 ) NOT NULL default '1',
	`keyword` varchar(40) NOT NULL default '',
	`data` varchar(150) NOT NULL default '',
	PRIMARY KEY  (`id`,`keyword`)
)
";
$check = $db->query($sql);
if(DB::IsError($check)) {
	die_freepbx("Can not create parkinglot table");
}

$results = array();
$sql = "SELECT id, keyword, data FROM parkinglot WHERE keyword = 'goto'";
$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
if (!DB::IsError($results)) { // error - table must not be there
	foreach ($results as $result) {
		$old_dest  = $result['data'];
		$id        = $result['id'];
		$keyword   = $result['keyword'];

		$new_dest = merge_ext_followme(trim($old_dest));
		if ($new_dest != $old_dest) {
			$sql = "UPDATE parkinglot SET data = '$new_dest' WHERE id = '$id'  AND keyword = '$keyword' AND data = '$old_dest'";
			$results = $db->query($sql);
			if(DB::IsError($results)) {
				die_freepbx($results->getMessage());
			}
		}
	}
}

// Version 2.5 migrate to recording ids
//
outn(_("Migrating recordings if needed.."));

$sql = "SELECT `data` FROM `parkinglot` WHERE  `id` = '1' AND `keyword` = 'parkingannmsg'";
$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
if(DB::IsError($results)) {
	die_freepbx($results->getMessage());
}
if (isset($results[0])) {
	if (trim($results[0]['data']) != '') {
		$rec_id = recordings_get_or_create_id($results[0]['data'], 'parking');
	} else {
		$rec_id = '';
	}
	// Delete just in case
	$sql="DELETE FROM `parkinglot` WHERE `keyword` = 'parkingannmsg_id'";
	$results = $db->query($sql);
	if(DB::IsError($results)) {
		out(_("fatal error"));
		die_freepbx($results->getMessage());
	}
	$sql="INSERT INTO `parkinglot` (`id`, `keyword`, `data`) VALUES ('1', 'parkingannmsg_id', '$rec_id')";
	$results = $db->query($sql);
	if(DB::IsError($results)) {
		out(_("fatal error"));
		die_freepbx($results->getMessage());
	}
	// Either way, delete it if it were there
	$sql="DELETE FROM `parkinglot` WHERE `keyword` = 'parkingannmsg'";
	$results = $db->query($sql);
	if(DB::IsError($results)) {
		out(_("fatal error"));
		die_freepbx($results->getMessage());
	}
	out(_("ok"));
} else {
	out(_("not needed"));
}

?>
