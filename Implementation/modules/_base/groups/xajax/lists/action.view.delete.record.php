<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.view.delete.record.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");
    
    $record_id = isset($record_id) ? (int)$record_id : null;
    $record = new Warecorp_List_Record($record_id);
    $objResponse->addScript('popup_window.close();');

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;
	
    if (!$AccessManager->canManageRecord($record, $this->currentGroup, $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';
    
    if ($record->getId()) {
        $list = new Warecorp_List_Item($record->getListId());
        $objResponse->addRemove("item_".($record->getId()));
        $record->delete();

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $list, "LISTS", "CHANGES", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "CHANGES");
//            $mail->addParam('section', "LISTS");
//            $mail->addParam('chObject', $list);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/

        $recordsCount = ($list->getRecordsCount()!=1) ? Warecorp::t("There are %s items in this list", array($list->getRecordsCount())) 
                                : Warecorp::t("There is %s item in this list",array($list->getRecordsCount()));
        $objResponse->addAssign("records_count",'innerHTML',  $recordsCount);
        $this->listsViewRefresh($objResponse, $record->getListId());
    }
