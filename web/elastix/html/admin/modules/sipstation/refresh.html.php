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
	require_once('/var/www/html/admin/common/php-asmanager.php');
	$astman		= new AGI_AsteriskManager();
	if (!isset($amp_conf["ASTMANAGERPROXYPORT"]) || !$res = $astman->connect("127.0.0.1:".$amp_conf["ASTMANAGERPROXYPORT"], $amp_conf["AMPMGRUSER"] , $amp_conf["AMPMGRPASS"], 'off')) {
		if (!$res = $astman->connect("127.0.0.1:".$amp_conf["ASTMANAGERPORT"], $amp_conf["AMPMGRUSER"] , $amp_conf["AMPMGRPASS"], 'off')) {
			unset( $astman );
		}
	}
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

$sip_username = $_POST['sip_username'];
$gateways['gw1'] = $_POST['gw1'];
$gateways['gw2'] = $_POST['gw2'];

$json_array['status'] = 'success';
// Get the Asterisk Registration Status
$trunk_status = sipstation_get_registration_status($sip_username);
foreach ($gateways as $gw => $trunk) {
  if (isset($trunk_status[$trunk])) {
    $json_array['asterisk_registry'][$gw] = $trunk_status[$trunk];
  } else {
    $json_array['asterisk_registry'][$gw] = _("Not Registered");
  }
}

for ($i=1;$i<3;$i++) {
  $channelid   = "fpbx-$i-$sip_username";
  $trunk_status = sipstation_get_peer_status($channelid);
  if ($trunk_status['sipstation_status'] == 'ok') {
    $json_array['trunk_qualify']["gw$i"] = $trunk_status['Status'];
  }
}

$json = new Services_JSON();
$value = $json->decode($_POST);

header("Content-type: application/json"); 
echo $json->encode($json_array);
