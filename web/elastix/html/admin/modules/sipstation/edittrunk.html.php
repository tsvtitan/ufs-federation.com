<?php
/* $Id:$ */

/* Copyright (c) 2009 Bandwidth.com
   Licensed for use by active FreePBX.com SIP Trunking Customers (SIPSTATION(tm)). Not licensed to be modified or redistributed in any fashion.
   No guarantees or warranties provided, use at your own risk. See license included with module for more details.
*/

// TODO: set this in production
error_reporting(0);

include_once('sipstation.utility.php');

if (! @include_once("common/json.inc.php")) {
  $quietmode=1;
  include_once("/var/www/html/admin/common/json.inc.php");
  include_once("/var/www/html/admin/functions.inc.php");
  $amp_conf = parse_amportal_conf("/etc/amportal.conf");
  include_once("/var/www/html/admin/common/db_connect.php");
  include_once("/var/www/html/admin/modules/core/functions.inc.php");
  if (!isset($active_modules)) {
    $active_modules = array();
  }
}
/* For testing:
*//*
include_once("common/json.inc.php");
*/

/* We can use the cached config data as we were just told that
   trunk params have changed and chose to update
 */

$filter = array(
  'sip_username' => true, 
  'sip_password' => true, 
  'gateways' => true,
  'asterisk_settings' => true,
);
$current_config = sipstation_get_config("xxx", false, $filter);

$need_reload = false;

$sip_user = $current_config['sip_username'];
$sip_pass = $current_config['sip_password'];

$tlist       = core_trunks_list(true);
$tech        = 'sip';

$peer_array = array();
foreach ($current_config['asterisk_settings']['peer'] as $param) {
  $peerdetails .= trim($param)."\n";
  $parts = explode('=',$param,2);
  $peer_array[$parts[0]] = $parts[1];
}
$peerdetails .= "username=$sip_user\nsecret=$sip_pass\nhost=";
$register    = "$sip_user:$sip_pass@";

for ($i=1;$i<3;$i++) {
  $gidx = "gw$i";
  $channelid   = "fpbx-$i-$sip_user";
  $gw          = $current_config['gateways'][$gidx];
  if (isset($tlist["SIP/$channelid"])) {
    $json_array['status'] = 'success';
    if (!isset($_POST[$gidx]) || $_POST[$gidx] != 'yes') {
      continue;
    }
    $globalvar = $tlist["SIP/$channelid"]['globalvar'];
    $trunknum  = ltrim($globalvar,'OUT_');
    $trunk_details = core_trunks_getDetails($trunknum);
    $userconfig = core_trunks_getTrunkUserConfig($trunknum);
    $old_peerdetails = explode("\n",core_trunks_getTrunkPeerDetails($trunknum));
    foreach ($old_peerdetails as $param) {
      $parts = explode('=',$param,2);
      switch (strtolower(trim($parts[0]))) {
        case 'allow':
          $allow = "allow=".$parts[1]."\n";
        break;
        case 'disallow':
          $disallow = "disallow=".$parts[1]."\n";
        break;
      }
    }
    $peer_stuff = $peerdetails.$gw."\n".$disallow.$allow;
    $reg = $register.$gw;

    /* Extract the allow/disallow now
    */
    $tech = $trunk_details['tech'];
    $outcid = $trunk_details['outcid'];
    $maxchans = $trunk_details['maxchans'];
    $dialoutprefix = $trunk_details['dialoutprefix'];
    $keepcid = $trunk_details['keepcid'];
    $failtrunk = $trunk_details['failscript'];
    $disabletrunk = $trunk_details['disabled'];

    // TODO: trunk_name, provider with 2.6
    /*
    $provider = $trunk_details['provider'];
    $trunk_name = $trunk_details['name'];
    */
	  core_trunks_edit($trunknum, $channelid, $dialoutprefix, $maxchans, $outcid, $peer_stuff, $usercontext, $userconfig, $reg, $keepcid, $failtrunk, $disabletrunk);
    $need_reload = true;
    $json_array['status'] = 'success';
    $json_array['trunk_updated'][$gidx] = $trunknum;
  }
}

/*
  if we made changes then we have to set the needsreload status and send back the reload bar to be inserted
*/
if ($need_reload) {
  needreload();
  if ($_POST['send_reload'] == 'yes') {
    ob_start();
    if (!@include ('views/freepbx_reloadbar.php')) {
      @include ('../../views/freepbx_reloadbar.php'); //TODO for debugging
    }
    $json_array['reload_bar'] = ob_get_clean();
    ob_start();
    if (!@include ('views/freepbx_reload.php')) {
      @include ('../../views/freepbx_reload.php'); //TODO for debugging
    }
    $json_array['reload_header'] = ob_get_clean();
  }
  $json_array['show_reload'] = 'yes';
} else {
  $json_array['show_reload'] = 'no';
}

$json = new Services_JSON();
$value = $json->decode($_POST);

header("Content-type: application/json"); 
echo $json->encode($json_array);
