<?php
/* $Id:$ */

/* Copyright (c) 2009 Bandwidth.com
   Licensed for use by active FreePBX.com SIP Trunking Customers (SIPSTATION(tm)). Not licensed to be modified or redistributed in any fashion.
   No guarantees or warranties provided, use at your own risk. See license included with module for more details.
*/

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

if (!function_exists('core_did_create_update')) {
  function core_did_create_update($did_vars) {
    $did_create['extension'] = isset($did_vars['extension']) ? $did_vars['extension'] : '';
    $did_create['cidnum']    = isset($did_vars['cidnum']) ? $did_vars['cidnum'] : '';

    if (count(core_did_get($did_create['extension'], $did_create['$cidnum']))) {
      return core_did_edit_properties($did_vars); //already exists so just edit properties
    } else {
		  $did_create['faxexten']    = isset($did_vars['faxexten'])    ? $did_vars['faxexten']    : '';
		  $did_create['faxemail']    = isset($did_vars['faxemail'])    ? $did_vars['faxemail']    : '';
		  $did_create['answer']      = isset($did_vars['answer'])      ? $did_vars['answer']      : '0';
		  $did_create['wait']        = isset($did_vars['wait'])        ? $did_vars['wait']        : '0';
		  $did_create['privacyman']  = isset($did_vars['privacyman'])  ? $did_vars['privacyman']  : '';
		  $did_create['alertinfo']   = isset($did_vars['alertinfo'])   ? $did_vars['alertinfo']   : '';
		  $did_create['ringing']     = isset($did_vars['ringing'])     ? $did_vars['ringing']     : '';
		  $did_create['mohclass']    = isset($did_vars['mohclass'])    ? $did_vars['mohclass']    : 'default';
		  $did_create['description'] = isset($did_vars['description']) ? $did_vars['description'] : '';
		  $did_create['grppre']      = isset($did_vars['grppre'])      ? $did_vars['grppre']      : '';
		  $did_create['delay_answer']= isset($did_vars['delay_answer'])? $did_vars['delay_answer']: '0';
		  $did_create['pricid']      = isset($did_vars['pricid'])      ? $did_vars['pricid']      : '';
		  $did_create['channel']     = isset($did_vars['channel'])     ? $did_vars['channel']     : ''; // pre 2.4

		  $did_dest                  = isset($did_vars['destination']) ? $did_vars['destination'] : '';
		  return core_did_add($did_vars, $did_dest);
    }
  }
}

if (!function_exists('core_did_edit_properties')) {
  function core_did_edit_properties($did_vars) {
    global $db;

    if (!is_array($did_vars)) {
      return false;
    }
    $extension = $db->escapeSimple(isset($did_vars['extension']) ? $did_vars['extension'] : '');
    $cidnum    = $db->escapeSimple(isset($did_vars['cidnum']) ? $did_vars['cidnum'] : '');
    $sql = "";
    foreach ($did_vars as $key => $value) {
      switch ($key) {
        case 'faxexten':
        case 'faxemail':
        case 'answer':
        case 'wait':
        case 'privacyman':
        case 'alertinfo':
        case 'ringing':
        case 'mohclass':
        case 'description':
        case 'grppre':
        case 'delay_answer':
        case 'pricid':
        case 'destination':
          $sql_value = $db->escapeSimple($value);
          $sql .= " `$key` = '$sql_value',";
        break;
      default:
      }
    }
    if ($sql == '') {
      return false;
    }
    $sql = substr($sql,0,(strlen($sql)-1)); //strip off tailing ','
    $sql_update = "UPDATE `incoming` SET"."$sql WHERE `extension` = '$extension' AND `cidnum` = '$cidnum'";
    return sql($sql_update);
  }
}

function sipstation_set_outboundcid($target, $cid) {
  global $db;
  global $astman;
  $exten = explode(',',$target);
  $extension = $exten[1];
  if ($astman) {
	  $astman->database_put("AMPUSER",$extension."/outboundcid","$cid");
  } else {
    freepbx_debug("could not get to manager");
  }
  $extension = $db->escapeSimple($extension); // not reall necessary but ...

  /* This is really bad practice, but until we can get a decent API that is able
     to update extensions without extreme pain, this will have to do.
  */
  sql("UPDATE `users` SET `outboundcid` = '$cid' WHERE `extension` = '$extension'");
}

$json_array['status'] = 'success';
$exten_cids = array();
$cnt = 0;
if (isset($_POST['dids'])) {

  $filter = array('dids' => true,);
  $dids_to_update = array();
  $dids_validation_fail = array();

  $current_dids = sipstation_get_config("xxx", false, $filter);
  $dids = unserialize($_POST['dids']);
  foreach ($dids as $did) {
    $did_parts = unserialize($did);
    if (isset($did_parts['did'])) {
      $update = false;
      //TODO: $failover flag tmp here
      if ($failover && $did_parts['failover'] != $current_dids['dids'][$did_parts['did']]['failover']) {
        if (preg_match('/^\s*([1]{0,1}[2-9]{1}\d{2}[2-9]{1}\d{6})\s*$/', $did_parts['failover'], $match)) {
          $dids_to_update[$did_parts['did']] = $match[1];
          $update = true;
        } else {
          $dids_validation_fail[$did_parts['did']] = $did_parts['failover'];
          continue;
        }
      }
      if ($did_parts['dest'] != 'blank' && $did_parts['dest'] != 'assigned') {
        $did_vars['extension'] = $did_parts['did'];
        $did_vars['destination'] = $did_parts['dest'];
        $did_vars['description'] = $did_parts['desc'];
        core_did_create_update($did_vars);
        if ($did_parts['setcid']) {
          sipstation_set_outboundcid($did_vars['destination'],$did_vars['extension']);
        }
        $update = true;
      }
      if ($update) {
        $cnt++;
      }
    } else {
      $json_array['status'] = _("An error was encountered updating DID destinations");;
    }
  }
  if ($failover) sipstation_put_dids($dids_to_update);
}

if (empty($dids_validation_fail)) {
  $json_array['update_count'] = $cnt;
  $json_array['status_message'] = sprintf(_("Successfully updated or created %s inbound routes for your DIDs"),$cnt);
} elseif ($cnt) {
  $json_array['status'] = 'validation_failures';
  $json_array['update_count'] = $cnt;
  $json_array['validation_failures'] = $dids_validation_fail;
  $validation_failures = count($dids_validation_fail);
  $json_array['status_message'] = sprintf(_("There are %s invalid entries, only updated or created %s inbound routes for your DIDs"),$validation_failures, $cnt);
} else {
  $json_array['status'] = 'validation_failures';
  $json_array['update_count'] = 0;
  $json_array['validation_failures'] = $dids_validation_fail;
  $json_array['status_message'] = sprintf(_("There were %s validation failures on the requested DIDs, no updates performed"),$validation_failures);
}

/*
  if we made changes then we have to set the needsreload status and send back the reload bar to be inserted
*/
if ($cnt) {
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
