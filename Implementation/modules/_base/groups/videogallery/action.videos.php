<?php
Warecorp::addTranslation('/modules/groups/videogallery/action.videos.php.xml');

    if ( !Warecorp_Video_AccessManager_Factory::create()->canViewGalleries($this->currentGroup, $this->_page->_user) ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }

    $this->_page->Xajax->registerUriFunction("share_group", "/groups/videogalleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/groups/videogalleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/groups/videogalleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/groups/videogalleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/groups/videogalleryUnShareDo/");
    $this->_page->Xajax->registerUriFunction("unshare_group_do", "/groups/videogalleryUnShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("unshare_friend_do", "/groups/videogalleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("stop_watching_do", "/groups/videogalleryStopWatchingDo/");
    $this->_page->Xajax->registerUriFunction("delete_gallery", "/groups/videogalleryDeleteGallery/");
    //$this->_page->Xajax->registerUriFunction("show_share_history", "/groups/videogalleryShowShareHistory/");

    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");

    $items_per_page = 9;
    $item_width     = 100;
    $item_height    = 100;

	$sortList = array(
		'maxkey' => 4,
		'titles' => array(
			1 => Warecorp::t('Newest to oldest'),
			2 => Warecorp::t('Oldest to Newest'),
			3 => Warecorp::t('Name Alphabetical'),
			4 => Warecorp::t('Reverse Alphabetical')
		)
	);

    if (SINGLEVIDEOMODE){
        if ( null !== $this->_page->_user->getId() ) {
    		$showList = array(
    			'maxkey' => 5,
    			'titles' => array(
    				1 => Warecorp::t('All videos'),
    				4 => Warecorp::t('Videos shared with me'),
    				5 => Warecorp::t('Videos with my comments')
    			)
    		);
        } else {
            $showList = array(
                'maxkey' => 1,
                'titles' => array(
                    1 => Warecorp::t('All videos')
                )
            );
        }

	    if (Warecorp_Video_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentGroup, $this->_page->_user)) {
			$showList['titles'][2] = Warecorp::t('Uploaded Videos');
			$showList['titles'][3] = Warecorp::t('Videos shared with group');
			$showList['maxkey'] = ( $showList['maxkey'] < 3 ) ? 3 : $showList['maxkey'];
		}
	    ksort($showList['titles']);
	}else{
	    if ( null !== $this->_page->_user->getId() ) {
    		$showList = array(
    			'maxkey' => 5,
    			'titles' => array(
    				1 => Warecorp::t('All galleries'),
    				4 => Warecorp::t('Collections shared with me'),
    				5 => Warecorp::t('Collections with my comments'),
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

	    if (Warecorp_Video_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentGroup, $this->_page->_user)) {
			$showList['titles'][2] = Warecorp::t('Uploaded Collections');
			$showList['titles'][3] = Warecorp::t('Collections shared with group');
			$showList['maxkey'] = ( $showList['maxkey'] < 3 ) ? 3 : $showList['maxkey'];
		}
	    ksort($showList['titles']);
	}

    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
    $this->params['sort'] = (isset($this->params['sort']))? $this->params['sort'] : 1;
	$this->params['show'] = (isset($this->params['show']))? $this->params['show'] : 1;
    $sort = ($this->params['sort'] <= $sortList['maxkey'])?$this->params['sort']:1;
	$show = in_array($this->params['show'], array_keys($showList['titles']))?$this->params['show']:1;

    $parivacy = array();
    if ( Warecorp_Video_AccessManager_Factory::create()->canViewPublicGalleries($this->currentGroup, $this->_page->_user) ) $parivacy[] = 0;
    if ( Warecorp_Video_AccessManager_Factory::create()->canViewPrivateGalleries($this->currentGroup, $this->_page->_user) ) $parivacy[] = 1;

    $galleriesList = $this->currentGroup->getVideoGalleries()
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
            $list = $tempGalleriesList->setCustomCondition()
						  ->addWhere('share = 1')
                          ->addWhere("real_owner_type = 'group'")
                          ->addWhere('real_owner_id = '.$this->currentGroup->getId())
                          ->addWhere("owner_type = 'user'")
                          ->addWhere('owner_id = '.$this->_page->_user->getId())
                          ->returnAsAssoc()->getList();
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

    $this->view->form = new Warecorp_Form('sortForm', 'POST',$this->currentGroup->getGroupPath('videos'));
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
    $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
    $this->view->user = $this->_page->_user;

    // paging
    $paging_url = $this->currentGroup->getGroupPath('videos').'sort/'.$sort.'/show/'.$show;
    $P = new Warecorp_Common_PagingProduct($galleriesCount, $items_per_page, $paging_url);
    $this->view->paging = $P->makePaging($this->params['page']);

    $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'list.tpl';
