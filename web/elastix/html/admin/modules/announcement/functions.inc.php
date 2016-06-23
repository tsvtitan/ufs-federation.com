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
function announcement_destinations() {
	// return an associative array with destination and description
	$extens = array();
	foreach (announcement_list() as $row) {
		$extens[] = array('destination' => 'app-announcement-'.$row['announcement_id'].',s,1', 'description' => $row[1]);
	}
	return $extens;
}

function announcement_getdest($exten) {
	return array('app-announcement-'.$exten.',s,1');
}

function announcement_getdestinfo($dest) {
	global $active_modules;

	if (substr(trim($dest),0,17) == 'app-announcement-') {
		$exten = explode(',',$dest);
		$exten = substr($exten[0],17);

		$thisexten = announcement_get($exten);
		if (empty($thisexten)) {
			return array();
		} else {
			$type = isset($active_modules['announcement']['type'])?$active_modules['announcement']['type']:'setup';
			return array('description' => sprintf(_("Announcement: %s"),$thisexten['description']),
			             'edit_url' => 'config.php?display=announcement&type='.$type.'&extdisplay='.urlencode($exten),
								  );
		}
	} else {
		return false;
	}
}

function announcement_recordings_usage($recording_id) {
	global $active_modules;

	$results = sql("SELECT announcement_id, description FROM announcement WHERE recording_id = '$recording_id'","getAll",DB_FETCHMODE_ASSOC);
	if (empty($results)) {
		return array();
	} else {
		$type = isset($active_modules['announcement']['type'])?$active_modules['announcement']['type']:'setup';
		foreach ($results as $result) {
			$usage_arr[] = array(
				'url_query' => 'config.php?display=announcement&type='.$type.'&extdisplay='.urlencode($result['announcement_id']),
				'description' => sprintf(_("Announcement: %s"),$result['description']),
			);
		}
		return $usage_arr;
	}
}

function announcement_get_config($engine) {
	global $ext;
	switch ($engine) {
		case 'asterisk':
			foreach (announcement_list() as $row) {
				$recording = recordings_get_file($row['recording_id']);
				if (! $row['noanswer']) {
					$ext->add('app-announcement-'.$row['announcement_id'], 's', '', new ext_gotoif('$["${CDR(disposition)}" = "ANSWERED"]','begin'));
					$ext->add('app-announcement-'.$row['announcement_id'], 's', '', new ext_answer(''));
					$ext->add('app-announcement-'.$row['announcement_id'], 's', '', new ext_wait('1'));
				}
				$ext->add('app-announcement-'.$row['announcement_id'], 's', 'begin', new ext_noop('Playing announcement '.$row['description']));
				if ($row['allow_skip'] || $row['repeat_msg']) {
					// allow skip
					if ($row['repeat_msg']) {
						$ext->add('app-announcement-'.$row['announcement_id'], 's', '', new ext_responsetimeout(1));
					}
					$ext->add('app-announcement-'.$row['announcement_id'], 's', 'play', new ext_background($recording.',nm'));
					if ($row['repeat_msg']) {
						$ext->add('app-announcement-'.$row['announcement_id'], 's', '', new ext_waitexten(''));
					}
					
					if ($row['allow_skip']) {
						$ext->add('app-announcement-'.$row['announcement_id'], '_X', '', new ext_noop('User skipped announcement'));
						if ($row['return_ivr']) {
							$ext->add('app-announcement-'.$row['announcement_id'], '_X', '', new ext_gotoif('$["x${IVR_CONTEXT}" = "x"]', $row['post_dest'].':${IVR_CONTEXT},return,1'));
						} else {
							$ext->add('app-announcement-'.$row['announcement_id'], '_X', '', new ext_goto($row['post_dest']));
						}
					}
					if ($row['repeat_msg']) {
						$ext->add('app-announcement-'.$row['announcement_id'], $row['repeat_msg'], '', new ext_goto('s,play'));
					}
				} else {
					$ext->add('app-announcement-'.$row['announcement_id'], 's', '', new ext_playback($recording.',noanswer'));
				}

				// if repeat_msg enabled then set exten to t to allow for the key to be pressed, otherwise play message and go
				$exten = $row['repeat_msg'] ? 't':'s';
				if ($row['return_ivr']) {
					$ext->add('app-announcement-'.$row['announcement_id'], $exten, '', new ext_gotoif('$["x${IVR_CONTEXT}" = "x"]', $row['post_dest'].':${IVR_CONTEXT},return,1'));
					if ($row['allow_skip'] || $row['repeat_msg'])
						$ext->add('app-announcement-'.$row['announcement_id'], 'i', '', new ext_gotoif('$["x${IVR_CONTEXT}" = "x"]', $row['post_dest'].':${IVR_CONTEXT},return,1'));
				} else {
					$ext->add('app-announcement-'.$row['announcement_id'], $exten, '', new ext_goto($row['post_dest']));
					if ($row['allow_skip'] || $row['repeat_msg'])
						$ext->add('app-announcement-'.$row['announcement_id'], 'i', '', new ext_goto($row['post_dest']));
				}
				
			}
		break;
	}
}

function announcement_list() {
	global $db;
	$sql = "SELECT announcement_id, description, recording_id, allow_skip, post_dest, return_ivr, noanswer, repeat_msg FROM announcement ORDER BY description ";
	$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	if(DB::IsError($results)) {
		die_freepbx($results->getMessage()."<br><br>Error selecting from announcement");	
	}

	// Make array backward compatible.
	$count = 0;
	foreach($results as $item) {
		$results[$count][0] = $item['announcement_id'];
		$results[$count][1] = $item['description'];
		$results[$count][2] = $item['recording_id'];
		$results[$count][3] = $item['allow_skip'];
		$results[$count][4] = $item['post_dest'];
		$results[$count][5] = $item['return_ivr'];
		$results[$count][6] = $item['noanswer'];
		$results[$count][7] = $item['repeat_msg'];
		$count++;
	}
	return $results;
}

function announcement_get($announcement_id) {
	global $db;
	$sql = "SELECT announcement_id, description, recording_id, allow_skip, post_dest, return_ivr, noanswer, repeat_msg FROM announcement WHERE announcement_id = '".$db->escapeSimple($announcement_id)."'";
	$row = $db->getRow($sql,DB_FETCHMODE_ASSOC);
	if(DB::IsError($row)) {
		die_freepbx($row->getMessage()."<br><br>Error selecting row from announcement");	
	}
	// Added Associative query above but put positional indexes back to maintain backward compatibility
	//
	$i = 0;
	foreach ($row as $item) {
		$row[$i] = $item;
		$i++;
	}
	return $row;
}

function announcement_add($description, $recording_id, $allow_skip, $post_dest, $return_ivr, $noanswer, $repeat_msg) {
	global $db;
	$sql = "INSERT INTO announcement (description, recording_id, allow_skip, post_dest, return_ivr, noanswer, repeat_msg) VALUES (".
		"'".$db->escapeSimple($description)."', ".
		"'".$recording_id."', ".
		"'".($allow_skip ? 1 : 0)."', ".
		"'".$db->escapeSimple($post_dest)."', ".
		"'".($return_ivr ? 1 : 0)."', ".
		"'".($noanswer ? 1 : 0)."', ".
		"'".$db->escapeSimple($repeat_msg)."')";
	$result = $db->query($sql);
	if(DB::IsError($result)) {
		die_freepbx($result->getMessage().$sql);
	}
}

function announcement_delete($announcement_id) {
	global $db;
	$sql = "DELETE FROM announcement WHERE announcement_id = ".$db->escapeSimple($announcement_id);
	$result = $db->query($sql);
	if(DB::IsError($result)) {
		die_freepbx($result->getMessage().$sql);
	}
	
}

function announcement_edit($announcement_id, $description, $recording_id, $allow_skip, $post_dest, $return_ivr, $noanswer, $repeat_msg) { 
	global $db;
	$sql = "UPDATE announcement SET ".
		"description = '".$db->escapeSimple($description)."', ".
		"recording_id = '".$recording_id."', ".
		"allow_skip = '".($allow_skip ? 1 : 0)."', ".
		"post_dest = '".$db->escapeSimple($post_dest)."', ".
		"return_ivr = '".($return_ivr ? 1 : 0)."', ".
		"noanswer = '".($noanswer ? 1 : 0)."', ".
		"repeat_msg = '".$db->escapeSimple($repeat_msg)."' ".
		"WHERE announcement_id = ".$db->escapeSimple($announcement_id);
	$result = $db->query($sql);
	if(DB::IsError($result)) {
		die_freepbx($result->getMessage().$sql);
	}
}

function announcement_check_destinations($dest=true) {
	global $active_modules;

	$destlist = array();
	if (is_array($dest) && empty($dest)) {
		return $destlist;
	}
	$sql = "SELECT announcement_id, post_dest, description FROM announcement ";
	if ($dest !== true) {
		$sql .= "WHERE post_dest in ('".implode("','",$dest)."')";
	}
	$results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);

	$type = isset($active_modules['announcement']['type'])?$active_modules['announcement']['type']:'setup';

	foreach ($results as $result) {
		$thisdest = $result['post_dest'];
		$thisid   = $result['announcement_id'];
		$destlist[] = array(
			'dest' => $thisdest,
			'description' => sprintf(_("Announcement: %s"),$result['description']),
			'edit_url' => 'config.php?display=announcement&type='.$type.'&extdisplay='.urlencode($thisid),
		);
	}
	return $destlist;
}

?>
