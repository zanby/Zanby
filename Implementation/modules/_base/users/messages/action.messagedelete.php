<?php
    Warecorp::addTranslation("/modules/users/messages/action.messagedelete.php.xml");
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    $this->params = $this->_getAllParams();

    if (isset($this->params['id'])) {
        $message = new Warecorp_Message_Standard($this->params['id']);
        if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
            if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) {
            	$message->delete();
            	$report = Warecorp::t("Deleted");
            }
            else {
                $message->moveToTrash();
                $report = Warecorp::t('Moved to trash');
            }
            $report = Warecorp::t('Deleted');
        } else {
            $report = Warecorp::t('Access denied');
        }
//        $this->_page->showAjaxAlert($report);
        $this->_redirect($this->_page->_user->getUserPath('messagelist'));
    } elseif (isset($this->params['folder'])) {
        $messageManager = new Warecorp_Message_List();
        $messageManager->setFolder(Warecorp_Message_eFolders::toInteger($this->params['folder']));
        $messages = $messageManager->findAllByOwner($this->_page->_user->getId());

        if (count($messages)) {
            $result = true;

            foreach($messages as $message) {
                if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) {
                	$result = $result && $message->delete();
                }
                else {
                    $result = $result && $message->moveToTrash();
                }
            }
            if ($result) {
                $report = Warecorp::t("Folder '%s' is empty", $this->params['folder']);
            }
            else {
                $report = Warecorp::t("Access denied");
            }
        } else {
             $report = Warecorp::t("Folder '%s' is empty", $this->params['folder']);
        }
//        $this->_page->showAjaxAlert($report);
        if (isset($this->params['activefolder'])) {
            $this->_redirect($this->_page->_user->getUserPath('messagelist').'folder/' . $this->params['activefolder'] . '/');
        }
        else $this->_redirect($this->_page->_user->getUserPath('messagelist').'folder/' . $this->params['folder'] . '/');
    } elseif (isset($this->params['deletelist'])) {

        if (isset($this->params['message_id'])) {
            $message_ids = array_keys($this->params['message_id']);
            $result = true;
            foreach($message_ids as $message_id) {

                $message = new Warecorp_Message_Standard($message_id);

                if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {

                    if ($message->getFolder() == Warecorp_Message_eFolders::TRASH ) {
                    	$result = $result && $message->delete();
                    }
                    else {
                        $result = $result && $message->moveToTrash();
                    }
                }
            }
            if ($result) {
                $report = Warecorp::t("Deleted");
            }
            else {
                $report = Warecorp::t("Access denied");
            }
        }
        else {
            $report = Warecorp::t("No message selected");
        } 
      
//        $this->_page->showAjaxAlert($report);
        $this->_redirect($this->_page->_user->getUserPath('messagelist'));
    }    
//    }elseif (isset($this->params['recoverylist'])) {
//        
//        if (isset($this->params['message_id'])) {
//            $message_ids = array_keys($this->params['message_id']);
//            $result = true;
//            foreach($message_ids as $message_id) {
//                $message = new Warecorp_Message_Standard($message_id);
//                
//                if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
//                	$result = $result && $message->recovery();
//                }
//            }
//            if ($result) {
//                $report = "restored";
//            }
//            else {
//                $report = "not restored";
//            }
//            
//        }
//        else {
//            $report = "No message selected";
//        } 
//        $property->width = 250;
//        $property->height = 100;
//        $this->_page->showAjaxAlert($report, $property);
//        $this->_redirect(LOCALE . '/messagelist/folder/trash/');
//    } 
    else {
        $report = Warecorp::t("Unknown action");
        $this->_redirect($this->_page->_user->getUserPath('messagelist'));
    }
    //$this->view->message = $report;
    //$this->view->bodyContent = 'users/messages/message_delete.tpl';
