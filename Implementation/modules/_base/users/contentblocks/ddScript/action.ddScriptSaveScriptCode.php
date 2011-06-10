<?php

$objResponse = new xajaxResponse();

$filename = SCRIPTING_UPLOAD_PATH."/".md5('user').md5($this->currentUser->getId()).$code;
$handle = fopen($filename.'.dat', "w+");
fwrite($handle, $contents);
fclose($handle);

$handle = fopen($filename.'.html', "w+");
$this->view->contents = $contents;
fwrite($handle, $this->view->getContents('content_objects/ddScript/html_content.tpl'));
fclose($handle);

$objResponse->addScript('applyEditMode2("'.$cloneId.'");');
