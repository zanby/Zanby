<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.volunteer.delete.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");
    
    $volunteer_id = isset($volunteer_id) ? (int)$volunteer_id : 0;
    $record_id    = isset($record_id)    ? (int)$record_id : 0;
    $record = new Warecorp_List_Record($record_id);
    
    if (!$AccessManager->canDeleteVolunteer($volunteer_id, $record, $this->currentGroup, $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';

    if ($record->getId()) {
        $record->deleteVolunteer($volunteer_id);

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, new Warecorp_List_Item($record->getListId()), "LISTS", "CHANGES", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "CHANGES");
//            $mail->addParam('section', "LISTS");
//            $mail->addParam('chObject', new Warecorp_List_Item($record->getListId()));
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/

        $objResponse->addRemove("volunteer_".($volunteer_id));
        $this->listsViewRefresh($objResponse, $record->getListId());
    }
