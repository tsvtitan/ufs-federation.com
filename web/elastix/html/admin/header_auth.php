<?php

if (isset($_REQUEST['logout'])) {
	// logging out..
	// remove the user
	unset($_SESSION['AMP_user']);
	// flag to prompt for pw again
	$_SESSION['logout'] = true; 

	showview('loggedout');
	exit;
}

$documentRoot = $_SERVER['DOCUMENT_ROOT'];
if(file_exists("$documentRoot/modules/sec_advanced_settings/libs/paloSantoChangePassword.class.php")){
    include_once "$documentRoot/modules/sec_advanced_settings/libs/paloSantoChangePassword.class.php";
    include_once("$documentRoot/libs/misc.lib.php");
    require_once "$documentRoot/configs/default.conf.php";
    global $arrConf;

    $pAdvancedSecuritySettings = new paloSantoAdvancedSecuritySettings($arrConf);
    $fromDirectAccess = (isset($_SERVER['REQUEST_URI']) && preg_match("/\/admin\/config.php/",$_SERVER['REQUEST_URI']))?true:false;

    if(!$pAdvancedSecuritySettings->isActivatedFreePBXFrontend() && $fromDirectAccess){
	unset($_SESSION['AMP_user']);
	$_SESSION['logout'] = true;

	$lang = get_language("$documentRoot/");
	$lang_file="$documentRoot/modules/sec_advanced_settings/lang/$lang.lang";
	if (file_exists("$lang_file")) include_once "$lang_file";
	else include_once "$documentRoot/modules/sec_advanced_settings/lang/en.lang";
	global $arrLangModule;

	$advice = isset($arrLangModule['Unauthorized'])?$arrLangModule['Unauthorized']:'Unauthorized';
	$msg1   = isset($arrLangModule['You are not authorized to access this page.'])?$arrLangModule['You are not authorized to access this page.']:'You are not authorized to access this page.';
	$msg2   = isset($arrLangModule["Enable direct access (Non-embedded) to FreePBX in \"Security >> Advanced Security Settings\" menu."])?$arrLangModule["Enable direct access (Non-embedded) to FreePBX in \"Security >> Advanced Security Settings\" menu."]:"Enable direct access (Non-embedded) to FreePBX in \"Security >> Advanced Security Settings\" menu.";
	$title  = isset($arrLangModule['Advice'])?$arrLangModule['Advice']:'Advice';

	$template['content']['msg']   =  "<br /><b style='font-size:1.5em;'>$advice</b> <p>$msg1<br/>$msg2</p>";
	$template['content']['title'] = $title;
	$template['content']['theme'] = $arrConf['mainTheme'];
	showview("elastix_advice",$template);
	exit;
    }
}

switch (strtolower($amp_conf['AUTHTYPE'])) {
	case 'database':
		if (!isset($_SESSION['AMP_user']) && isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !isset($_REQUEST['logout'])) {
			if (isset($_SESSION['logout']) && $_SESSION['logout']) {
				// workaround for HTTP-auth - just tried to logout, don't allow a log in (with the same credentials)
				unset($_SESSION['logout']);
				// afterwards, this falls through to the !AMP_user check below, and sends 401 header, which causes the browser to re-prompt the user
			} else {
				// not logged in, and have provided a user/pass
				$_SESSION['AMP_user'] = new ampuser($_SERVER['PHP_AUTH_USER']);
				
				if (!$_SESSION['AMP_user']->checkPassword(sha1($_SERVER['PHP_AUTH_PW']))) {
					// password failed and admin user fall-back failed
					unset($_SESSION['AMP_user']);
				} // else, succesfully logged in
			} 
		}

		if (!isset($_SESSION['AMP_user'])) {
			// not logged in, send headers
			@header('WWW-Authenticate: Basic realm="FreePBX '._('Administration').'"');
			@header('HTTP/1.0 401 Unauthorized');
			showview("unauthorized");
			exit;
		}
	break;
	case 'webserver':
		// handler for apache doing authentication
		if ((!isset($_SESSION['AMP_user']) || ($_SESSION['AMP_user']->username != $_SERVER['PHP_AUTH_USER'])) && !isset($_REQUEST['logout'])) {
			// not logged in, or username has changed;  and not trying to log out
			
			if (isset($_SESSION['logout']) && $_SESSION['logout']) {
				// workaround for HTTP-auth - just tried to logout, don't allow a log in (with the same credentials)
				unset($_SESSION['logout']);
				// afterwards, this falls through to the !AMP_user check below, and sends 401 header, which causes the browser to re-prompt the user
			} else {
				$_SESSION['AMP_user'] = new ampuser($_SERVER['PHP_AUTH_USER']);
				
				if ($_SESSION['AMP_user']->username == $amp_conf['AMPDBUSER']) {
					// admin user, grant full access
					$_SESSION['AMP_user']->setAdmin();
				}
			}
		}

		if (!isset($_SESSION['AMP_user'])) {
			// not logged in, send headers
			@header('WWW-Authenticate: Basic realm="FreePBX '._('Administration').'"');
			@header('HTTP/1.0 401 Unauthorized');
			showview("unauthorized");
			exit;
		}
	case 'none':
	default:
		if (!isset($_SESSION['AMP_user'])) {
			$_SESSION['AMP_user'] = new ampuser($amp_conf['AMPDBUSER']);
			$_SESSION['AMP_user']->setAdmin();
		}
	break;
}

?>
