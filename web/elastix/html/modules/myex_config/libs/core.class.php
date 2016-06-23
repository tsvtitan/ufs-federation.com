<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Elastix version 2.0.4                                                |
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
  $Id: puntosF_MyExtension.class.php,v 1.0 2011-03-30 15:00:00 Alberto Santos F.  asantos@palosanto.com Exp $*/
$root = $_SERVER["DOCUMENT_ROOT"];
require_once("$root/libs/paloSantoConfig.class.php");
require_once("$root/libs/misc.lib.php");
require_once("$root/configs/default.conf.php");
require_once("$root/libs/paloSantoDB.class.php");
require_once("$root/libs/paloSantoACL.class.php");

require_once("$root/modules/myex_config/libs/paloSantoMyExtension.class.php");


class core_MyExtension
{
    /**
     * Object paloACL
     *
     * @var object
     */
    private $_pACL;

    /**
     * Array that contains a paloDB Object, the key is the DSN of a specific database
     *
     * @var array
     */
    private $_dbCache;

    /**
     * ACL User ID for authenticated user
     *
     * @var integer
     */
    private $_id_user;

    /**
     * Description error message
     *
     * @var array
     */
    private $errMsg;

    /**
     * Constructor
     *
     */
    public function core_MyExtension()
    {
        $this->_pACL    = NULL;
        $this->_dbCache = array();
        $this->_id_user = NULL;
        $this->errMsg   = NULL;
    }

     /**
     * Static function that creates an array with all the functional points with the parameters IN and OUT
     *
     * @return  array     Array with the definition of the function points.
     */
    public static function getFP()
    {
        $arrData["setCallWaiting"]["params_IN"] = array(
            "callWaiting"      => array("type" => "boolean",   "required" => true)
        );

        $arrData["setCallWaiting"]["params_OUT"] = array(
            "return"           => array("type" => "boolean",   "required" => true)
        );

        $arrData["setCallMonitor"]["params_IN"] = array(
            "recordIncoming"      => array("type" => "string",   "required" => true),
            "recordOutgoing"      => array("type" => "string",   "required" => true)
        );

        $arrData["setCallMonitor"]["params_OUT"] = array(
            "return"           => array("type" => "boolean",   "required" => true)
        );

        $arrData["setDoNotDisturb"]["params_IN"] = array(
            "doNotDisturb"      => array("type" => "boolean",   "required" => true)
        );

        $arrData["setDoNotDisturb"]["params_OUT"] = array(
            "return"           => array("type" => "boolean",   "required" => true)
        );

        $arrData["setCallForward"]["params_IN"] = array(
            "callForward"                            => array("type" => "boolean",           "required" => false),
            "callForwardUnavailable"                 => array("type" => "boolean",           "required" => false),
            "callForwardBusy"                        => array("type" => "boolean",           "required" => false),
            "phoneNumberCallForward"                 => array("type" => "positiveInteger",   "required" => false),
            "phoneNumberCallForwardUnavailable"      => array("type" => "positiveInteger",   "required" => false),
            "phoneNumberCallForwardBusy"             => array("type" => "positiveInteger",   "required" => false)
        );

        $arrData["setCallForward"]["params_OUT"] = array(
            "return"           => array("type" => "boolean",   "required" => true)
        );

        return $arrData;
    }

    /**
     * Function that creates, if do not exist in the attribute dbCache, a new paloDB object for the given DSN
     *
     * @param   string   $sDSN   DSN of a specific database
     * @return  object   paloDB object for the entered database
     */
    private function & _getDB($sDSN)
    {
        if (!isset($this->_dbCache[$sDSN])) {
            $this->_dbCache[$sDSN] = new paloDB($sDSN);
        }
        return $this->_dbCache[$sDSN];
    }

    /**
     * Function that creates, if do not exist in the attribute _pACL, a new paloACL object
     *
     * @return  object   paloACL object
     */
    private function & _getACL()
    {
        global $arrConf;

        if (is_null($this->_pACL)) {
            $pDB_acl = $this->_getDB($arrConf['elastix_dsn']['acl']);
            $this->_pACL = new paloACL($pDB_acl);
        }
        return $this->_pACL;
    }

    /**
     * Function that reads the login user ID, that assumed is on $_SERVER['PHP_AUTH_USER']
     *
     * @return  integer   ACL User ID for authenticated user, or NULL if the user in $_SERVER['PHP_AUTH_USER'] does not exist
     */
    private function _leerIdUser()
    {
        if (!is_null($this->_id_user)) return $this->_id_user;

        $pACL = $this->_getACL();
        $id_user = $pACL->getIdUser($_SERVER['PHP_AUTH_USER']);
        if ($id_user == FALSE) {
            $this->errMsg["fc"] = 'INTERNAL';
            $this->errMsg["fm"] = 'User-ID not found';
            $this->errMsg["fd"] = 'Could not find User-ID in ACL for user '.$_SERVER['PHP_AUTH_USER'];
            $this->errMsg["cn"] = get_class($this);
            return NULL;
        }
        $this->_id_user = $id_user;
        return $id_user;
    }

    /**
     * Function that gets the extension of the login user, that assumed is on $_SERVER['PHP_AUTH_USER']
     *
     * @return  string   extension of the login user, or NULL if the user in $_SERVER['PHP_AUTH_USER'] does not have an extension     *                   assigned
     */
    private function _leerExtension()
    {
        // Identificar el usuario para averiguar el número telefónico origen
        $id_user = $this->_leerIdUser();

        $pACL = $this->_getACL();
        $user = $pACL->getUsers($id_user);
        if ($user === FALSE) {
            $this->errMsg["fc"] = 'ACL';
            $this->errMsg["fm"] = 'ACL lookup failed';
            $this->errMsg["fd"] = 'Unable to read information from ACL - '.$pACL->errMsg;
            $this->errMsg["cn"] = get_class($pACL);
            return NULL;
        }

        // Verificar si tiene una extensión
        $extension = $user[0][3];
        if ($extension == "") {
            $this->errMsg["fc"] = 'EXTENSION';
            $this->errMsg["fm"] = 'Extension lookup failed';
            $this->errMsg["fd"] = 'No extension has been set for user '.$_SERVER['PHP_AUTH_USER'];
            $this->errMsg["cn"] = get_class($pACL);
            return NULL;
        }

        return $extension;
    }

    /**
     * Functional point that sets the option call waiting to the extension of the authenticated user
     *
     * @param   boolean     $callWaiting   true the call waiting will be set to on, false it will be set to off
     * @return  boolean   True if the call waiting was set, or false if an error exists
     */
    public function setCallWaiting($callWaiting)
    {
        global $root;

        if(!isset($callWaiting)){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Parameter Error';
            $this->errMsg["fd"] = 'The parameter callWaiting has not been sent';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        $extension = $this->_leerExtension();
        $pDB       = new paloDB(generarDSNSistema('asteriskuser', 'asterisk', $root."/"));
        $pMyExtension = new paloSantoMyExtension($pDB);
        if(is_null($extension)){
            return false;
        }
        if($callWaiting)
            $enableCW = "on";
        else
            $enableCW = "off";
        $statusCW = $pMyExtension->setConfig_CallWaiting($enableCW,$extension);
        if(!$statusCW){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Error processing CallWaiting';
            $this->errMsg["fd"] = 'The CallWaiting could not be set';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        return true;
    }

    /**
     * Functional point that sets the option call monitor to the extension of the authenticated user
     *
     * @param   string     $recordIncoming   this option can be Always, Never or Adhoc
     * @param   string     $recordOutgoing   this option can be Always, Never or Adhoc
     * @return  boolean   True if the call monitor was set, or false if an error exists
     */
    public function setCallMonitor($recordIncoming, $recordOutgoing)
    {
        global $root;
        if(!isset($recordIncoming)){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Parameter Error';
            $this->errMsg["fd"] = 'The parameter recordIncoming has not been sent';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        if(!isset($recordOutgoing)){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Parameter Error';
            $this->errMsg["fd"] = 'The parameter recordOutgoing has not been sent';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        $extension = $this->_leerExtension();
        $pDB       = new paloDB(generarDSNSistema('asteriskuser', 'asterisk', $root."/"));
        $pMyExtension = new paloSantoMyExtension($pDB);
        if(is_null($extension)){
            return false;
        }
        if(($recordIncoming == "Always" || $recordIncoming == "Never" || $recordIncoming == "Adhoc") && ($recordOutgoing == "Always" || $recordOutgoing == "Never" || $recordOutgoing == "Adhoc"))
            $statusRecording = $pMyExtension->setRecordSettings($extension,$recordIncoming,$recordOutgoing);
        else{
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Parameter Error';
            $this->errMsg["fd"] = 'You entered a bad record Incoming or bad recordOutgoing, the options only are: Always, Never or Adhoc';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        if(!$statusRecording){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Error Processing Record Settings';
            $this->errMsg["fd"] = 'The Record Settings could not be set';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        return true;
    }

    /**
     * Functional point that sets the option call forward to the extension of the authenticated user
     *
     * @param   boolean     $callForward                         (optional) true will set a call forward, false will not
     * @param   integer     $phoneNumberCallForward              (optional) Number for the call forward
     * @param   boolean     $callForwardUnavailable              (optional) true will set a call forward on unavailable, false will not
     * @param   integer     $phoneNumberCallForwardUnavailable   (optional) Number for the call forward on unavailable
     * @param   boolean     $callForwardBusy                     (optional) true will set a call forward on busy, false will not
     * @param   integer     $phoneNumberCallForwardBusy          (optional) Number for the call forward on busy
     * @return  boolean     True if the call forward was set, or false if an error exists
     */
    public function setCallForward($callForward, $phoneNumberCallForward, $callForwardUnavailable, $phoneNumberCallForwardUnavailable, $callForwardBusy, $phoneNumberCallForwardBusy)
    {
        global $root;
        $extension = $this->_leerExtension();
        $pDB       = new paloDB(generarDSNSistema('asteriskuser', 'asterisk', $root."/"));
        $pMyExtension = new paloSantoMyExtension($pDB);
        $statusCF = $statusCFU = $statusCFB = true;
        if(is_null($extension)){
            return false;
        }
        if(isset($callForward)){
            if($callForward){
                if(isset($phoneNumberCallForward))
                    if(preg_match( "/^[0-9]+$/",$phoneNumberCallForward))
                        $statusCF   = $pMyExtension->setConfig_CallForward("on",$phoneNumberCallForward,$extension);
            }else
                $statusCF  = $pMyExtension->setConfig_CallForward("off","",$extension);
        }
        if(isset($callForwardUnavailable)){
            if($callForwardUnavailable){
                if(isset($phoneNumberCallForwardUnavailable))
                    if(preg_match( "/^[0-9]+$/",$phoneNumberCallForwardUnavailable))
                        $statusCFU  = $pMyExtension->setConfig_CallForwardOnUnavail("on",$phoneNumberCallForwardUnavailable,$extension);
            }else
                $statusCFU  = $pMyExtension->setConfig_CallForwardOnUnavail("off","",$extension);
        }
        if(isset($callForwardBusy)){
            if($callForwardBusy){
                if(isset($phoneNumberCallForwardBusy))
                    if(preg_match( "/^[0-9]+$/",$phoneNumberCallForwardBusy))
                        $statusCFB  = $pMyExtension->setConfig_CallForwardOnBusy("on",$phoneNumberCallForwardBusy,$extension);
            }else
                $statusCFB  = $pMyExtension->setConfig_CallForwardOnBusy("off","",$extension);
        }
        if(!$statusCF){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Error Processing Call Forward';
            $this->errMsg["fd"] = 'The Call Forward could not be set';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        if(!$statusCFU){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Error Processing Call Forward on Unavailable';
            $this->errMsg["fd"] = 'The Call Forward on Unavailable could not be set';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        if(!$statusCFB){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Error Processing Call Forward on Busy';
            $this->errMsg["fd"] = 'The Call Forward on Busy could not be set';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }

        return true;
    }

    /**
     * Functional point that sets the option do not Disturb to the extension of the authenticated user
     *
     * @param   boolean     $doNotDisturb   true the do not Disturb option will be set to on, false it will be set to off
     * @return  boolean     True if the do not Disturb option was set, or false if an error exists
     */
    public function setDoNotDisturb($doNotDisturb)
    {
        global $root;
        if(!isset($doNotDisturb)){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Parameter Error';
            $this->errMsg["fd"] = 'The parameter doNotDisturb has not been sent';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        $extension = $this->_leerExtension();
        $pDB       = new paloDB(generarDSNSistema('asteriskuser', 'asterisk',$root."/"));
        $pMyExtension = new paloSantoMyExtension($pDB);
        if(is_null($extension)){
            return false;
        }
        if($doNotDisturb)
            $enableDND = "on";
        else
            $enableDND = "off";
        $statusDND = $pMyExtension->setConfig_DoNotDisturb($enableDND,$extension);
        if(!$statusDND){
            $this->errMsg["fc"] = 'ERROR';
            $this->errMsg["fm"] = 'Error Processing Do Not Disturb';
            $this->errMsg["fd"] = 'The Do Not Disturb state could not be set';
            $this->errMsg["cn"] = get_class($this);
            return false;
        }
        return true;
    }

    /**
     * 
     * Function that returns the error message
     *
     * @return  string   Message error if had an error.
     */
    public function getError()
    {
        return $this->errMsg;
    }
}

?>