<?php
Warecorp::addTranslation('/modules/groups/gallery/action.photos.php.xml');

	if ( !Warecorp_Photo_AccessManager_Factory::create()->canViewGalleries($this->currentGroup, $this->_page->_user) ) {
	    $this->_redirect($this->currentGroup->getGroupPath('summary'));
	}
	
	$this->_page->Xajax->registerUriFunction("share_group", "/groups/galleryShareGroup/");
	$this->_page->Xajax->registerUriFunction("share_group_do", "/groups/galleryShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("share_friend", "/groups/galleryShareFriend/");
	$this->_page->Xajax->registerUriFunction("share_friend_do", "/groups/galleryShareFriendDo/");
	$this->_page->Xajax->registerUriFunction("unshare_do", "/groups/galleryUnShareDo/");
	$this->_page->Xajax->registerUriFunction("unshare_group_do", "/groups/galleryUnShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("unshare_friend_do", "/groups/galleryUnShareFriendDo/");	
	$this->_page->Xajax->registerUriFunction("stop_watching_do", "/groups/galleryStopWatchingDo/");
	$this->_page->Xajax->registerUriFunction("delete_gallery", "/groups/galleryDeleteGallery/");
	$this->_page->Xajax->registerUriFunction("show_share_history", "/groups/galleryShowShareHistory/");
	$this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
	$this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
	$this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
	$this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");		
	
	$items_per_page = 9;
	$item_width     = 175;
	$item_height    = 175;
	$sortList = array(
		'maxkey' => 4,
		'titles' => array(
			1 => Warecorp::t('Newest to oldest'),
			2 => Warecorp::t('Oldest to Newest'),
			3 => Warecorp::t('Name Alphabetical'),
			4 => Warecorp::t('Reverse Alphabetical')
		)
	);

	if ( null !== $this->_page->_user->getId() ) {
    	$showList = array(
    		'maxkey' => 5,
    		'titles' => array(
    			1 => Warecorp::t('All galleries'),
    			4 => Warecorp::t('Galleries shared with me'),
    			5 => Warecorp::t('Galleries with my comments')
    		)
    	);
	} else {
        $showList = array(
            'maxkey' => 1,
            'titles' => array(
                1 => Warecorp::t('All galleries')
            )
        );	    
	}
	
    if (Warecorp_Photo_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentGroup, $this->_page->_user)) {
		$showList['titles'][2] = Warecorp::t('Uploaded galleries');
		$showList['titles'][3] = Warecorp::t('Galleries shared with group');
		$showList['maxkey'] = ( $showList['maxkey'] < 3 ) ? 3 : $showList['maxkey']; 
	}
    ksort($showList['titles']);
	
	$this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
	$this->params['sort'] = (isset($this->params['sort']))? $this->params['sort'] : 1;
	$this->params['show'] = (isset($this->params['show']))? $this->params['show'] : 1;
	$sort = ($this->params['sort'] <= $sortList['maxkey'])?$this->params['sort']:1;
	$show = in_array($this->params['show'], array_keys($showList['titles']))?$this->params['show']:1;
	
    $parivacy = array();
    if ( Warecorp_Photo_AccessManager_Factory::create()->canViewPublicGalleries($this->currentGroup, $this->_page->_user) )  $parivacy[] = 0;
    if ( Warecorp_Photo_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentGroup, $this->_page->_user) ) $parivacy[] = 1;

	$galleriesList = $this->currentGroup->getGalleries()
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
		case 4:
			$galleriesList->setOrder('title desc');
			break;
	}
	
	if ( null === $this->_page->_user->getId() ) $show = 1;
	switch ($show) {
		case 1:
			break;
		case 2:
            $galleriesList->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN);
			break;
		case 3:
		    $galleriesList->setSharingMode(Warecorp_Photo_Enum_SharingMode::SHARED);
			break;
		case 4:
			$tempGalleriesList = $this->currentGroup->getGalleries();			
            $tempGalleriesList->setCustomCondition()
                ->addWhere('share = 1')
                ->addWhere("real_owner_type = 'group'")
                ->addWhere('real_owner_id = '.$this->currentGroup->getId())
                ->addWhere("owner_type = 'user'")
                ->addWhere('owner_id = '.$this->_page->_user->getId())
                ->returnAsAssoc();
            $list = $tempGalleriesList->getList();                    
			$list = array_keys($list);
			if (!empty($list)) $galleriesList->setIncludeIds($list); else $galleriesList->addWhere("1=2");
			break;
		case 5:
			$galleriesList->setWithComments($this->_page->_user->getId());
			break;
	}

	$galleriesCount = $galleriesList->getCount();

	$galleriesList = $galleriesList
	                      ->setCurrentPage($this->params['page'])
	                      ->setListSize($items_per_page);
	$galListObj = $galleriesList;
	$galleriesList = $galleriesList->getList();
	$galleriesListOwners = $galListObj->getListOwners();

	$this->view->form = new Warecorp_Form('sortForm', 'POST',$this->currentGroup->getGroupPath('photos'));
	$this->view->tags = $tags;
	$this->view->sortList = $sortList['titles'];
	$this->view->showList = $showList['titles'];
	$this->view->currentsort = $sort;
	$this->view->currentshow = $show;
 	$this->view->galleriesList = $galleriesList;
 	$this->view->galleriesListOwners = $galleriesListOwners;
	$this->view->galleriesCount = $galleriesCount;
	$this->view->item_width = $item_width;
	$this->view->item_height = $item_height;
	$this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
	$this->view->user = $this->_page->_user;
	
	// paging
	$paging_url = $this->currentGroup->getGroupPath('photos').'sort/'.$sort.'/show/'.$show;
	$P = new Warecorp_Common_PagingProduct($galleriesCount, $items_per_page, $paging_url);
	$this->view->paging = $P->makePaging($this->params['page']);
	

/*if(LOCALE == "rss"){
    include_once(ENGINE_DIR."/rss.class.php");

    $galleries = $this->currentGroup->getGalleries();
    $galleriesList = $galleries->getList();
    $galleriesCount = $galleries->getCount();
                 
    $rss = new UniversalFeedCreator();
    $rss->encoding = 'utf-8';
    $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';    
    $rss->title = $this->currentGroup->getName() . " galleries ";
    $rss->description = $this->currentGroup->getName() . " photo galleries ";
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
} */

// @todo - remove this block
//	if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
//	} else {
//	    //breadcrumb
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                $this->currentGroup->getName() => "")
//            ); 
//	}
	
	$this->view->bodyContent = 'groups/gallery/list.tpl';
