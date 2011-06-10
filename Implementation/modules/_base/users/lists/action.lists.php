<?php
    Warecorp::addTranslation("/modules/users/lists/action.lists.php.xml");

    if (!Warecorp_List_AccessManager_Factory::create()->canViewLists($this->currentUser, $this->_page->_user->getId())) {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }
    $dateObj = new Zend_Date();
    $dateObj->setTimezone($this->_page->_user->getTimezone());

    $_url = $this->currentUser->getUserPath(null, false);

    $listTypes = Warecorp_List_Item::getListTypesListAssoc();
    $listTypes = array('0'=> Warecorp::t('All')) + $listTypes;

    if (!isset($this->params['type']) || !isset($listTypes[$this->params['type']])) $this->params['type'] = 0;

    $list = new Warecorp_List_List( $this->currentUser );
    if (!Warecorp_List_AccessManager_Factory::create()->canViewPrivateLists($this->currentUser, $this->_page->_user)) {
        $list->setPrivacy(0);
    }
    if ( Warecorp_List_AccessManager_Factory::create()->canViewSharedLists ($this->currentUser, $this->_page->_user) ) {
        $listsList = $list->setOrder('vli.title')->getListsListByType( $this->params['type'], true, true );
    } else {
        $listsList = $list->setOrder('vli.title')->getListsListByType( $this->params['type'], false, false );
    }

    $list->getAllListTags();

    foreach ($listTypes as $id => &$type) {
        $type = array(
            'title'     =>$type,
            'url'       =>$_url.'/lists/type/'.$id.'/',
            'active'    =>($id == $this->params['type']),
        );
    }

    $myListMenu = array(
        array('title' => Warecorp::t('List Index'), 'url' => $_url."/lists/", 'active' =>1),
        array('title' => Warecorp::t('Search and Browse Lists'), 'url' => $_url."/listssearch/", 'active' =>0),
    );

    $this->view->listsList = $listsList;
    $this->_page->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_show", "/users/listsConfirmPopupShow/");
    $this->_page->Xajax->registerUriFunction("list_confirm_popup_close", "/users/listsConfirmPopupClose/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_show", "/users/listsSharePopupShow/");
    $this->_page->Xajax->registerUriFunction("list_share_popup_close", "/users/listsSharePopupClose/");
//    $this->_page->Xajax->registerUriFunction("list_unshare_popup_show", "/users/listsUnsharePopupShow/");
//    $this->_page->Xajax->registerUriFunction("list_unshare_popup_close", "/users/listsUnsharePopupClose/");
    $this->_page->Xajax->registerUriFunction("list_share", "/users/listsShare/");
    $this->_page->Xajax->registerUriFunction("list_unshare", "/users/listsUnshare/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    // RSS
/*    if(LOCALE == "rss"){
        include_once(ENGINE_DIR."/rss.class.php");
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';    
        $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $rss->title = $this->currentUser->getLogin() . " lists ";
        $rss->description = $this->currentUser->getLogin() . " lists ";
        $rss->copyright = "Copyright &copy; 2007, Zanby";

        $lists = $list->getListsList();
        foreach ($lists as $list){
            $item = new FeedItem();
            $item->title = $list->getTitle();
            $item->link = "http://".$_SERVER['HTTP_HOST'] . "/" . "en/listsview/listid/". $list->getId() . "/";
            $item->description = "Number of elements: ". $list->getRecordsCount() . "<br/>";
            $rss->addItem($item);
        }
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit;
    }*/
    // RSS end

// @todo - remove this block
//    breadcrumb
//    if ( $this->currentUser->getId() != $this->_page->_user->getId() ) {
//       $this->_page->breadcrumb = array_merge($this->_page->breadcrumb,
//                                           array($this->currentUser->getCity()->getState()->getCountry()->name => BASE_URL."/" .$this->_page->Locale. "/users/index/view/allstates/country/" .$this->currentUser->getCity()->getState()->getCountry()->id. "/",
//                                                 $this->currentUser->getCity()->getState()->name => BASE_URL."/" .$this->_page->Locale. "/users/index/view/allcities/state/" .$this->currentUser->getCity()->getState()->id. "/",
//                                                 $this->currentUser->getCity()->name => BASE_URL."/" .$this->_page->Locale. "/users/search/preset/city/id/" .$this->currentUser->getCity()->id. "/",
//                                                 $this->currentUser->getLogin() => null
//                                                 )
//                                           );
//    }

    $this->view->bodyContent = 'users/lists/lists.tpl';
    $this->view->addListLink = $_url.'/listsadd/'.(!empty($this->params['type']) ? "type/".floor($this->params['type'])."/" :"" );
    $this->view->deleteListLink = $_url.'/listsdelete/';
    $this->view->viewListLink = $_url.'/listsview/';
    $this->view->editListLink = $_url.'/listsedit/';
    $this->view->offWatchLink = $_url.'/listsoffwatch/';
    $this->view->typesTabs = $listTypes;
    $this->view->myListMenu = $myListMenu;
    $this->view->type = $this->params['type'];
    $this->view->recordObj = new Warecorp_List_Record();
    $this->view->listsTags = $list->getAllListTags();
    $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
    $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
    $this->view->friendsAssoc = $this->_page->_user->getId() ? $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();
