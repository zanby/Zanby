<?php	
    if ($this->currentUser->getId() != $this->_page->_user->getId())
    	$this->_redirect($this->_page->_user->getUserPath('photossearch'));
    	
	$this->_page->Xajax->registerUriFunction("searchdelete", "/users/photossearchdelete/");
	
    $items_per_page = 12;
	$item_width     = 100;
	$item_height    = 100;
	$type = 'preset';
	$sortList = Warecorp_Photo_Search::$sortList['titles'];
    
    $form = new Warecorp_Form('search_photos', 'POST', $this->_page->_user->getUserPath('photossearch'));
	$formRemember = new Warecorp_Form('formRemember','POST',$this->_page->_user->getUserPath('photossearch'));
	$whoUploadedList = Warecorp_Photo_Search::$whoUploadedList['titles'];	

    $_photo = new Warecorp_Photo_Standard();

    if ($formRemember->isPostback()) {			
	    if (!empty($this->params['searchtodel'])) {
	        $photoSearch = new Warecorp_Photo_Search($this->params['searchtodel']);
	        $photoSearch->delete();    	
	    } else {
	    	$photoSearch = new Warecorp_Photo_Search();
	    	$photoSearch->name = $this->params['search_name'];
		    $photoSearch->EntityTypeId = $_photo->EntityTypeId;
		    $photoSearch->userId = $this->currentUser->getId();
		    $photoSearch->params = (isset($_SESSION['search_params'])) ? $_SESSION['search_params'] : array();
		    if (!empty($photoSearch->params)) $photoSearch->save(); 
		    	else $this->_redirect($this->_page->_user->getUserPath('photossearch'));
	    }
	    unset($this->params['searchtodel']);
	    unset($this->params['search_name']);
	    $this->params['preset'] = 'old';
	}
	
    if (!empty($this->params['saved'])) {
        $photoSearch = new Warecorp_Photo_Search($this->params['saved']);
        if (isset($photoSearch->params) && is_array($photoSearch->params)) {
            $this->params = array_merge($this->params, $photoSearch->params);
        }
    } else {
        $photoSearch = new Warecorp_Photo_Search();
    }

	$template = 'users/gallery/searchresult.tpl';

    $this->view->formRemember = $formRemember;
    $this->view->isResultPage = true;
    $this->view->savedSearches = $photoSearch->getSavedSearchesAssoc($this->currentUser->getId(), $_photo->EntityTypeId);
    
	if (isset($this->params['preset'])) {
		if ($this->params['preset'] == 'old') {
			if (!isset($_SESSION['search_params'])) $this->_redirect($this->_page->_user->getUserPath('photossearch'));
			unset($this->params['preset']);
			$this->params = array_merge(isset($_SESSION['search_params'])?$_SESSION['search_params']:array(), $this->params);
		}
		$page = isset($this->params['page'])?$this->params['page']:1;
		$keywords = isset($this->params['keywords'])?$this->params['keywords']:'';
		$whoUploaded = isset($this->params['whouploaded'])?$this->params['whouploaded']:1;		

		$_SESSION['search_params'] = $this->params;
		unset($_SESSION['search_params']['page']);
		switch ($this->params['preset']) {
			case 'new':
				$sort = isset($this->params['sort'])?$this->params['sort']:4;
				$photosSearch = new Warecorp_Photo_Search();
				$photosSearch->setKeywords($keywords, true);
				$photosSearch->setWhoUploaded($whoUploaded);
				switch (Warecorp_Photo_Search::$whoUploadedList['titles'][$whoUploaded]) {
					case 'Friends': //no break
					case 'My Groups':  //no break
					case 'My Group Families':
						$data['user'] = $this->_page->_user;
						$photosSearch->setWhoUploadedParams($data);						
						break;					
				}				
				$photosSearch->searchByKeywords();
				$photosSearch->searchByWhoUploaded();
				$photosSearch->getIntersection();				
				break;
			case 'country':
				$sort = isset($this->params['sort'])?$this->params['sort']:1;
				$photosSearch = new Warecorp_Photo_Search();				
				$photosSearch->setCountry(floor($this->params['id']));				
				$photosSearch->searchByCountry();
				break;
			case 'city':
				$sort = isset($this->params['sort'])?$this->params['sort']:1;
				$photosSearch = new Warecorp_Photo_Search();
				$photosSearch->setCity(floor($this->params['id']));
				$photosSearch->searchByCity();
				break;
			case 'tag':
				$sort = isset($this->params['sort'])?$this->params['sort']:4;
				$photosSearch = new Warecorp_Photo_Search();
				$tagobj = new Warecorp_Data_Tag($this->params['id']);
				if ($tagobj->id !== null) $photosSearch->setKeywords($tagobj->name);
				$photosSearch->searchByKeywords();
				break;
			default:
				unset($_SESSION['search_params']);
				$this->_redirect($this->_page->_user->getUserPath('photossearch'));
				break;
		}	
				
		$photosSearch->setOrder($sort);
		$photosSearch->setCurrentPage($page);
		$photosSearch->setListSize($items_per_page);
		$photosList = $photosSearch->getSearchResult();
		$P = new Warecorp_Common_PagingProduct($photosSearch->getCount(), $items_per_page, $this->_page->_user->getUserPath('photossearch').'preset/old');
		$paging = $P->makePaging($page);
        $this->view->keywords = $keywords;
        $this->view->whoUploaded = $whoUploaded;
        $this->view->whoUploadedByString = Warecorp_Photo_Search::$whoUploadedList['titles'][$whoUploaded];
        $this->view->paging = $paging;
        $this->view->sortList = Warecorp_Photo_Search::$sortList['titles'];
        $this->view->sort = $sort;
    } elseif (isset($this->params['view'])) {
    	$photosList = array();
        switch ($this->params['view']) {
            case 'cities':
        	case 'countries' :
		        $allCountries = Warecorp_Location::getCountriesListAssoc();
		        $keys = array_keys($allCountries);
		        $countriesPhotosCounts = Warecorp_Photo_Search::getCountriesPhotosCount();
                $countries = array();
		        foreach ($keys as $key) {
		            if (isset($countriesPhotosCounts[$key])) $countries[$key] = array('name'=>$allCountries[$key], 'cnt'=>$countriesPhotosCounts[$key]);
		            	else $countries[$key] = array('name'=>$allCountries[$key], 'cnt'=>0);
		        }
		        $onCol = ceil(count($countries)/4);
		    	$this->view->allCountries = $countries;
		    	$this->view->onCol = $onCol;
            	
                $template = 'users/gallery/allcountries.tpl';
                if ($this->params['view'] == 'cities') $type = 'view'; else $type = 'preset';
                break;
            case 'state':		        
            	$state = Warecorp_Location_State::create($this->params['id']);
				if ($state->id === null) $this->_redirect($user->getUserPath('photossearch'));            	
            	$allCities = $state->getCitiesListAssoc();
		        $keys = array_keys($allCities);
		        $citiesPhotosCounts = Warecorp_Photo_Search::getCitiesPhotosCount($state->id);
                $cities = array();
		        foreach ($keys as $key) {
		            if (isset($citiesPhotosCounts[$key])) $cities[$key] = array('name'=>$allCities[$key], 'cnt'=>$citiesPhotosCounts[$key]);
		            	//else $cities[$key] = array('name'=>$allCities[$key], 'cnt'=>0);
		        }
		        //$onCol = ceil(count($allCities)/4);
                $onCol = ceil(count($cities)/4);

		        $this->view->country = $state->getCountry();
		        $this->view->state = $state;
		        $this->view->allCities = $cities;
		    	$this->view->onCol = $onCol;
               	$template = 'users/gallery/allcities.tpl';
                break;
			case 'country':
		        $country = Warecorp_Location_Country::create(isset($this->params['id'])?$this->params['id']:1);
            	$allStates = $country->getStatesListAssoc();
		        $keys = array_keys($allStates);
		        $statesPhotosCounts = Warecorp_Photo_Search::getStatesPhotosCount($country->id);
		        foreach ($keys as $key) {
		            if (isset($statesPhotosCounts[$key])) $states[$key] = array('name'=>$allStates[$key], 'cnt'=>$statesPhotosCounts[$key]);
		            	else $states[$key] = array('name'=>$allStates[$key], 'cnt'=>0);
		        }
		        $onCol = ceil(count($allStates)/4);
				$this->view->country = $country;
		        $this->view->allStates = $states;
		    	$this->view->onCol = $onCol;            	
                $template = 'users/gallery/allstates.tpl';
                $type = 'view';
                break;                
        }
    } else {
		$template = 'users/gallery/search.tpl';
		$countryList = Warecorp_Photo_Search::getTopCountries(12);
		$cityList = Warecorp_Photo_Search::getTopCities(12);
	
		$tagsList = new Warecorp_User_Tag_List($this->currentUser->getId());
	    $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->getPhotoTagsByRating();
	    $newtags = array();
	    foreach($tags as $key=>$tagrating) {
	    	$newtags[$key] = array();
	    	$newtags[$key]['obj'] = new Warecorp_Data_Tag($key);
	    	$newtags[$key]['rating'] = $tagrating;
	    }
	    $tags = array();
		while (is_array($newtags) && count($newtags) > 0) {
		    $val = array_rand($newtags);
		    $tags[$val] = $newtags[$val];
		    unset($newtags[$val]);
		}

		$photosSearch = new Warecorp_Photo_Search();
		$data['user'] = $this->_page->_user;
		$photosSearch->setWhoUploadedParams($data);						
		$photosSearch->setOrder(1);
		$photosSearch->setCurrentPage(1);
		$photosSearch->setListSize(5);
		
		$photosSearch->setWhoUploaded(2);
		$photosSearch->searchByWhoUploaded();		
		$photosList['friends'] = $photosSearch->getSearchResult();
		
		$photosSearch->setWhoUploaded(3);
		$photosSearch->searchByWhoUploaded();
		$photosList['groups'] = $photosSearch->getSearchResult();
		
		$photosSearch->setWhoUploaded(4);
		$photosSearch->searchByWhoUploaded();
		$photosList['families'] = $photosSearch->getSearchResult();
		
		$photosSearch->setWhoUploaded(1);
		$photosSearch->searchByWhoUploaded();
		$photosList['anyone'] = $photosSearch->getSearchResult();
		
        $this->view->countryList = $countryList;
        $this->view->cityList = $cityList;
        $this->view->tags = $tags;
        $this->view->isResultPage = false;
    }
    
    $this->view->form = $form;
    $this->view->whoUploadedList = $whoUploadedList;
    $this->view->photosList = $photosList;
    $this->view->user = $this->_page->_user;
    $this->view->type = $type;
    $this->view->bodyContent = $template;
    $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
