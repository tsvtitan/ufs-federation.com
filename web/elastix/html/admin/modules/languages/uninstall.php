<?php

global $db;

echo "dropping table languages..";
sql("DROP TABLE IF EXISTS `languages`");
echo "done<br>\n";

echo "dropping table language_incoming..";
sql("DROP TABLE IF EXISTS `language_incoming`");
echo "done<br>\n";


?>
