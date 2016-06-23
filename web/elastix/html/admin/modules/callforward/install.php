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
//for translation only
if (false) {
_("Call Forward");
_("Call Forward All Activate");
_("Call Forward All Deactivate");
_("Call Forward All Prompting Deactivate");
_("Call Forward Busy Activate");
_("Call Forward Busy Deactivate");
_("Call Forward Busy Prompting Deactivate");
_("Call Forward No Answer/Unavailable Activate");
_("Call Forward No Answer/Unavailable Deactivate");
_("Call Forward Toggle");
}

// Unconditional Call Forwarding
$fcc = new featurecode('callforward', 'cfon');
$fcc->setDescription('Call Forward All Activate');
$fcc->setDefault('*72');
$fcc->update();
unset($fcc);

$fcc = new featurecode('callforward', 'cfoff');
$fcc->setDescription('Call Forward All Deactivate');
$fcc->setDefault('*73');
$fcc->update();
unset($fcc);

$fcc = new featurecode('callforward', 'cfoff_any');
$fcc->setDescription('Call Forward All Prompting Deactivate');
$fcc->setDefault('*74');
$fcc->update();
unset($fcc);

// Call Forward on Busy
$fcc = new featurecode('callforward', 'cfbon');
$fcc->setDescription('Call Forward Busy Activate');
$fcc->setDefault('*90');
$fcc->update();
unset($fcc);

$fcc = new featurecode('callforward', 'cfboff');
$fcc->setDescription('Call Forward Busy Deactivate');
$fcc->setDefault('*91');
$fcc->update();
unset($fcc);

$fcc = new featurecode('callforward', 'cfboff_any');
$fcc->setDescription('Call Forward Busy Prompting Deactivate');
$fcc->setDefault('*92');
$fcc->update();
unset($fcc);

// Call Forward on No Answer/Unavailable (i.e. phone not registered)
$fcc = new featurecode('callforward', 'cfuon');
$fcc->setDescription('Call Forward No Answer/Unavailable Activate');
$fcc->setDefault('*52');
$fcc->update();
unset($fcc);

$fcc = new featurecode('callforward', 'cfuoff');
$fcc->setDescription('Call Forward No Answer/Unavailable Deactivate');
$fcc->setDefault('*53');
$fcc->update();
unset($fcc);

$fcc = new featurecode('callforward', 'cf_toggle');
$fcc->setDescription('Call Forward Toggle');
$fcc->setDefault('*740');
$fcc->update();
unset($fcc);

?>
