<?php
    $this->_page->Xajax->registerUriFunction ( "deleteMessage", "/users/deleteMessage/" ) ;
    $this->_page->Xajax->registerUriFunction ( "deleteMessageDo", "/users/deleteMessageDo/" ) ;
    $this->_page->Xajax->registerUriFunction ( "closePopup", "/ajax/closePopup/" ) ;
    $this->_page->Xajax->registerUriFunction ( "restoreMessage", "/users/restoreMessage/" ) ;
    
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    
    $this->params = $this->_getAllParams();

    $this->_page->Xajax->registerUriFunction("messages_markasread", "/users/messagesMarkAsRead/");
    $this->_page->Xajax->registerUriFunction("messages_markasunread", "/users/messagesMarkAsUnread/");
    
	$folder = (isset($this->params['folder'])) ? $this->params['folder'] : 'inbox';
	$order = (isset($this->params['order'])) ? $this->params['order'] : 'date-desc';
	
	// fields for headers
    $fields['from'] = array("active" => ($order == "from-asc" || $order == "from-desc"), "order" => ($order != "from-asc") ? "from-asc" : "from-desc", "name" => "from", "direction" => ($order == "from-asc") ? "up" : "down");
    $fields['to'] = array("active" => ($order == "to-asc" || $order == "to-desc"), "order" => ($order != "to-asc") ? "to-asc" : "to-desc", "name" => "to", "direction" => ($order == "to-asc") ? "up" : "down");
    $fields['subject'] = array("active" => ($order == "title-asc" || $order == "title-desc"), "order" => ($order != "title-asc") ? "title-asc" : "title-desc", "name" => "subject", "direction" => ($order == "title-asc") ? "up" : "down");
    $fields['date'] = array("active" => ($order == "date-asc" || $order == "date-desc"), "order" => ($order != "date-asc") ? "date-asc" : "date-desc", "name" => "date", "direction" => ($order == "date-asc") ? "up" : "down");
	// messages list
	$messageManager = new Warecorp_Message_List();
	$folderList = $messageManager->getMessagesFoldersList($this->_page->_user->getId());
	$page = (isset($this->params['page'])) ? $this->params['page'] : 1;
	$messageManager->setListSize(25);
	$messageManager->setCurrentPage($page);
	$messageManager->setFolder(Warecorp_Message_eFolders::toInteger($folder));
	$messageManager->setOrder($order);

	$messagelist = $messageManager->findByOwner($this->_page->_user->getId());
    $deleteForm = new Warecorp_Form('deleteForm', 'post', '/'.$this->_page->Locale.'/messagedelete/');
	
    $pagingUrl = $this->_page->_user->getUserPath('messagelist') . 'folder/'.$folder . '/order/' . $order;
	$P = new Warecorp_Common_PagingProduct($folderList[$folder]['all'], $messageManager->getListSize(), $pagingUrl);
    
    $this->view->fields = $fields;
    $this->view->order = $order;
    $this->view->deleteForm = $deleteForm;
    $this->view->folder = $folder;
    $this->view->folders = $folderList;
    $this->view->messagelist = $messagelist;
	$this->view->infoPaging = $P->makeInfoPaging($page);
	$this->view->linkPaging = $P->makeLinkPaging($page);
	$this->view->bodyContent = 'users/messages/messagelist.tpl';
