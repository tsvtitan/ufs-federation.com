<?php /* $Id: panel.php 6976 2008-10-14 02:13:55Z p_lindheimer $ */
//Copyright (C) 2004 Coalescent Systems Inc. (info@coalescentsystems.ca)
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$quietmode = isset($_REQUEST['quietmode'])?$_REQUEST['quietmode']:'';

include 'header.php';

$title=_("FreePBX: Flash Operator Panel");
$message=_("Flash Operator Panel");

$template['amp_conf'] = &$amp_conf;

showview('panel', array('title'=>$title, 'deptname' => $_SESSION["AMP_user"]->_deptname, 'amp_conf' => &$amp_conf));
?>
