<?php
// Try to determine the distro and kernel of the current machine
function irc_getversioninfo() {
  // Various places that may have the Distro Name..
  $version = "";
  $locations = array(
  	'Redhat' => '/etc/redhat-release', 
	'Fedora' => '/etc/fedora-release', 
	'Debian' => '/etc/debian_version', 
	'SuSE' => '/etc/SuSE-release', 
	'Gentoo' => '/etc/gentoo-release'
  );
  foreach ($locations as $distro => $loc) {
	if (is_readable($loc)) {
		$fh = fopen($loc, "r");
		if ($version != "") {
			$version .= " OR ".$distro.' '.fgets($fh, 80);
		} else {
			$version = $distro.' '.fgets($fh, 80);
		}
	}
  }
  if ($version == "") { 
	return "Unknown Version";
  } else {
	$lastchar = substr("$version", strlen("$version") - 1, 1);
               if ($lastchar == "\n")
               {
                       $version = substr("$version", 0, -1);
               } 
  	return $version; 
  }
}
?>
