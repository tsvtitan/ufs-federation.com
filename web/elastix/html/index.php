<?php 
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Elastix version 0.5                                                  |
  | http://www.elastix.org                                               |
  +----------------------------------------------------------------------+
  | Copyright (c) 2006 Palosanto Solutions S. A.                         |
  +----------------------------------------------------------------------+
  | Cdla. Nueva Kennedy Calle E 222 y 9na. Este                          |
  | Telfs. 2283-268, 2294-440, 2284-356                                  |
  | Guayaquil - Ecuador                                                  |
  | http://www.palosanto.com                                             |
  +----------------------------------------------------------------------+
  | The contents of this file are subject to the General Public License  |
  | (GPL) Version 2 (the "License"); you may not use this file except in |
  | compliance with the License. You may obtain a copy of the License at |
  | http://www.opensource.org/licenses/gpl-license.php                   |
  |                                                                      |
  | Software distributed under the License is distributed on an "AS IS"  |
  | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
  | the License for the specific language governing rights and           |
  | limitations under the License.                                       |
  +----------------------------------------------------------------------+
  | The Original Code is: Elastix Open Source.                           |
  | The Initial Developer of the Original Code is PaloSanto Solutions    |
  +----------------------------------------------------------------------+
  $Id: index.php,v 1.3 2007/07/17 00:03:42 gcarrillo Exp $ */

function spl_elastix_class_autoload($sNombreClase)
{
    if (!preg_match('/^\w+$/', $sNombreClase)) return;

    $sNombreBase = $sNombreClase.'.class.php';
    foreach (explode(':', ini_get('include_path')) as $sDirInclude) {
        if (file_exists($sDirInclude.'/'.$sNombreBase)) {
            require_once($sNombreBase);
            return;
        }
    }
}
spl_autoload_register('spl_elastix_class_autoload');

// Agregar directorio libs de script a la lista de rutas a buscar para require()
ini_set('include_path', dirname($_SERVER['SCRIPT_FILENAME'])."/libs:".ini_get('include_path'));

include_once("libs/misc.lib.php");
include_once "configs/default.conf.php";
include_once "libs/paloSantoDB.class.php";
include_once "libs/paloSantoMenu.class.php";
include_once("libs/paloSantoACL.class.php");// Don activate unless you know what you are doing. Too risky!

load_default_timezone();

session_name("elastixSession");
session_start();

if(isset($_GET['logout']) && $_GET['logout']=='yes') {
    $user = isset($_SESSION['elastix_user'])?$_SESSION['elastix_user']:"unknown";
    writeLOG("audit.log", "LOGOUT $user: Web Interface logout successful. Accepted logout for $user from $_SERVER[REMOTE_ADDR].");
    session_destroy();
    session_name("elastixSession");
    session_start();
    header("Location: index.php");
    exit;
}

//cargar el archivo de idioma
load_language();
$lang = get_language();
if(file_exists("langmenus/$lang.lang")){
    include_once "langmenus/$lang.lang";
    global $arrLangMenu;
    global $arrLang;
    $arrLang = array_merge($arrLang,$arrLangMenu);
}

$pdbACL = new paloDB($arrConf['elastix_dsn']['acl']);
$pACL = new paloACL($pdbACL);

if(!empty($pACL->errMsg)) {
    echo "ERROR DE DB: $pACL->errMsg <br>";
}

// Load smarty
$smarty = getSmarty($arrConf['mainTheme']);

//- 1) SUBMIT. Si se hizo submit en el formulario de ingreso
//-            autentico al usuario y lo ingreso a la sesion

if(isset($_POST['submit_login']) and !empty($_POST['input_user'])) {
    $pass_md5 = md5($_POST['input_pass']);
    if($pACL->authenticateUser($_POST['input_user'], $pass_md5)) {
        session_regenerate_id(TRUE);

        $_SESSION['elastix_user'] = $_POST['input_user'];
        $_SESSION['elastix_pass'] = $pass_md5;
        header("Location: index.php");
        writeLOG("audit.log", "LOGIN $_POST[input_user]: Web Interface login successful. Accepted password for $_POST[input_user] from $_SERVER[REMOTE_ADDR].");
        exit;
    } else {
        $user = urlencode(substr($_POST['input_user'],0,20));
        if(!$pACL->getIdUser($_POST['input_user'])) // not exists user?
            writeLOG("audit.log", "LOGIN $user: Authentication Failure to Web Interface login. Invalid user $user from $_SERVER[REMOTE_ADDR].");
        else
            writeLOG("audit.log", "LOGIN $user: Authentication Failure to Web Interface login. Failed password for $user from $_SERVER[REMOTE_ADDR].");
        // Debo hacer algo aquí?
    }
}

// 2) Autentico usuario
if (isset($_SESSION['elastix_user']) && 
    isset($_SESSION['elastix_pass']) && 
    $pACL->authenticateUser($_SESSION['elastix_user'], $_SESSION['elastix_pass'])) {
    
    $idUser = $pACL->getIdUser($_SESSION['elastix_user']);
    $pMenu = new paloMenu($arrConf['elastix_dsn']['menu']);
    $arrMenuFiltered = $pMenu->filterAuthorizedMenus($idUser);

    verifyTemplate_vm_email(); // para cambiar el template del email ue se envia al recibir un voicemail

    //traducir el menu al idioma correspondiente
    foreach($arrMenuFiltered as $idMenu=>$arrMenuItem) {
        $arrMenuFiltered[$idMenu]['Name'] = _tr($arrMenuItem['Name']);
    }

    $smarty->assign("THEMENAME", $arrConf['mainTheme']);

    /*agregado para register*/
    
    $smarty->assign("Register", _tr("Register"));
    $smarty->assign("lblRegisterCm", _tr("Register"));
    $smarty->assign("lblRegisteredCm", _tr("Registered"));
    if(!is_file("/etc/elastix.key")){
        $smarty->assign("Registered", _tr("Register"));
    	$smarty->assign("ColorRegister", "#FF0000"); 
    } else {
        $smarty->assign("Registered", _tr("Registered"));
    	$smarty->assign("ColorRegister", "#008800");
    }

    $smarty->assign("md_message_title", _tr('md_message_title'));
    $sCurYear = date('Y');
    if ($sCurYear < '2013') $sCurYear = '2013';
    $smarty->assign("currentyear", $sCurYear);
    $smarty->assign("ABOUT_ELASTIX_CONTENT", _tr('About Elastix Content'));
    $smarty->assign("ABOUT_CLOSED", _tr('About Elastix Closed'));
    $smarty->assign("LOGOUT", _tr('Logout'));
    $smarty->assign("VersionDetails", _tr('VersionDetails'));
    $smarty->assign("VersionPackage", _tr('VersionPackage'));
	$smarty->assign("textMode", _tr('textMode'));
    $smarty->assign("htmlMode", _tr('htmlMode'));
	$smarty->assign("AMOUNT_CHARACTERS", _tr("characters left"));
	$smarty->assign("SAVE_NOTE", _tr("Save Note"));
	$smarty->assign("MSG_SAVE_NOTE", _tr("Saving Note"));
	$smarty->assign("MSG_GET_NOTE", _tr("Loading Note"));
	$smarty->assign("LBL_NO_STICKY", _tr("Click here to leave a note."));
    $smarty->assign("ABOUT_ELASTIX", _tr('About Elastix')." ".$arrConf['elastix_version']);

    $selectedMenu = getParameter('menu');

    /* El módulo _elastixutils sirve para contener las utilidades json que
     * atienden requerimientos de varios widgets de la interfaz Elastix. Todo
     * requerimiento nuevo que no sea un módulo debe de agregarse aquí */
    // TODO: agregar manera de rutear _elastixutils a través de paloSantoNavigation
    if (!is_null($selectedMenu) && $selectedMenu == '_elastixutils' && 
        file_exists('modules/_elastixutils/index.php')) {
        
        // Cargar las configuraciones para el módulo elegido
        if (file_exists('modules/_elastixutils/configs/default.conf.php')) {
            require_once 'modules/_elastixutils/configs/default.conf.php';

            global $arrConf;
            global $arrConfModule;
            $arrConf = array_merge($arrConf, $arrConfModule);
        }
        
        // Cargar las traducciones para el módulo elegido
        load_language_module($selectedMenu);
        
        require_once 'modules/_elastixutils/index.php';
        echo _moduleContent($smarty, $selectedMenu);
        return;
    }

    /* El módulo pbxadmin que integra a FreePBX no construye enlaces con 
     * parámetros menu, ni con config.php en todos los casos. Por lo tanto, los
     * usos sucesivos de enlaces en FreePBX embebido requiren recordar que se
     * sirven a través de pbxadmin. */
    if (empty($selectedMenu) && !empty($_SESSION['menu']))
        $selectedMenu = $_SESSION['menu'];

    // Inicializa el objeto palosanto navigation
    $oPn = new paloSantoNavigation($arrMenuFiltered, $smarty, $selectedMenu);
    $selectedMenu = $oPn->getSelectedModule();
    $_SESSION['menu'] = $selectedMenu;

    // Guardar historial de la navegación
    // TODO: también para rawmode=yes ?
    putMenuAsHistory($pdbACL, $pACL, $idUser, $selectedMenu);

    // Obtener contenido del módulo, si usuario está autorizado a él
    $bModuleAuthorized = $pACL->isUserAuthorizedById($idUser, "access", $selectedMenu);
    $sModuleContent = ($bModuleAuthorized) ? $oPn->showContent() : '';    
    
    // rawmode es un modo de operacion que pasa directamente a la pantalla la salida
    // del modulo. Esto es util en ciertos casos.
    $rawmode = getParameter("rawmode");
    if(isset($rawmode) && $rawmode=='yes') {
        echo $sModuleContent;
    } else {
        $oPn->renderMenuTemplates();

        if (file_exists('themes/'.$arrConf['mainTheme'].'/themesetup.php')) {
        	require_once('themes/'.$arrConf['mainTheme'].'/themesetup.php');
            themeSetup($smarty, $selectedMenu, $pdbACL, $pACL, $idUser);
        }

        // Autorizacion
        if ($bModuleAuthorized) {
            $smarty->assign("CONTENT", $sModuleContent);
            $smarty->assign('MENU', (count($arrMenuFiltered) > 0) 
                ? $smarty->fetch("_common/_menu.tpl") 
                : _tr('No modules'));
        }
        $smarty->display("_common/index.tpl");
    }
} else {
	$rawmode = getParameter("rawmode");
    if(isset($rawmode) && $rawmode=='yes'){
        include_once "libs/paloSantoJSON.class.php";
        $jsonObject = new PaloSantoJSON();
        $jsonObject->set_status("ERROR_SESSION");
        $jsonObject->set_error(_tr("Your session has expired. If you want to do a login please press the button 'Accept'."));
        $jsonObject->set_message(null);
        Header('Content-Type: application/json');
        echo $jsonObject->createJSON();
    }
    else{
        $oPn = new paloSantoNavigation(array(), $smarty);
		$oPn->putHEAD_JQUERY_HTML();
		$smarty->assign("THEMENAME", $arrConf['mainTheme']);
		$smarty->assign("currentyear",date("Y"));
		$smarty->assign("PAGE_NAME", _tr('Login page'));
		$smarty->assign("WELCOME", _tr('Welcome to Elastix'));
		$smarty->assign("ENTER_USER_PASSWORD", _tr('Please enter your username and password'));
		$smarty->assign("USERNAME", _tr('Username'));
		$smarty->assign("PASSWORD", _tr('Password'));
		$smarty->assign("SUBMIT", _tr('Submit'));

		$smarty->display("_common/login.tpl");
	}
}
?>