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

$json_array['status'] = 'success';
$cnt = 0;
$need_reload = false;
$tlist = core_trunks_list(true);

/* TODO: should I pass in all the info to make the trunk here? Really it should
         alredy be made at this point and this is just a sanity check.
   TODO: In any event need to pass in enough info to configure dialpatterns for
         enabling 7 digit dialing
*/

$sip_user = $_POST['sip_username'];
if (isset($tlist["SIP/fpbx-1-$sip_user"]) && isset($tlist["SIP/fpbx-2-$sip_user"])) {
  $globalvar1 = $tlist["SIP/fpbx-1-$sip_user"]['globalvar'];
  $trunknum1  = ltrim($globalvar1,'OUT_');

  $globalvar2 = $tlist["SIP/fpbx-2-$sip_user"]['globalvar'];
  $trunknum2  = ltrim($globalvar2,'OUT_');

  // Check if we need to add dialrules
  //
  if (isset($_POST['areacode']) && trim($_POST['areacode']) != "") {

    /* We are adding a prefix, make sure it is 3 digits, then grab what's there
       rip it out if it is in there, push it on top and if the two differ, update
       if replacing, the old one will be further down and be dumped as a duplicate
    */
    $areacode = preg_replace("/[^0-9]/" ,"",trim($_POST['areacode']));
    if (strlen($areacode) == 3) {
      foreach (array($trunknum1,$trunknum2) as $trunk) {
        $dialrules = core_trunks_get_dialrules($trunk);
        if (is_array($dialrules) && count($dialrules)) {
          foreach ($dialrules as $rule) {
            $match   = $rule['match_pattern_pass'];
            $prefix  = $rule['match_pattern_prefix'];
            $prepend = $rule['prepend_digits'];

            $dialrules_tmp[] = array('match_pattern_prefix' => $prefix, 'match_pattern_pass' => $match, 'prepend_digits' => $prepend);
            if ($match != 'NXXXXXX' || $prepend != $areacode || $prefix != '') {
              $dialrules_2[] = array('match_pattern_prefix' => $prefix, 'match_pattern_pass' => $match, 'prepend_digits' => $prepend);
            }
				  }
			  } else {
          $dialrules_2 = array();
          $dialrules_tmp = array();
        }
        array_unshift($dialrules_2, array('match_pattern_prefix' => '', 'match_pattern_pass' => 'NXXXXXX', 'prepend_digits' => $areacode));
        
        if ($dialrules_2 != $dialrules_tmp) {
          core_trunks_update_dialrules($trunk, $dialrules_2, true);
          $need_reload = true;
        }
		    unset($dialrules_2);
		    unset($dialrules_tmp);
        unset($dialrules);
      }
    } else {
      $json_array['areacode_status'] = sprintf(_("The prefix you entered, %s, is not a proper prefix or the wrong length. It should be a 3 digit prefix."),$_POST['areacode']);
    }
  }

  $routes = core_routing_list();
  foreach($routes as $route) {
    $route_name = $route['name'].$route['route_id'];
    $trunks = core_routing_getroutetrunksbyid($route['route_id']);

    $gw1 = array_search($trunknum1,$trunks);
    $gw2 = array_search($trunknum2,$trunks);
    $gw1_change = ($gw1 !== false XOR $_POST[$route_name.'_id1'] == 'yes');
    $gw2_change = ($gw2 !== false XOR $_POST[$route_name.'_id2'] == 'yes');
    if ($gw1_change || $gw2_change) {
      /*
        Determine which one or both need to change then do them at the same time.
        They should all be created at this point from above, gw1 should be last
        so that trunk always ends up first.
      */
      $cnt++;
      if ($gw2_change) {
        if ($gw2===false) {
          array_unshift($trunks,$trunknum2);
        } else {
          unset($trunks[$gw2]);
        }
      }
      if ($gw1_change) {
        if ($gw1===false) {
          array_unshift($trunks,$trunknum1);
        } else {
          unset($trunks[$gw1]);
        }
      }
      foreach ($trunks as $trunk) {
        $new_trunks[] = $trunk;
      }
      // Now get all the route settings, then update with these new trunks
      //
      core_routing_updatetrunks($route['route_id'], $new_trunks, true);
      unset($new_trunks);
    }
  }
  $json_array['update_count'] = $cnt;
  if ($cnt) {
    $json_array['status_message'] = sprintf(_("Successfully configured %s routes to use your SIP trunks"),$cnt);
  } elseif ($need_reload) {
    $json_array['status_message'] = sprintf(_("Your Area Code was updated to %s"),$areacode);
  } else {
    $json_array['status_message'] = _("No updates were required, no routes or areacode were changed");
  }
} else {
  $json_array['update_count'] = 0;
  $json_array['status_message'] = sprintf(_("Your trunks are not configured. Click on the Get Account Info button in order to re-pull your account information and generate the required trunks and then try again."));
}

/*
  if we made changes then we have to set the needsreload status and send back the reload bar to be inserted
*/
if ($cnt || $need_reload) {
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
