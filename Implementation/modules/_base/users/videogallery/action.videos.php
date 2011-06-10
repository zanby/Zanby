<?php
    Warecorp::addTranslation("/modules/users/videogallery/action.videos.php.xml");

    if ( !Warecorp_Video_AccessManager_Factory::create()->canViewGalleries($this->currentUser, $this->_page->_user) ) {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }

    $this->_page->Xajax->registerUriFunction("share_group", "/users/videogalleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/users/videogalleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/users/videogalleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/users/videogalleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/users/videogalleryUnShareDo/");
    $this->_page->Xajax->registerUriFunction("unshare_group_do", "/users/videogalleryUnShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("unshare_friend_do", "/users/videogalleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("stop_watching_do", "/users/videogalleryStopWatchingDo/");
    $this->_page->Xajax->registerUriFunction("delete_gallery", "/users/videogalleryDeleteGallery/");
    //$this->_page->Xajax->registerUriFunction("show_share_history", "/users/videogalleryShowShareHistory/");
    
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/"); 
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    
    $items_per_page = 9;
    $item_width     = 100;
    $item_height    = 100;
    
    if (SINGLEVIDEOMODE){
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
	            1 => Warecorp::t('All Videos'),
	            5 => Warecorp::t('Videos shared with me'),
	            6 => Warecorp::t('Videos with my comments')
	        )
	    );

	    if ($this->_page->_user->getId() === $this->currentUser->getId()) {
			$showList['titles'][2] = Warecorp::t('Uploaded videos');
			$showList['titles'][3] = Warecorp::t('Shared videos');
			$showList['titles'][4] = Warecorp::t('Watching videos');
		}
	    ksort($showList['titles']);
    }else{
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
	            1 => Warecorp::t('All Collections'),
	            5 => Warecorp::t('Collections shared with me'),
	            6 => Warecorp::t('Collections with my comments')
	        )
	    );

	    if ($this->_page->_user->getId() === $this->currentUser->getId()) {
			$showList['titles'][2] = Warecorp::t('Uploaded Collections');
			$showList['titles'][3] = Warecorp::t('Shared Collections');
			$showList['titles'][4] = Warecorp::t('Watching Collections');
		}
	    ksort($showList['titles']);
    }

    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
    $this->params['sort'] = (isset($this->params['sort']))? $this->params['sort'] : 1;
    $this->params['show'] = (isset($this->params['show']))? $this->params['show'] : 1;
    $sort = ($this->params['sort'] <= $sortList['maxkey'])?$this->params['sort']:1;
	$show = in_array($this->params['show'], array_keys($showList['titles']))?$this->params['show']:1;

    $parivacy = array();
    if ( Warecorp_Video_AccessManager_Factory::create()->canViewPublicGalleries($this->currentUser, $this->_page->_user) ) $parivacy[] = 0;
    if ( Warecorp_Video_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentUser, $this->_page->_user) ) $parivacy[] = 1;


    //Warecorp_User::getGalleries()
    $galleriesList = $this->currentUser->getVideoGalleries()
                          ->setPrivacy($parivacy);
    $tags = $galleriesList->getAllVideosTags();

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

    $this->view->form = new Warecorp_Form('sortForm', 'POST',$this->currentUser->getUserPath('videos'));
    $this->view->tags = $tags;
    $this->view->sortList = $sortList['titles'];
    $this->view->showList = $showList['titles'];
    $this->view->currentsort = $sort;
    $this->view->currentshow = $show;
    $this->view->galleriesList = $galleriesList;
    $this->view->galleriesCount = $galleriesCount;
    $this->view->item_width = $item_width;
    $this->view->item_height = $item_height;
    $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
    $this->view->user = $this->_page->_user;

    // paging
    $paging_url = $this->currentUser->getUserPath('videos').'sort/'.$sort.'/show/'.$show;
    $P = new Warecorp_Common_PagingProduct($galleriesCount, $items_per_page, $paging_url);
    $this->view->paging = $P->makePaging($this->params['page']);

    $this->view->friendsAssoc = $this->_page->_user->getId() ?  $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();

    $this->view->bodyContent = 'users/videogallery/'.VIDEOMODEFOLDER.'list.tpl';
    

