<?php /* $Id: functions.inc.php 2188 2006-07-27 02:21:52Z p_lindheimer $ */

/* 	Generates dialplan for parkinglot
	We call this with retrieve_conf
*/

function parking_get_config($engine) {
	global $db;
	global $amp_conf;
	global $ext;  // is this the best way to pass this?
	global $asterisk_conf;
	global $core_conf;
	global $version;

	switch($engine) {
		case "asterisk":

		$contextname = 'park-dial';

		$parkinglot_id = 1; // only 1 parking lot, but prepare for future
		$results = parking_getconfig($parkinglot_id);

		// Got the array, let's go work out the required variables
		//
		$parkingenabled	= isset($results['parkingenabled'])?$results['parkingenabled']:'';
		$parkext 	= isset($results['parkext'])?$results['parkext']:70;
		$numslots  	= isset($results['numslots'])?$results['numslots']:8;
		$parkingtime 	= isset($results['parkingtime'])?$results['parkingtime']:'';
		$parkingcontext	= isset($results['parkingcontext'])?$results['parkingcontext']:'parkedcalls';
		$parkalertinfo 	= isset($results['parkalertinfo'])?$results['parkalertinfo']:'';
		$parkcid 	= isset($results['parkcid'])?$results['parkcid']:'';
		$parkingannmsg_id 	= isset($results['parkingannmsg_id'])?$results['parkingannmsg_id']:'';
		$goto	 	= isset($results['goto'])?$results['goto']:'from-pstn,s,1';

		$parkpos1	= $parkext + 1;
		$parkpos2	= $parkpos1 + $numslots -1;

		if ($parkingenabled) {
			// TODO: lookup ampportal.conf variables for this, don't hard code
			// first write features_additional.inc include file
			//
			$core_conf->addFeatureGeneral('parkext',$parkext);
			$core_conf->addFeatureGeneral('parkpos',$parkpos1."-".$parkpos2);
			$core_conf->addFeatureGeneral('context',$parkingcontext);

			if ($parkingtime) {
				$core_conf->addFeatureGeneral('parkingtime',$parkingtime);
			}

			// Now generate dialplan
			$ext->add($contextname, "t", '', new ext_noop('Parked Call Timed Out and Got Orphaned'));
			$ext->add($contextname, "_[0-9a-zA-Z*#].", '', new ext_noop('Parked Call Timed Out and Got Orphaned'));

			// If we have an appropriate Asterisk patch, set paraemters for Asterisk
			//
			if (isset($amp_conf["PARKINGPATCH"]) && strtolower($amp_conf["PARKINGPATCH"]) == 'true') {
				if ($parkalertinfo) {
					$core_conf->addFeatureGeneral('parkreturnalertinfo',$parkalertinfo);
				}
				if ($parkcid) {
					$core_conf->addFeatureGeneral('parkreturncidprefix',$parkcid);
				}
			// No patch, do the default for orphaned calls
			} else {
				if ($parkalertinfo) {
					$ext->add($contextname, "t", '', new ext_setvar('__ALERT_INFO',str_replace(';', '\;', $parkalertinfo)));
					$ext->add($contextname, "_[0-9a-zA-Z*#].", '', new ext_setvar('__ALERT_INFO',str_replace(';', '\;', $parkalertinfo)));
				}
				if ($parkcid) {
					$ext->add($contextname, "t", '', new ext_setvar('CALLERID(name)', $parkcid.'${CALLERID(name)}'));
					$ext->add($contextname, "_[0-9a-zA-Z*#].", '', new ext_setvar('CALLERID(name)', $parkcid.'${CALLERID(name)}'));
				}
			}

			if ($parkingannmsg_id != '') {
				$parkingannmsg = recordings_get_file($parkingannmsg_id);
				$ext->add($contextname, "t", '', new ext_playback($parkingannmsg));
				$ext->add($contextname, "_[0-9a-zA-Z*#].", '', new ext_playback($parkingannmsg));
			}
			// goto the destination here
			//
			$ext->add($contextname, "t", '', new ext_goto($goto));
			$ext->add($contextname, "_[0-9a-zA-Z*#].", '', new ext_goto($goto));

			// Asterisk 1.4 requires hints to be generated for parking
			//
			if (version_compare($version, "1.4", "ge")) {
				$parkhints = 'park-hints';
				$ext->addInclude('from-internal-additional', $parkhints); // Add the include from from-internal
				for ($slot = $parkpos1; $slot <= $parkpos2; $slot++) {
					$ext->addHint($parkhints, $slot, "park:$slot@$parkingcontext");
					$ext->add($parkhints, $slot, '', new ext_parkedcall($slot));
				}
			}
		}
		break;
	}
}

function parking_add($parkingenabled, $parkext, $numslots, $parkingtime, $parkingcontext, $parkalertinfo, $parkcid, $parkingannmsg_id, $goto) {
	global $db;

	$parkinglot_id 	= 1; // only 1 parkinglot but prepare for future

	// in future will do in a parking_del but not needed for now
	//
	$sql = "DELETE FROM parkinglot WHERE id = '$parkinglot_id'";
	$result = $db->query($sql);
	if(DB::IsError($result)) {
		die_freepbx($result->getMessage().$sql);
	}

	// Check for interger only inputs and set to default if not
	//  and set goto to default if not set for some reason
	//
	$parkext	= ctype_digit($parkext)		? $parkext	: 70;
	$numslots		= ctype_digit($numslots)		? $numslots	: 8;
	$parkingtime	= ctype_digit($parkingtime)	? $parkingtime	: '';
	$goto		= ($goto) 			? $goto		: 'from-pstn,s,1';

	$parkfields =	array(array($parkinglot_id, 'parkingenabled', "$parkingenabled"),
			array($parkinglot_id, 'parkext', "$parkext"),
			array($parkinglot_id, 'numslots', "$numslots"),
			array($parkinglot_id, 'parkingtime', "$parkingtime"),
			array($parkinglot_id, 'parkingcontext', trim("$parkingcontext")),
			array($parkinglot_id, 'parkalertinfo', trim("$parkalertinfo")),
			array($parkinglot_id, 'parkcid', trim("$parkcid")),
			array($parkinglot_id, 'parkingannmsg_id', "$parkingannmsg_id"),
			array($parkinglot_id, 'goto', "$goto"));

	$compiled = $db->prepare('INSERT INTO parkinglot (id, keyword, data) values (?,?,?)');

	$result = $db->executeMultiple($compiled,$parkfields);
	if(DB::IsError($result)) {
		die_freepbx($result->getDebugInfo()."<br><br>".'error adding to PARKING table');
	}
}


function parking_getconfig($parkinglot_id=1) {
	global $db;
	$sql = "SELECT keyword,data FROM parkinglot WHERE id = '$parkinglot_id'";
	$results = $db->getAssoc($sql);
	if(DB::IsError($results)) {
		$results = null;
	}
	return $results;
}

function parking_check_destinations($dest=true) {
	global $active_modules;

	$destlist = array();
	if (is_array($dest) && empty($dest)) {
		return $destlist;
	}
	$sql = "SELECT keyword, data FROM parkinglot WHERE keyword = 'goto' ";
	if ($dest !== true) {
		$sql .= "AND data in ('".implode("','",$dest)."')";
	}
	$results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);

	$type = isset($active_modules['parking']['type'])?$active_modules['parking']['type']:'setup';

	foreach ($results as $result) {
		$thisdest = $result['data'];
		$destlist[] = array(
			'dest' => $thisdest,
			'description' => _('Parking Lot'),
			'edit_url' => 'config.php?type='.$type.'&display=parking',
		);
	}
	return $destlist;
}

function parking_recordings_usage($recording_id) {
	global $active_modules;

	$my_id = sql("SELECT `data` FROM `parkinglot` WHERE `id` = '1' AND `keyword` = 'parkingannmsg_id' AND `data` = '$recording_id'","getOne");
	if (!isset($my_id) || $my_id == '') {
		return array();
	} else {
		$type = isset($active_modules['parking']['type'])?$active_modules['parking']['type']:'setup';
		$usage_arr[] = array(
			'url_query' => 'config.php?type='.$type.'&display=parking',
			'description' => _("Parking Lot"),
		);
		return $usage_arr;
	}
}

// Duly stolen from the queues module (since I can't count on it being there, but would not be bad to stuff back in the common include
//
function parking_timeString($seconds, $full = false) {
        if ($seconds == 0) {
                return "0 ".($full ? "seconds" : "s");
        }

        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        $days = floor($hours / 24);
        $hours = $hours % 24;

        if ($full) {
                return substr(
				($days ? $days." "._("day").(($days == 1) ? "" : "s").", " : "").
                              	($hours ? $hours." ".(($hours == 1) ? _("hour") : _("hours")).", " : "").
                              	($minutes ? $minutes." ".(($minutes == 1) ? _("minute") : _("minutes")).", " : "").
                              	($seconds ? $seconds." ".(($seconds == 1) ? _("second") : _("seconds")).", " : ""),
                              	0, -2);
        } else {
                return substr(($days ? $days."d, " : "").($hours ? $hours."h, " : "").($minutes ? $minutes."m, " : "").($seconds ? $seconds."s, " : ""), 0, -2);
        }
}

?>
