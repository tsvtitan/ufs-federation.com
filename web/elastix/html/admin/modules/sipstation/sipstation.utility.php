<?php
/* $Id:$ */

/* Copyright (c) 2009 Bandwidth.com
   Licensed for use by active FreePBX.com SIP Trunking Customers (SIPSTATION(tm)). Not licensed to be modified or redistributed in any fashion.
   No guarantees or warranties provided, use at your own risk. See license included with module for more details.
*/

/* hard coded hash of Asterisk codec names compared to what XML official name may send down
   will be filtered so only supported codecs remain, From Asterisk:

       1 (1 <<  0)      (0x1)  audio       g723   (G.723.1)
       2 (1 <<  1)      (0x2)  audio        gsm   (GSM)
       4 (1 <<  2)      (0x4)  audio       ulaw   (G.711 u-law)
       8 (1 <<  3)      (0x8)  audio       alaw   (G.711 A-law)
      16 (1 <<  4)     (0x10)  audio   g726aal2   (G.726 AAL2)
      32 (1 <<  5)     (0x20)  audio      adpcm   (ADPCM)
      64 (1 <<  6)     (0x40)  audio       slin   (16 bit Signed Linear PCM)
     128 (1 <<  7)     (0x80)  audio      lpc10   (LPC10)
     256 (1 <<  8)    (0x100)  audio       g729   (G.729A)
     512 (1 <<  9)    (0x200)  audio      speex   (SpeeX)
    1024 (1 << 10)    (0x400)  audio       ilbc   (iLBC)
    2048 (1 << 11)    (0x800)  audio       g726   (G.726 RFC3551)
    4096 (1 << 12)   (0x1000)  audio       g722   (G722)
*/
$failover = false;
$sipstation_xml_version = '1.0.0';
$ast_codec_hash = array(
  'g723'     => 'G.723.1',
  'gsm'      => 'GSM',
  'ulaw'     => 'G.711.U',
  'alaw'     => 'G.711.A',
  'g722aal2' => 'G.726.AAl2',
  'adpcm'    => 'ADPCM',
  'slin'     => 'SLIN', 
  'lpc10'    => 'LPC10',
  'g729'     => 'G.729.A',
  'speex'    => 'SpeeX',
  'ilbc'     => 'iLBC',
  'g726'     => 'G.726',
  'g722'     => 'G.722',
);

/* callback to filer out codecs not supported
 */
function sipstation_codec_filter($codec) {
  global $ast_codec_hash;

  $codec_split = explode(':',$codec,2);
  if (array_key_exists($codec_split[0],$ast_codec_hash)) {
    return $codec_split[0];
  } else {
    return false;
  }
}
function sipstation_supported_codecs($codec) {
  global $codec_array;
  return in_array($codec,$codec_array) ? $codec : false;
}

/* Returns a hash of settings from 'sip show peers' 
*/
function sipstation_get_peer_status($peer) {
  global $astman;
  $sip_peer['sipstation_status'] = 'ok';
  $response = $astman->send_request('Command',array('Command'=>"sip show peer $peer"));
  $buf = explode("\n",$response['data']);
  foreach ($buf as $res) {
    if (preg_match("/$peer\s*not\s+found\.{0,1}\s*$/",$res)) {
      $sip_peer['sipstation_status'] = 'no_peer';
    } elseif (preg_match("/^\s*(.*?)\s*:\s*(.*)$/",$res,$match)) {
      $sip_peer[$match[1]] = $match[2];
    }
  }
  return $sip_peer;
}

/* Returns a hash of settings from 'sip show settings' 
*/
function sipstation_get_sip_settings() {
  global $astman;
  $sip_peer['sipstation_status'] = 'ok';
  $response = $astman->send_request('Command',array('Command'=>"sip show settings"));
  $buf = explode("\n",$response['data']);
  foreach ($buf as $res) {
    if (preg_match("/$peer\s*not\s+found\.{0,1}\s*$/",$res)) {
      $sip_peer['sipstation_status'] = 'no_peer';
    } elseif (preg_match("/^\s*(.*?)\s*:\s*(.*)$/",$res,$match)) {
      $sip_peer[$match[1]] = $match[2];
    }
  }
  return $sip_peer;
}

/* Returns a filtered array of currently configured codecs, filtered
   against the list of supported codecs
*/
function sipstation_get_configured_codecs($peer, $peer_status=false) {
  if (!is_array($peer_status) || empty($peer_status)) {
    $peer_status = sipstation_get_peer_status($peer);
  }
  if ($peer_status['sipstation_status'] = 'ok') {
    if (preg_match("/^\s*\((.*)\)\s*$/",$peer_status['Codec Order'],$match)) {
      $codecs = explode(',',$match[1]);
      return array_filter(array_map('sipstation_codec_filter',$codecs));
    }
  }
}

function sipstation_get_key() {
  global $db;
	$sql = "SELECT * FROM module_xml WHERE id = 'sipstation_key'";
	$result = sql($sql,'getRow',DB_FETCHMODE_ASSOC);
	if (!isset($result['data']) || trim($result['data']) == "") {
    return false;
  } else {
    return $result['data'];
  }
}

/* Check if there is a valid key
 * Returns: nokey, valid, invalid, noserver (if server can't be contacted)
 */
function sipstation_check_key() {
	$sql = "SELECT * FROM `module_xml` WHERE `id` = 'sipstation_key'";
	$result = sql($sql,'getRow',DB_FETCHMODE_ASSOC);

	// if not set so this is a first time install
	// get a new hash to account for first time install
	//
	if (!isset($result['data']) || trim($result['data']) == "") {
    return 'nokey';
  } else {
    // TODO: should really encrypt/decrypt key
    //
    return sipstation_confirm_key($result['data']);
  }
}

/* deleted saved configuration if confirmation determines it is stale
 */
function sipstation_confirm_key($key) {
  $xml_array = sipstation_get_config(trim($key));
  if ($xml_array['status'] == 'success') {
    switch ($xml_array['query_status']) {
      case 'SUCCESS':
        return 'valid';
      case 'TEMPNOTAVAIL':
        return 'tempnotavail';
      case 'BADKEY':
        sipstation_remove_key();
      default:
        return 'invalid';
    }
  } else {
    return $xml_array['status'];
  }
}

function sipstation_set_key($key) {
  global $db;
  $status = sipstation_confirm_key($key);
  if ($status == 'valid' || $status == 'tempnotavail') {
    $data4sql = $db->escapeSimple($key);
    sql("DELETE FROM `module_xml` WHERE `id` = 'sipstation_key'");
    sql("INSERT INTO `module_xml` (`id`,`time`,`data`) VALUES ('sipstation_key',".time().",'".$data4sql."')");
  }
  return $status;
}

function sipstation_remove_key() {
  sql("DELETE FROM `module_xml` WHERE `id` = 'sipstation_key'");
  return $status;
}

/* save the retrieved configuration information into the db to be used to configure trunks and what not
 */
function sipstation_save_config($xml) {
  global $db;
  $data4sql = $db->escapeSimple($xml);
  sql("DELETE FROM `module_xml` WHERE `id` = 'sipstation_config'");
  sql("INSERT INTO `module_xml` (`id`,`time`,`data`) VALUES ('sipstation_config',".time().",'".$data4sql."')");
}

function sipstation_del_saved_config() {
  global $db;
  sql("DELETE FROM `module_xml` WHERE `id` = 'sipstation_config'");
}

function sipstation_retrieve_saved_config($format='') {
  global $db;
	$sql = "SELECT `data` FROM `module_xml` WHERE `id` = 'sipstation_config'";
	$xml_data = sql($sql, "getOne");
  if ($format == 'xml') {
    return $xml_data;
  } else {
    $parser = new xml2Array($xml_data);
    return $parser;
  }
}

function sipstation_get_settings($key, $online=true) {
  global $amp_conf;

  if ($online) {
    /*
    $fn = "https://store.freepbx.com/store/myaccount.xml?keycode=".urlencode($key);
    if (!$amp_conf['MODULEADMINWGET']) {
      $xml_data = @file_get_contents($fn);
    }
    if (empty($xml_data)) {
      exec("wget -O - $fn 2> /dev/null", $data_arr, $retcode);
      $xml_data = implode("\n",$data_arr);
    }
    */
    $xml_data = sipstation_curl_xml($key);

    sipstation_save_config($xml_data); // cache the latest
		$parser = new xml2Array($xml_data);

    return $parser;
  } else {
    return sipstation_retrieve_saved_config();
  }
}

// TODO: handle timeouts, other issues.
// TODO: make more general for any post, make generic RESTFUL function
function sipstation_curl_xml($keycode) {

  // TODO: TEST
  /* would be better to serialize the array and send it
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  $params = "keycode=".urlencode($keycode);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  */

  $url = "https://store.freepbx.com/store/myaccount.xml";
  //$params = "keycode=".urlencode($keycode);
  $params = array("keycode" => urlencode($keycode));
  $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

  $ch = curl_init();
  //curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return results to string
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');	// imitate classic browser behavior
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
  // TODO: deal with timeout!

  $xml = curl_exec ($ch);

  // TODO: curl info back from request, check return code
  // freepbx_debug(curl_getinfo($ch));

  // TODO: if ($xml === false)  handle it, retry, etc. 
  // TODO: or at least toss in some error xml response
  curl_close ($ch);

  return $xml;
}

//TODO: refactor with above to combine common parts

function sipstation_put_dids($dids) {
  if (empty($dids)) {
    return true;
  }
  $keycode = sipstation_get_key();
  $xml = '
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<xml type="sipstation/xml" version="1.0.0">
<xml_version>1.0.0</xml_version>';
  $xml .= "
<keycode>$keycode</keycode>
<dids>";
  foreach ($dids as $did => $failover) {
    $xml .= "\n<did failover=\"$failover\">$did</did>";
  }
  $xml .= "
</dids>
</xml>
";

  // TODO: different URL for putting just dids
  $url = "https://store.freepbx.com/store/myaccount.xml";
  $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);			// print results to string and not STDOUT
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');	// IMITATE CLASSIC BROWSER'S BEHAVIOUR 
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
  // TODO: deal with timeout!

  $xml = curl_exec ($ch);

  // curl info back from request
  $return_status = curl_getinfo($ch);
  // freepbx_debug($return_status);

  // TODO: if ($xml === false) { handle it, retry, etc. 
  // TODO: or at least toss in some error xml response
  curl_close ($ch);

  return $xml;
}

function sipstation_get_or_create_trunks(&$json_array,&$globalvar1,&$trunknum1,&$globalvar2,&$trunknum2) {
  // now check on trunk config
  // fpbx-1-$sip_user / fpbx-2-$sip_user
  // TODO: 2.6 has trunkname, provider options that should be used
  //
  global $ast_codec_hash;
  $sip_user = $json_array['sip_username'];
  $sip_pass = $json_array['sip_password'];
  $default_did = $json_array['e911_address']['default_did'];
  $need_reload = false;

  $tlist       = core_trunks_list(true);
  $tech        = 'sip';
  $keepcid     = 'off';
  $disabletrunk= 'off';

	$peerdetails = "disallow=all\nallow=".implode('&',array_keys($ast_codec_hash))."\n";
	$peer_array = array();
  foreach ($json_array['asterisk_settings']['peer'] as $param) {
    $peerdetails .= trim($param)."\n";
    $parts = explode('=',$param,2);
    $peer_array[$parts[0]] = $parts[1];
  }

	$peerdetails .= "username=$sip_user\nsecret=$sip_pass\nhost=";
	$register    = "$sip_user:$sip_pass@";

  for ($i=1;$i<3;$i++) {
    $gidx = "gw$i";
    $channelid   = "fpbx-$i-$sip_user";
    $gw          = $json_array['gateways'][$gidx];
    if (isset($tlist["SIP/$channelid"])) {
      $globalvar = $tlist["SIP/$channelid"]['globalvar'];
      $trunknum  = ltrim($globalvar,'OUT_');
      // Now get some trunk status info
      $trunk_status = sipstation_get_peer_status($channelid);
      if ($trunk_status['sipstation_status'] == 'ok') {
	      $json_array['trunk_qualify'][$gidx] = $trunk_status['Status'];
	      $json_array['trunk_codecs'][$gidx] = implode(' | ',sipstation_get_configured_codecs($channelid,$trunk_status));
      } else {
        //TODO: probably nothing
      }
      $json_array['trunk_name'][$gidx] = core_trunks_getTrunkTrunkName($trunknum);
    } else {
		  $trunknum = core_trunks_add($tech, $channelid, '', '', $default_did, $peerdetails.$gw, '', '', $register.$gw, $keepcid, '', $disabletrunk);
      $globalvar = "OUT_".$trunknum;
      $need_reload = true;
	    $json_array['created_trunks'][$gidx] = $channelid;
      // TODO: 2.6 should be name
      $json_array['trunk_name'][$gidx] = "SIP/$channelid";
    }
    // We need these next and need them past back up
    $gv = "globalvar$i";
    $tn = "trunknum$i";
    $$gv = $globalvar;
    $$tn = $trunknum;
  }

  $peer_array['username'] = $sip_user;
  $peer_array['secret'] = $sip_pass;

  $trunk_check = array($trunknum1, $trunknum2);
  $cnt = 1;
  foreach ($trunk_check as $tr) {
	  $json_array['trunk_id']["gw$cnt"] = $tr; // need to get this set for both anyhow
    $gw = $json_array['gateways']["gw$cnt"];
    $peer_array['host'] = $gw;
    $peer_stuff = array();
    $tr_reg = core_trunks_getTrunkRegister($tr);
    foreach (explode("\n",core_trunks_getTrunkPeerDetails($tr)) as $elem) {
      $temp = explode("=",$elem,2);
      if ($temp[0] == 'allow') {
        $peer_stuff[$temp[0]] = explode('&',$temp[1]);
      } elseif ($temp[0] != '') {
        $peer_stuff[$temp[0]] = $temp[1];
      }
    }
    // Unset some settings that do not hurt to change and might help
    //
    if (isset($peer_stuff['allow'])) {
      unset($peer_stuff['allow']);
    }

    if (isset($peer_stuff['disallow'])) {
      unset($peer_stuff['disallow']);
    }

    unset($peer_array['qualify']);
    if (isset($peer_stuff['qualify'])) {
      unset($peer_stuff['qualify']);
    }
    unset($peer_array['qualify']);

    if (isset($peer_stuff['context'])) {
      unset($peer_stuff['context']);
    }
    unset($peer_array['context']);

    if (isset($peer_stuff['qualifyfreq'])) {
      unset($peer_stuff['qualifyfreq']);
    }

    if (isset($peer_stuff['dtmfmode'])) {
      switch($peer_stuff['dtmfmode']) {
        case 'inband':
        case 'rfc2833':
        case 'auto':
          unset($peer_stuff['dtmfmode']);
          unset($peer_array['dtmfmode']);
        break;
      }
    }

    if ($peer_array != $peer_stuff || $tr_reg != $register.$gw) {
	    $json_array['changed_trunks']["gw$cnt"] = $tr;
    }
    $cnt++;
  }
  return $need_reload;
}

/*
Current format of 'sip show registry' with various possible states
Host                            Username       Refresh State                Reg.Time                 
trunk1.freepbx.com:5060         b04c1dsr           585 Registered           Sat, 27 Jun 2009 00:33:47
trunk2.freepbx.com:5060         b04c1dsr           585 Registered           Sat, 27 Jun 2009 00:33:48
phonebooth.bandwidth.com:5060   9192221234         585 Timeout              Sat, 27 Jun 2009 00:33:47
67.131.62.22:5060               myusername         585 Auth.Sent.           Sat, 27 Jun 2009 00:33:47
*/
function sipstation_get_registration_status($sip_user) {
  global $astman;
  $status_arr = array();
  if (!isset($astman)) {
    return $status_arr;
  }
  $response = $astman->send_request('Command',array('Command'=>"sip show registry"));
  $buf = explode("\n",$response['data']);
  $state_pos = false;
  foreach ($buf as $line) {
    if (trim($line) != '') {
      if ($state_pos===false) {
        // find the positions of the header columns so we can parse
        if ($state_pos = strpos($line,"State")) {
          $user_pos = strpos($line,"Username");
          $reg_pos = strpos($line,"Reg.Time");
          $host_pos = strpos($line,"Host");

          // Asterisk 1.2 does not have Reg. Time
          if ($reg_pos === false) {
            $reg_pos = strlen($line);
          }
        }
      } else {
        // get the username and if ours, trunk (host) and State of reg
        preg_match("/^([^\s]+)\s*/",substr($line,$user_pos),$matches);
        if ($sip_user == $matches[1]) {
          $trunk = trim(substr($line,$host_pos,($user_pos-$host_pos)));
          $trunk = preg_match("/^([^\s:]+)[:]{0,1}[\d]{0,5}\s*/",$trunk,$matches) ?  $matches[1] : $trunk;;
          $state = trim(substr($line,$state_pos,($reg_pos-$state_pos)));
          $status_arr[$trunk] = $state;
        }
      }
    }
  }
  return $status_arr;
}

/* Very simple function to determine if an IP address is in the private range. This would
   include:
   192.168.*.*, 172.16-31.*.* and 10.*.*.*
 */
function is_private_ip($address) {
  if (preg_match('/^(192|172|10)\.(\d{1,3})\.\d{1,3}\.\d{1,3}$/',$address,$match)) {
    switch($match[1]) {
    case '10':
      return true;
      break;
    case '192':
      if ($match[2] == '168') {
        return true;
      } else {
        return false;
      }
      break;
    case '172':
      if ($match[2] >= 16 && $match[2] <= 31) {
        return true;
      } else {
        return false;
      }
      break;
    }
  } else {
    return false;
  }
}

function sipstation_get_config($account_key, $online=true, $filter_sections=array()) {
  global $db;
  global $ast_codec_hash;
  global $codec_array;

  if (!empty($account_key)) {
    $json_array = array();
    $xml_parser = sipstation_get_settings($account_key, $online);

    if (!empty($xml_parser->data)) foreach ($xml_parser->data['xml'] as $key => $value) {
      switch ($key) {
        case 'xml_version':
        case 'query_status':
        case 'query_status_message':
        case 'sip_username':
        case 'sip_password':
        case 'num_trunks':
        case 'monthly_cost':
        case 'cid_format':
        case 'nat_troubleshooting':
          if (!empty($filter_sections) && (!isset($filter_sections[$key]) || !$filter_sections[$key])) { continue; }
          $json_array[$key] = trim("$value");
        break;
        case 'gateways':
        case 'e911_address':
        case 'registered_status':
          if (!empty($filter_sections) && (!isset($filter_sections[$key]) || !$filter_sections[$key])) { continue; }
          foreach ($value as $key2 => $value2) {
            if (is_array($value2)) {
              foreach ($value2 as $gw => $value3){
                $json_array[$key][$key2][$gw] = $value3 ? trim($value3) : '';
              }
            } else {
              $json_array[$key][$key2] = $value2 ? trim($value2) : '';
            }
          }
          if ($key == 'registered_status') {
            foreach ($value as $key2 => $value2) {
              if (is_array($value2)) {
                if ($value2['contact_ip'] == $value2['network_ip']) {
                  $json_array[$key][$key2]['ips_match'] = 'yes';
                } else {
                  $json_array[$key][$key2]['ips_match'] = is_private_ip($value2['contact_ip']) ? 'private' : 'no';
                }
              } else {
                if ($key2 != 'registered') continue;
                if ($json_array[$key]['contact_ip'] == $json_array[$key]['network_ip'] && $value2 == 'yes') {
                  $json_array[$key]['ips_match'] = 'yes';
                } else {
                  $json_array[$key]['ips_match'] = 'no';
                  $json_array[$key]['ips_match'] = is_private_ip($json_array[$key]['contact_ip']) ? 'private' : 'no';
                }
              }
            }
          }
        break;
        case 'dids':
          if (!empty($filter_sections) && (!isset($filter_sections[$key]) || !$filter_sections[$key])) { continue; }
          if (!empty($value['did']) && !is_array($value['did'])) {
            $tmp = $value['did'];
            unset($value['did']);
            $value['did'][] = $tmp;
            $single = true;
          } else {
            $single = false;
          }

          $idx = 0;
          foreach ($value['did'] as $did) {

            $path = $single ? "/xml/dids/did" : "/xml/dids/did/$idx";
            $idx++;
            $failover = $xml_parser->attributes[$path]['failover'];

            $did = trim($did);
            $exten = core_did_get($did);
            if (empty($exten)) {
              $json_array[$key][$did] = array('destination' => 'blank', 'desc' => _("Not Set"), 'description' => '', 'failover' => "$failover");
            } else {
              $dest_results = framework_identify_destinations($exten['destination']);
              if (is_array($dest_results[$exten['destination']])) { 
                /* This is really bad but the calls to core_users get are so heavy and core_users_list don't give details
                  that we will do this for now and deal with it later.
                */
                $user_cid_hash = array();
                $sql = "SELECT `extension`, `outboundcid` FROM `users`";
		            $user_cids = $db->getAll($sql,DB_FETCHMODE_ASSOC);
		            if(DB::IsError($user_cids)) {
                  freepbx_debug("Failed trying to get user cids");
                  freepbx_debug($user_cids->getMessage());
                  $user_cids = array();
                }
                foreach ($user_cids as $item) {
                  $user_cid_hash[$item['extension']] = $item['outboundcid'];
                }
                foreach ($dest_results[$exten['destination']] as $mod => $info) {
                  //$destination = (substr($exten['destination'],0,15) == 'from-did-direct' ? $exten['destination'] : 'assigned'); 
                  $is_checked = 0;
                  if (substr($exten['destination'],0,15) == 'from-did-direct') {
                    $destination = $exten['destination'];
                    $exten_arr = explode(',',$destination);
                    if (isset($exten_arr[1]) && isset($user_cid_hash[$exten_arr[1]])) {
                      $is_checked = preg_match('/^\s*[<]?('.$did.')[>]?\s*$|^\s*"[^"]*"\s*<('.$did.')>\s*$/',$user_cid_hash[$exten_arr[1]]);
                    } else {
                      $is_checked = 0;
                    }
                  } else {
                    $destination = 'assigned'; 
                  }
                  $json_array[$key][$did] = array('destination' => $destination, 'desc' => $info['description'], 'description' => $exten['description'], 'outboundcid' => $is_checked, 'failover' => "$failover");
                  break;
                }
              } else {
                $json_array[$key][$did] = array('destination' => 'blank', 'desc' => _("Not Set"), 'description' => '', 'failover' => "$failover");
              }
            }
          }
        break;
        case 'asterisk_settings':
          if (!empty($filter_sections) && (!isset($filter_sections[$key]) || !$filter_sections[$key])) { continue; }
          $json_array['asterisk_settings']['peer'] = $value['peer']['setting'];
        break;
      case 'codecs':
          if (!empty($filter_sections) && (!isset($filter_sections[$key]) || !$filter_sections[$key])) { continue; }
          $codec_array = $value['codec'];
          /* filter the Asterisk codec hash to only those that we are told are supported
          */
          $ast_codec_hash = array_filter(array_map('sipstation_supported_codecs',$ast_codec_hash));
        break;
      default:
      }
      $json_array['status'] = 'success';
    } else {
      $json_array['status'] = 'noserver';
    }
  } else {
    $json_array['status'] = 'nokey';
  }
return $json_array;
}
