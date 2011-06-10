<?php
    Warecorp::addTranslation("/modules/users/documents/action.documents.php.xml");
    $AccessManager = Warecorp_Document_AccessManager_Factory::create();
    
    if ( !Warecorp_Document_AccessManager_Factory::create()->canViewOwnerDocuments($this->currentUser, $this->currentUser, $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }

    $this->_page->Xajax->registerUriFunction("change_main_folder", "/users/documentChangeMainFolder/");
    $this->_page->Xajax->registerUriFunction("change_folder", "/users/documentChangeFolder/");
    $this->_page->Xajax->registerUriFunction("create_folder", "/users/documentCreateFolder/");
    $this->_page->Xajax->registerUriFunction("sort_files", "/users/documentSort/");
    $this->_page->Xajax->registerUriFunction("addtomy", "/users/documentAddToMy/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
	$this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
	$this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
	$this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");	

    //  Build Folders Tree
    //**************************************************************
    $treeObj = $this->currentUser->getArtifacts()->createDocumentTree();
    $treeObj->setCallbackFunction('DocumentApplication.changeActiveFolder');

    $tree = $treeObj->startTree('tree_0', 'tree_div_0');
    $users = array();
    array_unshift($users, $this->currentUser);
    $allowed_users = array();
    if ( sizeof($users) != 0 ) {
        foreach ( $users as $user ) {
            if ( true == Warecorp_Document_AccessManager_Factory::create()->canViewOwnerDocuments($this->currentUser, $user, $this->_page->_user->getId()) ) {
                $tmpTreeObj = $user->getArtifacts()->createDocumentTree();
                $tmpTreeObj->setShowDocuments(false);
                $tmpTreeObj->setShowMainFolder(true);
                $tmpTreeObj->setMainFolderName($user->getLogin());
                $tmpTreeObj->setMainCallbackFunction('DocumentApplication.cangeActiveMainFolder');
                $tmpTreeObj->setShowShared(false);
                $tree .= $tmpTreeObj->buildTree('tree_0');
                $allowed_users[] = $user;
            }
        }
    }
    $users = $allowed_users;
    unset($allowed_users);
    $tree .= $treeObj->endTree('tree_0');
    $this->view->DocumentTreeJS = $tree;

    //**************************************************************


    //  Build Current Folder List
    //**************************************************************
    $currUser = $users[0];
	$folder_id = isset($this->params['folder']) ? (int)floor($this->params['folder']) : null;
    $privacy = array();
    $shared = false;
    if ( Warecorp_Document_AccessManager_Factory::create()->canViewPublicDocuments($this->currentUser, $currUser, $this->_page->_user->getId()) ) {
        $privacy[] = 0;
    }
    if ( Warecorp_Document_AccessManager_Factory::create()->canViewPrivateDocuments($this->currentUser, $currUser, $this->_page->_user->getId()) ) {
        $privacy[] = 1;
        $shared = true;
    }

    $s = &$_SESSION['documents']['user']['order'][$this->currentUser->getId()];
    $s['files'] = "original_name asc";
    $s['dirs']  = "name asc";

    $listObj = $currUser->getArtifacts()->createDocumentList();
    $listObj->setCurrentUser($this->_page->_user);
    $listObj->setFolder($folder_id);
    $listObj->setPrivacy($privacy);
    $listObj->setShowShared($shared);
    $listObj->setOrder($s['files']);
    $documentsList = $listObj->getList($this->_page->_user);

    foreach($documentsList as &$doc) {
        $ddd = new Warecorp_Document_Item($doc->getId());
        $doc->setDescription($ddd->getDescription());
    }

    $foldersList = $currUser->getArtifacts()->getDocumentsFoldersList()->setFolder($folder_id)->setOrder($s['dirs'])->getList();
    $currentFolder = new Warecorp_Document_FolderItem($folder_id);

    $this->view->Warecorp = new Warecorp();
    $this->view->currUser = $currUser;
    $this->view->foldersList = $foldersList;
    $this->view->currentFolder = $currentFolder;
    $this->view->documentsList = $documentsList;
    $this->view->AccessManager = Warecorp_Document_AccessManager_Factory::create();

    //**************************************************************

    //  Rss Action
    //  @todo Для рсс сейчас показываются только для первого уровня, т.е без учета папок, над все
    //**************************************************************
/*	if (LOCALE == "rss") {
        include_once(ENGINE_DIR."/rss.class.php");
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
		$rss->link = "http://" . $_SERVER [ 'HTTP_HOST' ] . $_SERVER [ 'REQUEST_URI' ] ;
		$rss->title = $this->currentUser->getLogin() . "'s" . " documents " ;
		$rss->description = $this->currentUser->getLogin() . "'s" . " documents " ;
		$rss->copyright = "Copyright &copy; 2007, Zanby" ;
		$documents = $this->currentUser->getArtifacts()->createDocumentList()
		                  ->setShowShared($shared)->setFromAllFolders(true)->setOrder('vdl.creation_date DESC')->getList();
		foreach ( $documents as $document ) {
			$item = new FeedItem() ;
			$item->title = $document->getOriginalName();
			$path = substr ( $this->currentUser->getUserPath ( 'docget' ), 0, strlen ( $this->currentUser->getUserPath ( 'docget' ) ) - 11 ) ;
			$item->link = $path . "en/docget/docid/" . $document->getId() . "/" ;
			$item->description = $document->getDescription() ;
			$rss->addItem ( $item ) ;
		}
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit;
	}*/
// @todo - remove this block
	// Build Breadcrumb
    //**************************************************************
//    if ( $this->currentUser->getId() != $this->_page->_user->getId() ) {
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentUser->getCity()->getState()->getCountry()->name => "/" .$this->_page->Locale. "/users/index/view/allstates/country/" .$this->currentUser->getCity()->getState()->getCountry()->id. "/",
//                 $this->currentUser->getCity()->getState()->name => "/" .$this->_page->Locale. "/users/index/view/allcities/state/" .$this->currentUser->getCity()->getState()->id. "/",
//                 $this->currentUser->getCity()->name => "/" .$this->_page->Locale. "/users/search/preset/city/id/" .$this->currentUser->getCity()->id. "/",
//                 $this->currentUser->getLogin() => null
//                 )
//            );
//    }
	
    $this->view->friendsAssoc = $this->_page->_user->getId() ?  $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();
    $this->view->SWFUploadID = session_id();
    $this->view->errors = array(Warecorp::t('Please select file to upload'));
    $this->view->friendsAssoc = $this->_page->_user->getId() ?  $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();
    $this->view->upload_max_filesize = DOCUMENTS_SIZE_LIMIT;
    $this->view->bodyContent = 'users/documents/documents.tpl';
