<?php
//This file is part of FreePBX.
//
//    FreePBX is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 2 of the License, or
//    (at your option) any later version.
//
//    FreePBX is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with FreePBX.  If not, see <http://www.gnu.org/licenses/>.
//
// Copyright (C) 2005 qldrob
//
// TODO, is this needed...?
// is this global...? what if we include this files
// from a function...?

global $astman;

// Unconditional Call Forwarding
$fcc = new featurecode('callforward', 'cfon');
$fcc->delete();
unset($fcc);

$fcc = new featurecode('callforward', 'cfoff');
$fcc->delete();
unset($fcc);

$fcc = new featurecode('callforward', 'cfoff_any');
$fcc->delete();
unset($fcc);

// Call Forward on Busy
$fcc = new featurecode('callforward', 'cfbon');
$fcc->delete();
unset($fcc);

$fcc = new featurecode('callforward', 'cfboff');
$fcc->delete();
unset($fcc);

$fcc = new featurecode('callforward', 'cfboff_any');
$fcc->delete();
unset($fcc);

// Call Forward on No Answer/Unavailable (i.e. phone not registered)
$fcc = new featurecode('callforward', 'cfuon');
$fcc->delete();
unset($fcc);

$fcc = new featurecode('callforward', 'cfuoff');
$fcc->delete();
unset($fcc);

$fcc = new featurecode('callforward', 'cf_toggle');
$fcc->delete();
unset($fcc);

// remove all Call Forward options in effect on extensions
if ($astman) {
	$astman->database_deltree('CF');
	$astman->database_deltree('CFB');
	$astman->database_deltree('CFU');
} else {
	fatal("Cannot connect to Asterisk Manager with ".$amp_conf["AMPMGRUSER"]."/".$amp_conf["AMPMGRPASS"]);
}
?>
