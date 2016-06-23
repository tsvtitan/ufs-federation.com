<?php
/* $Id:$ */

// Original Release by Philippe Lindheimer
// Copyright Philippe Lindheimer (2009)
// Copyright Bandwidth.com (2009)
/*
   TODO: Get proper licensing.
         This is free to use and distribute for use. The licensing is NOT GPL or other open source and derivative work is not allowed.
*/

// TODO: set this in production
error_reporting(0);

include_once('sipstation.utility.php');

if (! @include_once("common/json.inc.php")) {
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
global $db;
global $sipstation_xml_version;
/* For testing:
*//*
include_once("common/json.inc.php");
*/


//TODO: mockup, would need to do error checking
//

$json_array = sipstation_get_config( sipstation_get_key() );

/* Check if we have trunks configured with the same credentials
   and if so, skip the trunk creation mode

   Note: there are some ugly queries below. We don't like to do that
         but the APIs are not ideal and very heavy. This turns out
         to be much more efficient to detect these trunks.
 */
$trunk_conflict = array();
if ($json_array['query_status'] == 'SUCCESS') {
  $sip_user = $json_array['sip_username'];
  $gateways = implode("','",$json_array['gateways']);

  $sql = "
   SELECT `id` FROM `sip` WHERE `keyword` = 'username' 
   AND `data` = '$sip_user' AND `id` IN (
     SELECT `id` FROM `sip` WHERE `keyword` = 'host' 
     AND `data` IN ('$gateways')
   )";
  $results = $db->getCol($sql);
  if(DB::IsError($results)) {
	  $results = array();
  }
  
  $sql = "SELECT `id` FROM `sip` WHERE `keyword` = 'register' AND (";
  foreach ($json_array['gateways'] as $gw) {
    $sql .= "(`data` LIKE '$sip_user:%@$gw%') OR ";
  }
  $sql = rtrim($sql,' OR').")";
  $results2 = $db->getCol($sql);
  if(DB::IsError($results)) {
	  $results2 = array();
  }
  $results2 = array_merge($results, $results2);

  /* Now we have to find the trunk, looking at the pre-2.6 and post
     2.6 account id formats
   */
  $existing_trunks = array();
  $existing_trunk_hash = array();
  foreach ($results2 as $tr) {
    if (preg_match('/^9999([\d]{1,2})$|^99999([\d]{1,2})$|^9999999([\d]{1,2})$|^tr-peer-([\d]+)$|^tr-user-([\d]+)$|^tr-reg-([\d]+)$/',$tr,$match)) {
      $existing_trunks[$match[count($match)-1]][] = $tr;
      $existing_trunk_hash[$tr] = $match[count($match)-1];
    }
  }
  $sql = "SELECT `data` name, `id` FROM `sip` WHERE `keyword` = 'account' AND `id` IN ('".implode("','",array_keys($existing_trunk_hash))."')";
  $results = $db->getAll($sql,DB_FETCHMODE_ASSOC);
  if(DB::IsError($results)) {
	  $results = array();
  }

  foreach ($results as $trunk_name) {
    if ($trunk_name['name'] != "fpbx-1-$sip_user" && $trunk_name['name'] != "fpbx-2-$sip_user") {
      $tname =  core_trunks_getTrunkTrunkName($existing_trunk_hash[$trunk_name['id']]);
      if ($tname != $trunk_name['name']) {
        $tname .= " ({$trunk_name['name']})";
      }
      $trunk_conflict[] = array('name' => $tname, 'href' => 'OUT_'.$existing_trunk_hash[$trunk_name['id']]);
    }
  }
}

/* If we detected trunk conflicts then we don't bother with the Trunk and Routing
   section, just report and be done.
 */
if (!empty($trunk_conflict)) {
  unset($json_array['query_status_message']);
  unset($json_array['query_status']);
  $json_array['trunk_conflict'] = $trunk_conflict;

} elseif ($json_array['query_status'] == 'SUCCESS' && $json_array['xml_version'] == $sipstation_xml_version) { // TODO: This needs to be checking that there was no failure
  $need_reload = sipstation_get_or_create_trunks($json_array,$globalvar1,$trunknum1,$globalvar2,$trunknum2);
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

  // Check trunknum1 for a prefix and use it since it is the primary trunk
  // hopefully they will be the same
  //
  $json_array['areacode'] = "";
  $dialrules = core_trunks_get_dialrules($trunknum1);
  if (is_array($dialrules) && count($dialrules)) {
    foreach ($dialrules as $rule) {
      if (preg_match('/^1{0,1}(\d{3})$/',$rule['prepend_digits'],$matches)) {
        $json_array['areacode'] = $matches[1];
        break;
      }
    }
  }
  $settings = sipstation_get_sip_settings();
  $json_array['asterisk_registerattempts'] = $settings['Outbound reg. attempts'];

  // Get the Asterisk Registration Status, retry once if bumping into Auth. Sent.
  //
  $try_again = false;
  $cnt = 0;
  do {
    if ($try_again) {
      sleep(1);
    }
    $cnt++;
    $trunk_status = sipstation_get_registration_status($json_array['sip_username']);
    foreach ($json_array['gateways'] as $gw => $trunk) {
      if (isset($trunk_status[$trunk])) {
        $json_array['asterisk_registry'][$gw] = $trunk_status[$trunk];
        $try_again = $trunk_status[$trunk] == 'Auth. Sent' ? true : $try_again;
      } else {
        $json_array['asterisk_registry'][$gw] = _("Not Registered");
      }
    }
  } while ($try_again && $cnt < 2);

  $routes = core_routing_list();

  foreach($routes as $route) {
    $route_name = $route['name'].$route['route_id'];
    $trunks = core_routing_getroutetrunksbyid($route['route_id']);

    $gw1 = array_search($trunknum1,$trunks);
    $gw1 = ($gw1 === false ? 0 : $gw1+1);

    $gw2 = array_search($trunknum2,$trunks);
    $gw2 = ($gw2 === false ? 0 : $gw2+1);
    $json_array['routes'][$route_name] = array('gw1' => $gw1, 'gw2' => $gw2);
  }
} elseif ($json_array['query_status'] == 'SUCCESS' && $json_array['xml_version'] != $sipstation_xml_version) {
	$json_array['status'] = sprintf(_("Your module version is not compatible with the current API requirements and needs to be updated. Expecting XML version %s and received version %s."),$sipstation_xml_version, $json_array['xml_version']);
  unset($json_array['query_status_message']);
  unset($json_array['query_status']);
} else {
	$json_array['status'] = $json_array['query_status_message'];
  unset($json_array['query_status_message']);
  unset($json_array['query_status']);
}

$json = new Services_JSON();
header("Content-type: application/json"); 
echo $json->encode($json_array);
