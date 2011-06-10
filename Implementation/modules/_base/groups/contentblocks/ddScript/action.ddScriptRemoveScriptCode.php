<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddScript/action.ddScriptRemoveScriptCode.php.xml');

$objResponse = new xajaxResponse();

$filename = SCRIPTING_UPLOAD_PATH."/".md5('group').md5($this->currentGroup->getId()).$code;
if (file_exists($filename.'.dat')) {
    @unlink($filename.'.dat');
}
if (file_exists($filename.'.html')) {
    @unlink($filename.'.html');
}
