<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddScript/action.ddScriptSaveScriptCode.php.xml');

$objResponse = new xajaxResponse();

$filename = SCRIPTING_UPLOAD_PATH."/".md5('group').md5($this->currentGroup->getId()).$code;
$handle = fopen($filename.'.dat', "w+");
fwrite($handle, $contents);
fclose($handle);

$handle = fopen($filename.'.html', "w+");
$this->view->contents = $contents;
fwrite($handle, $this->view->getContents('content_objects/ddScript/html_content.tpl'));
fclose($handle);

$objResponse->addScript('applyEditMode2("'.$cloneId.'");');
