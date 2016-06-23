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
// Copyright (C) 2005 mheydon1973
//
//for translation only
if (false) {
_("Call Waiting");
_("Call Waiting - Activate");
_("Call Waiting - Deactivate");
}

// Register FeatureCode - Activate
$fcc = new featurecode('callwaiting', 'cwon');
$fcc->setDescription('Call Waiting - Activate');
$fcc->setDefault('*70');
$fcc->update();
unset($fcc);

// Register FeatureCode - Deactivate
$fcc = new featurecode('callwaiting', 'cwoff');
$fcc->setDescription('Call Waiting - Deactivate');
$fcc->setDefault('*71');
$fcc->update();
unset($fcc);	
?>
