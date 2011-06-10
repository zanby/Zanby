<?php
    Warecorp::addTranslation("/modules/users/gallery/action.photos.php.xml");

    if ( !Warecorp_Photo_AccessManager_Factory::create()->canViewGalleries($this->currentUser, $this->_page->_user) ) {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }

    $this->_page->Xajax->registerUriFunction("share_group", "/users/galleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/users/galleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/users/galleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/users/galleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/users/galleryUnShareDo/");
    $this->_page->Xajax->registerUriFunction("unshare_group_do", "/users/galleryUnShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("unshare_friend_do", "/users/galleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("stop_watching_do", "/users/galleryStopWatchingDo/");
    $this->_page->Xajax->registerUriFunction("delete_gallery", "/users/galleryDeleteGallery/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/users/galleryShowShareHistory/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");

    $items_per_page = 9;
    $item_width     = 175;
    $item_height    = 175;
//     $sortList = array(
//         'maxkey' => 6,
//         'titles' => array(
//             1 => Warecorp::t('All - Newest to oldest'),
//             2 => Warecorp::t('All - Oldest to Newest'),
//             3 => Warecorp::t('All - by Name Alphabetical'),
//             4 => Warecorp::t('Galleries I have shared'),
//             5 => Warecorp::t('Galleries that have been shared with me'),
//             6 => Warecorp::t('Galleries with your comments')
//         )
//     );
    $sortList = array(
        'maxkey' => 3,
        'titles' => array(
            1 => Warecorp::t('Newest to oldest'),
            2 => Warecorp::t('Oldest to Newest'),
            3 => Warecorp::t('Name alphabetical')
        )
    );
    $showList = array(
        'maxkey' => 6,
        'titles' => array(
            1 => Warecorp::t('All galleries'),
            5 => Warecorp::t('Galleries shared with me'),
            6 => Warecorp::t('Galleries with my comments')
        )
    );

    if ($this->_page->_user->getId() === $this->currentUser->getId()) {
		$showList['titles'][2] = Warecorp::t('Uploaded galleries');
		$showList['titles'][3] = Warecorp::t('Shared galleries');
		$showList['titles'][4] = Warecorp::t('Watching galleries');
	}
    ksort($showList['titles']);

    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
    $this->params['sort'] = (isset($this->params['sort']))? $this->params['sort'] : 1;
    $this->params['show'] = (isset($this->params['show']))? $this->params['show'] : 1;
    $sort = ($this->params['sort'] <= $sortList['maxkey'])?$this->params['sort']:1;
	$show = in_array($this->params['show'], array_keys($showList['titles']))?$this->params['show']:1;

    $parivacy = array();
    if ( Warecorp_Photo_AccessManager_Factory::create()->canViewPublicGalleries($this->currentUser, $this->_page->_user) ) $parivacy[] = 0;
    if ( Warecorp_Photo_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentUser, $this->_page->_user) ) $parivacy[] = 1;

    //Warecorp_User::getGalleries()
    $galleriesList = $this->currentUser->getGalleries()
                          ->setPrivacy($parivacy);
    $tags = $galleriesList->getAllPhotosTags();
    switch ($sort) {
        case 1:
            $galleriesList->setOrder('creation_date desc');
            break;
        case 2:
            $galleriesList->setOrder('creation_date asc');
            break;
        case 3:
            $galleriesList->setOrder('title asc');
            break;
    }

    switch ($show) {
		case 1:

			break;
		case 2:
            $galleriesList->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
		                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN);
			break;
		case 3:
            $galleriesList->setCustomCondition()
						  ->addWhere('share = 1')
                          ->addWhere("real_owner_type = 'user'")
                          ->addWhere('real_owner_id = '.$this->_page->_user->getId());
			break;
        case 4:
            $galleriesList->addWhere('watch = 1')
                          ->addWhere("owner_type = 'user'")
                          ->addWhere('owner_id = '.$this->_page->_user->getId());
			break;
        case 5:
			if ($this->_page->_user->getId() != $this->currentUser->getId()){
				$tempGalleriesList = $this->currentUser->getGalleries();
	            $list = $tempGalleriesList->setCustomCondition()
							  ->addWhere('share = 1')
	                          ->addWhere("real_owner_type = 'user'")
	                          ->addWhere('real_owner_id = '.$this->currentUser->getId())
	                          ->addWhere("owner_type = 'user'")
	                          ->addWhere('owner_id = '.$this->_page->_user->getId())
	                          ->returnAsAssoc()->getList();
				$list = array_keys($list);
				if (!empty($list)) $galleriesList->setIncludeIds($list); else $galleriesList->addWhere("1=2");
			}else{
				$galleriesList->setSharingMode(Warecorp_Photo_Enum_SharingMode::SHARED);
			}
            break;
        case 6:
            $galleriesList->setWithComments($this->_page->_user->getId());
            break;
    }

    $galleriesCount = $galleriesList->getCount();

    $galleriesList = $galleriesList
                       ->setCurrentPage($this->params['page'])
                       ->setListSize($items_per_page)
                       ->getList();

    $this->view->form = new Warecorp_Form('sortForm', 'POST',$this->currentUser->getUserPath('photos'));
    $this->view->tags = $tags;
    $this->view->sortList = $sortList['titles'];
    $this->view->showList = $showList['titles'];
    $this->view->currentsort = $sort;
    $this->view->currentshow = $show;
    $this->view->galleriesList = $galleriesList;
    $this->view->galleriesCount = $galleriesCount;
    $this->view->item_width = $item_width;
    $this->view->item_height = $item_height;
    $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
    $this->view->user = $this->_page->_user;

    // paging
    $paging_url = $this->currentUser->getUserPath('photos').'sort/'.$sort.'/show/'.$show;
    $P = new Warecorp_Common_PagingProduct($galleriesCount, $items_per_page, $paging_url);
    $this->view->paging = $P->makePaging($this->params['page']);

    $this->view->friendsAssoc = $this->_page->_user->getId() ?  $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();

    $this->view->bodyContent = 'users/gallery/list.tpl';

//
//sample RSS feed
//

/*if(LOCALE == "rss"){
    include_once(ENGINE_DIR."/rss.class.php");

    $galleries = $this->currentUser->getGalleries();
    $galleriesList = $galleries->getList();
    $galleriesCount = $galleries->getCount();

    $rss = new UniversalFeedCreator();
    $rss->encoding = 'utf-8';
    $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
    $rss->title = $this->currentUser->getLogin() . " galleries ";
    $rss->description = $this->currentUser->getLogin() . " photo galleries ";
    $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $rss->copyright = "Copyright &copy; 2007, Zanby";
    foreach($galleriesList as $gallery){
        $item = new FeedItem();
        $item->title = $gallery->getTitle();
        $item->link = "http://".$_SERVER['HTTP_HOST']."/en/galleryView/id/".$gallery->getPhotos()->getLastPhoto()->getId()."/";
        $item->description = $gallery->getPhotos()->getCount() . " photos ";
        $rss->addItem($item);
    }
    header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
    print $rss->createFeed("RSS2.0");
    exit;
}*/



// @todo - remove this block
//breadcrumb
//if ($this->_page->breadcrumb != null){
//    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb,
//    array($this->currentUser->getCity()->getState()->getCountry()->name => "/" .$this->_page->Locale. "/users/index/view/allstates/country/" .$this->currentUser->getCity()->getState()->getCountry()->id. "/",
//    $this->currentUser->getCity()->getState()->name => "/" .$this->_page->Locale. "/users/index/view/allcities/state/" .$this->currentUser->getCity()->getState()->id. "/",
//    $this->currentUser->getCity()->name => "/" .$this->_page->Locale. "/users/search/preset/city/id/" .$this->currentUser->getCity()->id. "/",
//    $this->currentUser->getLogin() => null
//    )
//    );
