<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.volunteer.popup.close.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

    $record = isset($data['record_id']) ? new Warecorp_List_Record($data['record_id']) : new Warecorp_List_Record();

    if (count($data) != 0 && $this->_page->_user->getId() && $record->getId()) {
        $list = new Warecorp_List_Item($record->getListId());

    	if (!$AccessManager->canViewList($list, $this->currentGroup, $this->_page->_user->getId())) {
	        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
	        return;
	    }

        $form = new Warecorp_Form('volunteer_form', 'POST', 'javascript:void(0);');
        $form->addRule('comment', 'maxlength', Warecorp::t('Comment too long. %s characters available',100), array('max' => 100));

        if (isset($data['_wf__volunteer_form'])) {
            $_REQUEST['_wf__volunteer_form'] = $data['_wf__volunteer_form'];
        }

        if ($form->validate($data)) {
            $record->addVolunteer($data['comment']);

            /** Send notification to host **/
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $list, "LISTS", "CHANGES", false );
            
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "CHANGES");
//                $mail->addParam('section', "LISTS");
//                $mail->addParam('chObject', $list);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->sendToPMB(true);
//                $mail->send();
//            }
            /** --- **/

            $this->listsViewRefresh($objResponse, $record->getListId());
            $objResponse->addScript("popup_window.close();");
        } else {
            $list = new Warecorp_List_Item($record->getListId());
            $this->view->form = $form;
            $this->view->list = $list;
            $this->view->record = $record;
            $this->view->assign($data);
            
            //$content = $this->view->getContents('groups/lists/volunteer.popup.tpl');
            $content = '';
            foreach ($form->getErrorMessages() as $m) {
                $content .='<p><strong>'.Warecorp::t('ERROR').':</strong> '.$m.'</p>';
            }
            $objResponse->addClear("ErrorMessageMainTooLong", "innerHTML");
            $objResponse->addAssign("ErrorMessageMainTooLong", "innerHTML", $content);
            $objResponse->addAssign("ErrorMessageMainTooLong", "style.display", '');
        }
    } else {
        if (!$AccessManager->canViewLists($this->currentGroup, $this->_page->_user->getId())) {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        }
        $objResponse->addScript("popup_window.close();");
    }
