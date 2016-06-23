<?php

/*
 +-----------------------------------------------------------------------+
 | program/steps/settings/save_prefs.inc                                 |
 |                                                                       |
 | This file is part of the RoundCube Webmail client                     |
 | Copyright (C) 2005-2009, RoundCube Dev. - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Save user preferences to DB and to the current session              |
 |                                                                       |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id: save_prefs.inc 2983 2009-09-23 12:32:09Z alec $

*/

$CURR_SECTION = get_input_value('_section', RCUBE_INPUT_POST);

$a_user_prefs = array();

// set options for specified section
switch ($CURR_SECTION)
{
  case 'general':
    $a_user_prefs = array(
      'language'     => isset($_POST['_language']) ? get_input_value('_language', RCUBE_INPUT_POST) : $CONFIG['language'],
      'timezone'     => isset($_POST['_timezone']) ? (is_numeric($_POST['_timezone']) ? floatval($_POST['_timezone']) : get_input_value('_timezone', RCUBE_INPUT_POST)) : $CONFIG['timezone'],
      'dst_active'   => isset($_POST['_dst_active']) ? TRUE : FALSE,
      'pagesize'     => is_numeric($_POST['_pagesize']) ? max(2, intval($_POST['_pagesize'])) : $CONFIG['pagesize'],
      'index_sort'   => isset($_POST['_index_sort']) ? TRUE : FALSE,
      'prettydate'   => isset($_POST['_pretty_date']) ? TRUE : FALSE,
      'skin' 	     => isset($_POST['_skin']) ? get_input_value('_skin', RCUBE_INPUT_POST) : $CONFIG['skin'],
    );

  break;
  case 'mailbox':
    $a_user_prefs = array(
      'focus_on_new_message' => isset($_POST['_focus_on_new_message']) ? TRUE : FALSE,
      'preview_pane' 	     => isset($_POST['_preview_pane']) ? TRUE : FALSE,
      'mdn_requests'         => isset($_POST['_mdn_requests']) ? intval($_POST['_mdn_requests']) : 0,
      'keep_alive'           => isset($_POST['_keep_alive']) ? intval($_POST['_keep_alive'])*60 : $CONFIG['keep_alive'],
      'check_all_folders'    => isset($_POST['_check_all_folders']) ? TRUE : FALSE,
    );

  break;
  case 'mailview':
    $a_user_prefs = array(
      'prefer_html'     => isset($_POST['_prefer_html']) ? TRUE : FALSE,
      'inline_images'   => isset($_POST['_inline_images']) ? TRUE : FALSE,
      'show_images'     => isset($_POST['_show_images']) ? intval($_POST['_show_images']) : 0,
      'display_next'    => isset($_POST['_display_next']) ? TRUE : FALSE,
    );

  break;
  case 'compose':
    $a_user_prefs = array(
      'htmleditor'         => isset($_POST['_htmleditor']) ? TRUE : FALSE,
      'draft_autosave'     => isset($_POST['_draft_autosave']) ? intval($_POST['_draft_autosave']) : 0,
      'mime_param_folding' => isset($_POST['_mime_param_folding']) ? intval($_POST['_mime_param_folding']) : 0,
    );

  break;
  case 'server':
    $a_user_prefs = array(
      'read_when_deleted' => isset($_POST['_read_when_deleted']) ? TRUE : FALSE,
      'skip_deleted'      => isset($_POST['_skip_deleted']) ? TRUE : FALSE,
      'flag_for_deletion' => isset($_POST['_flag_for_deletion']) ? TRUE : FALSE,
      'logout_purge'      => isset($_POST['_logout_purge']) ? TRUE : FALSE,
      'logout_expunge'    => isset($_POST['_logout_expunge']) ? TRUE : FALSE,
    );

  break;
  case 'folders':
    $a_user_prefs = array(
      'drafts_mbox' => get_input_value('_drafts_mbox', RCUBE_INPUT_POST),
      'sent_mbox'   => get_input_value('_sent_mbox', RCUBE_INPUT_POST),
      'junk_mbox'   => get_input_value('_junk_mbox', RCUBE_INPUT_POST),
      'trash_mbox'  => get_input_value('_trash_mbox', RCUBE_INPUT_POST),
    );

  break;

   /*
   **********************************************
   * Agregado por: Carlos Chiriboga Calderon  v1
   * Adaptado por: Eduardo Cueva Delgado      v2
   *
   *  Case 'credentials'
   **********************************************
   *
   *Esta parte es la que permite el cambio de password desde el menu preferencias :)
   *
   */
  case 'credentials':
    $a_user_prefs = array(
      'rewrite_password'          => isset($_POST['_rewrite_password'])?$_POST['_rewrite_password']:"",
      'rewrite_retype_password'   => isset($_POST['_write_retype_password'])?$_POST['_write_retype_password']:"",
    );

  break;
}


$data = rcmail::get_instance()->plugins->exec_hook('save_preferences',
  array('prefs' => $a_user_prefs, 'section' => $CURR_SECTION));

$a_user_prefs = $data['prefs'];

// don't override these parameters
foreach ((array)$CONFIG['dont_override'] as $p)
  $a_user_prefs[$p] = $CONFIG[$p];

/****************VARIABLES DE ERRORES******************/
// DESARROLLADO POR: Eduardo Cueva v2
$err_pass = FALSE;
$bErrorDB = FALSE;
/******************************************************/


// verify some options
switch ($CURR_SECTION)
{
  case 'general':

    // switch UI language
    if (isset($_POST['_language']) && $a_user_prefs['language'] != $_SESSION['language']) {
      $RCMAIL->load_language($a_user_prefs['language']);
      $OUTPUT->command('reload', 500);
    }

    // switch skin
    $OUTPUT->set_skin($a_user_prefs['skin']);

    // force min size
    if ($a_user_prefs['pagesize'] < 1)
      $a_user_prefs['pagesize'] = 10;

    if (isset($CONFIG['max_pagesize']) && ($a_user_prefs['pagesize'] > $CONFIG['max_pagesize']))
      $a_user_prefs['pagesize'] = (int) $CONFIG['max_pagesize'];

  break;
  case 'mailbox':

    // force keep_alive
    if (isset($a_user_prefs['keep_alive'])) {
      $a_user_prefs['keep_alive'] = max(60, $CONFIG['min_keep_alive'], $a_user_prefs['keep_alive']);
      if (!empty($CONFIG['session_lifetime']))
        $a_user_prefs['keep_alive'] = min($CONFIG['session_lifetime']*60, $a_user_prefs['keep_alive']);
    }

  break;
  case 'folders':

    // special handling for 'default_imap_folders'
    if (in_array('default_imap_folders', (array)$CONFIG['dont_override'])) {
      foreach (array('drafts_mbox','sent_mbox','junk_mbox','trash_mbox') as $p)
        $a_user_prefs[$p] = $CONFIG[$p];
    } else {
      $a_user_prefs['default_imap_folders'] = array('INBOX');
      foreach (array('drafts_mbox','sent_mbox','junk_mbox','trash_mbox') as $p) {
        if ($a_user_prefs[$p])
          $a_user_prefs['default_imap_folders'][] = $a_user_prefs[$p];
      }
    }

  break;
    case 'credentials':
        if(trim($_POST['_rewrite_password']) == trim($_POST['_write_retype_password']) && $_POST['_rewrite_password'] != ""){
            $_passwd    = $_POST['_rewrite_password'];
            $_user      = $_SESSION['username'];
           $bErrorDB = FALSE;
           $path_root = $_SERVER["DOCUMENT_ROOT"];
           include_once("$path_root/libs/misc.lib.php");
           include_once("$path_root/configs/default.conf.php");
           include_once("$path_root/libs/paloSantoDB.class.php");

           $pDB = new paloDB("sqlite3:////var/www/db/acl.db");
           if(!empty($pDB->errMsg)) {
               echo "ERROR DE DB: $pDB->errMsg <br/>";
               $bErrorDB = TRUE;
           } else {
               // SQLite no soporta UPDATE con múltiples tablas. Se tiene que averiguar
               // el valor correcto de acl_profile_properties.id_profile
               $sPeticionSQL = 
                   'SELECT a.id_profile '.
                   'FROM acl_resource r, acl_user_profile up, acl_profile_properties a, acl_profile_properties b '.
                   'WHERE r.name = "webmail" '.
                       'AND up.id_resource = r.id '.
                       'AND up.id_profile = a.id_profile '.
                       'AND a.id_profile = b.id_profile '.
                       'AND a.property = "login" '.
                       'AND b.property = "domain" '.
                       'AND (a.value || "." || b.value) = ?';
               $tupla = $pDB->getFirstRowQuery($sPeticionSQL, FALSE, array($_SESSION['username']));
               if (!is_array($tupla)) {
                   print "ERROR DE DB: ".$pDB->errMsg;
                   $bErrorDB = TRUE;
               } elseif (count($tupla) > 0) {
                   $idPerfil = $tupla[0];
                   $sPeticionSQL = 'UPDATE acl_profile_properties SET value = ? WHERE id_profile = ? AND property = "password"';

                   $r = $pDB->genQuery($sPeticionSQL, array($_passwd, $idPerfil));
                   if (!$r) {
                       print "ERROR DE DB: ".$pDB->errMsg;
                       $bErrorDB = TRUE;
                   }
               }
           }

           if (!$bErrorDB) {
               $pDB = new paloDB("sqlite3:////var/www/db/email.db");
               if(!empty($pDB->errMsg)) {
                   echo "ERROR DE DB: $pDB->errMsg <br/>";
                   $bErrorDB = TRUE;
               } else {
                   $sPeticionSQL = 'UPDATE accountuser SET password = ? WHERE username = ?';
                   $r = $pDB->genQuery($sPeticionSQL, array($_passwd, $_SESSION['username']));
                   if (!$r) {
                       print "ERROR DE DB: ".$pDB->errMsg;
                       $bErrorDB = TRUE;
                   }
               }
           }

           // Cambiar clave IMAP si no se ha encontrado error de DB
           if (!$bErrorDB) {
               exec("echo \"$_passwd\" | sudo -u root /usr/sbin/saslpasswd2 -c $_user -u example.com",$output);
               $_SESSION['password'] = $RCMAIL->encrypt($_POST['_rewrite_password']);
           }
        }else
            $err_pass = true;
        break;
}


if ($USER->save_prefs($a_user_prefs) && $CURR_SECTION != "credentials")
  $OUTPUT->show_message('successfullysaved', 'confirmation');

if ($CURR_SECTION == "credentials"){
    if(!$err_pass){ //no hay errores en el password
        if(!$bErrorDB)  // no hay errores de base de datos
            $OUTPUT->show_message('successfullysaved', 'confirmation');
        else
            $OUTPUT->show_message('errorsaving', 'error');
    }else{ // hay errores en el password no coinciden o estan vacias
        $OUTPUT->show_message('passwordsnotmatch', 'error');
    }
}

// display the form again
rcmail_overwrite_action('edit-prefs');

?>
