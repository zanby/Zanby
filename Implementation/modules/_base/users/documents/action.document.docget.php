<?php
    if ( isset($this->params['docid']) && floor($this->params['docid']) != 0 ) {
        if (Warecorp_Document_Item::isDocumentExists($this->params['docid'])) {
            $Doc = new Warecorp_Document_Item($this->params['docid']);
            
            if ( ($Doc->isPrivate() && Warecorp_Document_AccessManager_Factory::create()->canViewPrivateDocuments($this->currentUser, $this->currentUser, $this->_page->_user->getId()))
                ||(!$Doc->isPrivate() && Warecorp_Document_AccessManager_Factory::create()->canViewPublicDocuments($this->currentUser, $this->currentUser, $this->_page->_user->getId()))
               ) {
                header("Content-Type: " . $Doc->getMimeType());
                header("Content-Length: ". filesize(DOC_ROOT.$Doc->getFilePath()));
                header("Content-Disposition: attachment; filename=\"" . $Doc->getOriginalName() . "\"");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: must-revalidate");
                header("Content-Location: ".$Doc->getOriginalName());
                readfile(DOC_ROOT.$Doc->getFilePath());
                exit;
            }
        }
    }
    $this->_redirect($this->currentUser->getUserPath('documents'));