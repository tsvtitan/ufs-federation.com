<?php /* $Id: $ */
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


isset($_REQUEST['action'])?$action = $_REQUEST['action']:$action='';
isset($_REQUEST['id'])?$extdisplay = $_REQUEST['id']:$extdisplay='';

$dispnum = "miscdests"; //used for switch on config.php

switch ($action) {
	case "add":
		miscdests_add($_REQUEST['description'],$_REQUEST['destdial']);
		needreload();
		redirect_standard();
	break;
	case "delete":
		miscdests_del($extdisplay);
		needreload();
		redirect_standard();
	break;
	case "edit":  //just delete and re-add
		miscdests_update($extdisplay,$_REQUEST['description'],$_REQUEST['destdial']);
		needreload();
		redirect_standard('id');
	break;
}

$miscdests = miscdests_list();

// Make array of feature code for <SELECT> list
$featurecodes = featurecodes_getAllFeaturesDetailed();
if (isset($featurecodes)) {
	foreach ($featurecodes as $item) {
		$moduledesc =isset($item['moduledescription'])?_($item['moduledescription']):null;
		$moduleena = ($item['moduleenabled'] == 1 ? true : false);
		if ($moduleena) {
			$featureena = ($item['featureenabled'] == 1 ? true : false);
			if ($featureena) {
				$featureid = $item['modulename'] . ':' . $item['featurename'];
				$featuredesc = _($item['featuredescription']);
				
				$featurecodedefault = (isset($item['defaultcode']) ? $item['defaultcode'] : '');
				$featurecodecustom = (isset($item['customcode']) ? $item['customcode'] : '');
				$featureactualcode = ($featurecodecustom != '' ? $featurecodecustom : $featurecodedefault);
				
				$fclist[$featureid] = $featuredesc." ($featureactualcode)";
			}
		}
	}
	asort($fclist);
}

?>

</div>

<!-- right side menu -->
<div class="rnav"><ul>
    <li><a id="<?php echo ($extdisplay=='' ? 'current':'') ?>" href="config.php?display=<?php echo urlencode($dispnum)?>"><?php echo _("Add Misc Destination")?></a></li>
<?php
if (isset($miscdests)) {
	foreach ($miscdests as $miscdest) {
		echo "<li><a id=\"".($extdisplay==$miscdest[0] ? 'current':'')."\" href=\"config.php?display=".urlencode($dispnum)."&id=".urlencode($miscdest[0])."\">{$miscdest[1]}</a></li>";
	}
}
?>
</ul></div>


<div class="content">
<?php
if ($action == 'delete') {
	echo '<br><h3>'._("Misc Destination").' '.$extdisplay.' '._("deleted").'!</h3><br><br><br><br><br><br><br><br>';
} else {
	if ($extdisplay){ 
		//get details for this meetme
		$thisMiscDest = miscdests_get($extdisplay);
		//create variables
		$description = "";
		$destdial = "";
		extract($thisMiscDest);
	}

	$helptext = _("Misc Destinations are for adding destinations that can be used by other FreePBX modules, generally used to route incoming calls. If you want to create feature codes that can be dialed by internal users and go to various destinations, please see the <strong>Misc Applications</strong> module.");


	
		if ($extdisplay){ ?>
	<h2><?php echo _("Misc Destination:")." ". $description; ?></h2>
<?php
			$usage_list = framework_display_destination_usage(miscdests_getdest($extdisplay));
			$delURL = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&action=delete';
			$tlabel = sprintf(_("Delete Misc Destination %s"),$description);
			$label = '<span><img width="16" height="16" border="0" title="'.$tlabel.'" alt="" src="images/core_delete.png"/>&nbsp;'.$tlabel.'</span>';
?>
			<a href="<?php echo $delURL ?>"><?php echo $label; ?></a>
<?php
			if (!empty($usage_list)) {
?>
				<br /><a href="#" class="info"><?php echo $usage_list['text']?>:<span><?php echo $usage_list['tooltip']?></span></a>
<?php
			}
		} else { 
			echo "<h2>"._("Add Misc Destination")."</h2>";
			echo $helptext;
		}
?>
	<form autocomplete="off" name="editMD" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return editMD_onsubmit();">
	<input type="hidden" name="display" value="<?php echo $dispnum?>">
	<input type="hidden" name="action" value="<?php echo ($extdisplay ? 'edit' : 'add') ?>">
	<table>
	<tr><td colspan="2"><h5><?php echo ($extdisplay ? _("Edit Misc Destination") : _("Add Misc Destination")) ?><hr></h5></td></tr>
<?php		if ($extdisplay){ ?>
		<tr><td><input type="hidden" name="id" value="<?php echo $extdisplay; ?>"></td></tr>
<?php		} ?>
	<tr>
		<td><a href="#" class="info"><?php echo _("Description:")?><span><?php echo _("Give this Misc Destination a brief name to help you identify it.")?></span></a></td>
		<td><input type="text" name="description" value="<?php echo (isset($description) ? $description : ''); ?>" tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>
	<tr>
		<td><a href="#" class="info"><?php echo _("Dial:")?><span><?php echo _("Enter the number this destination will simulate dialing, exactly as you would dial it from an internal phone. When you route a call to this destination, it will be as if the caller dialed this number from an internal phone.") ?></span></a></td>
		<td>
			<input type="text" name="destdial" value="<?php echo (isset($destdial) ? $destdial : ''); ?>" tabindex="<?php echo ++$tabindex;?>">&nbsp;&nbsp;
			<?php if (isset($fclist)) { ?>
			<select id="fc" onchange="fc_onchange();" tabindex="<?php echo ++$tabindex;?>">
			<option value="">--<?php echo _("featurecode shortcuts"); ?>--</option>
			<?php
			foreach ($fclist as $fckey => $fcdesc) {
				?>
				<option value="{<?php echo $fckey; ?>}"><?php echo _($fcdesc); ?></option>
				<?php
			}
			?>
			</select>
			<?php } ?>
		</td>
	</tr>

	
	<tr>
		<td colspan="2"><br><h6><input name="Submit" type="submit" value="<?php echo _("Submit Changes")?>" tabindex="<?php echo ++$tabindex;?>"></h6>
		</td>
	</tr>
	</table>
<script language="javascript">
<!--

var theForm = document.editMD;

if (theForm.description.value == "") {
	theForm.description.focus();
} else {
	theForm.destdial.focus();
}

function editMD_onsubmit()
{
	var msgInvalidDescription = "<?php echo _('Please enter a valid Description'); ?>";
	var msgInvalidDial = "<?php echo _('Please enter a valid Dial string'); ?>";
	
	defaultEmptyOK = false;
	if (!isAlphanumeric(theForm.description.value))
		return warnInvalid(theForm.description, msgInvalidDescription);

	// go thru text and remove the {} bits so we only check the actual dial digits
	var fldText = theForm.destdial.value;
	var chkText = "";
	
	if ( (fldText.indexOf("{") > -1) && (fldText.indexOf("}") > -1) ) { // has one or more sets of {mod:fc}
		
		var inbraces = false;
		for (var i=0; i<fldText.length; i++) {
			if ( (fldText.charAt(i) == "{") && (inbraces == false) ) {
				inbraces = true;
			} else if ( (fldText.charAt(i) == "}") && (inbraces == true) ) {
				inbraces = false;
			} else if ( inbraces == false ) {
				chkText += fldText.charAt(i);
			}
		}
		
		// if there is nothing in chkText but something in fldText
		// then the field must contain a featurecode only, therefore
		// there really is something in thre!
		if ( (chkText == "") & (fldText != "") )
			chkText = "0";
			
	} else {
		chkText = fldText;
	}
	// now do the check using the chkText var made above
	if (!isDialDigits(chkText))
		return warnInvalid(theForm.destdial, msgInvalidDial);
	
	return true;
}

function fc_onchange() {
	theForm.destdial.value = theForm.fc.value;
	theForm.fc.selectedIndex = 0;
}
//-->
</script>
	</form>
<?php		
} //end if action == delGRP
?>
