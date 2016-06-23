<?php
/* $Id:$ */

/* Copyright (c) 2009 Bandwidth.com
   Licensed for use by active FreePBX.com SIP Trunking Customers (SIPSTATION(tm)). Not licensed to be modified or redistributed in any fashion.
   No guarantees or warranties provided, use at your own risk. See license included with module for more details.
*/

global $db;
global $amp_conf;

if (! function_exists("out")) {
	function out($text) {
		echo $text."<br />";
	}
}
if (! function_exists("outn")) {
	function outn($text) {
		echo $text;
	}
}

/* Delete all occurences of the specified trunk from all routes that may use it
 * this only gets called if the extensions table is still present and this is needed
 */
function __core_routing_trunk_del($trunknum) {
  global $db;

  $sql = "DELETE FROM `extensions` WHERE `application` = 'Macro' AND `context` LIKE 'outrt-%' AND `args` LIKE 'dialout-%,$trunknum,%'";
  $result = $db->query($sql);
}

/* A long standing bug resulted in routes with trunk numbers that had been deleted. Because trunk numbers are recycled (something that
   should be removed in the future), this can result in a new trunk being created and then silently inserted as part of a route that is
   not intended. This will find all phantom trunks and remove them from routes.

   The following code should no longer be required with the new outbound routes stuff. However, it's remotely possible that this module
   could get loaded on an upgrade scenario where core hasn't done a migration yet. Setting this module to depend on core makes for a
   more painful upgrade process so we will simply leave this code in place since it checks for the extensions table and if not found
   it does nothing. If found, it just manipulates that table which could still help in a subsequent migration to the new outbound routes.
*/

/* Get a list of all trunks being used
 */
outn(_("Checking routes for trunks.."));
$sql = "SELECT DISTINCT `args` FROM `extensions` WHERE `context` LIKE 'outrt-%' AND `application` = 'Macro' AND `args` LIKE 'dialout-%'";
$results = $db->getCol($sql);
if(DB::IsError($results)) {
	$results = array();
  out(_("ok"));
} else {
  $trunks = array();
  $trunks_hash = array();
  foreach ($results as $trunk_call) {
    if (preg_match('/^dialout-(?:trunk|enum|dundi),([\d]+),.*$/',$trunk_call,$match) != 1) {
      out(_("error detected"));
      out(sprintf(_("an erroneous entry, %s,  was found in extensions table that should not be there"),$trunk_call));
    } else {
      $trunks_hash[$match[1]] = "OUT_".$match[1];
    }
  }
  $num_trunks = count($trunks_hash);
  out(sprintf(_("found %s"),$num_trunks));

  outn(_("checking for phantoms.."));

  require_once($amp_conf['AMPWEBROOT'].'/admin/modules/core/functions.inc.php');
  $trunks = core_trunks_list(true);
  
  $bad_trunks = array();
  $cnt = 0;
  foreach ($trunks_hash as $trunknum => $globalvar) {
    $bad = true;
    foreach ($trunks as $trunk) {
      if ($trunk['globalvar'] == $globalvar) {
        $bad = false;
        break;
      }
    }
    if ($bad) {
      $cnt++;
      outn("$trunknum..");
      __core_routing_trunk_del($trunknum);
    }
  }
  if ($cnt) {
    out(sprintf(_("removed %s phantoms"),$cnt));
  } else {
    out(_("none"));
  }
}
?>
