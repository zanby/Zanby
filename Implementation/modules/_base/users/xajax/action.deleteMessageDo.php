<?php
    Warecorp::addTranslation("/modules/users/xajax/action.deleteMessageDo.php.xml");

    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    $objResponse = new xajaxResponse();
    $objResponse->addScript("popup_window.close();");
    $report = '';
    $redirectUrl = '';
//    if (is_integer($messageId)) {
//        $message = new Warecorp_Message_Standard($messageId);
//        if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
//            if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) $message->delete();
//            else  $message->moveToTrash();  
//            $report = 'Deleted';
//            $redirectUrl = $this->_page->_user->getUserPath('messagelist');
//        }
//    }
    if (is_array($messageId)) {
        if ($messageId[0] != null) {
            $messageStandard = new Warecorp_Message_Standard($messageId[0]);
        	$eFolders = new Warecorp_Message_eFolders();
        	$folder = $eFolders->toString($messageStandard->getFolder());
        	if (count($messageId)>1) {
        		$messageText = Warecorp::t('Messages');
        	}
        	else {
        		$messageText = Warecorp::t('Message');
        	}
        	if ($folder == 'trash')
        		$report =  $messageText . ' ' . Warecorp::t('removed');
        	else $report = $messageText . ' ' . Warecorp::t('deleted');

        	$deletedCount = 0;

        	foreach ($messageId as $id){
        	    $message = new Warecorp_Message_Standard($id);
        	    if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
            	    if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) { 
            	       if ($message->delete()) $deletedCount++;
            	    }
                    else  {
                       if ($message->moveToTrash()) $deletedCount++;
                    }
        	    }
        	}
        	if ($deletedCount == 0) $report = Warecorp::t("Can't remove message");

        	$redirectUrl = $this->_page->_user->getUserPath('messagelist/folder/'.$folder);
        }
        else {
            $report = Warecorp::t('No messages selected');
            $redirectUrl = '';
        }
    }
    $objResponse->showAjaxAlert($report);
    $objResponse->addRedirect($redirectUrl);