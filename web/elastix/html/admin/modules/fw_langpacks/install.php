<?php

// HELPER FUNCTIONS:

function fw_langpacks_print_errors($src, $dst, $errors) {
	echo "error copying fw_langpacks files:<br />'cp -rf' from src: '$src' to dst: '$dst'...details follow<br />";
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
 * fw_langpacks install script
 *
 * for each installed component on the target system, copy localization files using the -u option
 * on copy which will only copy them if our copy is newer then the destination which protects
 * from overwriting destination files that have been updated by the user.
 */
	$htdocs_source = dirname(__FILE__)."/htdocs";
	$htdocs_dest = $amp_conf['AMPWEBROOT'];

	if (!file_exists($htdocs_source)) {
    out(sprintf(_("No directory %s, install script not needed"),$htdocs_source));
    return true;
  }

	// Always copy main FreePBX amp.po/mo files
	//
	out(sprintf(_("Preparing to copy %s to %s"),'i18n',"$htdocs_dest/admin"));
	$htdocs_copy[] = array("source" => "$htdocs_source/admin/i18n", "dest" => "$htdocs_dest/admin");

	// If ARI is there copy those
	//
	if (is_dir("$htdocs_dest/recordings")) {
		$htdocs_copy[] = array("source" => "$htdocs_source/recordings", "dest" => "$htdocs_dest");
		out(sprintf(_("Preparing to copy %s to %s"),'recordings',"$htdocs_dest"));
	} else {
		out(sprintf(_("No destination directory %s to copy %s to"),"$htdocs_dest/recordings","recordings"));
	}

	// Now for each module we have, make sure the module is in the destination as we don't want to create
	// empty destinatino folders with just i18n directories.
	//
	$dir = opendir($htdocs_source.'/admin/modules');
	while ($file = readdir($dir)) {
		if (is_dir("$htdocs_dest/admin/modules/$file") && ($file != ".") && ($file != "..")) {
			out(sprintf(_("Preparing to copy %s to %s"),"$file","$htdocs_dest/admin/modules"));
			$htdocs_copy[] = array("source" => "$htdocs_source/admin/modules/$file", "dest" => "$htdocs_dest/admin/modules");
		} else if ($file != "." && $file != "..") {
			out(sprintf(_("No destination directory %s to copy %s to"),"$htdocs_dest/modules/$file","$file"));
		}
	}

	foreach ($htdocs_copy as $translations) {
		exec("cp -ru ".$translations['source']." ".$translations['dest']." 2>&1",$out,$ret);
		if ($ret != 0) {
			fw_langpacks_print_errors($translations['source'], $translations['dest'], $out);
		} else {
			out(sprintf(_("Updated %s"),basename($translations['source'])));
		}
	}
?>
