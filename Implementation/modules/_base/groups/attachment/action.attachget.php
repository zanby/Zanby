<?php
Warecorp::addTranslation('/modules/groups/attachment/action.attachget.php.xml');

    if ( isset($this->params['attachid']) && floor($this->params['attachid']) != 0 ) {
        if (Warecorp_Data_AttachmentFile::isAttachmentFileExists($this->params['attachid'])) {
            $attach = new Warecorp_Data_AttachmentFile($this->params['attachid']);

			header("Content-Type: " . $attach->getMimeType());
            header("Content-Length: ". filesize(ATTACHMENT_DIR.md5($attach->id).'.file'));
            header("Content-Disposition: attachment; filename=\"" . $attach->originalName . "\"");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate");
            header("Content-Location: ".$attach->originalName);
			readfile(ATTACHMENT_DIR.md5($attach->id).'.file');
			exit;
        }
    } else {
        //$this->_redirect($this->currentUser->getUserPath('documents'));
    }