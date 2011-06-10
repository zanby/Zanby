<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.edit.delete.record.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
//     if (!$AccessManager->canManageLists($this->currentGroup, $this->_page->_user->getId())) {
//         $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
//         return;
//     }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'edit';
    
    if (isset($_SESSION['list_edit']) && isset($record_id) && isset($_SESSION['list_edit']['records'][$record_id])){

        $_list = new Warecorp_List_Item($_SESSION['list_edit']['id']);
	    if (!$AccessManager->canManageList($_list, $this->currentGroup, $this->_page->_user->getId())) {
    	    $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
       	 	return;
    	}
		$list_edit = &$_SESSION['list_edit'];
        $this->listsDeleteRecord($objResponse, $record_id, $list_edit);

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, new Warecorp_List_Item($list_edit['id']), "LISTS", "CHANGES", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "CHANGES");
//            $mail->addParam('section', "LISTS");
//            $mail->addParam('chObject', new Warecorp_List_Item($list_edit['id']));
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/
    }
