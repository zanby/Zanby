<?php

$objResponse = new xajaxResponse();

$filename = SCRIPTING_UPLOAD_PATH."/".md5('user').md5($this->currentUser->getId()).$code;
if (file_exists($filename.'.dat')) {
    @unlink($filename.'.dat');
}
if (file_exists($filename.'.html')) {
    @unlink($filename.'.html');
}
