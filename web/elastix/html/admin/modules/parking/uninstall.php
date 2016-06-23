<?php /* $Id: uninstall.php  $ */
//
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

parking_remove_config();


function parking_remove_config() {
		//open the file and truncate. If diabled, file will be deleted this way
		//AND GET THE ENV VARIABLES TO CALL THIS BY

		$filename = (isset($amp_conf["ASTETCDIR"]) ? $amp_conf["ASTETCDIR"] : "/etc/asterisk/") . "parking_additional.inc";
		$fh = fopen($filename, "w+");
		fwrite($fh, ";*** WARNING: DO NOT HAND EDIT THIS FILE IT IS AUTO-GENERATD ***\n;\n");
		fwrite($fh, ";***              PARKING LOT HAS BEEN DISABLED              ***\n;\n");
		fwrite($fh, ";*** The parking lot module has been removed! ***\n;\n");
		fwrite($fh, ";*** Edits will be preserved unless the module is reinstalled. ***\n;\n");
		fclose($fh);
		chmod($filename, 0660);
}

?>
