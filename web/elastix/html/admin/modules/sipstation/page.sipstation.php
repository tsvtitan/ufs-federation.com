<?php /* $Id:$ */
/* Copyright (c) 2009 Bandwidth.com
   Licensed for use by active FreePBX.com SIP Trunking Customers (SIPSTATION(tm)). Not licensed to be modified or redistributed in any fashion.
   No guarantees or warranties provided, use at your own risk. See license included with module for more details.
*/

include_once('sipstation.utility.php');
//print_r($_REQUEST);

$tabindex       = 0;
$dispnum        = "sipstation";
$error_displays = array();
$action         = isset($_POST['action'])?$_POST['action']:'';
$key_status     = isset($_POST['key_status'])?$_POST['key_status']:'';

$account_key    = isset($_POST['account_key'])?$_POST['account_key']:'';

$remove_key     = isset($_POST['remove_key'])? true : false;

if (isset($_POST['remove_key_del_trunks'])) {
  $action = 'remove_all';
}

switch ($action) {
  case "ajax_get":
  case "dest_post":
  case "route_post":
  case "reset_trunk":
  case "refresh_display":
    $action($_POST);
    exit;
  break;

  case "remove_all":  //just delete and re-add
    foreach ($_POST['trunkid'] as $trunk_id) {
      core_trunks_del($trunk_id,'sip');
    }
    sipstation_remove_key();
    needreload();
  break;

  case "edit":  //just delete and re-add
    if ($remove_key) {
      sipstation_remove_key();
      $key_status = 'nokey';
    } elseif ($key_status == 'nokey') {
      // TOOD: provide feedback if they give blank blank key, maybe just js validation?
      $set_key_status = sipstation_set_key($account_key);
    }
  break;
  default:
}
$status = sipstation_check_key(); // nokey, valid, invalid, noserver, tempnotavail
?>
</div>
<div class="content">
  <!--h2><?php //echo _("Edit Settings"); ?></h2-->

  <form autocomplete="off" name="editSip" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
  <input type="hidden" name="action" id="action" value="edit">
  <input type="hidden" name="key_status" id="key_status" value="<?php echo $status ?>">
  <input type="hidden" name="trunkid[gw1]" id="trunkid_gw1" value="">
  <input type="hidden" name="trunkid[gw2]" id="trunkid_gw2" value="">
  <table width="690">
<?php
if ($action == 'remove_all') {
?>
  <tr>
    <td colspan="2">
      <br /><br /><br />
      <div class="sipstation-errors">
        <p><?php echo _("KEYS AND TRUNKS REMOVED!") ?></p>
        <ul>
<?php
    echo _("Your SIPSTATION trunks and key have been removed from your system, make sure to Apply Configuration Changes for this to take effect");
?>
        </ul>
      </div>
    </td>
  </tr>
<?php
}
?>
  <tr>
    <td colspan="2">
      <h5>
<?php
  if ($status != 'nokey') {
?>
              <span class="sipstation-section toggle-minus" id="account_access_section">&nbsp</span>
<?php
  }
?>
              <?php echo sprintf(_("%s Account Access"),'SIPSTATION&trade;') ?>
        <hr>
      </h5>
    </td>
  </tr>

<?php
  switch ($status) { // nokey, valid, invalid, noserver
  case 'nokey': // valid, invalid, noserver
?>
  <tr id="sipstation-system-status">
    <td colspan="2">
      <div id="sipstation-information">
        <a border="none" href="https://store.freepbx.com" title="Click to access SIPSTATION Portal and Trunk Purchases" Alt="SIPSTATION PORTAL AND TRUNK PURCHASE" target="_sipstation"><img align="center" src="images/storeFrontSipStation-00001.png" width="771px" height="211px" border="0"></a>
        <p>
          <?php echo sprintf(_("This module requires %s trunking service available at %s or click on the image above. Once you have service a key will be available in the portal. Enter it below to use this module. The key is very long, use \"Copy\" & \"Paste\" to copy it here. The key will be stored securely and can be removed at any time to stop access. If the key is compromised, you can contact customer support at voip@freepbx.com and have a new one re-generated.<br /><br />Once active, this module will configure your trunks, routes and DIDs and provide diagnostic tools to configure and monitor your service."),'<a href="https://store.freepbx.com" target="_sipstation" title="FreePBX SIP Store and Portal">SIPSTATION&trade;</a>','<a href="https://store.freepbx.com" title="Access the FreePBX SIPSTATION(TM) SIP service store and portal">https://store.freepbx.com</a>') ?>
        </p>
      </div>
    </td>
  </tr>
  <tr>
    <td><a href="#" class="info"><?php echo _("Account Key")?><span><?php echo _("In order to use this service you must have a SIPSTATION FreePBX.com portal account and service (https://store.freepbx.com). From there you can obtain a unique secure key which you should copy and paste into this key field. Once entered, you can access your services from within this module without exposing your account username and password. You can remove the key at any time")?></span></a></td>
    <td><input type="text" size="100" id="account_key" name="account_key" tabindex="<?php echo ++$tabindex;?>"></td>
  </tr>

  <tr>
    <td colspan="2"><br>
      <h6><input type="submit" name="add_key" id="add_key" value="<?php echo _("Add Key")?>" tabindex="<?php echo ++$tabindex;?>"></h6>
    </td>
  </tr>

<?php
  break;
  case 'invalid': // nokey
  case 'noserver':
  case 'tempnotavail':
?>
  <tr class="account_access_section">
    <td colspan="2">
      <small><?php echo sprintf(_("To disable account access, click %s. The auto generated trunk, route and DID configurations will remain active even if the key is removed. To also remove the Trunks, chose the %s options instead"), "<i>"._("Remove Key")."</i>", "<i>"._("Remove Key & Delete Trunks")."</i>") ?></small><br /><br />
    </td>
  </tr>

  <tr class="account_access_section">
    <td colspan="2">
      <input type="submit" name="remove_key" id="remove_key" value="<?php echo _("Remove Key")?>" tabindex="<?php echo ++$tabindex;?>" />&nbsp;
      <input type="submit" name="remove_key_del_trunks" id="remove_key_del_trunks"  value="<?php echo _("Remove Key & Delete Trunks")?>" tabindex="<?php echo ++$tabindex;?>" />&nbsp;
    </td>
  </tr>

  <tr class="firewall-test" id="sipstation-system-status">
    <td colspan="2"><h5><?php echo _("System Status") ?><hr></h5></td>
  </tr>

  <tr>
    <td colspan="2">
      <div class="sipstation-errors">
        <p><?php echo _("WARNING") ?></p>
        <ul>
<?php
  switch ($status) {
  case 'invalid':
    echo _("Your key is no longer valid. Click below to remove the current key. To obtain a new key and use this module, visit the portal at https://store.freepbx.com and log into your account.");
    break;
  case 'tempnotavail':
    echo _("The server is currently unavailable and we can not process your request. Please check back with us later. We apologize for the inconvenience.");
    break;
  default:
    echo _("The server is currently not responding. It is either unavailable or access is being blocked. If the server is unavailable, please try again later.");
  }
?>
        </ul>
      </div>
    </td>
  </tr>

<?php
  break;
  case 'valid':
?>
  <tr class="account_access_section">
    <td colspan="2">
      <small><?php echo sprintf(_("To disable account access, click %s. To update account information, click %s. If port forwarding is configured on your firewall/router, you can test it with the %s button. Port forwarding can provide more reliable service and better quality and we recommend setting it up. The test sends a packet to an unused Asterisk RTP port at your WAN address and results in a PASS if the packet is properly received."), "<i>"._("Remove Key")."</i>","<i>"._("Update Account Info")."</i>","<i>"._("Run Firewall Test")."</i>") ?></small><br /><br />
    </td>
  </tr>
  <tr class="account_access_section">
    <td colspan="2">
      <input type="submit" name="remove_key" id="remove_key" value="<?php echo _("Remove Key")?>" tabindex="<?php echo ++$tabindex;?>" />&nbsp;
      <input type="submit" name="remove_key_del_trunks" id="remove_key_del_trunks"  value="<?php echo _("Remove Key & Delete Trunks")?>" tabindex="<?php echo ++$tabindex;?>" />&nbsp;
      <input type="button" id="account-access-button"  value="<?php echo _("Get Account Info")?>" tabindex="<?php echo ++$tabindex;?>" />&nbsp;
      <input type="button" id="firewall-test-button"  value="<?php echo _("Run Firewall Test")?>" tabindex="<?php echo ++$tabindex;?>" />&nbsp;
      <input type="button" id="reset-trunks-button"  value="<?php echo _("Reset Trunks")?>" tabindex="<?php echo ++$tabindex;?>" />
    </td>
  </tr>

  <tr class="firewall-test" id="sipstation-system-status">
    <td colspan="2"><h5><?php echo _("System Status") ?><hr></h5></td>
  </tr>

  <tr class="account-settings">
    <td><a href="#" class="info"><?php echo _("Trunk Status")?><span><?php echo _("Provides Gateway Side and Client side realtime status information on your trunks and SIP registrations. Also provides a quick link to the FreePBX trunk configurations page. More details on the tooltips below.")?></span></a></td>
    <td class="asterisk-register-fields"><table width="100%"><tr>
  	  <td width="50%" align="center">
        <a href="#" class="info" id="trunk_id_gw1"><?php echo _("Primary") ?></a>
      </td>
  	  <td width="50%" align="center">
        <a href="#" class="info" id="trunk_id_gw2"><?php echo _("Secondary") ?></a>
      </td>
	  </tr></table></td>
  </tr>

  <tr class="account-settings">
    <td><a href="#" class="info"><?php echo _("Asterisk Reg.")?><span><?php echo _("Status of Registration as reported by Asterisk on your local system.")?></span></a></td>
    <td class="asterisk-register-fields"><table width="100%"><tr>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="asterisk_registry_gw1" name="asterisk_registry_gw1" value="">
      </td>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="asterisk_registry_gw2" name="asterisk_registry_gw2" value="" >
      </td>
	  </tr></table></td>
  </tr>

  <tr id="asterisk-registerattempts-msg">
    <td></td>
    <td class="warning-messages">
      <?php echo sprintf(_("Warning: The Asterisk configuration: %s, is set wrong. Change this to 0 to continually retry registrations until successful. You can use the Asterisk SIP Settings module to set this."),"registerattempts") ?>
	  </td>
  </tr>

  <tr class="account-settings">
    <td><a href="#" class="info"><?php echo _("Contact IP")?><span><?php echo _("This is the contact IP as seen on the gateway and provides warnings if errors are detected. These SHOULD be your external IP as seen on the WAN side of your router. If they are not, or if they do not match your Network IP, you should configure your NAT settings in the Asterisk SIP Settings module or in sip_nat.conf if not using that module.")?></span></a></td>
    <td><table width="100%"><tr>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="contact_ip_gw1" name="contact_ip_gw1" class="register-fields-gw1" value="">
      </td>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="contact_ip_gw2" name="contact_ip_gw2" class="register-fields-gw2" value="" >
      </td>
	  </tr></table></td>
  </tr>

  <tr class="account-settings">
    <td><a href="#" class="info"><?php echo _("Network IP")?><span><?php echo _("This is the network IP as seen on the gateway and provides warnings if errors are detected. These SHOULD be your external IP as seen on the WAN side of your router. If they are not, or if they do not match your Network IP, you should configure your NAT settings in the Asterisk SIP Settings module or in sip_nat.conf if not using that module.")?></span></a></td>
    <td><table width="100%"><tr>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="network_ip_gw1" name="network_ip_gw1" class="register-fields-gw1" value="">
      </td>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="network_ip_gw2" name="network_ip_gw2" class="register-fields-gw2" value="" >
      </td>
	  </tr></table></td>
  </tr>

  <tr class="gateway-reginfo-msg" id="gateway-private-msg">
    <td colspan="2" class="warning-messages">
      <?php echo _("Warning: The SIP Contact header is not set to your WAN IP. It is set to your internal private IP behind NAT. The gateway will attempt to decipher your proper address but your configuration is incorrect. You should review the NAT settings in the Asterisk SIP Settings module, or sip_nat.conf if not using that module.") ?>
	  </td>
  </tr>

  <tr class="gateway-reginfo-msg" id="gateway-broken-msg">
    <td colspan="2" class="warning-messages">
      <?php echo _("ERROR: Your SIP Contact header is a non-private IP address that does not match your network IP. Your system will probably fail typically resulting in one way audio issues. This is usually caused because of an externip setting that is not the same as you WAN IP, or an externhost setting with dynamic DNS information that is not updated. You should check the Asterisk SIP Settings Module or your sip_nat.conf file for the proper settings.") ?>
	  </td>
  </tr>

  <tr class="account-settings">
    <td><a href="#" class="info"><?php echo _("SIP Ping")?><span><?php echo _("Roundtrip signaling delay to SIP server as determined by the Asterisk 'qualify' command. This is only signaling delay. The voice connections (RTP media streams) are routed from your system to the closest POP (point of presence) where the call enters the PSTN. This assures the optimal minimum latency but can't be reported because it's dependent on each call.")?></span></a></td>
    <td class="asterisk-qualify-fields"><table width="100%"><tr>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="trunk_qualify_gw1" name="trunk_qualify_gw1" value="<?php echo _("Not Available") ?>">
      </td>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="trunk_qualify_gw2" name="trunk_qualify_gw2" value="<?php echo _("Not Available") ?>" >
      </td>
	  </tr></table></td>
  </tr>

  <tr class="account-settings">
    <td><a href="#" class="info"><?php echo _("Codec Priorities")?><span><?php echo _("Codec Priority Asterisk reports for these trunks. This is filtered to only show codecs supported by the gateways. The Codecs can be edited on the trunk page to make changes to priority or available codecs.")?></span></a></td>
    <td class="asterisk-codec-fields"><table width="100%"><tr>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="trunk_codecs_gw1" name="trunk_codecs_gw1" value="<?php echo _("Not Available") ?>">
      </td>
  	  <td width="50%" align="center">
        <input type="text" readonly="readonly" size="24" id="trunk_codecs_gw2" name="trunk_codecs_gw2" value="<?php echo _("Not Available") ?>" >
      </td>
	  </tr></table></td>
  </tr>

  <tr class="firewall-test-fields">
    <td>
      <a href="#" class="info"><?php echo _("Firewall Test")?><span><?php echo sprintf(_("Status result of Firewall Test. If %s, it means we successfully received the RTP packet that was sent from the remote server. If %s, it means the packet sent from the remote server was blocked by your firewall or lost in the Internet. You can retry the test."),_("PASS"),_("FAIL"))?></span></a>
    </td>
    <td><table width="100%"><tr>
  	  <td width="50%" align="center">
		    <small><b><?php echo _("Status:") ?>&nbsp;</b></small><input type="text" id="firewall_status" name="firewall_status" size="6" value="">
      </td>
  	  <td width="50%" align="center">
			  <small><b><?php echo _("External IP:") ?>&nbsp;</b></small><input type="text" id="firewall_externip" name="firewall_externip" size="14" value="">
      </td>
	  </tr></table></td>
  </tr>

  <tr class="account-settings">
    <td colspan="2">
      <h5>
        <span class="sipstation-section toggle-minus" id="account_summary_section">&nbsp</span>
        <?php echo _("Account Settings") ?>
        <hr>
      </h5>
    </td>
  </tr>

  <tr class="account-settings account_summary_section">
    <td><a href="#" class="info"><?php echo _("SIP Credentials")?><span><?php echo _("The SIP Username and Password (secret) for this account. You can change the password in the SIPSTATION(TM) portal at https://store.freepbx.com")?></span></a></td>
    <td><table width="100%"><tr>
      <td width="50%">
		<small><b><?php echo _("Username:") ?> </b></small><input type="text" readonly="readonly" size="16" id="sip_username" name="sip_username" value="<?php echo $sip_username ?>">
      </td>
	  <td width="50%">
	    <small><b><?php echo _("Password:") ?> </b></small><input type="text" readonly="readonly" size="16" id="sip_password" name="sip_password" value="<?php echo $sip_password ?>">
	  </td>
	</tr></table></td>
  </tr>

  <tr class="account-settings account_summary_section">
    <td><a href="#" class="info"><?php echo _("Gateways")?><span><?php echo _("Primary and Secondary servers to send SIP traffic to. These are used in the automatic trunk configuration.")?></span></a></td>
    <td><table width="100%"><tr>
	  <td width="50%">
		<small><b><?php echo _("Primary") ?>:&nbsp;</b></small>
		<input type="text" readonly="readonly" size="16" id="gw1" name="gw1" value="<?php echo $gw1 ?>">
	  </td>
	  <td width="50%">
		<small><b><?php echo _("Secondary") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="16" id="gw2" name="gw2" value="<?php echo $gw2 ?>">
	  </td>
	</tr></table></td></tr>

  <tr class="account-settings account_summary_section">
<td><a href="#" class="info"><?php echo _("Services")?><span><?php echo _("The number of concurrent calls that have been purchased and are configured for your service. Sometimes called trunks and similar to the number of PRI channels or POTS lines in a traditional telco environment.<br /> Your monthly charge includes all costs for DIDs and unlimited trunks.<br /> The Caller ID Number can be configured in the https://store.freepbx.com portal to send either standard 10 Digit NPA (for North American Numbers) or the E164 standard which is +1NXXNXXXXXX for NPA numbers and +NN XXXXXX.. for other countries where +NN is the Country Code.")?></span></a></td>
    <td><table width="100%"><tr>
	  <td>
		<small><b><?php echo _("Channels") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="2" id="num_trunks" name="num_trunks" value="">&nbsp;&nbsp;
	  </td>
	  <td>
	    <small><b><?php echo _("Monthly Cost") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="8" id="monthly_cost" name="monthly_cost" value="">
	  </td>
	  <td>
	    <small><b><?php echo _("CID Format") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="8" id="cid_format" name="cid_format" value="">
	  </td>
	</tr></table></td>
  </tr>

  <tr class="account_summary_section"><td><br /></td></tr>

  <tr class="e911-location e911_default_did account_summary_section">
  <td><a href="#" class="info"><?php echo _("E911 Location")?><span><?php echo _("This the E911 registered address. It is critical the E911 information is accurate and a valid US address, or leave it blank. You are responsible for the accuracy of this information and there may be substantial penalties by your local authorities if it is not accurate when E911 service is used. The settings can be changed from the https://store.freepbx.com account portal.<br />The E911 Caller ID will be transmitted to the E911 operator for ALL calls made to 911 Emergency Services and any CID you set will be ignored, whether it is one of your account DIDs or any other CID. You must have E911 address information entered to use E911 services.")?></span></a></td>
    <td>
      <small><b><?php echo _("E911 Caller ID") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="16" id="default_did" name="default_did">
    </td>
  </tr>

  <tr class="e911-location e911_street1 account_summary_section">
  <td></td>
    <td>
      <small><b><?php echo _("Address 1") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="50" id="street1" name="street1">
    </td>
  </tr>

  <tr class="e911-location e911_street2 account_summary_section">
  <td></td>
    <td>
      <small><b><?php echo _("Address 2") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="50" id="street2" name="street2">
    </td>
  </tr>

  <tr class="e911-location e911_city e911_state e911_zip account_summary_section">
  <td></td>
    <td>
      <small><b><?php echo _("City") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="25" id="city" name="city">&nbsp;
      <small><b><?php echo _("State") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="3" id="state" name="state">&nbsp;
      <small><b><?php echo _("Zip") ?>:&nbsp;</b></small><input type="text" readonly="readonly" size="8" id="zip" name="zip">
    </td>
  </tr>
</table>

<table width=690" id="trunk_routes_section_table">
  <tr class="account-settings">
    <td colspan="2">
      <h5>
        <span class="sipstation-section toggle-minus" id="trunk_routes_section">&nbsp</span>
        <a href="#" class="info"><?php echo _("Route and Trunk Configuration") ?><span><?php echo _("Check/Uncheck the boxes and submit to add/remove this service as the primary trunks to any listed route. Both gateways should be configured to allow for redundancy. If gateways are already configured in the route, the box will be checked, even if they are not the primary trunks for that route. Click on the route name to link directly to the Outbound Routes page for any route.<br />An Area Code can be set to enable 7 digit dialing for any route configured to pass 7 digits.") ?></span></a>
        <hr>
      </h5>
    </td>
  </tr>
  <tr class="account-settings trunk-routes trunk_routes_section">
    <td colspan="2">
      <small><?php echo sprintf(_("Check Primary (%s) and Secondary (%s) Trunk for each route that should be configured with the %s service. The trunks will be inserted into the corresponding routes upon clicking the %s button. You can enable 7 digit dialing with the trunk by entering your area code as well."),"<i>gw1</i>","<i>gw2</i>","SIPSTATION&trade;","<i>"._("Update Route/Trunk Configurations")."</i>") ?></small><br /><br />
    </td>
  </tr>

  <tr class="account-settings trunk-routes trunk_routes_section">
    <td>
      <a href="#" class="info"><?php echo _("Area Code")?><span><?php echo _("Provide your 3 digit area code if you would like your trunks to allow 7 digit dialing and automatically prepend your area code. This requires the route to be configured to send a 7 digit number.")?></span></a>
    </td>
    <td>
      <input type="text" maxlength="3" size="4" id="areacode" name="areacode" value="">
    </td>
  </tr>

  <tr class="trunk_routes_section trunk-routes trunk_routes_section"><td></td><tr>
<?php
  $routes = core_routing_list();
  foreach($routes as $route) {
    $route_label = sprintf("%'03s: %s",$route['seq'],$route['name']);
    $route_name  = $route['name'].$route['route_id'];
    // The classes on the two checkboxes are purposely swapped to allow js to auto check the second box
?>
  <tr class="account-settings trunk-routes trunk_routes_section">
<td id="<?php echo $route_name.'-lab'?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?display=routing&extdisplay='.$route['route_id'] ?>" title="<?php echo sprintf(_("Edit: %s"),$route_label) ?>"><?php echo $route_label ?></a></td>
    <td>
      <input type="checkbox" value="1" name="<?php echo $route_name.'_id1' ?>" id="<?php echo $route_name.'_id1' ?>" class="route-checkbox <?php echo $route_name.'_id2' ?>" tabindex="$tabindex" />
      <label for="<?php echo $route_name.'_id1' ?>"> <small>gw1</small> </label>
      <input type="checkbox" value="1" name="<?php echo $route_name.'_id2' ?>" id="<?php echo $route_name.'_id2' ?>" class="route-checkbox <?php echo $route_name.'_id1' ?>" tabindex="$tabindex" />
      <label for="<?php echo $route_name.'_id2' ?>"> <small>gw2</small> </label>
    </td>
  </tr>
<?php
  }
?>
  <tr id="route-button" class="account-settings trunk_routes_section">
    <td colspan="2"><br \>
      <input type="button" id="route-submit-button"  value="<?php echo _("Update Route/Trunk Configurations")?>" tabindex="<?php echo ++$tabindex;?>" />
    </td>
  </tr>
</table>

<table width=690">
  <tr class="account-settings">
    <td colspan="8">
      <h5>
        <span class="sipstation-section toggle-minus" id="did_section">&nbsp</span>
        <a href="#" class="info"><?php echo _("DID Configuration") ?><span><?php echo _("You can assign each DID to any of your extensions on this screen to generate a route to that extension with default settings. If you check the \"Set CID\" box you will set the extension's outbound CID to this DID also. (Note the Outbound CID on an extension will not be deleted if you subsequently change the assignment of that DID, unless another DID is assigned with the box checked. You will need to go to the extension and modify it otherwise.) If the DID is already assigned to something other than an extension, the destination will be listed and can be changed here. To assign to other destination types, click the DID link to go direct to the Inbound route for that DID or to create a new one.") ?></span></a>
        <hr>
      </h5>
    </td>
  </tr>
  <tr class="account-settings did-header did_section">
    <td><?php echo _("DID") ?></td>
    <td></td>
<?php
    if ($failover) {
?>
    <td><?php echo _("Failover #") ?></td>
    <td></td>
<?php
    }
?>
    <td><?php echo _("Description") ?></td>
    <td></td>
    <td><?php echo _("Route To") ?></td>
    <td><?php echo _("Set CID") ?></td>
  </tr>

  <tr id="did-button" class="account-settings did_section">
    <td colspan="4"><br \>
      <input type="button" id="did-submit-button"  value="<?php echo _("Update DID Configurations")?>" tabindex="<?php echo ++$tabindex;?>" />
    </td>
  </tr>

<?php
  $did_tabindex = $tabindex;
  $tabindex += 900; // make room for dynamic insertion of new fields

  } // end switch ($status)
?>
</table>
<script language="javascript">
<!--
<?php
echo "var dispnum='$dispnum.';"; // used for cookie namespace
if ($status == 'valid') {
?>
$(document).ready(function(){
  if (typeof jQuery.cookie != 'function') {
    /**
    * We are putting this into core, but for past versions that don't have it, ...
    *
    * Cookie plugin
    *
    * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
    * Dual licensed under the MIT and GPL licenses:
    * http://www.opensource.org/licenses/mit-license.php
    * http://www.gnu.org/licenses/gpl.html
    *
    */
    jQuery.cookie = function(name, value, options) {
      if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
          value = '';
          options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
          var date;
          if (typeof options.expires == 'number') {
            date = new Date();
            date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
          } else {
            date = options.expires;
          }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
      } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
          var cookies = document.cookie.split(';');
          for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) == (name + '=')) {
              cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
              break;
            }
          }
        }
        return cookieValue;
      }
    };
  }
  /* On click ajax to pbx and determine extenral network and localnet settings */
  $.ajaxSetup({
    timeout:20000
  });
  $("#account-access-button").click(function(){
    var key_status = $("#key_status").val();
    if (key_status != "valid") {
      var msg = "<?php echo _("A valid key is required to perform this action and the server must be available. Error:") ?>" + key_status;
      var lab = "<?php echo _("ERROR") ?>";
      errorMessage(msg, lab);
      return false;
    }
    var send_reload = '&send_reload='+($('#need_reload_block').size() == 0 ? 'yes':'no');
    $.ajax({
      type: 'POST',
      url: "<?php echo $_SERVER["PHP_SELF"]; ?>",
			data: "display=<?php echo $module_page ?>&action=ajax_get&quietmode=1"+send_reload,
      dataType: 'json',
      success: function(data) {
        if (data.status == 'success') {
          $("#account-access-button").val("<?php echo _("Update Account Info")?>");
          for(var member in data) { 
            switch(member) 
            {
            case 'registered_status':
              if (data.registered_status.gw1.registered == 'yes') {
                $('.register-fields-gw1').addClass('registered-yes').removeClass('registered-no');
              } else {
                $('.register-fields-gw1').addClass('registered-no').removeClass('registered-yes');
              }
              if (data.registered_status.gw1.ips_match == 'yes') {
                $('.register-fields-gw1').addClass('ips-match').removeClass('ips-mismatch').removeClass('ips-private');
              } else if (data.registered_status.gw1.ips_match == 'private') {
                $('.register-fields-gw1').addClass('ips-private').removeClass('ips-match').removeClass('ips-mismatch').removeClass('registered-yes');
              } else {
                $('.register-fields-gw1').addClass('ips-mismatch').removeClass('ips-match').removeClass('ips-private').removeClass('registered-yes').addClass('registered-no');
              }

              if (data.registered_status.gw2.registered == 'yes') {
                $('.register-fields-gw2').addClass('registered-yes').removeClass('registered-no');
              } else {
                $('.register-fields-gw2').addClass('registered-no').removeClass('registered-yes');
              }
              if (data.registered_status.gw2.ips_match == 'yes') {
                $('.register-fields-gw2').addClass('ips-match').removeClass('ips-mismatch').removeClass('ips-private');
              } else if (data.registered_status.gw2.ips_match == 'private') {
                $('.register-fields-gw2').addClass('ips-private').removeClass('ips-match').removeClass('ips-mismatch').removeClass('registered-yes');
              } else {
                $('.register-fields-gw2').addClass('ips-mismatch').removeClass('ips-match').removeClass('ips-private').removeClass('registered-yes').addClass('registered-no');
              }

              if (data.registered_status.gw1.ips_match == 'no' || data.registered_status.gw2.ips_match == 'no') {
                $('.gateway-reginfo-msg').hide();
                $('#gateway-broken-msg').show();
              } else if (data.registered_status.gw1.ips_match == 'private' || data.registered_status.gw2.ips_match == 'private') {
                $('.gateway-reginfo-msg').hide();
                $('#gateway-private-msg').show();
              } else {
                $('.gateway-reginfo-msg').hide();
              }

             	$('#contact_ip_gw1').val(data.registered_status.gw1.contact_ip);
             	$('#contact_ip_gw2').val(data.registered_status.gw2.contact_ip);
             	$('#network_ip_gw1').val(data.registered_status.gw1.network_ip);
             	$('#network_ip_gw2').val(data.registered_status.gw2.network_ip);
              break;
            case 'gateways':
              $.each(data[member], function(key){
               	$('#'+key).val(this);
              });
              break;
            case 'e911_address':
              $.each(data[member], function(key){
               	$('#'+key).val(this);
              });
              break;
            case 'asterisk_registry':
              updateAstRegister(data[member]);
              break;
            case 'asterisk_registerattempts':
              if (data[member] == 0) {
                $('#asterisk-registerattempts-msg').hide();
              } else {
                $('#asterisk-registerattempts-msg').show();
              }
              break;
            case 'created_trunks':
              notifyCreatedTrunks(data[member]);
              break;
            case 'changed_trunks':
              notifyChangedTrunks(data[member]);
              $('#reset-trunks-button').show();
              break;
            case 'trunk_conflict':
              notifyExistingTrunks(data[member]);
              $('#trunk_routes_section_table').hide();
              break;
            case 'trunk_codecs':
              $.each(data[member], function(key){
                if (this != '') {
               	  $('#'+member+'_'+key).val(this);
                } else {
                  $('#'+member+'_'+key).val("<?php echo _("NO CODECS") ?>").addClass('no_codecs');
                }
              });
              break;
            case 'trunk_qualify':
              $.each(data[member], function(key){
               	$('#'+member+'_'+key).val(this);
              });
              break;
            case 'trunk_id':
              $.each(data[member], function(key){
                $('#'+member+'_'+key).attr("href","<?php echo $_SERVER['PHP_SELF'].'?display=trunks&extdisplay=OUT_' ?>"+this);
                $('#trunkid_'+key).val(this);
              });
              break;
            case 'trunk_name':
              $.each(data[member], function(key){
                $('#trunk_id_'+key).attr("title",this);
              });
              break;
            case 'dids':
              $('.did-entries').remove();
              $.each(data.dids, function(did,dest){
                addDidField(did,dest);
              });
              var max_width = 0;
              $('.did_dest').each(function(){
                var this_width = $(this).width();
                max_width = this_width > max_width ? this_width : max_width;
              });
              $('.did_dest').width(max_width);

              $(".did_dest").change(function(){
                if (this.value == "blank" || this.value == "assigned") {
                  $("."+this.id).hide();
                } else {
                  $("."+this.id).show();
                }
              });

              break;
            case 'routes':
              $.each(data.routes, function(route,trunks){
                $('#'+route+'_id1').attr('checked',trunks.gw1);
                $('#'+route+'_id2').attr('checked',trunks.gw2);
              });
              break;
            case 'monthly_cost':
              $('#'+member).val("$"+data[member]);
              break;
            case 'show_reload':
              if (data.show_reload == 'yes') {
                $('#need_reload_block').fadeIn();
              }
              break;
            case 'reload_bar':
              if (!$('#need_reload_block').fadeIn().size()) {
                $('#logo').after(data[member]);
                $('#moduleBox').before(data.reload_header);
              }
              break;
            case 'reload_header':
              // handled above
            case 'status':
            case 'nat_troubleshooting':
              break;
            default:
              $('#'+member).val(data[member]);
            }
          }; 
          /*
            TODO: do we need to remove .account-settings class from non section head rows?
            .account-settings gets the section headers showing. Maybe the other sections should
            now have that class removed, I don't think it is needed with the other code.
            Check on that as it is somewhat kludgey now.
          */
          $(".account-settings").show();

	        $(".sipstation-section").each(function(){
            if ($.cookie(dispnum+this.id) == 'collapsed') {
              $("."+this.id).hide();
              $(this).removeClass("toggle-minus").addClass("toggle-plus")
            } else {
              $("."+this.id).show();
              if (!$.trim($("#street2").val())) {
                $(".e911_street2").hide();
              };
              $(this).removeClass("toggle-plus").addClass("toggle-minus")
            }
          });

        } else {
          var lab = "<?php echo _("ERROR") ?>";
          errorMessage(data.status, lab);
        }
      },
      error: function(data) {
        var msg = "<?php echo _("An Error occurred trying to contact the server for account settings.")?>";
        var lab = "<?php echo _("SERVER ERROR") ?>";
        errorMessage(msg, lab);
      }
    });
    return false;
  });

  $("#firewall-test-button").click(function(){
    $.ajax({
      type: 'POST',
      url: "<?php echo $_SERVER["PHP_SELF"]; ?>",
      data: "quietmode=1&skip_astman=1&handler=file&module=<?php echo $dispnum ?>&file=firewall.html.php",
      dataType: 'json',
      success: function(data) {
        $(".firewall-test-fields").show();

        $('#firewall_externip').val(data.externip);
        if (data.status == 'success') {
          $('#firewall_status').val("PASS");
          $('#firewall_status').addClass('firewall-pass').removeClass('firewall-fail');
          removeMessageBox();
          /*  Iterate through each localnet:netmask pair. Put them into any fields on the form
           *  until we have no more, than create new ones
					 */
        } else {
          $('#firewall_status').val("FAIL");
          $('#firewall_status').addClass('firewall-fail').removeClass('firewall-pass');
          var lab = "<?php echo _("FIREWALL TEST WARNING") ?>";
          errorMessage(data.status_message, lab);
        }
      },
      error: function(data) {
        var msg = "<?php echo _("An Error occurred trying run firewall test")?>";
        var lab = "<?php echo _("FIREWALL TEST ERROR") ?>";
        errorMessage(msg, lab);
      }
    });
    return false;
  });

  /*
   TODO: check which trunks we are getting back, and then updated their styles
   */
  $('#reset-trunks-button').click(function(){
    var send_reload = '&send_reload='+($('#need_reload_block').size() == 0 ? 'yes':'no');
    var to_reset = "";

    $('.changed_trunks').each(function(){
      to_reset += "&"+this.id+"=yes";
    });
    $.ajax({
      type: 'POST',
      url: "<?php echo $_SERVER["PHP_SELF"]; ?>",
			data: "display=<?php echo $module_page ?>&action=reset_trunk&quietmode=1"+send_reload+to_reset,
      dataType: 'json',
      success: function(data) {
        if (data.status == 'success') {
          /* if the reload_block is not there, we blindly insert the info we think we got because we told
             it in the ajax call to send it to use, should wo double check?
          */
          if (data.trunk_updated != undefined) {
            notifyTrunkUpdated(data.trunk_updated);
            $('#reset-trunks-button').hide();
          }
          if (!$('#need_reload_block').fadeIn().size()) {
            $('#logo').after(data.reload_bar);
            $('#moduleBox').before(data.reload_header);
          }
        } else {
          var lab = "<?php echo _("ERROR") ?>";
          errorMessage(data.status, lab);
        }
      },
      error: function(data) {
        var msg = "<?php echo _("An Error occurred trying Submit")?>";
        var lab = "<?php echo _("ERROR") ?>";
        errorMessage(msg, lab);
      }
    });
    return false;
  });

  $("#route-submit-button").click(function(){
    var routes = "";
    $('.route-checkbox').each(function(){
      routes += "&"+this.id+"="+(this.checked?'yes':'no');
    });
    var areacode = '&areacode='+$('#areacode').val();
    var send_reload = '&send_reload='+($('#need_reload_block').size() == 0 ? 'yes':'no');
    $.ajax({
      type: 'POST',
      url: "<?php echo $_SERVER["PHP_SELF"]; ?>",
      data: "display=<?php echo $module_page ?>&action=route_post&quietmode=1&sip_username="+$('#sip_username').val()+areacode+routes+send_reload,
      dataType: 'json',
      success: function(data) {
        if (data.status == 'success') {
          /* if the reload_block is not there, we blindly insert the info we think we got because we told
             it in the ajax call to send it to use, should wo double check?
          */
          if ((data.show_reload == 'yes') && !$('#need_reload_block').fadeIn().size()) {
            $('#logo').after(data.reload_bar).fadeIn();
            $('#moduleBox').before(data.reload_header);
          }
          if (data.show_reload == 'yes') {
            $('#need_reload_block').fadeIn();
          }
          if (data.created_trunks != undefined) {
            notifyCreatedTrunks(data.created_trunks);
          }
          var lab = "<?php echo _("UPDATES") ?>";
          noticeMessage(data.status_message, lab);
        } else {
          var lab = "<?php echo _("NOTICE") ?>";
          noticeMessage(data.status, lab);
        }
      },
      error: function(data) {
        var msg = "<?php echo _("An Error occurred trying Submit")?>";
        var lab = "<?php echo _("ERROR") ?>";
        errorMessage(msg, lab);
      }
    });
    return false;
  });

  $("#did-submit-button").click(function(){
    /* var send_did = new Array(); */
    var send_did = new Array();
    var dids = "";
    $('.did-entries').each(function(){
      destination = $(this).find('select option:selected').val();
      set_cid = $(this).find('.did_outboundcid:checked').length;
<?php
    if ($failover) {
?>
      failover_dest = $.trim($(this).find('.did_failover').val());
<?php
    } else {
?>
      failover_dest = '';
<?php
    }
?>
      if (failover_dest != '' || ((destination != 'blank') && (destination != 'assigned'))) {
        send_did.push({did:$(this).find('.dids').text(), dest:destination, failover:failover_dest ,desc:$(this).find('.did_description').val(), setcid:set_cid});
      }
    });
    var send_reload = '&send_reload='+($('#need_reload_block').size() == 0 ? 'yes':'no');
    var send_serialized_did = "&dids="+encodeURIComponent(js_array_to_php_array(send_did));
    $.ajax({
      type: 'POST',
      url: "<?php echo $_SERVER["PHP_SELF"]; ?>",
			data: "display=<?php echo $module_page ?>&action=dest_post&quietmode=1"+send_reload+send_serialized_did,
      dataType: 'json',
      success: function(data) {
        $('.validation-error').removeClass('validation-error');
        if (data.status == 'success') {
          /* if the reload_block is not there, we blindly insert the info we think we got because we told
             it in the ajax call to send it to use, should wo double check?
          */
          if (data.update_count && !$('#need_reload_block').fadeIn().size()) {
            $('#logo').after(data.reload_bar);
            $('#moduleBox').before(data.reload_header);
          }
          var lab = "<?php echo _("UPDATES") ?>";
          noticeMessage(data.status_message, lab);
        } else if (data.status == 'validation_failures') {
          var lab = "<?php echo _("WARNING") ?>";
          markDidValidationFailures(data.validation_failures);
          errorMessage(data.status_message, lab);
        } else {
          var lab = "<?php echo _("ERROR") ?>";
          markDidValidationFailures(data.validation_failures);
          errorMessage(data.status, lab);
        }
      },
      error: function(data) {
        var msg = "<?php echo _("An Error occurred trying Submit")?>";
        var lab = "<?php echo _("ERROR") ?>";
        errorMessage(msg, lab);
      }
    });
    return false;
  });

  /* Add a Custom Var / Val textbox */
  $('.account-settings').hide();
  $('#asterisk-registerattempts-msg').hide();
  $('.gateway-reginfo-msg').hide();
  $('.e911-location').hide();
  $('.firewall-test-fields').hide();
  $('#reset-trunks-button').hide();
  $(".route-checkbox").click(function(){
    if (this.checked) {
      $('.'+this.id).each(function(){
        this.checked=true;
      })
    }
  });

	$(".sipstation-section").click(function(){
    if ($.cookie(dispnum+this.id) == 'expanded') {
      $("."+this.id).hide();
      $.cookie(dispnum+this.id,'collapsed', { expires: 365 });
      $(this).removeClass("toggle-minus").addClass("toggle-plus")
    } else {
      $("."+this.id).show();
      $.cookie(dispnum+this.id,'expanded', { expires: 365 });
      if (!$.trim($("#street2").val())) {
        $(".e911_street2").hide();
      };
      $(this).removeClass("toggle-plus").addClass("toggle-minus")
    }
  });

  var confirm_remove_key = "<?php echo _("You will no longer have access to your account unless you re-enter your key. It will be removed from the system. Your configured trunks will still remain active. Do you want to continue?")?>"
  $("#remove_key").click(function(){
    return confirm(confirm_remove_key);
  });

  var confirm_remove_key_del_trunks = "<?php echo _("This will delete the trunks from your configuration and you will no longer have access to your account unless you re-enter your key. Do you really want to continue and delete your trunks?")?>"
  $("#remove_key_del_trunks").click(function(){
    return confirm(confirm_remove_key_del_trunks);
  });

<?php
  /* this will insert the addClass jquery calls to all id's in error */
  if (!empty($error_displays)) {
    foreach ($error_displays as $js_disp) {
      echo "  ".$js_disp['js'];
    }
  }
  echo "did_tabindex = $did_tabindex;\n";
  if ($status == 'valid') {
?>
  $("#account-access-button").trigger('click');
  $(".sipstation-section").each(function(){
    if ($.cookie(dispnum+this.id) == 'collapsed') {
      $(this).removeClass("toggle-plus").addClass("toggle-minus")
    }
  });
  scheduleStatsUpdate();
<?php
  }
?>
  /* TODO: alert happens before reload function, find way to trigger after reload
  $("#need_reload_block > a").attr('onclick','').click(function(){
    freepbx_show_reload();
    alert("reload done");
  });
  */
});

var dest = new Array();
var descr = new Array();
<?php
  $cnt = 0;
  foreach (core_destinations() as $dest) {
    if ($dest['category'] == 'Extensions') {
      echo 'dest['.$cnt.']="'.htmlspecialchars(trim($dest['destination'])).'";'."\n";
      echo 'descr['.$cnt.']="'.htmlspecialchars(trim($dest['description'])).'";'."\n";
      $cnt++;
    }
  }
?>
var theForm = document.editSip;

function updateStats() {
  var params = '&gw1='+$('#gw1').val()+'&gw2='+$('#gw2').val()+'&sip_username='+$('#sip_username').val();
  $.ajax({
    type: 'POST',
    url: "<?php echo $_SERVER["PHP_SELF"]; ?>",
    data: "display=<?php echo $module_page ?>&action=refresh_display&quietmode=1&restrictmods=<?php echo $module_page ?>"+params,
    dataType: 'json',
    success: function(data) {
      if (data.status == 'success') {
        if (data.asterisk_registry != undefined) {
          updateAstRegister(data.asterisk_registry);
        }
        if (data.trunk_qualify != undefined) {
          $.each(data.trunk_qualify, function(key){
            $('#trunk_qualify_'+key).val(this);
          });
        }
      }
      scheduleStatsUpdate();
    },
    error: function(data) {
      //console.log("Returned from ajax refresh with error, re-scheduling the refresh");
      scheduleStatsUpdate();
    }
  });
}
function scheduleStatsUpdate() {
  stats_timer = setTimeout('updateStats();',120000);
}
function updateAstRegister(member) {
  $.each(member, function(key){
    if (key != 'srv') {
      if (this == 'Registered') {
        $('#asterisk_registry_'+key).addClass('ast-registered-yes').removeClass('ast-registered-no').val(this);
      } else {
        $('#asterisk_registry_'+key).addClass('ast-registered-no').removeClass('ast-registered-yes').val(this);
      }
    }
  });
}
/* DID rows */
function addDidField(key, val) {
  var idx = $(".dids").size();
  var tabindex = did_tabindex + idx + idx + idx + idx;
  var hide_check = false;

  var head = '<tr class="account-settings did-entries did_section" style="display: table-row;">';

  var addedit = val.destination == 'blank' ? "<?php echo _("Add: ") ?>" : "<?php echo _("Edit: ") ?>";
  var did_value = '<td><span id="did_value_'+key+'" name="did_value_'+key+'" class="dids"><a href="<?php $_SERVER['PHP_SELF']?>?display=did&extdisplay='+key+'/" title="'+addedit+key+'" >'+key+'</a></span></td>&nbsp;';

<?php
    if ($failover) {
?>
  var did_failover = '<td><input size="12" maxlength="11" type="text" id="did_failover_'+key+'" name="did_failover_'+key+'" class="did_failover" value="'+val.failover+'" tabindex="'+tabindex+'"></td>&nbsp;';
<?php
  } else {
?>
  var did_failover = '';
<?php
  }
?>

  var did_description = '<td><input type="text" id="did_description_'+key+'" name="did_description_'+key+'" class="did_description" value="'+val.description+'" tabindex="'+tabindex+'"></td>&nbsp;';

  var did_select = '<td><select id="did_select_'+key+'" name="did_select_'+key+'" class="did_dest">';
  if (val.destination == 'blank') {
    did_select += '<option value="blank" selected="selected"><?php echo _("Not Created") ?></option>';
    hide_check = true;
  }
  if (val.destination == 'assigned') {
    did_select += '<option value="assigned">'+val.desc+'</option>';
    hide_check = true;
  }
  $.each(descr, function(idx,desc){
    var sel_val = (dest[idx] == val.destination ? '" selected="selected"' : '');
    did_select += '<option class="did_dest" value="'+dest[idx]+'"'+sel_val+'>'+desc+'</option>';
  });
  did_select += '</select></td>';

  var did_outboundcid = '<td><input type="checkbox" id="did_outboundcid_'+key+'" name="did_outboundcid_'+key+'" class="did_outboundcid did_select_'+key+'" tabindex="'+(tabindex+2)+'"'+(val.outboundcid || val.destination == 'blank' ? "checked":"")+'></td>';
  var footer = '</tr>';

  $("#did-button").before(head + did_value + did_failover + did_description + did_select + did_outboundcid + footer);
  if (hide_check) {
    $(".did_select_"+key).hide();
  }
}

function markDidValidationFailures(failed_dids) {
  $.each(failed_dids, function(key){
    //console.log('did_failures', key, this);
    $('#did_failover_'+key).addClass('validation-error')
  });
}

function notifyCreatedTrunks(trunks) {
  var label = '<?php echo _("NOTICE") ?>';
  var content = '';
  $.each(trunks, function(key){
    switch(key) {
      case 'gw1':
      content += '<li> <?php echo _("Generated Primary Trunk: ")?>' + this +'</li>';
        break;
      case 'gw2':
      content += '<li> <?php echo _("Generated Secondary Trunk: ")?>' + this +'</li>';
        break;
      default:
    }
  });
  content += '<li><?php echo _("Trunks can be added to routes below.")?> </li>';
  content += '<li><?php echo _("Account DIDs can be configured below.")?> </li>';
  displayMessageBox(content, label, 'sipstation-messages');
}

function notifyExistingTrunks(trunks) {
  var label = '<?php echo _("WARNING") ?>';
  var content = '';
  content += '<li><?php echo _("There are trunks configured with identical credentials. They must be removed to use this module for Routing and Trunk Configuration.")?> </li>';
  content += '<ul>';
  $.each(trunks, function(key){
    content += '<li><a href="<?php echo $_SERVER['PHP_SELF'].'?display=trunks&extdisplay=' ?>'+this.href+'">'+'<?php echo _("Trunk: ") ?>'+this.name+'</a></li>';
  });
  content += '</ul>';
  displayMessageBox(content, label, 'sipstation-messages');
}

function notifyChangedTrunks(trunks) {

  var label = '<?php echo _("NOTICE") ?>';
  var content = '';
  content += '<li><?php echo _("Trunk settings have been updated or you have made changes. You may want to press the \"Reset Trunks\" button. The effected trunks are listed and you can click to edit them:")?> </li>';
  content += '<ul>';
  $.each(trunks, function(key){
    switch(key) {
      case 'gw1':
      content += '<li><a href="<?php echo $_SERVER['PHP_SELF'].'?display=trunks&extdisplay=' ?>'+this+'">'+'<?php echo _("Primary Trunk") ?></a></li>';
        break;
      case 'gw2':
      content += '<li><a href="<?php echo $_SERVER['PHP_SELF'].'?display=trunks&extdisplay=' ?>'+this+'">'+'<?php echo _("Secondary Trunk") ?></a></li>';
        break;
      default:
    }
    $('#'+key).addClass('changed_trunks');
  });
  content += '</ul>';
  displayMessageBox(content, label, 'sipstation-messages');
}

function notifyTrunkUpdated(trunks) {

  var label = '<?php echo _("UPDATE") ?>';
  var content = '';
  content += '<li><?php echo _("Trunks have been reset to the default configuration. All codecs and other setups have been preserved.")?> </li>';
  content += '<ul>';
  $.each(trunks, function(key){
    switch(key) {
      case 'gw1':
      content += '<li><a href="<?php echo $_SERVER['PHP_SELF'].'?display=trunks&extdisplay=' ?>'+this+'">'+'<?php echo _("Primary Trunk Reset") ?></a></li>';
        break;
      case 'gw2':
      content += '<li><a href="<?php echo $_SERVER['PHP_SELF'].'?display=trunks&extdisplay=' ?>'+this+'">'+'<?php echo _("Secondary Trunk Reset") ?></a></li>';
        break;
      default:
    }
    $('#'+key).removeClass('changed_trunks');
  });
  content += '</ul>';
  displayMessageBox(content, label, 'sipstation-messages');
}

function js_array_to_php_array (a)
// This converts a javascript array to a string in PHP serialized format.
// This is useful for passing arrays to PHP. On the PHP side you can 
// unserialize this string from a cookie or request variable. For example,
// assuming you used javascript to set a cookie called "php_array"
// to the value of a javascript array then you can restore the cookie 
// from PHP like this:
//
//    $my_array = unserialize(_REQUEST['passed_array']);
//
// This automatically converts both keys and values to strings.
// The return string is not URL escaped, so you must call the
// Javascript "encodeURIComponent()" function before you pass this string to PHP.
//
// TODO: this serialized each sub array separately such that it returns a one
//       dimmensional array that then needs each sub array to be seriallized.
// TODO: should just seriallized it intelligently so that the receiving end does
//       not need to know the structure...
{
  var a_php = "";
  var total = 0;
  for (var key in a) {
    var element = ((typeof a[key] == "object") ? js_array_to_php_array(a[key]) : a[key]);
    ++ total;
    a_php = a_php + "s:" +
    String(key).length + ":\"" + String(key) + "\";s:" +
    String(element).length + ":\"" + String(element) + "\";";
  }
  a_php = "a:" + total + ":{" + a_php + "}";
  return a_php;
}

<?php
} elseif (isset($set_key_status) && $set_key_status != 'valid') {
	switch ($set_key_status) {
		case 'nokey':
?>
			var msg = "<?php echo _("You did not enter a key, Please enter a key to add.")?>";
<?php
			break;
		case 'invalid':
?>
			var msg = "<?php echo _("You have entered an invalid key. You can find your key in the portal and should copy and paste it below.") ?>";
<?php
			break;
    case 'noserver':
?>
      var msg = "<?php echo sprintf(_("%s: This module can not reach the server to obtain your account information. If you can navigate to <a href='https://store.freepbx.com'>https://store.freepbx.com</a> from a browser then there is an issue with your firewall or DNS resolution. If this is a first time install you might be able to reboot to rectify the issue. If you have an aggressive firewall with content filtering or equivalent, you may have to disable that feature for this server or white list the store.freepbx.com site if possible."),$set_key_status) ?>";
<?php
      break;
		default:
?>
			var msg = "<?php echo sprintf(_("There is a problem with your key: %s"),$set_key_status) ?>";
<?php
	}
?>
var lab = "<?php echo _("ERROR") ?>";
errorMessage(msg, lab);
<?php
} // end if ($status = 'valid')
?>
function errorMessage(message, label) {
  displayMessageBox('<li>'+message+'</li>', label,'sipstation-errors')
}

function noticeMessage(message, label) {
  displayMessageBox('<li>'+message+'</li>', label,'sipstation-messages')
}
function displayMessageBox(message, label, format) {
  $(".sipstation-msg-box").remove();
  if (label == undefined) {
    label = "<?php echo _("ERROR") ?>";
  }
  var head = '<tr class="sipstation-msg-box"><td colspan="2"><div class="'+format+'"><p>'+ label +'</p><ul>';
  var footer = '</ul></div></td></tr><tr class="sipstation-msg-box"><td></td><td></td></tr><tr class="sipstation-msg-box"><td></td><td></td></tr>';
  $("#sipstation-system-status").after(head + message + footer);
  $("#sipstation-system-status").get(0).scrollIntoView();
}
function removeMessageBox() {
  $('.sipstation-msg-box').remove();
}
//-->
</script>
</form>
<?php		

/********** UTILITY FUNCTIONS **********/

function process_errors($errors) {
  foreach($errors as $error) {
    $error_display[] = array(
      'js' => "$('#".$error['id']."').addClass('validation-error');\n",
      'div' => $error['message'],
    );
  }
  return $error_display;
}

/************ AJAX FUNCTIONS ***********/

function ajax_get($post) {
  include_once("sipstation.html.php");
  return true;
}
function dest_post($post) {
  include_once("destinations.html.php");
  return true;
}
function route_post($post) {
  include_once("routes.html.php");
  return true;
}
function reset_trunk($post) {
  include_once("edittrunk.html.php");
  return true;
}
function refresh_display($post) {
  include_once("refresh.html.php");
  return true;
}
?>
