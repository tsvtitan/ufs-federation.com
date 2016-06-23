<?php

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Elastix version 2.0.0-31                                             |
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
  $Id: index.php,v 1.1 2010-08-09 10:08:51 Mercy Anchundia manchundia@palosanto.com Exp $ */
//include elastix framework
include_once "libs/paloSantoForm.class.php";
include_once "libs/paloSantoACL.class.php";
include_once "libs/paloSantoJSON.class.php";

function _moduleContent(&$smarty, $module_name) {
    //include module files
    include_once "modules/$module_name/configs/default.conf.php";
    include_once "modules/$module_name/libs/paloSantoRegistration.class.php";

    load_language_module($module_name);

    //global variables
    global $arrConf;
    global $arrConfModule;
    $arrConf = array_merge($arrConf, $arrConfModule);

    //folder path for custom templates
    $base_dir = dirname($_SERVER['SCRIPT_FILENAME']);
    $templates_dir = (isset($arrConf['templates_dir'])) ? $arrConf['templates_dir'] : 'themes';
    $local_templates_dir = "$base_dir/modules/$module_name/" . $templates_dir . '/' . $arrConf['theme'];

    //conexion resource
    $pDB = new paloDB($arrConf['dsn_conn_database']);
    $pDBACL = new paloDB($arrConf['elastix_dsn']['acl']);

    //actions
    $action = getAction();
    $content = "";

    switch ($action) {
        case "save":
            $content = saveRegister($pDB, $arrConf);
            break;
        case "isRegistered":
            $content = isRegisteredServer($pDB, $arrConf);
            break;
        case "saveByAccount":
            $content = saveRegisterByAccount($pDB, $arrConf);
            break;
        case "getDataRegisterServer":
            $content = getDataRegistration($pDB, $arrConf);
            break;
        case "showAboutAs":
            $content = showFormAboutAs($smarty, $module_name, $local_templates_dir, $arrConf);
            break;
        case "showRPMS_Version":
            $content = showFormRPMS_Version($smarty, $module_name, $local_templates_dir, $arrConf);
            break;
        default: // view_form
            $content = viewFormRegister($smarty, $module_name, $local_templates_dir, $pDB, $arrConf, $pDBACL);
            break;
    }
    return $content;
}

function showFormAboutAs($smarty, $module_name, $local_templates_dir, $arrConf) {
    $oForm = new paloForm($smarty, array());

    $smarty->assign("ABOUT_ELASTIX", _tr('About Elastix') . " " . $arrConf['elastix_version']);
    $smarty->assign("ABOUT_ELASTIX2", _tr('About Elastix2'));
    $smarty->assign("ABOUT_ELASTIX_CONTENT", _tr('About Elastix Content'));
    $smarty->assign("ABOUT_CLOSED", _tr('About Elastix Closed'));

    $jsonObject = new PaloSantoJSON();
    $response = array();

    $response['html'] = $oForm->fetchForm("$local_templates_dir/_aboutas.tpl", "", "");
    $response['title'] = _tr('About Elastix') . " " . $arrConf['elastix_version'];

    if ($arrConf['mainTheme'] == "elastixwave" || $arrConf['mainTheme'] == "elastixneo")
        $response['title'] = _tr('About Elastix2');

    $jsonObject->set_message($response);
    return $jsonObject->createJSON();
}

function showFormRPMS_Version($smarty, $module_name, $local_templates_dir, $arrConf) {
    $oForm = new paloForm($smarty, array());

    $smarty->assign("VersionDetails", _tr('VersionDetails'));
    $smarty->assign("VersionPackage", _tr('VersionPackage'));
    $smarty->assign("textMode", _tr('textMode'));
    $smarty->assign("htmlMode", _tr('htmlMode'));

    $jsonObject = new PaloSantoJSON();
    $response = array();
    $response['html'] = $oForm->fetchForm("$local_templates_dir/_rpms_version.tpl", "", "");
    $response['title'] = _tr('VersionPackage');

    $jsonObject->set_message($response);
    return $jsonObject->createJSON();
}

function viewFormRegister($smarty, $module_name, $local_templates_dir, &$pDB, $arrConf, &$pDBACL) {
    $pACL = new paloACL($pDBACL);
    $arrFormRegister = createFieldForm($arrConf);
    $oForm = new paloForm($smarty, $arrFormRegister);

    $smarty->assign("identitykeylbl", _tr("Your Server ID"));
    $smarty->assign("registration", _tr("registration"));
    $smarty->assign("registered_server", _tr("Your Elastix server is registered"));
    $smarty->assign("registration_server", _tr("registration_server"));
    $smarty->assign("Cancel", _tr("Cancel"));
    $smarty->assign("module_name", $module_name);
    $smarty->assign("sending", _tr("Save information and sending data"));
    $smarty->assign("currentyear", date("Y"));
    $smarty->assign("EMAIL", _tr("EMAIL"));
    $smarty->assign("PASSWORD", _tr("PASSWORD"));
    $smarty->assign("USERNAME", _tr("USERNAME"));
    $smarty->assign("DONT_HAVE_ACCOUNT", _tr("Don't have an Elastix Cloud account?"));
    $smarty->assign("ACCESS_ACCOUNT",_tr("Access my account Elastix Cloud"));
    $smarty->assign("ELASTIX_LICENSED", _tr("is licensed under"));
    $smarty->assign("REGISTER_ACTION", _tr("REGISTER_ACTION"));
    $smarty->assign("BY", _tr("by"));

    $user = isset($_SESSION['elastix_user']) ? $_SESSION['elastix_user'] : "";

    // Estado de registrado
    // no      = no registrado
    // yes-all = si registrado y asociado a una cuenta en cloud
    // yes-inc = si registrado pero no tiene una cuenta asociada en cloud    
    $pRegister = new paloSantoRegistration($pDB,$arrConf["url_webservice"]);
    $registered = $pRegister->isRegistered();
    if ($registered=="no")
        $smarty->assign("Activate_registration", _tr("Activate registration"));
    else
        $smarty->assign("Activate_registration", _tr("Update Information"));
    
    $tpl = "$local_templates_dir/_registration.tpl";
    $smarty->assign("alert_message", _tr("alert_message_form"));
    
    if(getParameter("action") == "cloudlogin"){
        $tpl = ($registered=="yes-all") ? "$local_templates_dir/_cloud_registered.tpl" : "$local_templates_dir/_cloud_login.tpl";
        $smarty->assign("alert_message", ($registered=="yes-all") ? _tr("alert_message_registered") : _tr("alert_message_unregister"));
    }
    
    if ($pACL->isUserAdministratorGroup($user))
        $htmlForm = $oForm->fetchForm($tpl, "", "");
    else
        $htmlForm = "<div align='center' style='font-weight: bolder;'>" . _tr("Not user allowed to access this content") . "</div>";

    $jsonObject = new PaloSantoJSON();
    $msgResponse = array();
    $msgResponse['form'] = $htmlForm;
    $msgResponse['registered'] = $registered;
    $msgResponse['msgloading'] = _tr("Getting infomation from Elastix Web Services.");

    $jsonObject->set_status("TRUE");
    $jsonObject->set_message($msgResponse);

    return $jsonObject->createJSON();
}

function isRegisteredServer(&$pDB, $arrConf) {
    $pRegister = new paloSantoRegistration($pDB,$arrConf["url_webservice"]);
    $iRegister = $pRegister->isRegisteredInfo();
   
    $jsonObject = new PaloSantoJSON();
    $jsonObject->set_status("TRUE");
    $jsonObject->set_message($iRegister);
    return $jsonObject->createJSON();
}

function saveRegisterByAccount(&$pDB, $arrConf) {
    $pRegister = new paloSantoRegistration($pDB,$arrConf["url_webservice"]);
    $jsonObject = new PaloSantoJSON();
    $username = trim(getParameter("username"));
    $password = trim(getParameter("password"));

    // proceso de validacion de datos
    if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $username)) {
        $jsonObject->set_status("FALSE");
        $jsonObject->set_message(_tr("* Email: Only format email "));
        return $jsonObject->createJSON();
    }

    $data = array(
        $username, 
        $password);
    
    if (!$pRegister->processSaveDataRegister($data,"byAccount")) {
        $jsonObject->set_status("FALSE");
        $msg = _tr($pRegister->errMsg);
    } else {
        $jsonObject->set_status("TRUE");        
        $msg = _tr("Your information has been saved.");
    }
    
    $iRegister = $pRegister->isRegisteredInfo();
    $iRegister['msg'] = $msg;
    $jsonObject->set_message($iRegister);    
    return $jsonObject->createJSON();
}

// primero se guarda de manera local y luego se llama al webservice 
// donde envia los datos a almacenar y responde con un valor 
// si se almaceno correctamente.
function saveRegister(&$pDB, $arrConf) {
    $pRegister = new paloSantoRegistration($pDB,$arrConf["url_webservice"]);
    $jsonObject = new PaloSantoJSON();
    $contact_name = trim(getParameter("contactNameReg"));
    $email = trim(getParameter("emailReg"));
    $emailConf = trim(getParameter("emailConfReg"));
    $password = trim(getParameter("passwdReg"));
    $passwordConf = trim(getParameter("passwdConfReg"));
    $phone = trim(getParameter("phoneReg"));
    $company = trim(getParameter("companyReg"));
    $address = trim(getParameter("addressReg"));
    $city = trim(getParameter("cityReg"));
    $country = trim(getParameter("countryReg"));
    $str_error = "";

    // proceso de validacion de datos    
    if ($company == '')
        $str_error .= _tr("* Company: text ") . ".\n";
    if (!preg_match("/^.+$/", $country))
        $str_error .= _tr("* Country: Selected a country ") . ".\n";
    if ($city == '')
        $str_error .= _tr("* City: text ") . ".\n";
    if (!preg_match("/^[0-9\+\-]+$/", $phone))
        $str_error .= _tr("* Phone: number ") . ".\n";
    if ($address == '')
        $str_error .= _tr("* Address: text ") . ".\n";
    if ($contact_name == '')
        $str_error .= _tr("* Contact Name: Only text ") . ".\n";

    if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email))
        $str_error .= _tr("* Email: Only format email ") . ".\n";
    if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $emailConf))
        $str_error .= _tr("* Email Confirm: Only format email ") . ".\n";
    if ($email != $emailConf)
        $str_error .= _tr("* Emails do not match ")  . ".\n";
    
    if(!$pRegister->isStrongPassword($password))
        $str_error .= _tr("* Password: Must be at least 10 ") . ".\n";
    if(!$pRegister->isStrongPassword($passwordConf))
        $str_error .= _tr("* Password Confirm: Must be at least 10 ") . ".\n";
    if ($password != $passwordConf)
        $str_error .= _tr("* Passwords do not match ")  . ".\n";
    
    if ($str_error !== "") {
        $jsonObject->set_status("FALSE");
        $jsonObject->set_error(_tr("Please fill the correct values in fields: ") . "\n\n" . $str_error);
        return $jsonObject->createJSON();
    }

    $data = array(
        $password, $contact_name,
        $email, $phone, $company, 
        $address, $city, $country);

    if (!$pRegister->processSaveDataRegister($data,"newAccount")) {
        $jsonObject->set_status("FALSE");
        $msg = _tr($pRegister->errMsg);
    } else {
        $jsonObject->set_status("TRUE");        
        $msg = _tr("Your information has been saved.");
    }
    
    $iRegister = $pRegister->isRegisteredInfo();
    $iRegister['msg'] = $msg;
    $jsonObject->set_message($iRegister);    
    return $jsonObject->createJSON();
}

function getDataRegistration(&$pDB, $arrConf) {
    $pRegister = new paloSantoRegistration($pDB,$arrConf["url_webservice"]);
    $data = $pRegister->processGetDataRegister();
    
    $jsonObject = new PaloSantoJSON();
    $jsonObject->set_error(_tr($pRegister->errMsg));
    $jsonObject->set_status( ($data) ? "TRUE":"FALSE" );
    $jsonObject->set_message($data);
    
    return $jsonObject->createJSON();
}

function createFieldForm($arrConf) {
    $arrFields = array(
        "contactNameReg" => array("LABEL" => _tr("Contact Name"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "contactNameReg", "style" => "width: 230px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => "",
            "EDITABLE" => "",
        ),
        "emailReg" => array("LABEL" => _tr("Email"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "emailReg", "style" => "width: 230px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "email",
            "VALIDATION_EXTRA_PARAM" => "",
            "EDITABLE" => "",
        ),
        "emailConfReg" => array("LABEL" => _tr("Email Confirm"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "emailConfReg", "style" => "width: 230px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "email",
            "VALIDATION_EXTRA_PARAM" => "",
            "EDITABLE" => "",
        ),
        "passwdReg" => array("LABEL" => _tr("Password"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "PASSWORD",
            "INPUT_EXTRA_PARAM" => array("id" => "passwdReg", "style" => "width: 230px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => "",
            "EDITABLE" => "",
        ),
        "passwdConfReg" => array("LABEL" => _tr("Password Confirm"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "PASSWORD",
            "INPUT_EXTRA_PARAM" => array("id" => "passwdConfReg", "style" => "width: 230px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => "",
            "EDITABLE" => "",
        ),
        "phoneReg" => array("LABEL" => _tr("Phone"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "phoneReg", "style" => "width: 120px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "ereg",
            "VALIDATION_EXTRA_PARAM" => "^[0-9\(\)\+-]+\d$"
        ),
        "countryReg" => array("LABEL" => _tr("Country"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "SELECT",
            "INPUT_EXTRA_PARAM" => $arrConf['countries'],
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => ""
        ),
        "companyReg" => array("LABEL" => _tr("Company"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "companyReg", "style" => "width: 335px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => "",
            "EDITABLE" => "",
        ),
        "addressReg" => array("LABEL" => _tr("Address"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "addressReg", "style" => "width: 335px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => "",
        ),
        "cityReg" => array("LABEL" => _tr("City"),
            "REQUIRED" => "yes",
            "INPUT_TYPE" => "TEXT",
            "INPUT_EXTRA_PARAM" => array("id" => "cityReg", "style" => "width: 120px; margin: 2px 0px;"),
            "VALIDATION_TYPE" => "text",
            "VALIDATION_EXTRA_PARAM" => ""
        ),
    );
    return $arrFields;
}

function getAction() {
    if (getParameter("save_new")) //Get parameter by POST (submit)
        return "save_new";
    else if (getParameter("action") == "saveregister")
        return "save";
    else if (getParameter("action") == "isRegistered") 
        return "isRegistered";
    else if (getParameter("action") == "savebyaccount")
        return "saveByAccount";
    else if (getParameter("action") == "getDataRegisterServer")
        return "getDataRegisterServer";
    else if (getParameter("action") == "showAboutAs")
        return "showAboutAs";
    else if (getParameter("action") == "showRPMS_Version")
        return "showRPMS_Version";
    else
        return "report"; //cancel
}

?>