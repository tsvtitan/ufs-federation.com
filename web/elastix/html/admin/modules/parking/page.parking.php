<?php /* $Id: page.parking.php 2243 2006-08-12 17:13:17Z p_lindheimer $ */
//Copyright (C) 2006 Astrogen LLC 
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$dispnum = 'parking'; //used for switch on config.php
$tabindex = 0;

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

isset($_REQUEST['parkingenabled'])?$parkingenabled=$_REQUEST['parkingenabled']:$parkingenabled='';
isset($_REQUEST['parkext'])?$parkext = trim($_REQUEST['parkext']):$parkext='70';
isset($_REQUEST['numslots'])?$numslots = trim($_REQUEST['numslots']):$numslots='8';
isset($_REQUEST['parkingtime'])?$parkingtime = trim($_REQUEST['parkingtime']):$parkingtime='45';
isset($_REQUEST['parkingcontext'])?$parkingcontext = trim($_REQUEST['parkingcontext']):$parkingcontext='parkedcalls';
isset($_REQUEST['parkalertinfo'])?$parkalertinfo = trim($_REQUEST['parkalertinfo']):$parkalertinfo='';
isset($_REQUEST['parkcid'])?$parkcid = trim($_REQUEST['parkcid']):$parkcid='';
isset($_REQUEST['parkingannmsg_id'])?$parkingannmsg_id = trim($_REQUEST['parkingannmsg_id']):$parkingannmsg_id='';

if (isset($_REQUEST['goto0']) && isset($_REQUEST[$_REQUEST['goto0']."0"])) {
        $goto = $_REQUEST[$_REQUEST['goto0']."0"];
} else {
        $goto = '';
}

// do if we are submitting a form
if(isset($_POST['action'])){

	if ($action == 'edtPARKING') {
		parking_add($parkingenabled, $parkext, $numslots, $parkingtime, $parkingcontext, $parkalertinfo, $parkcid, $parkingannmsg_id, $goto);
		needreload();
		redirect_standard();
	}
}
?>
</div>
<div class="content">
<?php

// get the parkinglot settings if not a submit
//
if (!$action) {
	$parkinglot_id = 1; // only 1 lot now but prepare for future
	$parkingInfo = parking_getconfig($parkinglot_id);
	if (is_array($parkingInfo)) extract($parkingInfo);
}

?>
<h2><?php echo _("Parking Lot Configuration")?></h2>
<form name="parking" action="config.php" method="post" onsubmit="return parking_onsubmit();">
<input type="hidden" name="display" value="parking"/>
<input type="hidden" name="action" value="edtPARKING"/>
<table>
<tr><td colspan="2"><h5><?php echo _("Parking Lot Options")?><hr></h5></td></tr>
	<tr>
	<td><a href=# class="info"><?php echo _("Enable Parking Lot Feature")?><span><?php echo _("Check this box to enable the parking feature")?></span></a></td>
	<td align=right><input type="checkbox" value="s" name="parkingenabled" <?php  echo ($parkingenabled ? 'CHECKED' : '')?> tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>
	<tr>
	<td><a href=# class="info"><?php echo _("Parking Lot Extension:")?><span>
<?php echo _("This is the extension where you will transfer a call to park it.")?><br>
	</span></a></td>
	<td align=right><input type="text" size="3" name="parkext" value="<?php  echo htmlspecialchars($parkext)?>" tabindex="<?php echo ++$tabindex;?>"/></td>
	</tr>
	<tr>
		<td><a href="#" class="info"><?php echo _("Number of Slots:")?><span><?php echo _("The total number of parking lot spaces to configure. Example, if 70 is the extension and 8 slots are configured, the parking slots will be 71-78")?></span></a></td>
		<td align=right>
			<select name="numslots" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($numslots) ? $numslots : 8);
				for ($i=2; $i <= 20; $i++) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.$i.'</option>';
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
	<td><a href="#" class="info"><?php echo _("Parking Timeout:")?><span><?php echo _("The timeout period that a parked call will attempt to ring back the original parker if not answered")?></span></a></td>
		<td align=right>
			<select name="parkingtime" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($parkingtime) ? $parkingtime : 45);
				for ($i=15; $i <= 600; $i+=15) {
					echo '<option value="'.$i.'"'.($i == $parkingtime ? ' SELECTED' : '').'>'.parking_timeString($i,true).'</option>';
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
	<td><a href=# class="info"><?php echo _("Parking Lot Context:")?><span>
<?php echo _("This is the parking lot context. You should not change it from the default unless you know what you are doing.")?><br>
	</span></a></td>
	<td align=right><input type="text" size="19" name="parkingcontext" value="<?php  echo htmlspecialchars($parkingcontext)?>" tabindex="<?php echo ++$tabindex;?>"/></td>
	</tr>
<tr><td colspan="2"><br><h5><?php echo _("Actions for Timed-Out Orphans")?><hr></h5></td></tr>
	<tr>
	<td><a href=# class="info"><?php echo _("Parking Alert-Info:")?><span>
<?php echo _("Alert-Info to put in channel before going to defined destination below. This can create distinct rings on some SIP phones and can serve to alert the recipients that the call is from an Orphaned parked call")?><br>
	</span></a></td>
	<td align=right><input type="text" size="19" name="parkalertinfo" value="<?php  echo htmlspecialchars($parkalertinfo)?>" tabindex="<?php echo ++$tabindex;?>"/></td>
	</tr>
	<tr>
	<td><a href=# class="info"><?php echo _("CallerID Prepend:")?><span>
<?php echo _("String to pre-pend to the current Caller-ID associated with this call (if any), before going to defined destination below. This can serve to alert the recipients that the call is from an Orphaned parked call")?><br>
	</span></a></td>
	<td align=right><input type="text" size="19" name="parkcid" value="<?php  echo htmlspecialchars($parkcid)?>" tabindex="<?php echo ++$tabindex;?>"/></td>
	</tr>
<?php if(function_exists('recordings_list')) { //only include if recordings is enabled?>
	<tr>
		<td><a href="#" class="info"><?php echo _("Announcement:")?><span><?php echo _("Optional message to be played to the orphaned caller prior to going on the to supplied destination below.<br><br>To add additional recordings please use the \"System Recordings\" MENU to the left")?></span></a></td>
		<td align=right>
			<select name="parkingannmsg_id" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$tresults = recordings_list();
				$default = (isset($parkingannmsg_id) ? $parkingannmsg_id : '');
				echo '<option value="">'._("None")."</option>";
				if (isset($tresults[0])) {
					foreach ($tresults as $tresult) {
						echo '<option value="'.$tresult['id'].'"'.($tresult['id'] == $default ? ' SELECTED' : '').'>'.$tresult['displayname']."</option>\n";
					}
				}

			?>
			</select>
		</td>
	</tr>
<?php } else { ?>
	<tr>
		<td><a href="#" class="info"><?php echo _("Announcement:")?><span><?php echo _("Optional message to be played to the orphaned caller prior to going on to the supplied destination below.<br><br>You must install and enable the \"Systems Recordings\" Module to edit this option")?></span></a></td>
		<td align=right>
			<?php
				$default = (isset($parkingannmsg_id) ? $parkingannmsg_id : '');
			?>
			<input type="hidden" name="parkingannmsg_id" value="<?php echo $default; ?>"><?php echo ($default != '' ? $default : 'None'); ?>
		</td>
	</tr>
<?php } ?>

	<tr><td colspan="2"><br><h5><?php echo _("Destination for Orphaned Parked Calls")?>:<hr></h5></td></tr>
<?php
//draw goto selects
echo drawselects($goto,0);
?>
	<tr>
		<td colspan="2"><br><h6><input name="Submit" type="submit" value="<?php echo _("Submit Changes")?>" tabindex="<?php echo ++$tabindex;?>"></h6></td>
	</tr>
	</table>

<script language="javascript">
<!--

var theForm = document.parking;

function parking_onsubmit() {
	var msgInvalidExtension = "<?php echo _('Please enter a valid numeric parking lot extension'); ?>";

	defaultEmptyOK = false;
	if (!isInteger(theForm.parkext.value))
		return warnInvalid(theForm.parkext, msgInvalidExtension);
	return true;
}

//-->
</script>
</form>

