<?php
    Warecorp::addTranslation("/modules/users/xajax/action.restoreMessageDo.php.xml");

    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }

    $objResponse = new xajaxResponse();
    $objResponse->addScript("popup_window.close();");
    $report = '';
    if (is_numeric($messageId)) {
        $message = new Warecorp_Message_Standard($messageId);
        if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
            if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) $message->delete();
            else  $message->moveToTrash();  
            $report = Warecorp::t('Message deleted');
            $redirectUrl = $this->_page->_user->getUserPath('messagelist');
        }
    }
    elseif (is_array($messageId)) {
        if ($messageId[0] != null) {
            $messageStandard = new Warecorp_Message_Standard($messageId[0]);
        	$eFolders = new Warecorp_Message_eFolders();
        	$folder = $eFolders->toString($messageStandard->getFolder());
        	if ($folder == 'trash') 
        		$report = Warecorp::t('Messages removed');
        	else $report = Warecorp::t('Messages deleted');
        	foreach ($messageId as $id){
        	    $message = new Warecorp_Message_Standard($id);
        	    if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
            	    if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) $message->delete();
                    else  $message->moveToTrash();
        	    }
        	}
        	$redirectUrl = $this->_page->_user->getUserPath('messagelist/folder/'.$folder);
        }
        else {
            $report = Warecorp::t('No messages selected');
            $redirectUrl = '';
        }
    }
    elseif (is_string($messageId)) {
        if ( $messageId == 'trash') {
        $messageManager = new Warecorp_Message_List();
        $messageManager->setFolder(Warecorp_Message_eFolders::toInteger($messageId));
        $messages = $messageManager->findAllByOwner($this->_page->_user->getId()); 
            if (count($messages)) {
                $redirectUrl = $this->_page->_user->getUserPath('messagedelete/folder/trash');
                $report = Warecorp::t('Trash is empty');
            }
            elseif (count($messages) == 0){
                $redirectUrl = '';
                $report = Warecorp::t('Trash is empty');
            }
        }
    }
    $objResponse->showAjaxAlert($report);
    $objResponse->addRedirect($redirectUrl);
