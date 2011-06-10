<?php
Warecorp::addTranslation('/modules/groups/action.getpublishdata.php.xml');

//
//REMOVE THIS FILE.
//

$cont = Warecorp_DDPages::getAllBlocksHTML($this->_page, $this->currentGroup, $this->_page->_user);

$out = str_replace('"', "'", $cont["Content"]);
//$out = str_replace("\r\n", "", $out);
$out = str_replace("\r", "", $out);
$out = str_replace("\n", "", $out);


$out = "document.write(\"".$out."\");";

print $out;
exit;
/**/
