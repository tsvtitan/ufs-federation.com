<?php
// This file is part of FreePBX.
//
//    FreePBX is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 2 of the License, or
//    (at your option) any later version.
//
//    FreePBX is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with FreePBX.  If not, see <http://www.gnu.org/licenses/>.
//
//    Copyright 2006 FreePBX
//
// HELPER FUNCTIONS:

function fw_ari_print_errors($src, $dst, $errors) {
	echo "error copying fw_ari files:<br />'cp -rf' from src: '$src' to dst: '$dst'...details follow<br />";
	foreach ($errors as $error) {
		echo "$error<br />";
	}
}

if (! function_exists('out')) {
	function out($text) {
		echo $text."<br>";
	}
}

if (! function_exists('outn')) {
	function outn($text) {
		echo $text;
	}
}

if (! function_exists('error')) {
	function error($text) {
		echo "[ERROR] ".$text."<br>";
	}
}

if (! function_exists('fatal')) {
	function fatal($text) {
		echo "[FATAL] ".$text."<br>";
		exit(1);
	}
}

if (! function_exists('debug')) {
	function debug($text) {
		global $debug;
		
		if ($debug) echo "[DEBUG-preDB] ".$text."<br>";
	}
}

global $amp_conf;
global $asterisk_conf;

$debug = false;
$dryrun = false;

/** verison_compare that works with freePBX version numbers
 *  included here because there are some older versions of functions.inc.php that do not have
 *  it included as it was added during 2.3.0beta1
 */
if (!function_exists('version_compare_freepbx')) {
	function version_compare_freepbx($version1, $version2, $op = null) {
		$version1 = str_replace("rc","RC", strtolower($version1));
		$version2 = str_replace("rc","RC", strtolower($version2));
		if (!is_null($op)) {
			return version_compare($version1, $version2, $op);
		} else {
			return version_compare($version1, $version2);
		}
	}
}

/*
 * fw_ari install script
 */
	$htdocs_ari_source = dirname(__FILE__)."/htdocs_ari/*";
	$htdocs_ari_dest = $amp_conf['AMPWEBROOT']."/recordings";

	if (!file_exists(dirname($htdocs_ari_source))) {
    out(sprintf(_("No directory %s, install script not needed"),dirname($htdocs_ari_source)));
    return true;
  }

	exec("cp -rf $htdocs_ari_source $htdocs_ari_dest 2>&1",$out,$ret);
	if ($ret != 0) {
		fw_ari_print_errors($htdocs_ari_source, $htdocs_ari_dest, $out);
	}
	// Make sure that libfreepbx.javascripts.js is available to ARI
	$libfreepbx = $amp_conf['AMPWEBROOT'].'/admin/common/libfreepbx.javascripts.js';
	$dest_libfreepbx = $htdocs_ari_dest.'/theme/js/libfreepbx.javascripts.js'; 
		if (file_exists($libfreepbx) && !file_exists($dest_libfreepbx)) {
		outn(_("linking libfreepbx.javascripts.js to theme/js.."));
		if (link($libfreepbx, $dest_libfreepbx)) {
			out(_("ok"));
		} else {
			out(_("possible error - check warning message"));
		}
	}
?>
