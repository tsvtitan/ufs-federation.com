<?php /* $Id */

function timeconditions_getdest($exten) {
	return array('timeconditions,'.$exten.',1');
}

function timeconditions_getdestinfo($dest) {
	global $active_modules;

	if (substr(trim($dest),0,15) == 'timeconditions,') {
		$exten = explode(',',$dest);
		$exten = $exten[1];
		$thisexten = timeconditions_get($exten);
		if (empty($thisexten)) {
			return array();
		} else {
			//$type = isset($active_modules['announcement']['type'])?$active_modules['announcement']['type']:'setup';
			return array('description' => sprintf(_("Time Condition: %s"),$thisexten['displayname']),
			             'edit_url' => 'config.php?display=timeconditions&itemid='.urlencode($exten),
			            );
		}
	} else {
		return false;
	}
}

// returns a associative arrays with keys 'destination' and 'description'
function timeconditions_destinations() {
	//get the list of timeconditions
	$results = timeconditions_list(true);

	// return an associative array with destination and description
	if (isset($results)) {
		foreach($results as $result){
				$extens[] = array('destination' => 'timeconditions,'.$result['timeconditions_id'].',1', 'description' => $result['displayname']);
		}
		return $extens;
	} else {
		return null;
	}
}

/* 	Generates dialplan for conferences
	We call this with retrieve_conf
*/
function timeconditions_get_config($engine) {
	global $ext;  // is this the best way to pass this?
	global $conferences_conf;

	switch($engine) {
		case "asterisk":
			$timelist = timeconditions_list(true);
			if(is_array($timelist)) {
				foreach($timelist as $item) {
					// add dialplan
          // note we don't need to add 2nd optional option of true, gotoiftime will convert '|' to ',' for 1.6+
					$times = timeconditions_timegroups_get_times($item['time']);
					if (is_array($times)) {
						foreach ($times as $time) {
							$ext->add('timeconditions', $item['timeconditions_id'], '', new ext_gotoiftime($time[1],$item['truegoto']));
						}
					}
					$ext->add('timeconditions', $item['timeconditions_id'], '', new ext_goto($item['falsegoto']));
				}
			}
		break;
	}
}

function timeconditions_check_destinations($dest=true) {
	global $active_modules;

	$destlist = array();
	if (is_array($dest) && empty($dest)) {
		return $destlist;
	}
	$sql = "SELECT timeconditions_id, displayname, truegoto, falsegoto FROM timeconditions ";
	if ($dest !== true) {
		$sql .= "WHERE (truegoto in ('".implode("','",$dest)."') ) OR (falsegoto in ('".implode("','",$dest)."') )";
	}
	$results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);

	$type = isset($active_modules['timeconditions']['type'])?$active_modules['timeconditions']['type']:'setup';

	foreach ($results as $result) {
		$thisdest    = $result['truegoto'];
		$thisid      = $result['timeconditions_id'];
		$description = sprintf(_("Time Condition: %s"),$result['displayname']);
		$thisurl     = 'config.php?display=timeconditions&itemid='.urlencode($thisid);
		if ($dest === true || $dest = $thisdest) {
			$destlist[] = array(
				'dest' => $thisdest,
				'description' => $description,
				'edit_url' => $thisurl,
			);
		}
		$thisdest = $result['falsegoto'];
		if ($dest === true || $dest = $thisdest) {
			$destlist[] = array(
				'dest' => $thisdest,
				'description' => $description,
				'edit_url' => $thisurl,
			);
		}
	}
	return $destlist;
}

//get the existing meetme extensions
function timeconditions_list($getall=false) {
	$results = sql("SELECT * FROM timeconditions","getAll",DB_FETCHMODE_ASSOC);
	if(is_array($results)){
		foreach($results as $result){
			// check to see if we have a dept match for the current AMP User.
			if ($getall || checkDept($result['deptname'])){
				// return this item's dialplan destination, and the description
				$allowed[] = $result;
			}
		}
	}
	if (isset($allowed)) {
		return $allowed;
	} else { 
		return null;
	}
}

function timeconditions_get($id){
	//get all the variables for the timecondition
	$results = sql("SELECT * FROM timeconditions WHERE timeconditions_id = '$id'","getRow",DB_FETCHMODE_ASSOC);
	return $results;
}

function timeconditions_del($id){
	$results = sql("DELETE FROM timeconditions WHERE timeconditions_id = \"$id\"","query");
}

//obsolete handled in timegroups module
function timeconditions_get_time( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish) {

	//----- Time Hour Interval proccess ----
	//
	if ($minute_start == '-') {
		$time_minute_start = "*";
	} else {
		$time_minute_start = sprintf("%02d",$minute_start);
	}
	if ($minute_finish == '-') {
		$time_minute_finish = "*";
	} else {
 		$time_minute_finish = sprintf("%02d",$minute_finish);
	}
	if ($hour_start == '-') {
		$time_hour_start = '*';
	} else {
		$time_hour_start = sprintf("%02d",$hour_start) . ':' . $time_minute_start;
	}
	if ($hour_finish == '-') {
		$time_hour_finish = '*';
	} else {
		$time_hour_finish = sprintf("%02d",$hour_finish) . ':' . $time_minute_finish;
	}
	if ($time_hour_start == $time_hour_finish) {
		$time_hour = $time_hour_start;
	} else {
		$time_hour = $time_hour_start . '-' . $time_hour_finish;
	}

	//----- Time Week Day Interval proccess -----
	//
	if ($wday_start == '-') {
		$time_wday_start = '*';
	} else {
		$time_wday_start = $wday_start;
	}
	if ($wday_finish == '-') {
		$time_wday_finish = '*';
	} else {
		$time_wday_finish = $wday_finish;
	}
	if ($time_wday_start == $time_wday_finish) {
		$time_wday = $time_wday_start;
	} else {
		$time_wday = $time_wday_start . '-' . $time_wday_finish;
	}

	//----- Time Month Day Interval proccess -----
	//
	if ($mday_start == '-') {
		$time_mday_start = '*';
	} else {
		$time_mday_start = $mday_start;
	}
	if ($mday_finish == '-') {
		$time_mday_finish = '*';
	} else {
		$time_mday_finish = $mday_finish;
	}
	if ($time_mday_start == $time_mday_finish) {
		$time_mday = $time_mday_start;
	} else {
		$time_mday = $time_mday_start . '-' . $time_mday_finish;
	}

	//----- Time Month Interval proccess -----
	//
	if ($month_start == '-') {
		$time_month_start = '*';
	} else {
		$time_month_start = $month_start;
	}
	if ($month_finish == '-') {
		$time_month_finish = '*';
	} else {
		$time_month_finish = $month_finish;
	}
	if ($time_month_start == $time_month_finish) {
		$time_month = $time_month_start;
	} else {
		$time_month = $time_month_start . '-' . $time_month_finish;
	}
	$time = $time_hour . '|' . $time_wday . '|' . $time_mday . '|' . $time_month;
	return $time;
}

function timeconditions_add($post){
	if(!timeconditions_chk($post)) {
		return false;
	}
	extract($post);

	// $time = timeconditions_get_time( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish);

	if(empty($displayname)) {
	 	$displayname = "unnamed";
	}
	$results = sql("INSERT INTO timeconditions (displayname,time,truegoto,falsegoto,deptname) values (\"$displayname\",\"$time\",\"${$goto0.'0'}\",\"${$goto1.'1'}\",\"$deptname\")");
}

function timeconditions_edit($id,$post){
	if(!timeconditions_chk($post)) {
		return false;
	}
	extract($post);

	// $time = timeconditions_get_time( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish);
	
	if(empty($displayname)) { 
		$displayname = "unnamed";
	}
	$results = sql("UPDATE timeconditions SET displayname = \"$displayname\", time = \"$time\", truegoto = \"${$goto0.'0'}\", falsegoto = \"${$goto1.'1'}\", deptname = \"$deptname\" WHERE timeconditions_id = \"$id\"");
}

// ensures post vars is valid
function timeconditions_chk($post){
	return true;
}

function timeconditions_timegroups_usage($group_id) {

	$results = sql("SELECT timeconditions_id, displayname FROM timeconditions WHERE time = '$group_id'","getAll",DB_FETCHMODE_ASSOC);
	if (empty($results)) {
		return array();
	} else {
		foreach ($results as $result) {
			$usage_arr[] = array(
				"url_query" => "display=timeconditions&itemid=".$result['timeconditions_id'],
				"description" => $result['displayname'],
			);
		}
		return $usage_arr;
	}
}


function timeconditions_timegroups_list_usage($timegroup_id) {
	global $active_modules;
	$full_usage_arr = array();

	foreach(array_keys($active_modules) as $mod) {
		$function = $mod."_timegroups_usage";
		if (function_exists($function)) {
			$timegroup_usage = $function($timegroup_id);
			if (!empty($timegroup_usage)) {
				$full_usage_arr = array_merge($full_usage_arr, $timegroup_usage);
			}
		}
	}
	return $full_usage_arr;
}

/*
The following functions are available to other modules.

function timeconditions_timegroups_add_group($description,$times=null) return the inserted id
	expects an array of times, each an associative array
	Array ( [0] => Array ( [hour_start] => - [minute_start] => - [hour_finish] => - 
	[minute_finish] => - [wday_start] => - [wday_finish] => - [mday_start] => - 
	[mday_finish] => - [month_start] => - [month_finish] => - ) )

function timeconditions_timegroups_add_group_timestrings($description,$times=null) return the inserted id
	alternative to above. expects an array of time strings instead of associative array of hours minutes etc.

function timeconditions_timegroups_list_groups()
	returns an array of id and descriptions for any time groups defined by the user
	the array contains inidces 0 and 1 for the rnav and associative value and text for select boxes

function timeconditions_timegroups_get_times($timegroup)
	returns an array of id and time string of the users time selections for the selected timegroup

function timeconditions_timegroups_buildtime( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish) 
	should never be needed by another module, as this module should be the only place creating the time string, as it returns the string to other modules.

function timeconditions_timegroups_drawtimeselects($name, $time)
	should never be needed by another module, as this module should be the only place drawing the time selects
*/

//lists any time groups defined by the user
function timeconditions_timegroups_list_groups() {
	global $db;
	$tmparray = array();

	$sql = "select id, description from timegroups_groups order by description";
	$results = $db->getAll($sql);
	if(DB::IsError($results)) {
		$results = null;
	}
	foreach ($results as $val) {
		$tmparray[] = array($val[0], $val[1], "value" => $val[0], "text" => $val[1]);
	}
	return $tmparray;
}

//---------------------------------------------

//timegroups page helper
//we are using gui styles so there is very little on the page
//the timegroups page is used to create time string 
//to be used by other modules for gotoif or includes or IFTIME func
function timeconditions_timegroups_configpageinit($dispnum) {
global $currentcomponent;

	switch ($dispnum) {
		case 'timegroups':
			$currentcomponent->addguifunc('timeconditions_timegroups_configpageload');
			$currentcomponent->addprocessfunc('timeconditions_timegroups_configprocess', 5);  
		break;
	}
}

//actually render the timegroups page
function timeconditions_timegroups_configpageload() {
	global $currentcomponent;

	$descerr = _("Description must be alpha-numeric, and may not be left blank");
	$extdisplay = isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:null;
	$action= isset($_REQUEST['action'])?$_REQUEST['action']:null;
	if ($action == 'del') {
		$currentcomponent->addguielem('_top', new gui_pageheading('title', _("Time Group").": $extdisplay"._(" deleted!"), false), 0);
		unset($extdisplay);
	}
//need to get page name/type dynamically
	$query = ($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'type=setup&display=timegroups&extdisplay='.$extdisplay;
	$delURL = $_SERVER['PHP_SELF'].'?'.$query.'&action=del';
	$info = '';
	if (!$extdisplay) {
		$currentcomponent->addguielem('_top', new gui_pageheading('title', _("Add Time Group"), false), 0);
		$currentcomponent->addguielem(_("Time Group"), new gui_textbox('description', '', _("Description"), _("This will display as the name of this Time Group."), '!isAlphanumeric() || isWhitespace()', $descerr, false), 3);
	} else {
		$savedtimegroup= timeconditions_timegroups_get_group($extdisplay);
		$timegroup = $savedtimegroup[0];
		$description = $savedtimegroup[1];
		$currentcomponent->addguielem('_top', new gui_hidden('extdisplay', $extdisplay));
		$currentcomponent->addguielem('_top', new gui_pageheading('title', _("Edit Time Group").": $description", false), 0);
		$tlabel = sprintf(_("Delete Time Group %s"),$extdisplay);
		$label = '<span><img width="16" height="16" border="0" title="'.$tlabel.'" alt="" src="images/core_delete.png"/>&nbsp;'.$tlabel.'</span>';
		$currentcomponent->addguielem('_top', new gui_link('del', $label, $delURL, true, false), 0);

		$usage_list = timeconditions_timegroups_list_usage($extdisplay);
		$count = 0;
		foreach ($usage_list as $link) {
			$label = '<span><img width="16" height="16" border="0" title="'.$link['description'].'" alt="" src="images/time_link.png"/>&nbsp;'.$link['description'].'</span>';
			$timegroup_link = $_SERVER['PHP_SELF'].'?'.$link['url_query'];
			$currentcomponent->addguielem(_("Used By"), new gui_link('link'.$count++, $label, $timegroup_link, true, false), 4);
		}


		$currentcomponent->addguielem(_("Time Group"), new gui_textbox('description', $description, _("Description"), _("This will display as the name of this Time Group."), '', '', false), 3);
		$timelist = timeconditions_timegroups_get_times($extdisplay);
		foreach ($timelist as $val) {
			$timehtml = timeconditions_timegroups_drawtimeselects('times['.$val[0].']',$val[1]);
			$timehtml = '<tr><td colspan="2"><table>'.$timehtml.'</table></td></tr>';
			$currentcomponent->addguielem($val[1], new guielement('dest0', $timehtml, ''),5);
		}
	}
	$timehtml = timeconditions_timegroups_drawtimeselects('times[new]',null);
	$timehtml = '<tr><td colspan="2"><table>'.$timehtml.'</table></td></tr>';
	$currentcomponent->addguielem(_("New Time"), new guielement('dest0', $timehtml, ''),6);
	$currentcomponent->addguielem('_top', new gui_hidden('action', ($extdisplay ? 'edit' : 'add')));
}

//handle timegroups page submit button
function timeconditions_timegroups_configprocess() {
	$action= isset($_REQUEST['action'])?$_REQUEST['action']:null;
	$timegroup= isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:null;
	$description= isset($_REQUEST['description'])?$_REQUEST['description']:null;
	$times = isset($_REQUEST['times'])?$_REQUEST['times']:null;

	switch ($action) {
		case 'add':
			timeconditions_timegroups_add_group($description,$times);
			break;
		case 'edit':
			timeconditions_timegroups_edit_group($timegroup,$description);
			timeconditions_timegroups_edit_times($timegroup,$times);
			break;
		case 'del':
			timeconditions_timegroups_del_group($timegroup);
			break;
	}
}

//these are the users time selections for the current timegroup
function timeconditions_timegroups_get_times($timegroup, $convert=false) {
	global $db;
	global $version;

  if ($convert && (!isset($version) || $version == '')) {
    $engineinfo = engine_getinfo();
    $version =  $engineinfo['version'];
  }
  if ($convert) {
    $ast_ge_16 = version_compare($version,'1.6','ge');
  }
	$sql = "select id, time from timegroups_details where timegroupid = $timegroup";
	$results = $db->getAll($sql);
	if(DB::IsError($results)) {
		$results = null;
	}
	foreach ($results as $val) {
    $times = ($convert && $ast_ge_16) ? strtr($val[1],'|',',') : $val[1];
    $tmparray[] = array($val[0], $times);
	}
	return $tmparray;
}

//retrieve a single timegroup for the timegroups page
function timeconditions_timegroups_get_group($timegroup) {
	global $db;

	$sql = "select id, description from timegroups_groups where id = $timegroup";
	$results = $db->getAll($sql);
	if(DB::IsError($results)) {
 		$results = null;
	}
	$tmparray = array($results[0][0], $results[0][1]);
	return $tmparray;
}

//add a new timegroup for timegroups page
//expects an array of times, each an associative array
//Array ( [0] => Array ( [hour_start] => - [minute_start] => - [hour_finish] => - 
//[minute_finish] => - [wday_start] => - [wday_finish] => - [mday_start] => - 
//[mday_finish] => - [month_start] => - [month_finish] => - ) )
function timeconditions_timegroups_add_group($description,$times=null) {
	global $db;

	$sql = "insert timegroups_groups(description) VALUES ('$description')";
	$db->query($sql);
	$timegroup=mysql_insert_id();
	if (isset($times)) {
		timeconditions_timegroups_edit_times($timegroup,$times);
	}
	needreload();
	return $timegroup;
}

function timeconditions_timegroups_add_group_timestrings($description,$timestrings) {
	global $db;

	$sql = "insert timegroups_groups(description) VALUES ('$description')";
	$db->query($sql);
	$timegroup=mysql_insert_id();
	timeconditions_timegroups_edit_timestrings($timegroup,$timestrings);
	needreload();
	return $timegroup;
}

//delete a single timegroup from the timegroups page
function timeconditions_timegroups_del_group($timegroup) {
	global $db;

	$sql = "delete from timegroups_details where timegroupid = $timegroup";
	$db->query($sql);
	$sql = "delete from timegroups_groups where id = $timegroup";
	$db->query($sql);
	needreload();
}

//update a single timegroup from the timegroups page
function timeconditions_timegroups_edit_group($timegroup,$description) {
	global $db;

	$sql = "update timegroups_groups set description = '$description' where id = $timegroup";
	$db->query($sql);
	needreload();
}

//update the timegroup_detail under a single timegroup from the timegroups page
function timeconditions_timegroups_edit_times($timegroup,$times) {
	global $db;

	$sql = "delete from timegroups_details where timegroupid = $timegroup";
	$db->query($sql);
	foreach ($times as $key=>$val) {
		extract($val);
		$time = timeconditions_timegroups_buildtime( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish);
		if (isset($time) && $time != '' && $time <> '*|*|*|*') {
			$sql = "insert timegroups_details (timegroupid, time) values ($timegroup, '$time')";
			$db->query($sql);
		}
	}
	needreload();
}

//update the timegroup_detail under a single timegroup 
function timeconditions_timegroups_edit_timestrings($timegroup,$timestrings) {
	global $db;

	$sql = "delete from timegroups_details where timegroupid = $timegroup";
	$db->query($sql);
	foreach ($timestrings as $key=>$val) {
		$time = $val;
		if (isset($time) && $time != '' && $time <> '*|*|*|*') {
			$sql = "insert timegroups_details (timegroupid, time) values ($timegroup, '$time')";
			$db->query($sql);
		}
	}
	needreload();
}

function timeconditions_timegroups_drawgroupselect($elemname, $currentvalue = '', $canbeempty = true, $onchange = '', $default_option = '') {
	global $tabindex;
	$output = '';
	$onchange = ($onchange != '') ? " onchange=\"$onchange\"" : '';
	
	$output .= "\n\t\t\t<select name=\"$elemname\" tabindex=\"".++$tabindex."\" id=\"$elemname\"$onchange>\n";
	// include blank option if required
	if ($canbeempty) {
		$output .= '<option value="">'.($default_option == '' ? _("--Select a Group--") : $default_option).'</option>';			
	}
	// build the options
	$valarray = timeconditions_timegroups_list_groups();
	foreach ($valarray as $item) {
		$itemvalue = (isset($item['value']) ? $item['value'] : '');
		$itemtext = (isset($item['text']) ? _($item['text']) : '');
		$itemselected = ($currentvalue == $itemvalue) ? ' selected' : '';
		
		$output .= "\t\t\t\t<option value=\"$itemvalue\"$itemselected>$itemtext</option>\n";
	}
	$output .= "\t\t\t</select>\n\t\t";
	return $output;
}

//---------------------------------stolen from time conditions and heavily modified------------------------------------------

function timeconditions_timegroups_drawtimeselects($name, $time) {
	$html = '';
	// ----- Load Time Pattern Variables -----
	if (isset($time)) {
		list($time_hour, $time_wday, $time_mday, $time_month) = explode('|', $time);
	} else {
		list($time_hour, $time_wday, $time_mday, $time_month) = Array('*','-','-','-');
	}
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Time to start:").'</td>';
	$html = $html.'<td>';
	// Hour could be *, hh:mm, hh:mm-hhmm
	if ( $time_hour === '*' ) {
		$hour_start = $hour_finish = '-';
		$minute_start = $minute_finish = '-';
	} else {
		list($hour_start_string, $hour_finish_string) = explode('-', $time_hour);
		if ($hour_start_string === '*') {
			$hour_start_string = $hour_finish_string;
		}
		if ($hour_finish_string === '*') {
			$hour_finish_string = $hour_start_string;
		}
		list($hour_start, $minute_start) = explode( ':', $hour_start_string);
		list($hour_finish, $minute_finish) = explode( ':', $hour_finish_string);
		if ( !$hour_finish) {
			$hour_finish = $hour_start;
		}
		if ( !$minute_finish) {
			$minute_finish = $minute_start;
		}
	}
	$html = $html.'<select name="'.$name.'[hour_start]">';
	$default = '';
	if ( $hour_start === '-' ) {
		$default = ' selected';
	}
	$html = $html."<option value=\"-\" $default>-";
	for ($i = 0 ; $i < 24 ; $i++) {
		$default = "";
		if ( sprintf("%02d", $i) === $hour_start ) {
			$default = ' selected';
		}
		$html = $html."<option value=\"$i\" $default> ".sprintf("%02d", $i);
	}
	$html = $html.'</select>';
	$html = $html.'<nbsp>:<nbsp>';
	$html = $html.'<select name="'.$name.'[minute_start]">';
	$default = '';
	if ( $minute_start === '-' ) {
	 	$default = ' selected';
	}
	$html = $html."<option value=\"-\" $default>-";
	for ($i = 0 ; $i < 60 ; $i++) {
		$default = "";
		if ( sprintf("%02d", $i) === $minute_start ) {
		 	$default = ' selected';
		}
		$html = $html."<option value=\"$i\" $default> ".sprintf("%02d", $i);
	}
	$html = $html.'</select>';
	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Time to finish:").'</td>';
	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[hour_finish]">';
	$default = '';
	if ( $hour_finish === '-' ) {
	 	$default = ' selected';
	}
	$html = $html."<option value=\"-\" $default>-";
	for ($i = 0 ; $i < 24 ; $i++) {
		$default = "";
		if ( sprintf("%02d", $i) === $hour_finish) {
		 	$default = ' selected';
		}
		$html = $html."<option value=\"$i\" $default> ".sprintf("%02d", $i);
	}
	$html = $html.'</select>';
	$html = $html.'<nbsp>:<nbsp>';
	$html = $html.'<select name="'.$name.'[minute_finish]">';
	$default = '';
	if ( $minute_finish === '-' ) {
	 	$default = ' selected';
	}
	$html = $html."<option value=\"-\" $default>-";
	for ($i = 0 ; $i < 60 ; $i++) {
		$default = '';
		if ( sprintf("%02d", $i) === $minute_finish ) {
		 	$default = ' selected';
		}
		$html = $html."<option value=\"$i\" $default> ".sprintf("%02d", $i);
	}
	$html = $html.'</select>';
	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'<tr>';

	// WDay could be *, day, day1-day2
	if ( $time_wday != '*' ) {
		list($wday_start, $wday_finish) = explode('-', $time_wday);
		if ($wday_start === '*') {
			$wday_start = $wday_finish;
		}
		if ($wday_finish === '*') {
			$wday_finish = $wday_start;
		}
		if ( !$wday_finish) {
		 	$wday_finish = $wday_start;
		}
	} else {
		$wday_start = $wday_finish = '-';
	}
	$html = $html.'<td>'._("Week Day Start:").'</td>';
	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[wday_start]">';
	if ( $wday_start == '-' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"-\" $default>-";
 
	if ( $wday_start == 'mon' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"mon\" $default>" . _("Monday");

	if ( $wday_start == 'tue' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"tue\" $default>" . _("Tuesday");

	if ( $wday_start == 'wed' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"wed\" $default>" . _("Wednesday");

	if ( $wday_start == 'thu' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"thu\" $default>" . _("Thursday");

	if ( $wday_start == 'fri' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"fri\" $default>" . _("Friday");

	if ( $wday_start == 'sat' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"sat\" $default>" . _("Saturday");

	if ( $wday_start == 'sun' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"sun\" $default>" . _("Sunday");

	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Week Day finish:").'</td>';
	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[wday_finish]">';

	if ( $wday_finish == '-' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"-\" $default>-";
 
	if ( $wday_finish == 'mon' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"mon\" $default>" . _("Monday");

	if ( $wday_finish == 'tue' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"tue\" $default>" . _("Tuesday");

	if ( $wday_finish == 'wed' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"wed\" $default>" . _("Wednesday");

	if ( $wday_finish == 'thu' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"thu\" $default>" . _("Thursday");

	if ( $wday_finish == 'fri' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"fri\" $default>" . _("Friday");

	if ( $wday_finish == 'sat' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"sat\" $default>" . _("Saturday");

	if ( $wday_finish == 'sun' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"sun\" $default>" . _("Sunday");

	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Month Day start:").'</td>';

	// MDay could be *, day, day1-day2
	if ( $time_mday != '*' ) {
		list($mday_start, $mday_finish) = explode('-', $time_mday);
		if ($mday_start === '*') {
			$mday_start = $mday_finish;
		}
		if ($mday_finish === '*') {
			$mday_finish = $mday_start;
		}
		if ( !$mday_finish) { 
			$mday_finish = $mday_start;
		}
	} else {
		$mday_start = $mday_finish = '-';
	}

	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[mday_start]">';
	$default = '';
	if ( $mday_start == '-' ) {
		$default = ' selected';
	}
	$html = $html."<option value=\"-\" $default>-";
	for ($i = 1 ; $i < 32 ; $i++) {
		$default = '';
		if ( $i == $mday_start ) {
		 	$default = ' selected';
		}
		$html = $html."<option value=\"$i\" $default> $i";
	}
	$html = $html.'</select>';
	$html = $html.'</td>';
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Month Day finish:").'</td>';
	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[mday_finish]">';
	$default = '';
	if ( $mday_finish == '-' ) {
 		$default = ' selected';
	}
	$html = $html."<option value=\"-\" $default>-";
	for ($i = 1 ; $i < 32 ; $i++) {
		$default = '';
		if ( $i == $mday_finish ) {
		 	$default = ' selected';
		}
		$html = $html."<option value=\"$i\" $default> $i";
	}
	$html = $html.'</select>';
	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Month start:").'</td>';

	// Month could be *, month, month1-month2
	if ( $time_month != '*' ) {
		list($month_start, $month_finish) = explode('-', $time_month);
		if ($month_start === '*') {
			$month_start = $month_finish;
		}
		if ($month_finish === '*') {
			$month_finish = $month_start;
		}
		if ( !$month_finish) {
		 	$month_finish = $month_start;
		}
	} else {
		$month_start = $month_finish = '-';
	}
	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[month_start]">';

	if ( $month_start == '-' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"-\" $default>-";

	if ( $month_start == 'jan' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"jan\" $default>" . _("January");
	                               
	if ( $month_start == 'feb' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"feb\" $default>" . _("February");
	
	if ( $month_start == 'mar' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"mar\" $default>" . _("March");
	                               
	if ( $month_start == 'apr' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"apr\" $default>" . _("April");
	 
	if ( $month_start == 'may' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"may\" $default>" . _("May");
                               
	if ( $month_start == 'jun' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"jun\" $default>" . _("June");

	if ( $month_start == 'jul' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"jul\" $default>" . _("July");
	                               
	if ( $month_start == 'aug' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"aug\" $default>" . _("August");
	 
	if ( $month_start == 'sep' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"sep\" $default>" . _("September");
                               
	if ( $month_start == 'oct' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"oct\" $default>" . _("October");

	if ( $month_start == 'nov' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"nov\" $default>" . _("November");
	                               
	if ( $month_start == 'dec' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"dec\" $default>" . _("December");

	$html = $html.'</select>';
	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'<tr>';
	$html = $html.'<td>'._("Month finish:").'</td>';
	$html = $html.'<td>';
	$html = $html.'<select name="'.$name.'[month_finish]">';

	if ( $month_finish == '-' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"-\" $default>-";

	if ( $month_finish == 'jan' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"jan\" $default>" . _("January");
	                               
	if ( $month_finish == 'feb' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"feb\" $default>" . _("February");
	
	if ( $month_finish == 'mar' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"mar\" $default>" . _("March");
	                               
	if ( $month_finish == 'apr' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"apr\" $default>" . _("April");
	 
	if ( $month_finish == 'may' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"may\" $default>" . _("May");
	                               
	if ( $month_finish == 'jun' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"jun\" $default>" . _("June");
	
	if ( $month_finish == 'jul' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"jul\" $default>" . _("July");
	                               
	if ( $month_finish == 'aug' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"aug\" $default>" . _("August");
	 
	if ( $month_finish == 'sep' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"sep\" $default>" . _("September");
	                               
	if ( $month_finish == 'oct' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"oct\" $default>" . _("October");
	
	if ( $month_finish == 'nov' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"nov\" $default>" . _("November");
	                               
	if ( $month_finish == 'dec' ) { 
		$default = ' selected'; 
	} else {
		$default = '';
	}
	$html = $html."<option value=\"dec\" $default>" . _("December");

	$html = $html.'</select>';
	$html = $html.'</td>';
	$html = $html.'</tr>';
	$html = $html.'</tr>';
	return $html;
}

function timeconditions_timegroups_buildtime( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish) {

	//----- Time Hour Interval proccess ----
	//
	if ($minute_start == '-') {
		$time_minute_start = "00";
	} else {
		$time_minute_start = sprintf("%02d",$minute_start);
	}
	if ($minute_finish == '-') {
		$time_minute_finish = "00";
	} else {
		$time_minute_finish = sprintf("%02d",$minute_finish);
	}
	if ($hour_start == '-') {
		$time_hour_start = '*';
	} else {
		$time_hour_start = sprintf("%02d",$hour_start) . ':' . $time_minute_start;
	}
	if ($hour_finish == '-') {
		$time_hour_finish = '*';
	} else {
		$time_hour_finish = sprintf("%02d",$hour_finish) . ':' . $time_minute_finish;
	}
	if ($time_hour_start === '*') {
		$time_hour_start = $time_hour_finish;
	}
	if ($time_hour_finish === '*') {$time_hour_finish = $time_hour_start;}
	if ($time_hour_start == $time_hour_finish) {
		$time_hour = $time_hour_start;
	} else {
		$time_hour = $time_hour_start . '-' . $time_hour_finish;
	}

	//----- Time Week Day Interval proccess -----
	//
	if ($wday_start == '-') {
		$time_wday_start = '*';
	} else {
		$time_wday_start = $wday_start;
	}
	if ($wday_finish == '-') {
		$time_wday_finish = '*';
	} else {
		$time_wday_finish = $wday_finish;
	}
	if ($time_wday_start === '*') {
		$time_wday_start = $time_wday_finish;
	}
	if ($time_wday_finish === '*') {
		$time_wday_finish = $time_wday_start;
	}
	if ($time_wday_start == $time_wday_finish) {
		$time_wday = $time_wday_start;
	} else {
		$time_wday = $time_wday_start . '-' . $time_wday_finish;
	}

	//----- Time Month Day Interval proccess -----
	//
	if ($mday_start == '-') {
		$time_mday_start = '*';
	} else {
		$time_mday_start = $mday_start;
	}
	if ($mday_finish == '-') {
		$time_mday_finish = '*';
	} else {
		$time_mday_finish = $mday_finish;
	}
	if ($time_mday_start === '*') {
		$time_mday_start = $time_mday_finish;
	}
	if ($time_mday_finish === '*') {
		$time_mday_finish = $time_mday_start;
	}
	if ($time_mday_start == $time_mday_finish) {
		$time_mday = $time_mday_start;
	} else {
		$time_mday = $time_mday_start . '-' . $time_mday_finish;
	}

	//----- Time Month Interval proccess -----
	//
	if ($month_start == '-') {
		$time_month_start = '*';
	} else {
		$time_month_start = $month_start;
	}
	if ($month_finish == '-') {
		$time_month_finish = '*';
	} else {
		$time_month_finish = $month_finish;
	}
	if ($time_month_start === '*') {
		$time_month_start = $time_month_finish;
	}
	if ($time_month_finish === '*') {
		$time_month_finish = $time_month_start;
	}
	if ($time_month_start == $time_month_finish) {
		$time_month = $time_month_start;
	} else {
		$time_month = $time_month_start . '-' . $time_month_finish;
	}
	$time = $time_hour . '|' . $time_wday . '|' . $time_mday . '|' . $time_month;
	return $time;
}

//---------------------------end stolen from timeconditions-------------------------------------

?>
