<?php /* $Id: page.queues.php 9966 2010-06-27 07:47:41Z mickecarlsson $ */
//Copyright (C) 2004 Coalescent Systems Inc. (info@coalescentsystems.ca)
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

//used for switch on config.php
$dispnum = 'queues';

isset($_REQUEST['action'])?$action = $_REQUEST['action']:$action='';
//the extension we are currently displaying
isset($_REQUEST['extdisplay'])?$extdisplay=$_REQUEST['extdisplay']:$extdisplay='';
isset($_REQUEST['account'])?$account = $_REQUEST['account']:$account='';
isset($_REQUEST['name'])?$name = $_REQUEST['name']:$name='';
isset($_REQUEST['password'])?$password = $_REQUEST['password']:$password='';
isset($_REQUEST['agentannounce_id'])?$agentannounce_id = $_REQUEST['agentannounce_id']:$agentannounce_id='';
isset($_REQUEST['prefix'])?$prefix = $_REQUEST['prefix']:$prefix='';
isset($_REQUEST['alertinfo'])?$alertinfo = $_REQUEST['alertinfo']:$alertinfo='';
isset($_REQUEST['joinannounce_id'])?$joinannounce_id = $_REQUEST['joinannounce_id']:$joinannounce_id='';
$maxwait = isset($_REQUEST['maxwait'])?$_REQUEST['maxwait']:'';
$cwignore = isset($_REQUEST['cwignore'])?$_REQUEST['cwignore']:'0';
$queuewait = isset($_REQUEST['queuewait'])?$_REQUEST['queuewait']:'0';
$rtone = isset($_REQUEST['rtone'])?$_REQUEST['rtone']:'0';
$qregex = isset($_REQUEST['qregex'])?$_REQUEST['qregex']:'';
$weight = isset($_REQUEST['weight'])?$_REQUEST['weight']:'0';
$autofill = isset($_REQUEST['autofill'])?$_REQUEST['autofill']:'no';
$togglehint = isset($_REQUEST['togglehint'])?$_REQUEST['togglehint']:'0';
$dynmemberonly = isset($_REQUEST['dynmemberonly'])?$_REQUEST['dynmemberonly']:'no';
$use_queue_context = isset($_REQUEST['use_queue_context'])?$_REQUEST['use_queue_context']:'0';
$exten_context = "from-queue";

$engineinfo = engine_getinfo();
$astver =  $engineinfo['version'];
$ast_ge_16 = version_compare($astver, '1.6', 'ge');

if (isset($_REQUEST['goto0']) && isset($_REQUEST[$_REQUEST['goto0']."0"])) {
	$goto = $_REQUEST[$_REQUEST['goto0']."0"];
} else {
	$goto = '';
}
if (isset($_REQUEST["members"])) {
	$members = explode("\n",$_REQUEST["members"]);

	if (!$members) {
		$members = null;
	}
	
	foreach (array_keys($members) as $key) {
		//trim it
		$members[$key] = trim($members[$key]);

		// check if an agent (starts with a or A)

    $exten_prefix = strtoupper(substr($members[$key],0,1));
		$this_member = preg_replace("/[^0-9#\,*]/", "", $members[$key]);
    switch ($exten_prefix) {
    case 'A':
      $exten_type = 'Agent';
      break;
    case 'S':
      $exten_type = 'SIP';
      break;
    case 'X':
      $exten_type = 'IAX2';
      break;
    case 'Z':
      $exten_type = 'ZAP';
      break;
    case 'D':
      $exten_type = 'DAHDI';
      break;
    default;
      $exten_type = 'Local';
    }

		$penalty_pos = strrpos($this_member, ",");
		if ( $penalty_pos === false ) {
				$penalty_val = 0;
		} else {
				$penalty_val = substr($this_member, $penalty_pos+1); // get penalty
				$this_member = substr($this_member,0,$penalty_pos); // clean up ext
				$this_member = preg_replace("/[^0-9#*]/", "", $this_member); //clean out other ,'s
				$penalty_val = preg_replace("/[^0-9*]/", "", $penalty_val); // get rid of #'s if there
				$penalty_val = ($penalty_val == "") ? 0 : $penalty_val;
		}

		// remove blanks // prefix with the channel
		if (empty($this_member))  
			unset($members[$key]);
		else {
      switch($exten_type) {
        case 'Agent':
        case 'SIP':
        case 'IAX2':
        case 'ZAP':
        case 'DAHDI':
			    $members[$key] = "$exten_type/$this_member,$penalty_val";
          break;
        case 'Local':
			    $members[$key] = "$exten_type/$this_member@$exten_context/n,$penalty_val";
      }
		}
	}
	// check for duplicates, and re-sequence
	// $members = array_values(array_unique($members));
}

if (isset($_REQUEST["dynmembers"])) {
	$dynmembers=explode("\n",$_REQUEST["dynmembers"]);
	if (!$dynmembers) {
		$dynmembers = null;
	}
}


// do if we are submitting a form
if(isset($_POST['action'])){
	//check if the extension is within range for this user
	if (isset($account) && !checkRange($account)){
		echo "<script>javascript:alert('"._("Warning! Extension")." $account "._("is not allowed for your account.")."');</script>";
	} else {
		
		//if submitting form, update database
		switch ($action) {
			case "add":
				$conflict_url = array();
				$usage_arr = framework_check_extension_usage($account);
				if (!empty($usage_arr)) {
					$conflict_url = framework_display_extension_usage_alert($usage_arr);
				} else {
					queues_add($account,$name,$password,$prefix,$goto,$agentannounce_id,$members,$joinannounce_id,$maxwait,$alertinfo,$cwignore,$qregex,$queuewait,$use_queue_context,$dynmembers,$dynmemberonly,$togglehint);
					needreload();
          $_REQUEST['extdisplay'] = $account;
					redirect_standard('extdisplay');
				}
			break;
			case "delete":
				queues_del($account);
				needreload();
				redirect_standard();
			break;
			case "edit":  //just delete and re-add
				queues_del($account);
				queues_add($account,$name,$password,$prefix,$goto,$agentannounce_id,$members,$joinannounce_id,$maxwait,$alertinfo,$cwignore,$qregex,$queuewait,$use_queue_context,$dynmembers,$dynmemberonly,$togglehint);
				needreload();
				redirect_standard('extdisplay');
			break;
		}
	}
}

//get unique queues
$queues = queues_list();
	
?>
</div>

<div class="rnav"><ul>
    <li><a id="<?php echo ($extdisplay=='' ? 'current':'') ?>" href="config.php?display=<?php echo urlencode($dispnum)?>"><?php echo _("Add Queue")?></a></li>
<?php
if (isset($queues)) {
	foreach ($queues as $queue) {
		echo "<li><a id=\"".($extdisplay==$queue[0] ? 'current':'')."\" href=\"config.php?display=".urlencode($dispnum)."&extdisplay=".urlencode($queue[0])."\">{$queue[0]}:{$queue[1]}</a></li>";
	}
}
?>
</ul>
</div>

<div class="content">
<?php
if ($action == 'delete') {
	echo '<br><h3>'._("Queue").' '.$account.' '._("deleted").'!</h3><br><br><br><br><br><br><br><br>';
} else {
	$member = array();
	//get members in this queue
	$thisQ = queues_get($extdisplay);
	//create variables
	extract($thisQ);

  $mem_array = array();
  foreach ($member as $mem) {
    if (preg_match("/^(Local|Agent|SIP|DAHDI|ZAP|IAX2)\/([\d]+).*,([\d]+)$/",$mem,$matches)) {
      switch ($matches[1]) {
        case 'Agent':
          $exten_prefix = 'A';
          break;
        case 'SIP':
          $exten_prefix = 'S';
          break;
        case 'IAX2':
          $exten_prefix = 'X';
          break;
        case 'ZAP':
          $exten_prefix = 'Z';
          break;
        case 'DAHDI':
          $exten_prefix = 'D';
          break;
        case 'Local':
          $exten_prefix = '';
          break;
      }
      $mem_array[] = $exten_prefix.$matches[2].','.$matches[3];
    }
  }
	
	$delButton = "
				<form name=delete action=\"{$_SERVER['PHP_SELF']}\" method=POST>
					<input type=\"hidden\" name=\"display\" value=\"{$dispnum}\">
					<input type=\"hidden\" name=\"account\" value=\"{$extdisplay}\">
					<input type=\"hidden\" name=\"action\" value=\"delete\">
					<input type=submit value=\""._("Delete Queue")."\">
				</form>";
?>

<?php if (!empty($conflict_url)) {
      	echo "<h5>"._("Conflicting Extensions")."</h5>";
      	echo implode('<br .>',$conflict_url);
      }
?>
<?php if ($extdisplay != '') { ?>
	<h2><?php echo _("Queue:")." ". $extdisplay; ?></h2>
<?php } else { ?>
	<h2><?php echo _("Add Queue"); ?></h2>
<?php } ?>

<?php		if ($extdisplay != '') { 
					echo $delButton;
					$usage_list = framework_display_destination_usage(queues_getdest($extdisplay));
					if (!empty($usage_list)) {
?>
						<a href="#" class="info"><?php echo $usage_list['text']?>:<span><?php echo $usage_list['tooltip']?></span></a>
<?php
					}
				} 
?>
	<form autocomplete="off" name="editQ" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<input type="hidden" name="display" value="<?php echo $dispnum?>">
	<input type="hidden" name="action" value="<?php echo (($extdisplay != '') ? 'edit' : 'add') ?>">
	<table>
	<tr><td colspan="2"><h5><?php echo ($extdisplay ? _("Edit Queue") : _("Add Queue")) ?><hr></h5></td></tr>
	<tr>
<?php		if ($extdisplay != ''){ ?>
		<input type="hidden" name="account" value="<?php echo $extdisplay; ?>">
<?php		} else { ?>
		<td><a href="#" class="info"><?php echo _("Queue Number:")?><span><?php echo _("Use this number to dial into the queue, or transfer callers to this number to put them into the queue.<br><br>Agents will dial this queue number plus * to log onto the queue, and this queue number plus ** to log out of the queue.<br><br>For example, if the queue number is 123:<br><br><b>123* = log in<br>123** = log out</b>")?></span></a></td>
		<td><input type="text" name="account" value="" tabindex="<?php echo ++$tabindex;?>"></td>
<?php		} ?>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Queue Name:")?><span><?php echo _("Give this queue a brief name to help you identify it.")?></span></a></td>
		<td><input type="text" name="name" value="<?php echo (isset($name) ? $name : ''); ?>" tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Queue Password:")?><span><?php echo _("You can require agents to enter a password before they can log in to this queue.<br><br>This setting is optional.")?></span></a></td>
		<td><input type="text" name="password" value="<?php echo (isset($password) ? $password : ''); ?>" tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>

<?php if ($amp_conf['USEDEVSTATE']) { ?>
	<tr>
  <td><a href="#" class="info"><?php echo _("Generate Device Hints:")?><span><?php echo _("If checked, individual hints and dialplan will be generated for each SIP and IAX2 device that could be part of this queue. These are used in conjunction with programmable BLF phone buttons to log into and out of a queue and generate BLF status as to the current state. The format of the hints is<br /><br />*45ddd*qqq<br /><br />where *45 is the currently defined toggle feature code, ddd is the device number (typically the same as the extension number) and qqq is this queue's number.")?></span></a></td>
		<td>
			<input name="togglehint" type="checkbox" value="1" <?php echo (isset($togglehint) && $togglehint == '1' ? 'checked' : ''); ?>  tabindex="<?php echo ++$tabindex;?>"/>
		</td>
	</tr>
<?php } ?>
	
	<tr>
		<td><a href="#" class="info"><?php echo _("CID Name Prefix:")?><span><?php echo _("You can optionally prefix the Caller ID name of callers to the queue. ie: If you prefix with \"Sales:\", a call from John Doe would display as \"Sales:John Doe\" on the extensions that ring.")?></span></a></td>
		<td><input size="4" type="text" name="prefix" value="<?php echo (isset($prefix) ? $prefix : ''); ?>" tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Wait Time Prefix:")?><span><?php echo _("When set to Yes, the CID Name will be prefixed with the total wait time in the queue so the answering agent is aware how long they have waited. It will be rounded to the nearest minute, in the form of Mnn: where nn is the number of minutes.").'<br />'._("If the call is subsequently transfered, the wait time will reflect the time since it first entered the queue or reset if the call is transfered to another queue with this feature set.")?></span></a></td>
		<td>
			<select name="queuewait" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($queuewait) ? $queuewait : '0');
				$items = array('1'=>_("Yes"),'0'=>_("No"));
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>
			</select>
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Alert Info")?><span><?php echo _('ALERT_INFO can be used for distinctive ring with SIP devices.')?></span></a>:</td>
		<td><input type="text" name="alertinfo" size="30" value="<?php echo (isset($alertinfo)?$alertinfo:'') ?>" tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>

	<tr>
    <td valign="top"><a href="#" class="info"><?php echo _("Static Agents") ?>:<span><br><?php echo _("Static agents are extensions that are assumed to always be on the queue.  Static agents do not need to 'log in' to the queue, and cannot 'log out' of the queue.<br><br>List extensions to ring, one per line.<br><br>You can include an extension on a remote system, or an external number (Outbound Routing must contain a valid route for external numbers). You can put a \",\" after the agent followed by a penalty value, see Asterisk documentation concerning penalties.<br /><br /> An advanced mode has been added which allows you to prefix an agent number with S, X, Z, D or A. This will force the agent number to be dialed as an Asterisk device of type SIP, IAX2, ZAP, DAHDI or Agent respectively. This mode is for advanced users and can cause known issues in FreePBX as you are by-passing the normal dialplan. If your 'Agent Restrictions' are not set to 'Extension Only' you will have problems with subsequent transfers to voicemail and other issues may also exist. (Channel Agent is deprecated starting with Asterisk 1.4 and gone in 1.6+.)") ?><br><br></span></a></td>
		<td valign="top">
			<textarea id="members" cols="15" rows="<?php  $rows = count($mem_array)+1; echo (($rows < 5) ? 5 : (($rows > 20) ? 20 : $rows) ); ?>" name="members" tabindex="<?php echo ++$tabindex;?>"><?php echo implode("\n",$mem_array) ?></textarea>
		</td>
	</tr>

	<tr>
		<td>
		<a href=# class="info"><?php echo _("Extension Quick Pick")?>
			<span>
				<?php echo _("Choose an extension to append to the end of the static agents list above.")?>
			</span>
		</a>
		</td>
		<td>
			<select onChange="insertExten('');" id="insexten" tabindex="<?php echo ++$tabindex;?>">
				<option value=""><?php echo _("(pick extension)")?></option>
	<?php
				$results = core_users_list();
				foreach ($results as $result) {
					echo "<option value='".$result[0]."'>".$result[0]." (".$result[1].")</option>\n";
				}
	?>
			</select>
		</td>
	</tr>

	<tr>
		<td valign="top"><a href="#" class="info"><?php echo _('Dynamic Members') ?>:<span><br><?php echo _("Dynamic Members are extensions or callback numbers that can log in and out of the queue. When a member logs in to a queue, their penalty in the queue will be as specified here. Extensions included here will NOT automatically be logged in to the queue.") ?><br><br></span></a></td>
		<td valign="top">
			<textarea id="dynmembers" cols="15" rows="<?php  $rows = count($dynmembers)+1; echo (($rows < 5) ? 5 : (($rows > 20) ? 20 : $rows) ); ?>" name="dynmembers" tabindex="<?php echo ++$tabindex;?>"><?php echo $dynmembers; ?></textarea>
		</td>
	</tr>

	<tr>
		<td>
		<a href=# class="info"><?php echo _("Extension Quick Pick")?>
			<span>
				<?php echo _("Choose an extension to append to the end of the dynamic member list above.")?>
			</span>
		</a>
		</td>
		<td>
			<select onChange="insertExten('dyn');" id="dyninsexten" tabindex="<?php echo ++$tabindex;?>">
				<option value=""><?php echo _("(pick extension)")?></option>
	<?php
				$results = core_users_list();
				foreach ($results as $result) {
					echo "<option value='".$result[0]."'>".$result[0]." (".$result[1].")</option>\n";
				}
	?>
			</select>
		</td>
	</tr>

	<tr>
	  <td><a href="#" class="info"><?php echo _("Restrict Dynamic Agents")?><span><?php echo _('Restrict dynamic queue member logins to only those listed in the Dynamic Members list above. When set to Yes, members not listed will be DENIED ACCESS to the queue.')?></span></a></td>
    <td><input type="radio" name="dynmemberonly" value="yes" <?php echo ($dynmemberonly=='yes'?'checked':'');?>><?php echo _('Yes')?><input type="radio" name="dynmemberonly" value="no" <?php echo ($dynmemberonly!='yes'?'checked':'');?> ><?php echo _('No')?>
		</td>
	</tr>

	<tr>
	<td><a href="#" class="info"><?php echo _("Agent Restrictions")?><span><?php echo _("When set to 'Call as Dialed' the queue will call an extension just as if the queue were another user. Any Follow-Me or Call Forward states active on the extension will result in the queue call following these call paths. This behavior has been the standard queue behavior on past FreePBX versions. <br />When set to 'No Follow-Me or Call Forward', all agents that are extensions on the system will be limited to ringing their extensions only. Follow-Me and Call Forward settings will be ignored. Any other agent will be called as dialed. This behavior is similar to how extensions are dialed in ringgroups. <br />When set to 'Extensions Only' the queue will dial Extensions as described for 'No Follow-Me or Call Forward'. Any other number entered for an agent that is NOT a valid extension will be ignored. No error checking is provided when entering a static agent or when logging on as a dynamic agent, the call will simply be blocked when the queue tries to call it. For dynamic agents, see the 'Agent Regex Filter' to provide some validation.")?></span></a></td>
		<td>
			<select name="use_queue_context" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($use_queue_context) ? $use_queue_context : '0');
				echo '<option value="0"'. ($default == '0' ? ' SELECTED' : '').'>'._("Call as Dialed")."\n";
				echo '<option value="1"'. ($default == '1' ? ' SELECTED' : '').'>'._("No Follow-Me or Call Forward")."\n";
				echo '<option value="2"'. ($default == '2' ? ' SELECTED' : '').'>'._("Extensions Only")."\n";
			?>
			</select>
		</td>
	</tr>

	<tr><td colspan="2"><br><h5><?php echo _("Queue Options")?><hr></h5></td></tr>
<?php if(function_exists('recordings_list')) { //only include if recordings is enabled?>
	<tr>
		<td><a href="#" class="info"><?php echo _("Agent Announcement:")?><span><?php echo _("Announcement played to the Agent prior to bridging in the caller <br><br> Example: \"the Following call is from the Sales Queue\" or \"This call is from the Technical Support Queue\".<br><br>To add additional recordings please use the \"System Recordings\" MENU to the left. Compound recordings composed of 2 or more sound files are not displayed as options since this feature can not accept such recordings.")?></span></a></td>
		<td>
			<select name="agentannounce_id" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$tresults = recordings_list(false);
				$default = (isset($agentannounce_id) ? $agentannounce_id : '');

				echo '<option value="">'._("None").'</option>';
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
		<td><a href="#" class="info"><?php echo _("Agent Announcement:")?><span><?php echo _("Announcement played to the Agent prior to bridging in the caller <br><br> Example: \"the Following call is from the Sales Queue\" or \"This call is from the Technical Support Queue\".<br><br>You must install and enable the \"Systems Recordings\" Module to edit this option")?></span></a></td>
		<td>
			<?php
				$default = (isset($agentannounce_id) ? $agentannounce_id : '');
			?>
			<input type="hidden" name="agentannounce_id" value="<?php echo $default; ?>"><?php echo ($default != '' ? $default : ''); ?>
		</td>
	</tr>
<?php 
	}
if(function_exists('recordings_list')) { //only include if recordings is enabled ?>
	<tr>
		<td><a href="#" class="info"><?php echo _("Join Announcement:")?><span><?php echo _("Announcement played to callers once prior to joining the queue.<br><br>To add additional recordings please use the \"System Recordings\" MENU to the left")?></span></a></td>
		<td>
			<select name="joinannounce_id" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$tresults = recordings_list();
				$default = (isset($joinannounce_id) ? $joinannounce_id : '');
				echo '<option value="None">'._("None");
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
		<td><a href="#" class="info"><?php echo _("Join Announcement:")?><span><?php echo _("Announcement played to callers once prior to joining the queue.<br><br>You must install and enable the \"Systems Recordings\" Module to edit this option")?></span></a></td>
		<td>
			<?php
				$default = (isset($joinannounce_id) ? $joinannounce_id : '');
			?>
			<input type="hidden" name="joinannounce_id" value="<?php echo $default; ?>"><?php echo ($default != '' ? $default : ''); ?>
		</td>
	</tr>
<?php 
}

if(function_exists('music_list')) { //only include if music module is enabled?>
	<tr>
		<td><a href="#" class="info"><?php echo _("Music on Hold Class:")?><span><?php echo _("Music (or Commercial) played to the caller while they wait in line for an available agent. Choose \"inherit\" if you want the MoH class to be what is currently selected, such as by the inbound route.<br><br>  This music is defined in the \"Music on Hold\" Menu to the left.")?></span></a></td>
		<td>
			<select name="music" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$tresults = music_list();
				array_unshift($tresults,'inherit');
				$default = (isset($music) ? $music : 'inherit');
				if (isset($tresults)) {
					foreach ($tresults as $tresult) {
						$searchvalue="$tresult";
						$ttext = $tresult;
						if($tresult == 'inherit') $ttext = _("inherit");
						if($tresult == 'none') $ttext = _("none");
						if($tresult == 'default') $ttext = _("default");						
						echo '<option value="'.$tresult.'" '.($searchvalue == $default ? 'SELECTED' : '').'>'.$ttext;
					}
				}
			?>		
			</select>		
		</td>
	</tr>
<?php } ?>

	<tr>
		<td><a href="#" class="info"><?php echo _("Ringing Instead of MoH:")?><span><?php echo _("Enabling this option make callers hear a ringing tone instead of Music on Hold.<br/>Enabling this ignores any Music on Hold Class selected as well as ignoring any configured periodic announcements configured.")?></span></a></td>
		<td>
			<input name="rtone" type="checkbox" value="1" <?php echo (isset($rtone) && $rtone == 1 ? 'checked' : ''); ?>  tabindex="<?php echo ++$tabindex;?>"/>
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Max Wait Time:")?><span><?php echo _("The maximum number of seconds a caller can wait in a queue before being pulled out.  (0 for unlimited).")?></span></a></td>
		<td>
			<select name="maxwait" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($maxwait) ? $maxwait : 0);
				for ($i=0; $i < 60; $i+=10) {
					if ($i == 0)
						echo '<option value="">'._("Unlimited").'</option>';
					else
						echo '<option value="'.$i.'"'.($i == $maxwait ? ' SELECTED' : '').'>'.$i.' '._("seconds").'</option>';
				}
				for ($i=60; $i < 300; $i+=30) {
					echo '<option value="'.$i.'"'.($i == $maxwait ? ' SELECTED' : '').'>'.queues_timeString($i,true).'</option>';
				}
				for ($i=300; $i < 1200; $i+=60) {
					echo '<option value="'.$i.'"'.($i == $maxwait ? ' SELECTED' : '').'>'.queues_timeString($i,true).'</option>';
				}
				for ($i=1200; $i <= 3600; $i+=300) {
					echo '<option value="'.$i.'"'.($i == $maxwait ? ' SELECTED' : '').'>'.queues_timeString($i,true).'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Max Callers:")?><span><?php echo _("Maximum number of people waiting in the queue (0 for unlimited)")?></span></a></td>
		<td>
			<select name="maxlen" tabindex="<?php echo ++$tabindex;?>">
			<?php 
				$default = (isset($maxlen) ? $maxlen : 0);
				for ($i=0; $i <= 50; $i++) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.$i.'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Join Empty:")?><span><?php echo _("If you wish to allow callers to join queues that currently have no agents, set this to yes. Set to strict if callers cannot join a queue with no members or only unavailable members")?></span></a></td>
		<td>
			<select name="joinempty" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($joinempty) ? $joinempty : 'yes');
				$items = array('yes'=>_("Yes"),'strict'=>_("Strict"),'no'=>_("No"));
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Leave When Empty:")?><span><?php echo _("If you wish to remove callers from the queue if there are no agents present, set this to yes. Set to strict if callers cannot join a queue with no members or only unavailable members")?></span></a></td>
		<td>
			<select name="leavewhenempty" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($leavewhenempty) ? $leavewhenempty : 'no');
				$items = array('yes'=>_("Yes"),'strict'=>_("Strict"),'no'=>_("No"));
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td>
			<a href="#" class="info"><?php echo _("Ring Strategy:")?>
				<span>
					<b><?php echo _("ringall")?></b>:  <?php echo _("ring all available agents until one answers (default)")?><br>
<?php
        if (!$ast_ge_16) {
?>
					<b><?php echo _("roundrobin")?></b>: <?php echo _("take turns ringing each available agent")?><br>
<?php
        }
?>
					<b><?php echo _("leastrecent")?></b>: <?php echo _("ring agent which was least recently called by this queue")?><br>
					<b><?php echo _("fewestcalls")?></b>: <?php echo _("ring the agent with fewest completed calls from this queue")?><br>
					<b><?php echo _("random")?></b>: <?php echo _("ring random agent")?><br>
					<b><?php echo _("rrmemory")?></b>: <?php echo _("round robin with memory, remember where we left off last ring pass")?><br>
<?php
        if ($ast_ge_16) {
?>
					<b><?php echo _("linear")?></b>: <?php echo _("rings agents in the order specified, for dynamic agents in the order they logged in")?><br>
					<b><?php echo _("wrandom")?></b>: <?php echo _("random using the member's penalty as a weighting factor, see asterisk documentation for specifics")?><br>
<?php
        }
?>
				</span>
			</a>
		</td>
		<td>
			<select name="strategy" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($strategy) ? $strategy : 'ringall');
				$items = array('ringall','roundrobin','leastrecent','fewestcalls','random','rrmemory');
        if ($ast_ge_16) {
				  $items[] = 'linear';
				  $items[] = 'wrandom';
          unset($items[array_search('roundrobin',$items)]);
        }
				foreach ($items as $item) {
					echo '<option value="'.$item.'" '.($default == $item ? 'SELECTED' : '').'>'._($item);
				}
			?>		
			</select>
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Agent Timeout:")?><span><?php echo _("The number of seconds an agent's phone can ring before we consider it a timeout. Unlimited or other timeout values may still be limited by system ringtime or individual extension defaults.")?></span></a></td>
		<td>
			<select name="timeout" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($timeout) ? $timeout : 15);
				echo '<option value="0" '.(0 == $default ? 'SELECTED' : '').'>'._("Unlimited").'</option>';
				for ($i=1; $i <= 60; $i++) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.$i.' '._("seconds").'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Retry:")?><span><?php echo _("The number of seconds we wait before trying all the phones again. Choosing \"No Retry\" will exit the Queue and go to the fail-over destination as soon as the first attempted agent times-out, additional agents will not be attempted.")?></span></a></td>
		<td>
			<select name="retry" tabindex="<?php echo ++$tabindex;?>">
      <?php
				$default = (isset($retry) ? $retry : 5);
				echo '<option value="none" '.(($default == "none") ? 'SELECTED' : '').'>'._("No Retry").'</option>';
				for ($i=0; $i <= 20; $i++) {
					echo '<option value="'.$i.'" '.(("$i" == "$default") ? 'SELECTED' : '').'>'.$i.' '._("seconds").'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Wrap-Up-Time:")?><span><?php echo _("After a successful call, how many seconds to wait before sending a potentially free agent another call (default is 0, or no delay)")?></span></a></td>
		<td>
			<select name="wrapuptime" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($wrapuptime) ? $wrapuptime : 0);
				for ($i=0; $i <= 60; $i++) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.$i.' '._("seconds").'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Call Recording:")?><span><?php echo _("Incoming calls to agents can be recorded. (saved to /var/spool/asterisk/monitor)")?></span></a></td>
		<td>
			<select name="monitor-format" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (empty($thisQ['monitor-format']) ? "no" : $thisQ['monitor-format']);  
				echo '<option value="wav49" '.($default == "wav49" ? 'SELECTED' : '').'>'._("wav49").'</option>';
				echo '<option value="wav" '.($default == "wav" ? 'SELECTED' : '').'>'._("wav").'</option>';
				echo '<option value="gsm" '.($default == "gsm" ? 'SELECTED' : '').'>'._("gsm").'</option>';
				echo '<option value="" '.($default == "no" ? 'SELECTED' : '').'>'._("No").'</option>';
			?>	
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Event When Called:")?><span><?php echo _("When this option is set to YES, the following manager events will be generated: AgentCalled, AgentDump, AgentConnect and AgentComplete.")?></span></a></td>
		<td>
			<select name="eventwhencalled" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($eventwhencalled) ? $eventwhencalled : 'no');
				$items = array('yes'=>_("Yes"),'no'=>_("No"));
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>
			</select>
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Member Status:")?><span><?php echo _("When if this is option is set to YES, the following manager event will be generated: QueueMemberStatus")?></span></a></td>
		<td>
			<select name="eventmemberstatus" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($eventmemberstatus) ? $eventmemberstatus : 'no');
				$items = array('yes'=>_("Yes"),'no'=>_("No"));
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>
			</select>
		</td>
	</tr>

	<tr>
	<td><a href="#" class="info"><?php echo _("Skip Busy Agents:")?><span><?php echo _("When set to 'Yes' agents who are on an occupied phone will be skipped as if the line were returning busy. This means that Call Waiting or multi-line phones will not be presented with the call and in the various hunt style ring strategies, the next agent will be attempted. <br />When set to 'Yes + (ringinuse=no)' the queue configuration flag 'ringinuse=no' is set for this queue in addition to the phone's device status being monitored. This results in the queue tracking remote agents (agents who are a remote PSTN phone, called through Follow-Me, and other means) as well as PBX connected agents, so the queue will not attempt to send another call if they are already on a call from any queue. <br />When set to 'Queue calls only (ringinuse=no)' the queue configuration flag 'ringinuse=no' is set for this queue also but the device status of locally connected agents is not monitored. The behavior is to limit an agent belonging to one or more queues to a single queue call. If they are occupied from other calls, such as outbound calls they initiated, the queue will consider them available and ring them since the device state is not monitored with this option. <br /><br />WARNING: When using the settings that set the 'ringinuse=no' flag, there is a NEGATIVE side effect. An agent who transfers a queue call will remain unavailable by any queue until that call is terminated as the call still appears as 'inuse' to the queue UNLESS 'Agent Restrictions' is set to 'Extensions Only'.")?></span></a></td>
		<td>
			<select name="cwignore" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($cwignore) ? $cwignore : 'no');
				$items = array('0' => _("No"), 
				               '1'=>_("Yes"),
											 '2'=>_("Yes + (ringinuse=no)"),
											 '3'=>_("Queue calls only (ringinuse=no)"),
										 );
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>
			</select>
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Queue Weight")?>:<span><?php echo _("Gives queues a 'weight' option, to ensure calls waiting in a higher priority queue will deliver its calls first if there are agents common to both queues.")?></span></a></td>
		<td>
			<select name="weight" tabindex="<?php echo ++$tabindex;?>">
			<?php 
				$default = (isset($weight) ? $weight : 0);
				for ($i=0; $i <= 10; $i++) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.$i.'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Autofill:")?><span><?php echo _("Starting with Asterisk 1.4, if this is checked, and multiple agents are available, Asterisk will send one call to each waiting agent (depending on the ring strategy). Otherwise, it will hold all calls while it tries to find an agent for the top call in the queue making other calls wait. This was the behavior in Asterisk 1.2 and has no effect in 1.2. See Asterisk documentation for more details of this feature.")?></span></a></td>
		<td>
			<input name="autofill" type="checkbox" value="1" <?php echo (isset($autofill) && $autofill == 'yes' ? 'checked' : ''); ?>  tabindex="<?php echo ++$tabindex;?>"/>
		</td>
	</tr>
		
	<tr>
		<td><a href="#" class="info"><?php echo _("Agent Regex Filter")?><span><?php echo _("Provides an optional regex expression that will be applied against the agent callback number. If the callback number does not pass the regex filter then it will be treated as invalid. This can be used to restrict agents to extensions within a range, not allow callbacks to include keys like *, or any other use that may be appropriate. An example input might be:<br />^([2-4][0-9]{3})$<br />This would restrict agents to extensions 2000-4999. Or <br />^([0-9]+)$ would allow any number of any length, but restrict the * key.<br />WARNING: make sure you understand what you are doing or otherwise leave this blank!")?></span></a></td>
		<td><input type="text" name="qregex" value="<?php echo (isset($qregex) ? $qregex : ''); ?>"></td>
	</tr>
	
	<tr>
		<td><a href="#" class="info"><?php echo _("Report Hold Time:")?><span><?php echo _("If you wish to report the caller's hold time to the member before they are connected to the caller, set this to yes.")?></span></a></td>
		<td>
			<select name="reportholdtime" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($reportholdtime) ? $reportholdtime : 'no');
				$items = array('yes'=>_("Yes"),'no'=>_("No"));
				foreach ($items as $item=>$val) {
					echo '<option value="'.$item.'" '. ($default == $item ? 'SELECTED' : '').'>'.$val;
				}
			?>		
			</select>		
		</td>
	</tr>
	
	<tr>
		<td><a href="#" class="info"><?php echo _("Service Level:")?><span><?php echo _("Used for service level statistics (calls answered within service level time frame)")?></span></a></td>
		<td>
			<select name="servicelevel" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($servicelevel) ? $servicelevel : 60);
				for ($i=15; $i <= 300; $i+=15) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.$i.' '._("seconds").'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>
	
	<tr><td colspan="2"><br><h5><?php echo _("Caller Position Announcements")?><hr></h5></td></tr>
	<tr>
		<td><a href="#" class="info"><?php echo _("Frequency:")?><span><?php echo _("How often to announce queue position and estimated holdtime (0 to Disable Announcements).")?></span></a></td>
		<td>
			<select name="announcefreq" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($thisQ['announce-frequency']) ? $thisQ['announce-frequency'] : 0);
				for ($i=0; $i <= 1200; $i+=15) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.queues_timeString($i,true).'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Announce Position:")?><span><?php echo _("Announce position of caller in the queue?")?></span></a></td>
		<td>
			<select name="announceposition" tabindex="<?php echo ++$tabindex;?>">
			<?php //setting to "no" will override sounds queue-youarenext, queue-thereare, queue-callswaiting� 
				$default = (isset($thisQ['announce-position']) ? $thisQ['announce-position'] : "no");  
					echo '<option value=yes '.($default == "yes" ? 'SELECTED' : '').'>'._("Yes").'</option>';
					echo '<option value=no '.($default == "no" ? 'SELECTED' : '').'>'._("No").'</option>';
			?>		
			</select>		
		</td>
	</tr>

	<tr>
		<td><a href="#" class="info"><?php echo _("Announce Hold Time:")?><span><?php echo _("Should we include estimated hold time in position announcements?  Either yes, no, or only once; hold time will not be announced if <1 minute")?> </span></a></td>
		<td>
			<select name="announceholdtime" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($thisQ['announce-holdtime']) ? $thisQ['announce-holdtime'] : "no");
				echo '<option value=yes '.($default == "yes" ? 'SELECTED' : '').'>'._("Yes").'</option>';
				echo '<option value=no '.($default == "no" ? 'SELECTED' : '').'>'._("No").'</option>';
				echo '<option value=once '.($default == "once" ? 'SELECTED' : '').'>'._("Once").'</option>';
			?>		
			</select>		
		</td>
	</tr>

	<tr><td colspan="2"><br><h5><?php echo _("Periodic Announcements")?><hr></h5></td></tr>

	
<?php if(function_exists('ivr_list')) { //only include if IVR module is enabled ?>
	<tr>
		<td><a href="#" class="info"><?php echo _("IVR Break Out Menu:")?><span> <?php echo _("You can optionally present an existing IVR as a 'break out' menu.<br><br>This IVR must only contain single-digit 'dialed options'. The Recording set for the IVR will be played at intervals specified in 'Repeat Frequency', below.")?></span></a></td>
		<td>
			<select name="announcemenu" tabindex="<?php echo ++$tabindex;?>">
			<?php // setting this will set the context= option
			$default = (isset($announcemenu) ? $announcemenu : "none");
			
			echo '<option value=none '.($default == "none" ? 'SELECTED' : '').'>'._("None").'</option>';
			
			//query for exisiting aa_N contexts
			//
			// If a previous bogus IVR was listed, we will leave it in with an error but will no longer show such IVRs as valid options.
			$unique_aas = ivr_list();		
			
			$compound_recordings = false;
			$is_error = false;
			if (isset($unique_aas)) {
				foreach ($unique_aas as $unique_aa) {
					$menu_id = $unique_aa['ivr_id'];
					$menu_name = $unique_aa['displayname'];

					$unique_aa['announcement'] = recordings_get_file($unique_aa['announcement_id']);
					if (strpos($unique_aa['announcement'],"&") === false) {
						echo '<option value="'.$menu_id.'" '.($default == $menu_id ? 'SELECTED' : '').'>'.($menu_name ? $menu_name : _("Menu ID ").$menu_id)."</option>\n";
					} 
					else {
						$compound_recordings = true;
						if ($menu_id == $default) {
							echo '<option style="color:red" value="'.$menu_id.'" '.($default == $menu_id ? 'SELECTED' : '').'>'.($menu_name ? $menu_name : _("Menu ID ").$menu_id)." (**)</option>\n";
							$is_error = true;
						}
					}
				}
			}
			?>
			</select>
			<?php
			if ($is_error) {
			?>
				<small><a style="color:red"  href="#" class="info"><?php echo ($is_error ? _("(**) ERRORS") : _("(**) Warning Potential Errors"))?>
					<span> 
						<?php 
							if ($is_error) {
								echo _("ERROR: You have selected an IVR's that use Announcements created from compound sound files. The Queue is not able to play these announcements. This IVR's recording will be truncated to use only the first sound file. You can correct the problem by selecting a different announcement for this IVR that is not from a compound sound file. The IVR itself can play such files, but the Queue subsystem can not").'<br />'._("Earlier versions of this module allowed such queues to be chosen, once changing this setting, it will no longer appear as an option");
							}
						?>
					</span></small>
				</a>
			<?php
			}
			?>

		</td>
	</tr>
	
	<tr>
		<td><a href="#" class="info"><?php echo _("Repeat Frequency:")?><span><?php echo _("How often to announce a voice menu to the caller (0 to Disable Announcements).")?></span></a></td>
		<td>
			<select name="pannouncefreq" tabindex="<?php echo ++$tabindex;?>">
			<?php
				$default = (isset($thisQ['periodic-announce-frequency']) ? $thisQ['periodic-announce-frequency'] : 0);
				for ($i=0; $i <= 1200; $i+=15) {
					echo '<option value="'.$i.'" '.($i == $default ? 'SELECTED' : '').'>'.queues_timeString($i,true).'</option>';
				}
			?>		
			</select>		
		</td>
	</tr>

<?php } else {
	echo "<input type=\"hidden\" name=\"announcemenu\" value=\"none\">";
	}
	// implementation of module hook
	// object was initialized in config.php
	echo $module_hook->hookHtml;
?>

	<tr><td colspan="2"><br><h5><?php echo _("Fail Over Destination")?><hr></h5></td></tr>
	<?php 
	echo drawselects($goto,0);
	?>
	
	<tr>
		<td colspan="2"><br><h6><input name="Submit" type="button" value="<?php echo _("Submit Changes")?>" onclick="checkQ(editQ);" tabindex="<?php echo ++$tabindex;?>"></h6></td>		
	</tr>
	</table>

<script language="javascript">
<!--

function insertExten(type) {
	exten = document.getElementById(type+'insexten').value;

	grpList=document.getElementById(type+'members');
	if (grpList.value[ grpList.value.length - 1 ] == "\n") {
		grpList.value = grpList.value + exten + ',0';
	} else {
		grpList.value = grpList.value + '\n' + exten + ',0';
	}

	// reset element
	document.getElementById(type+'insexten').value = '';
}

function checkQ(theForm) {
	var bad = "false";
	var msgWarnRegex = "<?php echo _("Using a Regex filter is fairly advanced, please confirm you know what you are doing or leave this blank"); ?>";

	var whichitem = 0;
	while (whichitem < theForm.goto0.length) {
		if (theForm.goto0[whichitem].checked) {
			theForm.goto0.value=theForm.goto0[whichitem].value;
		}
		whichitem++;
	}

	if (!isInteger(theForm.account.value)) {
		<?php echo "alert('"._("Queue Number must not be blank")."')"?>;
		bad="true";
	}

	defaultEmptyOK = false;	
	if (!isAlphanumeric(theForm.name.value)) {
		<?php echo "alert('"._("Queue name must not be blank and must contain only alpha-numeric characters")."')"?>;
		bad="true";
	}
	if (!isEmpty(theForm.qregex.value)) {
		if (!confirm(msgWarnRegex)) {
			bad="true";
		}
	}

	if (bad == "false") {
		theForm.submit();
	}
}

//-->
</script>

	</form>
<?php		
} //end if action == delGRP
?>
