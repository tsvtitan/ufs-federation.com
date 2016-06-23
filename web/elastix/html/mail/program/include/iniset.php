<?php

/*
 +-----------------------------------------------------------------------+
 | program/include/iniset.php                                            |
 |                                                                       |
 | This file is part of the RoundCube Webmail client                     |
 | Copyright (C) 2008-2009, RoundCube Dev, - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Setup the application envoronment required to process               |
 |   any request.                                                        |
 +-----------------------------------------------------------------------+
 | Author: Till Klampaeckel <till@php.net>                               |
 |         Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id: iniset.php 3081 2009-10-31 13:20:02Z thomasb $

*/


// application constants
define('RCMAIL_VERSION', '0.3.1');
define('RCMAIL_CHARSET', 'UTF-8');
define('JS_OBJECT_NAME', 'rcmail');

if (!defined('INSTALL_PATH')) {
  define('INSTALL_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
}

define('RCMAIL_CONFIG_DIR', INSTALL_PATH . 'config');

// make sure path_separator is defined
if (!defined('PATH_SEPARATOR')) {
  define('PATH_SEPARATOR', (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? ';' : ':');
}

///////////////////////////////////////////////////////////
// Por Alex Villacís Lasso: detectar sesión de Elastix
session_name("elastixSession");
session_start();
$bEmbedElastix = FALSE;
if(isset($_POST['_user']) && isset($_POST['_pass'])) {
    $usertmp = trim($_POST['_user']);
    $passtmp = trim($_POST['_pass']);
    
    if(!empty($usertmp) && !empty($passtmp))
        $bEmbedElastix = FALSE;
    else
        $bEmbedElastix = TRUE;
}
else if(isset($_SESSION['elastix_user'])) {
  $bEmbedElastix = TRUE;
}
///////////////////////////////////////////////////////////

// RC include folders MUST be included FIRST to avoid other
// possible not compatible libraries (i.e PEAR) to be included
// instead the ones provided by RC
$include_path = INSTALL_PATH . PATH_SEPARATOR;
$include_path.= INSTALL_PATH . 'program' . PATH_SEPARATOR;
$include_path.= INSTALL_PATH . 'program/lib' . PATH_SEPARATOR;
$include_path.= INSTALL_PATH . 'program/include' . PATH_SEPARATOR;
$include_path.= INSTALL_PATH . '..' . PATH_SEPARATOR;
$include_path.= ini_get('include_path');

if (set_include_path($include_path) === false) {
  die('Fatal error: ini_set/set_include_path does not work.');
}

ini_set('error_reporting', E_ALL&~E_NOTICE);
if  (isset($_SERVER['HTTPS'])) {
   ini_set('session.cookie_secure', ($_SERVER['HTTPS'] && ($_SERVER['HTTPS'] != 'off'))?1:0);
} else {
   ini_set('session.cookie_secure', 0);
}
//ini_set('session.name', 'roundcube_sessid');
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);

// increase maximum execution time for php scripts
// (does not work in safe mode)
@set_time_limit(120);

// set internal encoding for mbstring extension
if(extension_loaded('mbstring'))
  mb_internal_encoding(RCMAIL_CHARSET);
	      

/**
 * Use PHP5 autoload for dynamic class loading
 * 
 * @todo Make Zend, PEAR etc play with this
 * @todo Make our classes conform to a more straight forward CS.
 */
function rcube_autoload($classname)
{
  $filename = preg_replace(
      array(
        '/MDB2_(.+)/',
        '/Mail_(.+)/',
        '/Net_(.+)/',
        '/^html_.+/',
        '/^utf8$/',
        '/html2text/'
      ),
      array(
        'MDB2/\\1',
        'Mail/\\1',
        'Net/\\1',
        'html',
        'utf8.class',
        'lib/html2text'  // see #1485505
      ),
      $classname
  );
  include $filename. '.php';
}

spl_autoload_register('rcube_autoload');

/**
 * Local callback function for PEAR errors
 */
function rcube_pear_error($err)
{
  error_log(sprintf("%s (%s): %s",
    $err->getMessage(),
    $err->getCode(),
    $err->getUserinfo()), 0);
}

// include global functions
require_once 'include/bugs.inc';
require_once 'include/main.inc';
require_once 'include/rcube_shared.inc';


// set PEAR error handling (will also load the PEAR main class)
PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'rcube_pear_error');
