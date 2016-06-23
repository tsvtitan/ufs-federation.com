<?php 
/* $Id */

function fax_getdest($exten) {
	return array("ext-fax,$exten,1");
}

function fax_getdestinfo($dest) {
  global $amp_conf;
	if (substr(trim($dest),0,8) == 'ext-fax,') {
		$usr = explode(',',$dest);
		$usr = $usr[1];
		$thisusr = fax_get_user($usr);
		if (empty($thisusr)) {
			return array();
		} else {
			$display = ($amp_conf['AMPEXTENSIONS'] == "deviceanduser")?'users':'extensions';
			return array('description' => sprintf(_("Fax user %s"),$usr),
			             'edit_url' => 'config.php?display='.$display.'&extdisplay='.urlencode($usr),
								  );
		}
	} else {
		return false;
	}
}

function fax_check_destinations($dest=true) {
	global $active_modules;

	$destlist = array();
	if (is_array($dest) && empty($dest)) {
		return $destlist;
	}
	$sql = "SELECT a.extension, a.cidnum, b.description, a.destination FROM fax_incoming a JOIN incoming b ";
  $sql .= "WHERE a.extension = b.extension AND a.cidnum = b.cidnum AND a.legacy_email IS NULL ";
	if ($dest !== true) {
		$sql .= "AND a.destination in ('".implode("','",$dest)."') ";
	}
	$sql .= "ORDER BY extension, cidnum";
	$results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);

	//$type = isset($active_modules['announcement']['type'])?$active_modules['announcement']['type']:'setup';

	foreach ($results as $result) {
		$thisdest = $result['destination'];
		$thisid   = $result['extension'].'/'.$result['cidnum'];
		$destlist[] = array(
			'dest' => $thisdest,
			'description' => sprintf(_("Inbound Fax Detection: %s (%s)"),$result['description'],$thisid),
			'edit_url' => 'config.php?display=did&extdisplay='.urlencode($thisid),
		);
	}
	return $destlist;
}

function fax_applyhooks() {
	global $currentcomponent;
	// Add the 'process' function - this gets called when the page is loaded, to hook into 
	// displaying stuff on the page.
	$currentcomponent->addguifunc('fax_configpageload');
}

// This is called before the page is actually displayed, so we can use addguielem(). draws hook on the extensions/users page
function fax_configpageload() {
	global $currentcomponent;
	global $display;
	$extdisplay=isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:'';
	$extensions=isset($_REQUEST['extensions'])?$_REQUEST['extensions']:'';
	$users=isset($_REQUEST['users'])?$_REQUEST['users']:'';
	
	if ($display == 'extensions' || $display == 'users') {
		if($extdisplay!=''){
			$fax=fax_get_user($extdisplay);
			$faxenabled=$fax['faxenabled'];
			$faxemail=$fax['faxemail'];
		}//get settings in to variables
		$section = _('Fax');
		$toggleemail='if($(this).attr(\'checked\')){$(\'[id^=fax]\').removeAttr(\'disabled\');}else{$(\'[id^=fax]\').attr(\'disabled\',\'true\');$(this).removeAttr(\'disabled\');}';
		//check for fax prequsits, and alert the user if something is amiss
		$fax=fax_detect();
		if(!$fax['module']){//missing modules
			$currentcomponent->addguielem($section, new gui_label('error','<font color="red">'._('ERROR: No FAX modules detected! Fax-related dialplan will <b>NOT</b> be generated. This module requires Fax for Asterisk or spandsp based app_fax or app_rxfax to function.').'</font>'));
		}elseif($fax['module'] == 'res_fax' && $fax['license'] < 1){//missing licese
                        $currentcomponent->addguielem($section, new gui_label('error','<font color="gray">'._('NOTICE: No Fax license detected. Fax-related dialplan will not be generated! This module has detected that Fax for Asterisk is installed without a license. At least one license is required (it is available for free) and must be installed.').'</font>'));
		}
		$usage_list = framework_display_destination_usage(fax_getdest($extdisplay));
		if (!empty($usage_list)) {
			$currentcomponent->addguielem('_top', new gui_link_label('faxdests', "&nbsp;Fax".$usage_list['text'], $usage_list['tooltip'], true), 5);
		}
		
		$currentcomponent->addguielem($section, new gui_checkbox('faxenabled',$faxenabled,_('Enabled'), _('Enable this user to receive faxes'),'true','',$toggleemail));
		$currentcomponent->addguielem($section, new gui_textbox('faxemail', $faxemail, _('Fax Email'), _('Enter an email address where faxes sent to this extension will be delivered.'), '!isEmail()', _('Please Enter a valid email address for fax delivery.'), TRUE, '', ($faxenabled == 'true')?'':'true'));
	}
}

function fax_configpageinit($pagename) {
	global $currentcomponent;
	// On a 'new' user, 'tech_hardware' is set, and there's no extension. 
	if (($_REQUEST['display'] == 'users'||$_REQUEST['display'] == 'extensions')&& isset($_REQUEST['extdisplay']) && $_REQUEST['extdisplay'] != '') {
	$currentcomponent->addprocessfunc('fax_configpageload', 1);
	$currentcomponent->addprocessfunc('fax_configprocess', 1);
	}
}

//prosses recived arguments
function fax_configprocess() {
       $action                 = isset($_REQUEST['action']) ?$_REQUEST['action']:null;
       $ext                            = isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:$_REQUEST['extension'];
	$faxenabled = isset($_REQUEST['faxenabled'])?$_REQUEST['faxenabled']:null;
       $faxemail       = isset($_REQUEST['faxemail'])?$_REQUEST['faxemail']:null;
       switch ($action) {
               case 'edit':
                       fax_save_user($ext,$faxenabled,$faxemail);
                       break;
               case 'del':
                       fax_delete_user($ext);
                       break;
       }

}

function fax_dahdi_faxdetect(){
	/*
	 * kepping this always set to true for freepbx 2.7 as we cant currently properly detect this - MB
	 * 
	 */
  return true;
}

function fax_delete_incoming($extdisplay){
	global $db;
	$opts=explode('/', $extdisplay);$extension=$opts['0'];$cidnum=$opts['1']; //set vars
	sql("DELETE FROM fax_incoming WHERE cidnum = '".$db->escapeSimple($cidnum)."' and extension = '".$db->escapeSimple($extension)."'");
}

function fax_delete_user($faxext) {
       global $db;
       $faxext=$db->escapeSimple($faxext);
       sql('DELETE FROM fax_users where user = "'.$faxext.'"');
}

function fax_destinations(){
	global $module_page;

	foreach (fax_get_destinations() as $row) {
		$extens[] = array('destination' => 'ext-fax,' . $row['user'] . ',1', 'description' => $row['name'].' ('.$row['user'].')', 'category' => _('Fax Recipient'));
	}
	return isset($extens)?$extens:null;
}

//check to see if any fax modules and licenses are loaded in to asterisk
function fax_detect($astver=null){
	global $amp_conf;
	global $astman;

  if ($astver === null) {
    $engineinfo = engine_getinfo();
    $astver =  $engineinfo['version'];
  }
  $ast_ge_14 = version_compare($astver, '1.4', 'ge');

	$fax=null;
	$appfax = $recivefax = false;//return false by default in case asterisk isnt reachable
	if ($amp_conf['AMPENGINE'] == 'asterisk' && isset($astman) && $astman->connected()) {
		//check for fax modules
    $module_show_command = $ast_ge_14 ? 'module show like ' : 'show modules like ';
		$app = $astman->send_request('Command', array('Command' => $module_show_command.'res_fax'));
		if (preg_match('/[1-9] modules loaded/', $app['data'])){
      $fax['module']='res_fax';
    } else {
		  $recive = $astman->send_request('Command', array('Command' => $module_show_command.'app_fax'));
		  if (preg_match('/[1-9] modules loaded/', $recive['data'])){$fax['module']='app_fax';}
    }
    if (!isset($fax['module'])) {
		  $app = $astman->send_request('Command', array('Command' => $module_show_command.'app_rxfax'));
      $fax['module'] = preg_match('/[1-9] modules loaded/', $app['data']) ? 'app_rxfax': null;
    }
		$response = $astman->send_request('Command', array('Command' => $module_show_command.'app_nv_faxdetect'));
    $fax['nvfax']= preg_match('/[1-9] modules loaded/', $response['data']) ? true : false;

    switch($fax['module']) {
    case 'res_fax':
      $fax['receivefax'] = 'receivefax';
      break;
    case 'app_rxfax':
      $fax['receivefax'] = 'rxfax';
      break;
    case 'app_fax':
      $application_show_command = $ast_ge_14 ? 'core show applications like ' : 'show applications like ';
		  $response = $astman->send_request('Command', array('Command' => $application_show_command.'receivefax'));
      if (preg_match('/1 Applications Matching/', $response['data'])) {
        $fax['receivefax'] = 'receivefax';
      } else {
		    $response = $astman->send_request('Command', array('Command' => $application_show_command.'rxfax'));
        if (preg_match('/1 Applications Matching/', $response['data'])) {
          $fax['receivefax'] = 'rxfax';
        } else {
          $fax['receivefax'] = 'none';
        }
      }
      break;
    }

		//get license count
		$lic = $astman->send_request('Command', array('Command' => 'fax show stats'));
		foreach(explode("\n",$lic['data']) as $licdata){
		$d=explode(':',$licdata);
		$data[trim($d['0'])]=isset($d['1'])?trim($d['1']):null;
		}
		$fax['license']=$data['Licensed Channels'];
	}
	return $fax;
}

function fax_get_config($engine){
  global $version;
  global $ext;
  global $amp_conf;
  global $core_conf;

	$fax=fax_detect($version);
	if($fax['module']){ //dont continue unless we have a fax module in asterisk

    $t38_fb = version_compare($version, '1.6', 'ge')?',f':'';
		$context='ext-fax';
		$dests=fax_get_destinations();
		$sender_address=sql('SELECT value FROM fax_details WHERE `key` = \'sender_address\'','getRow');
		if($dests){
			foreach ($dests as $row) {
				$exten=$row['user'];
				$ext->add($context, $exten, '', new ext_noop('Receiving Fax for: '.$row['name'].' ('.$row['user'].'), From: ${CALLERID(all)}'));
				$ext->add($context, $exten, '', new ext_set('FAX_RX_EMAIL', $row['faxemail']));			
		    $ext->add($context, $exten, 'receivefax', new ext_goto('receivefax','s'));
			}
		}
    /*
      FAX Failures are not handled well as of this coding in by ReceiveFAX. If there is a license available then it provides
      information. If not, nothing is provided. FAXSTATUS is supported in 1.4 to handle legacy with RxFax(). In order to create
      dialplan to try and handle all cases, we use FAXSTATUS and set it ourselves as needed. It appears that if a fax fails with
      ReceiveFAX we can always continue execution and if it succeeds, then execution goes to hangup. So using that information
      we try to trap and report on all cases.
    */
    $exten = 's';
	  $ext->add($context, $exten, '', new ext_macro('user-callerid')); // $cmd,n,Macro(user-callerid)
    $ext->add($context, $exten, '', new ext_noop('Receiving Fax for: ${FAX_RX_EMAIL} , From: ${CALLERID(all)}'));
    $ext->add($context, $exten, 'receivefax', new ext_stopplaytones(''));
    switch ($fax['module']) {
    case 'app_rxfax':
      $ext->add($context, $exten, '', new ext_rxfax('${ASTSPOOLDIR}/fax/${UNIQUEID}.tif')); //recive fax, then email it on
    break;
    case 'app_fax':
      // $fax['receivefax'] should be rxfax or receivefax, it could be none in which case we don't know. We'll just make it
      // ReceiveFAX in that case since it will fail anyhow.
      if ($fax['receivefax'] == 'rxfax') {
        $ext->add($context, $exten, '', new ext_rxfax('${ASTSPOOLDIR}/fax/${UNIQUEID}.tif')); //recive fax, then email it on
      } elseif ($fax['receivefax'] == 'receivefax') {
        $ext->add($context, $exten, '', new ext_receivefax('${ASTSPOOLDIR}/fax/${UNIQUEID}.tif'.$t38_fb)); //recive fax, then email it on
      } else {
        $ext->add($context, $exten, '', new ext_noop('ERROR: NO Receive FAX application detected, putting in dialplan for ReceiveFAX as default'));
        $ext->add($context, $exten, '', new ext_receivefax('${ASTSPOOLDIR}/fax/${UNIQUEID}.tif'.$t38_fb)); //recive fax, then email it on
			  $ext->add($context, $exten, '', new ext_execif('$["${FAXSTATUS}" = ""]','Set','FAXSTATUS=${IF($["${FAXOPT(error)}" = ""]?"FAILED LICENSE EXCEEDED":"FAILED FAXOPT: error: ${FAXOPT(error)} status: ${FAXOPT(status)} statusstr: ${FAXOPT(statusstr)}")}'));
      }
    break;
    case 'res_fax':
      $ext->add($context, $exten, '', new ext_receivefax('${ASTSPOOLDIR}/fax/${UNIQUEID}.tif'.$t38_fb)); //recive fax, then email it on
      // Some versions or settings appear to have successful completions continue, so check status and goto hangup code
      $ext->add($context, $exten, '', new ext_execif('$["${FAXOPT(error)}"=""]','Set','FAXSTATUS=FAILED LICENSE EXCEEDED'));
      $ext->add($context, $exten, '', new ext_execif('$["${FAXOPT(error)}"!="" && "${FAXOPT(error)}"!="NO_ERROR"]','Set','FAXSTATUS="FAILED FAXOPT: error: ${FAXOPT(error)} status: ${FAXOPT(status)} statusstr: ${FAXOPT(statusstr)}"'));
		  $ext->add($context, $exten, '', new ext_hangup());

    break;
    default: // unknown
      $ext->add($context, $exten, '', new ext_noop('No Known FAX Technology installed to receive a fax, aborting'));
			$ext->add($context, $exten, '', new ext_set('FAXSTATUS','FAILED No Known Fax Reception Apps available to process'));
			$ext->add($context, $exten, '', new ext_hangup());
    }
    $exten = 'h';
		$ext->add($context, $exten, '', new ext_gotoif('$["${FAXSTATUS:0:6}" = "FAILED"]', 'failed'));
    $ext->add($context, $exten, 'process', new ext_gotoif('$[${LEN(${FAX_RX_EMAIL})} = 0]','end'));
    $ext->add($context, $exten, '', new ext_system('${ASTVARLIBDIR}/bin/fax-process.pl --to "${FAX_RX_EMAIL}" --from "'.$sender_address['0'].'" --dest "${FROM_DID}" --subject "New fax from ${URIENCODE(${CALLERID(name)})} ${URIENCODE(<${CALLERID(number)}>)}" --attachment fax_${URIENCODE(${CALLERID(number)})}.pdf --type application/pdf --file ${ASTSPOOLDIR}/fax/${UNIQUEID}.tif'));

	  $ext->add($context, $exten, 'end', new ext_macro('hangupcall'));
    $ext->add($context, $exten, 'failed', new ext_noop('FAX ${FAXSTATUS} for: ${FAX_RX_EMAIL} , From: ${CALLERID(all)}'),'process',101);
	  $ext->add($context, $exten, '', new ext_macro('hangupcall'));


		//write out res_fax.conf and res_fax_digium.conf
		fax_write_conf();
  
    $modulename = 'fax';
    $fcc = new featurecode($modulename, 'simu_fax');
    $fc_simu_fax = $fcc->getCodeActive();
    unset($fcc);
  
    if ($fc_simu_fax != '') {
      $default_address = sql('SELECT value FROM fax_details WHERE `key` = \'FAX_RX_EMAIL\'','getRow');
      $ext->addInclude('from-internal-additional', 'app-fax'); // Add the include from from-internal
      $ext->add('app-fax', $fc_simu_fax, '', new ext_setvar('FAX_RX_EMAIL', $default_address[0]));
      $ext->add('app-fax', $fc_simu_fax, '', new ext_goto('1', 's', 'ext-fax'));
      $ext->add('app-fax', 'h', '', new ext_macro('hangupcall'));
    }
    // This is not really needed but is put here in case some ever accidently switches the order below when
    // checking for this setting since $fax['module'] will be set there and the 2nd part never checked
    $fax_settings['force_detection'] = 'yes';
	} else {
    $fax_settings=fax_get_settings();
  }
	if ($fax['module'] | $fax_settings['force_detection'] == 'yes') { //dont continue unless we have a fax module in asterisk
	  $ast_ge_16= version_compare($version, '1.6', 'ge');
	  if ($ast_ge_16 && isset($core_conf) && is_a($core_conf, "core_conf")) {
		  $core_conf->addSipGeneral('faxdetect','yes');
	  }

		$ext->add('ext-did-0001', 'fax', '', new ext_goto('${CUT(FAX_DEST,^,1)},${CUT(FAX_DEST,^,2)},${CUT(FAX_DEST,^,3)}'));
		$ext->add('ext-did-0002', 'fax', '', new ext_goto('${CUT(FAX_DEST,^,1)},${CUT(FAX_DEST,^,2)},${CUT(FAX_DEST,^,3)}'));

    // Add fax extension to ivr and announcement as inbound controle may be passed quickly to them and still detection is desired
    if (function_exists('ivr_list')) {
			$ivrlist = ivr_list();
			if(is_array($ivrlist)) foreach($ivrlist as $item) {
		    $ext->add("ivr-".$item['ivr_id'], 'fax', '', new ext_goto('${CUT(FAX_DEST,^,1)},${CUT(FAX_DEST,^,2)},${CUT(FAX_DEST,^,3)}'));
      }
    }
    if (function_exists('announcement_list')) foreach (announcement_list() as $row) {
      $ext->add('app-announcement-'.$row['announcement_id'], 'fax', '', new ext_goto('${CUT(FAX_DEST,^,1)},${CUT(FAX_DEST,^,2)},${CUT(FAX_DEST,^,3)}'));
    }
	}
}


function fax_get_destinations(){
	global $db;
	$sql = "SELECT fax_users.user,fax_users.faxemail,users.name FROM fax_users, users where fax_users.faxenabled = 'true' and users.extension = fax_users.user ORDER BY fax_users.user";
	$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	if(DB::IsError($results)) {
		die_freepbx($results->getMessage()."<br><br>Error selecting from fax");	
	}
	return $results;
}

function fax_get_incoming($extension=null,$cidnum=null){
	global $db;
	if($extension !== null || $cidnum !== null){
		$sql="SELECT * FROM fax_incoming WHERE extension = ? AND cidnum = ?";
		$settings = $db->getRow($sql, array($extension, $cidnum), DB_FETCHMODE_ASSOC);
		if(isset($settings['legacy_email'])&&$settings['legacy_email']=='NULL'){$settings['legacy_email']=null;}//convert string to real value
	}else{
		$sql="SELECT fax_incoming.*, incoming.pricid FROM fax_incoming, incoming where fax_incoming.cidnum=incoming.cidnum and fax_incoming.extension=incoming.extension;";
		$settings=$db->getAll($sql, DB_FETCHMODE_ASSOC);
	}
	return $settings;
}

function fax_get_user($faxext){
	global $db;
	if($faxext){
		$sql="SELECT * FROM fax_users WHERE user = '".$faxext."'";
		$settings = $db->getRow($sql, DB_FETCHMODE_ASSOC);
	}else{
		$sql="SELECT * FROM fax_users";
		$settings = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	}
	if(!is_array($settings)){$settings=array();}//make sure were retuning an array (even if its blank)
	return $settings;
}

function fax_get_settings(){
	$settings = sql('SELECT * FROM fax_details', 'getAssoc', 'DB_FETCHMODE_ASSOC');
	foreach($settings as $setting => $value){
		$set[$setting]=$value['0'];
	}
	if(!is_array($set)){$set=array();}//never return a null value
	return $set;
}


function fax_hook_core($viewing_itemid, $target_menuid){
	//hmm, not sure why engine_getinfo() isnt being called here?! should probobly read: $info=engine_getinfo();
	//this is what serves fax code to inbound routing
	$tabindex=null;
	$type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
	$extension=isset($_REQUEST['extension'])?$_REQUEST['extension']:'';
	$cidnum=isset($_REQUEST['cidnum'])?$_REQUEST['cidnum']:'';
	$extdisplay=isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:'';
	
	//if were editing, get save parms. Get parms
	if ($type != 'setup'){
		if(!$extension && !$cidnum){//set $extension,$cidnum if we dont already have them
			$opts=explode('/', $extdisplay);$extension=$opts['0'];$cidnum=$opts['1'];
		}
		$fax=fax_get_incoming($extension,$cidnum);
	}else{
	  $fax=null;
	}
	$html='';
	if($target_menuid == 'did'){
    $fax_dahdi_faxdetect=fax_dahdi_faxdetect();
    $fax_sip_faxdetect=fax_sip_faxdetect();
    $dahdi=ast_with_dahdi()?_('Dahdi'):_('Zaptel');
    $fax_detect=fax_detect();
    $fax_settings=fax_get_settings();
    //ensure that we are using destination for both fax detect and the regular calls
		$html='<script type="text/javascript">$(document).ready(function(){
		$("input[name=Submit]").click(function(){
			if($("input[name=faxenabled]:checked").val()=="true" && !$("[name=gotoFAX]").val()){//ensure the user selected a fax destination
			alert('._('"You have selected Fax Detection on this route. Please select a valid destination to route calls detected as faxes to."').');return false; }	}) });</script>';
		$html .= '<tr><td colspan="2"><h5>';
		$html.=_('Fax Detect');
		$html.='<hr></h5></td></tr>';
		$html.='<tr>';
		$html.='<td><a href="#" class="info">';
		$html.=_("Detect Faxes").'<span>'._("Attempt to detect faxes on this DID.")."<ul><li>"._("No: No attempts are made to auto-determine the call type; all calls sent to destination below. Use this option if this DID is used exclusively for voice OR fax.")."</li><li>"._("Yes: try to auto determine the type of call; route to the fax destination if call is a fax, otherwise send to regular destination. Use this option if you receive both voice and fax calls on this line")."</li>";
		if($fax_settings['legacy_mode'] == 'yes' || $fax['legacy_email']!==null){
    	$html.='<li>'._('Legacy: Same as YES, only you can enter an email address as the destination. This option is ONLY for supporting migrated legacy fax routes. You should upgrade this route by choosing YES, and selecting a valid destination!').'</li>';
		}		
		$html.='</ul></span></a>:</td>';
		
		//dont allow detection to be set if we have no valid detection types
		if(!$fax_dahdi_faxdetect && !$fax_sip_faxdetect && !$fax_detect['nvfax']){
			$js="if ($(this).val() == 'true'){alert('"._('No fax detection methods found or no valid license. Faxing cannot be enabled.')."');return false;}";
			$html.='<td><input type="radio" name="faxenabled" value="false" CHECKED />No';
			$html.='<input type="radio" name="faxenabled" value="true"  onclick="'.$js.'"/>Yes</td></tr>';
			$html.='</table><table>';
		}else{
			/* 
			 * show detection options
			 *
			 * js to show/hide the detection settings. Second slide is always in a 
			 * callback so that we ait for the fits animation to complete before 
			 * playing the second
			 */
			if($fax['legacy_email']===null && $fax_settings['legacy_mode'] == 'no'){
				$jsno="$('.faxdetect').slideUp();"; 
				$jsyes="$('.faxdetect').slideDown();";
			}else{
				$jsno="$('.faxdetect').slideUp();$('.legacyemail').slideUp();"; 
				$jsyes="$('.legacyemail').slideUp('400',function(){
							$('.faxdetect').slideDown()
						});";
				$jslegacy="$('.faxdest27').slideUp('400',function(){ 
								$('.faxdetect, .legacyemail').not($('.faxdest27')).slideDown();
						});";
			}
			$html.='<td><input type="radio" name="faxenabled" value="false" CHECKED onclick="'.$jsno.'"/>No';
			$html.='<input type="radio" name="faxenabled" value="true" '.($fax?'CHECKED':'').' onclick="'.$jsyes.'"/>Yes';
			if($fax['legacy_email']!==null || $fax_settings['legacy_mode'] == 'yes'){
				$html.='<input type="radio" name="faxenabled" value="legacy"'.($fax['legacy_email'] !== null ? ' CHECKED ':'').'onclick="'.$jslegacy.'"/>Legacy';
			}
      $html.='</td></tr>';
			$html.='</table>';
		}	
		//fax detection+destinations, hidden if there is fax is disabled
		$html.='<table class=faxdetect '.($fax?'':'style="display: none;"').'>';	
		$info=engine_getinfo();
		$html.='<tr><td width="156px"><a href="#" class="info">'._('Fax Detection type').'<span>'._("Type of fax detection to use.")."<ul><li>".$dahdi.": "._("use ").$dahdi._(" fax detection; requires 'faxdetect=' to be set to 'incoming' or 'both' in ").$dahdi.".conf</li><li>"._("Sip: use sip fax detection (t38). Requires asterisk 1.6.2 or greater and 'faxdetect=yes' in the sip config files")."</li><li>"._("NV Fax Detect: Use NV Fax Detection; Requires NV Fax Detect to be installed and recognized by asterisk")."</li></ul>".'.</span></a>:</td>';
		$html.='<td><select name="faxdetection" tabindex="'.++$tabindex.'">';
		//$html.='<option value="Auto"'.($faxdetection == 'auto' ? 'SELECTED' : '').'>'. _("Auto").'</option>';<li>Auto: allow the system to chose the best fax detection method</li>		
		$html.='<option value="dahdi" '.($fax['detection'] == 'dahdi' ? 'SELECTED' : '').' '.($fax_dahdi_faxdetect?'':'disabled').'>'.$dahdi.'</option>';
		$html.='<option value="nvfax"'.($fax['detection'] == 'nvfax' ? 'SELECTED' : '').($fax_detect['nvfax']?'':'disabled').'>'. _("NVFax").'</option>';
		$html.='<option value="sip" '.($fax['detection'] == 'sip' ? 'SELECTED' : '').' '.((($info['version'] >= "1.6.2") && $fax_sip_faxdetect)?'':'disabled').'>'. _("Sip").'</option>';
		$html.='</select></td></tr>';
		
		$html.='<tr><td><a href="#" class="info">'._("Fax Detection Time").'<span>'._('How long to wait and try to detect fax. Please note that callers to a '.$dahdi.' channel will hear ringing for this amount of time (i.e. the system wont "answer" the call, it will just play ringing)').'.</span></a>:</td>';
		$html.='<td><select name="faxdetectionwait" tabindex="'.++$tabindex.'">';
		if(!$fax['detectionwait']){$fax['detectionwait']=4;}//default wait time is 4 second
		for($i=2;$i < 11; $i++){
			$html.='<option value="'.$i.'" '.($fax['detectionwait']==$i?'SELECTED':'').'>'.$i.'</option>';	
		}
		$html.='</select></td></tr>';
		if($fax['legacy_email']!==null || $fax_settings['legacy_mode'] == 'yes'){	
			$html.='</table>';
			$html.='<table class="legacyemail"'.($fax['legacy_email'] === null ? ' style="display: none;"':'').'>';
			$html.='<tr ><td><a href="#" class="info">'._("Fax Email Destination").'<span>'._('Address to email faxes to on fax detection.<br />PLEASE NOTE: In this version of FreePBX, you can now set the fax destination from a list of destinations. Extensions/Users can be fax enabled in the user/extension screen and set an email address there. This will create a new destination type that can be selected. To upgrade this option to the full destination list, select YES to Detect Faxes and select a destination. After clicking submit, this route will be upgraded. This Legacy option will no longer be available after the change, it is provided to handle legacy migrations from previous versions of FreePBX only.').'.</span></a>:</td>';
			$html.='<td><input name="legacy_email" value="'.$fax['legacy_email'].'"></td></tr>';
			$html.='</table>';
			$html.='<table class="faxdest27 faxdetect" style="display: none" >';
	}		
		$html.='<tr class="faxdest"><td><a href="#" class="info">'._("Fax Destination").'<span>'._('Where to send the call if we detect that its a fax').'.</span></a>:</td>';
		$html.='<td>';
		$html.=$fax_detect?drawselects(isset($fax['destination'])?$fax['destination']:null,'FAX',false,false):'';
		$html.='</td></tr></table>';
		$html.='<table>';
	}
	return $html;

}

function fax_hookGet_config($engine){
  global $version;
	$fax=fax_detect($version);
  if ($fax['module']) {
	  $fax_settings['force_detection'] = 'yes';
  } else {
    $fax_settings=fax_get_settings();
  }
	if($fax_settings['force_detection'] == 'yes'){ //dont continue unless we have a fax module in asterisk
		global $ext;
		global $engine;
		$routes=fax_get_incoming();
		foreach($routes as $current => $route){
      if ($route['detection'] == 'nvfax' && !$fax['nvfax']) {
        //TODO: add notificatoin to notification panel that this was skipped because NVFaxdetec not present
        continue; // skip this one if there is no NVFaxdetect installed on this system
      }
			if($route['extension']=='' && $route['cidnum']){//callerID only
				$extension='s/'.$route['cidnum'];
				$context=($route['pricid']=='CHECKED')?'ext-did-0001':'ext-did-0002';
			}else{
				if(($route['extension'] && $route['cidnum'])||($route['extension']=='' && $route['cidnum']=='')){//callerid+did / any/any
					$context='ext-did-0001';
				}else{//did only
					$context='ext-did-0002';
				}
				$extension=($route['extension']!=''?$route['extension']:'s').($route['cidnum']==''?'':'/'.$route['cidnum']);
			}
      if ($route['legacy_email'] === null) {
			  $ext->splice($context, $extension, 'dest-ext', new ext_setvar('FAX_DEST',str_replace(',','^',$route['destination'])));
      } else {
			  $ext->splice($context, $extension, 'dest-ext', new ext_setvar('FAX_DEST','ext-fax^s^1'));
        if ($route['legacy_email']) {
			    $fax_rx_email = $route['legacy_email'];
        } else {
          if (!isset($default_fax_rx_email)) {
			      $default_address = sql('SELECT value FROM fax_details WHERE `key` = \'fax_rx_email\'','getRow');
            $default_fax_rx_email = $default_address[0];
          }
          $fax_rx_email = $default_fax_rx_email;
        }
			  $ext->splice($context, $extension, 'dest-ext', new ext_setvar('FAX_RX_EMAIL',$fax_rx_email));
      }
		  $ext->splice($context, $extension, 'dest-ext', new ext_answer(''));
      if ($route['detection'] == 'nvfax') {
		    $ext->splice($context, $extension, 'dest-ext', new ext_playtones('ring'));
		    $ext->splice($context, $extension, 'dest-ext', new ext_nvfaxdetect($route['detectionwait'].",t"));
      } else {
		    $ext->splice($context, $extension, 'dest-ext', new ext_wait($route['detectionwait']));
      }
		}
	}
}

function fax_hookProcess_core(){
	$display=isset($_REQUEST['display'])?$_REQUEST['display']:'';
	$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
	if ($display == 'did' && $action!=''){

	  $cidnum=isset($_REQUEST['cidnum'])?$_REQUEST['cidnum']:'';
	  $extension=isset($_REQUEST['extension'])?$_REQUEST['extension']:'';
	  $extdisplay=isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:'';
	  $enabled=isset($_REQUEST['faxenabled'])?$_REQUEST['faxenabled']:'false';
	  $detection=isset($_REQUEST['faxdetection'])?$_REQUEST['faxdetection']:'';
	  $detectionwait=isset($_REQUEST['faxdetectionwait'])?$_REQUEST['faxdetectionwait']:'';
	  $dest=(isset($_REQUEST['gotoFAX'])?$_REQUEST['gotoFAX'].'FAX':null);
	  $dest=isset($_REQUEST[$dest])?$_REQUEST[$dest]:'';
    if ($enabled != 'legacy') {
      $legacy_email = null;
    } else {
	    $legacy_email=isset($_REQUEST['legacy_email'])?$_REQUEST['legacy_email']:'';
    }
		fax_delete_incoming($extdisplay);	//remove mature entry on edit or delete
		if (($action == 'edtIncoming' || $action == 'addIncoming') && $enabled != 'false'){
			fax_save_incoming($cidnum,$extension,$enabled,$detection,$detectionwait,$dest,$legacy_email);
		}
	}
}


function fax_save_incoming($cidnum,$extension,$enabled,$detection,$detectionwait,$dest,$legacy_email){
	global $db;
  $legacy_email =  $legacy_email === null ? 'NULL' : "'".$db->escapeSimple("$legacy_email")."'";
	sql("INSERT INTO fax_incoming (cidnum, extension, detection, detectionwait, destination, legacy_email) VALUES ('".$db->escapeSimple($cidnum)."', '".$db->escapeSimple($extension)."', '".$db->escapeSimple($detection)."', '".$db->escapeSimple($detectionwait)."', '".$db->escapeSimple($dest)."',".$legacy_email.")");
}

function fax_save_settings($settings){
	global $db;
	if (is_array($settings)) foreach($settings as $key => $value){
		sql("REPLACE INTO fax_details (`key`, `value`) VALUES ('".$key."','".$db->escapeSimple($value)."')");
	}
}

function fax_save_user($faxext,$faxenabled,$faxemail){
	global $db;
	$faxext=$db->escapeSimple($faxext);
	$faxenabled=$db->escapeSimple($faxenabled);
	$faxemail=$db->escapeSimple($faxemail);
	sql('REPLACE INTO fax_users (user, faxenabled, faxemail) VALUES ("'.$faxext.'","'.$faxenabled.'","'.$faxemail.'")');
}

function fax_sip_faxdetect(){
	global $asterisk_conf;
  return true;
}

//write out res_fax.conf and res_fax_digium.conf
function fax_write_conf(){
	global $amp_conf, $WARNING_BANNER;
	$set=fax_get_settings();
	//res_fax.conf
	$data=$WARNING_BANNER;
	$data.="[general]\n";
	$data.="#include res_fax_custom.conf\n";
	$data.='minrate='.$set['minrate']."\n";
	$data.='maxrate='.$set['maxrate']."\n";
	$file=fopen($amp_conf['ASTETCDIR'].'/res_fax.conf','w');
	fwrite($file, $data);
	fclose($file);
	
	//res_fax_digium.conf
	$data=$WARNING_BANNER;
	$data.="[general]\n";
	$data.="#include res_fax_digium_custom.conf\n";
	$data.='ecm='.$set['ecm']."\n";
	$file=fopen($amp_conf['ASTETCDIR'].'/res_fax_digium.conf','w');
	fwrite($file, $data);
	fclose($file);
}
?>
