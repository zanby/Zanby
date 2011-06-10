<?php
    $this->_page->Xajax->registerUriFunction ( "deleteMessage", "/users/deleteMessage/" ) ;
    $this->_page->Xajax->registerUriFunction ( "deleteMessageDo", "/users/deleteMessageDo/" ) ;
    $this->_page->Xajax->registerUriFunction ( "closePopup", "/ajax/closePopup/" ) ;
    //access control
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    $this->params = $this->_getAllParams();
    // message
    $this->view->message = $message = new Warecorp_Message_Standard($this->params['id']);
    $this->view->canReply = ($message->getSender() instanceof Warecorp_User && $message->getFolder() != Warecorp_Message_eFolders::SENT);
    
    $defaultTimezone = date_default_timezone_get(); 
    date_default_timezone_set('UTC');
    $_usertime = new Zend_Date();
    //$_usertime->setTimezone("UTC");
    $_usertime->setIso($message->getCreateDate());
    date_default_timezone_set($this->_page->_user->getTimezone()); 
//    $_usertime->setTimezone($this->_page->_user->getTimezone());
    $this->view->messageCreateDate = date("Y-m-d H:i:s",$_usertime->getTimeStamp());
    date_default_timezone_set($defaultTimezone); 
    if (!($message) || ($message->getOwnerId() != $this->_page->_user->getId())) {
        $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/');
    }
        
    if ($message->getIsRead() == 0){
        $message->setIsRead(1);
        $message->update();
    }
    $folder = $message->getFolder();
    $order = (isset($this->params['order'])) ? $this->params['order'] : 'date-desc';
    // folder list
	$messageManager = new Warecorp_Message_List();
	$folderList = $messageManager->getMessagesFoldersList($this->_page->_user->getId());
	$messageManager->setFolder($folder);
	$messageManager->setOrder($order);
	
	$folder = Warecorp_Message_eFolders::toString($folder);
	$this->view->previous = $messageManager->getPreviousMessageId($message, $this->_page->_user->getId());
	$this->view->next = $messageManager->getNextMessageId($message, $this->_page->_user->getId());
    $this->view->folder = $folder;
    $this->view->folders = $folderList;
    $this->view->order = $order;
	$this->view->infoPaging = $messageManager->getIndexMessageInList($message->getId(), $this->_page->_user->getId()) . ' of ' . $folderList[$folder]['all'];
	
    $this->view->bodyContent = 'users/messages/messageview.tpl'; 
