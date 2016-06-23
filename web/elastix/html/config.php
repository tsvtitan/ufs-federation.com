<?php

// Este script es un wrapper para que freePBX funcione sin mayores modificaciones
// Ha sido creado debido a que freePBX tiene muchos links quemados que referencian
// al script config.php
if(isset($_GET['display'])){
    if($_GET['display']=="fop2users" || $_GET['display']=="fop2groups" || $_GET['display']=="fop2buttons")
	$_GET['menu'] = $_GET['display'];
    else
	$_GET['menu'] = "pbxadmin";
}else
    $_GET['menu'] = "pbxadmin";
include "/var/www/html/index.php";

?>
