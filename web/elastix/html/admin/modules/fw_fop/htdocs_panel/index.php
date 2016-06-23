<?php

function parse_amportal_conf($filename) {
	$file = file($filename);
	foreach ($file as $line) {
		if (preg_match("/^\s*([a-zA-Z0-9_]+)=([a-zA-Z0-9 .&-@=_!<>\"\']+)\s*$/",$line,$matches)) {
			$conf[ $matches[1] ] = $matches[2];
		}
	}
	return $conf;
}

function showview($viewname, $parameters = false) {
    $documentRoot = $_SERVER["DOCUMENT_ROOT"];
    if (is_array($parameters)) {
           extract($parameters);
    }
    $viewname = str_replace('..','.',$viewname); // protect against going to subdirectories
    if (file_exists("$documentRoot/admin/views/".$viewname.'.php')) {
           include("$documentRoot/admin/views/".$viewname.'.php');
    }
}

/*******Empieza validación para saber si el usuario tiene permisos o no para ingresar al fop********/
$documentRoot = $_SERVER["DOCUMENT_ROOT"];
include_once "$documentRoot/libs/paloSantoDB.class.php";
include_once "$documentRoot/libs/paloSantoACL.class.php";

session_name("elastixSession");
session_start();
$elastix_user = (isset($_SESSION["elastix_user"]))?$_SESSION["elastix_user"]:null;
$pDB = new paloDB("sqlite3:////var/www/db/acl.db");
$pACL = new paloACL($pDB);
if(!$pACL->isUserAuthorized($elastix_user,"access","fop")){
       include_once "$documentRoot/libs/misc.lib.php";
       include_once "$documentRoot/configs/default.conf.php";
       $lang = get_language("$documentRoot/");
       if(file_exists("$documentRoot/lang/$lang.lang"))
           include_once "$documentRoot/lang/$lang.lang";
       else
           include_once "$documentRoot/lang/en.lang";
       global $arrConf;
       global $arrLang;
       $advice = isset($arrLang["Unauthorized"])?$arrLang["Unauthorized"]:"Unauthorized";
       $msg1 = isset($arrLang['You are not authorized to access this page.'])?$arrLang['You are not authorized to access this page.']:'You are not authorized to access this page.';
       $msg2 = isset($arrLang['You have not permissions to access to Flash Operator Panel. Please contact your administrator.'])?$arrLang['You have not permissions to access to Flash Operator Panel. Please contact your administrator.']:'You have not permissions to access to Flash Operator Panel. Please contact your administrator.';
       $title  = isset($arrLang['Advice'])?$arrLang['Advice']:'Advice';
       $template['content']['msg']   =  "<br /><b style='font-size:1.5em;'>$advice</b> <p>$msg1<br/>$msg2</p>";
        $template['content']['title'] = $title;
        $template['content']['theme'] = $arrConf['mainTheme'];
       showview("elastix_advice",$template);
       exit;
}
/**********Fin de la validación******************************************************************/

$request = $_SERVER['REQUEST_URI'];

if(preg_match("/variables\.txt/",$request)){
     echo file_get_contents("/var/www/html/panel/variables.txt");
     exit;
}

if(preg_match("/operator_panel\.swf/",$request)){
     echo file_get_contents("/var/www/html/panel/flash/operator_panel.swf");
     exit;
}

$amp_conf = parse_amportal_conf("/etc/amportal.conf");

if ($amp_conf["AMPWEBADDRESS"] == "")
	{$amp_conf["AMPWEBADDRESS"] = $_SERVER["HTTP_HOST"];}
	
if ($_SERVER["HTTP_HOST"] != $amp_conf["AMPWEBADDRESS"]) {
	$proto = ((isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) ? "https" : "http");
	header("Location: ".$proto."://".$amp_conf["AMPWEBADDRESS"].$_SERVER["REQUEST_URI"]);
	exit;
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Flash Operator Panel</title>
<style>
<!--
html,body {
	margin: 0;
	padding: 0;
	height: 100%;
	width: 100%;
}

-->
</style>
</head>
<body bgcolor="#ffffff">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="100%" height="100%" id="operator_panel" align="left">
<param name="WMode" value="transparent"/>
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="flash/operator_panel.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<param name="scale" value="exactfit" />
<embed src="flash/operator_panel.swf" quality="high" scale="exactfit" bgcolor="#ffffff" width="100%" height="100%" name="operator_panel" align="left" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" />
</object>
</body>
</html>
