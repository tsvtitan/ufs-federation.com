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
  $Id: index.php,v 1.1.1.1 2007/07/06 21:31:56 gcarrillo Exp $ */

function _moduleContent(&$smarty, $module_name)
{

    include_once "libs/paloSantoDB.class.php";
    include_once "libs/paloSantoForm.class.php";

 //include module files
    include_once "modules/$module_name/configs/default.conf.php";
    //include file language agree to elastix configuration
    //if file language not exists, then include language by default (en)
    $lang=get_language();
    $base_dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $lang_file="modules/$module_name/lang/$lang.lang";
    if (file_exists("$base_dir/$lang_file")) include_once "$lang_file";
    else include_once "modules/$module_name/lang/en.lang";


    //global variables
    global $arrConf;
    global $arrConfModule;
    global $arrLang;
    global $arrLangModule;
    $arrConf = array_merge($arrConf,$arrConfModule);
    $arrLang = array_merge($arrLang,$arrLangModule);

    //folder path for custom templates
    $templates_dir=(isset($arrConf['templates_dir']))?$arrConf['templates_dir']:'themes';
    $local_templates_dir="$base_dir/modules/$module_name/".$templates_dir.'/'.$arrConf['theme'];
    

    $contenido='';
    $msgError='';

    $pDBSetting = new paloDB($arrConf['dsn_conn_database']);
 
    $arrForm  = array("fax_master"       => array("LABEL"                   => $arrLang["Fax Master Email"],
                                                    "REQUIRED"               => "yes",
                                                    "EDITABLE"               => "yes",
                                                    "INPUT_TYPE"             => "TEXT",
                                                    "INPUT_EXTRA_PARAM"      => "",
                                                    "VALIDATION_TYPE"        => "ereg",
                                                    "VALIDATION_EXTRA_PARAM" => "^[a-zA-Z0-9_.\-]+@[a-zA-Z0-9_.\-]+\.[a-zA-Z0-9_.\-]+$"),
                     );


    $oForm = new paloForm($smarty, $arrForm);
    $oForm->setEditMode();
    //obtener el valor de la tarifa por defecto
    $arrDefault['fax_master']=get_key_settings($pDBSetting,"fax_master");
    $smarty->assign("FAXMASTER_MSG", $arrLang["Write the email address which will receive the notifications of received messages, errors and activity summary of the Fax Server"]);

    $smarty->assign("icon", "/modules/$module_name/images/fax_fax_master.png"); 
    $smarty->assign("APPLY_CHANGES", $arrLang["Save"]);
    $smarty->assign("REQUIRED_FIELD", $arrLang["Required field"]);
    $strReturn = $oForm->fetchForm("$local_templates_dir/fax_master.tpl", $arrLang["Fax Master Configuration"], $arrDefault);

    if(isset($_POST['save_default'])) {
        $oForm = new paloForm($smarty, $arrForm);
        $arrDefault['fax_master'] = $_POST['fax_master'];
        $bMostrarError=TRUE;
        if($oForm->validateForm($_POST)) {
            $bMostrarError=FALSE;
            $bValido=set_key_settings($pDBSetting,'fax_master',$arrDefault['fax_master']);
           
            if(!$bValido) {
                echo $arrLang["Error when saving Fax Master"];
            } else {
                //guardar en /etc/postfix/virtual
                $bExito=modificar_archivos_mail($arrDefault['fax_master'],$error);
                if ($bExito){
                    header("Location: index.php?menu=faxmaster");
                }else{
                    $mensaje=$error;
                    $bMostrarError=TRUE;
                }
            }
        }else
            $mensaje=$arrLang["Value for Fax Master is not valid"];
        if ($bMostrarError) {
            // Error
            $smarty->assign("mb_title", $arrLang["Validation Error"]);
            $smarty->assign("mb_message", $mensaje);

            $smarty->assign("APPLY_CHANGES", $arrLang["Save"]);
            $smarty->assign("REQUIRED_FIELD", $arrLang["Required field"]);
            $strReturn = $oForm->fetchForm("$local_templates_dir/fax_master.tpl", $arrLang["Fax Master Configuration"], $arrDefault);
        }
    }

    return $strReturn;
}

function modificar_archivos_mail($email, &$error)
{
    $output = $retval = NULL;
    exec('/usr/bin/elastix-helper faxconfig --faxmaster 2>&1', $output, $retval);
    if (is_array($output)) $error = implode('<br/>', $output);
    return ($retval == 0);
}
?>
