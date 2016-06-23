<?php

// Delete the old code if still there
//
$fcc = new featurecode('daynight', 'toggle-mode');
$fcc->delete();
unset($fcc);	

$list = daynight_list();
foreach ($list as $item) {
	$id = $item['ext'];
	$fcc = new featurecode('daynight', 'toggle-mode-'.$id);
	$fcc->delete();
	unset($fcc);	
}

sql('DROP TABLE IF EXISTS daynight');

?>
