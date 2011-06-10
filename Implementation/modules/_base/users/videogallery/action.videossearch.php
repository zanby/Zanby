<?php
    if ($this->currentUser->getId() != $this->_page->_user->getId())
        $this->_redirect($this->_page->_user->getUserPath('videossearch'));

    $this->_page->Xajax->registerUriFunction("searchdelete", "/users/videossearchdelete/");

    $items_per_page = 12;
    $item_width     = 100;
    $item_height    = 100;
    $type = 'preset';
    $sortList = Warecorp_Video_Search::$sortList['titles'];

    $form = new Warecorp_Form('search_videos', 'POST', $this->_page->_user->getUserPath('videossearch'));
    $formRemember = new Warecorp_Form('formRemember','POST',$this->_page->_user->getUserPath('videossearch'));
    $whoUploadedList = Warecorp_Video_Search::$whoUploadedList['titles'];

    $_video = new Warecorp_Video_Standard();

    if ($formRemember->isPostback()) {
        if (!empty($this->params['searchtodel'])) {
            $videoSearch = new Warecorp_Video_Search($this->params['searchtodel']);
            $videoSearch->delete();
        } else {
            $videoSearch = new Warecorp_Video_Search();
            $videoSearch->name = $this->params['search_name'];
            $videoSearch->EntityTypeId = $_video->EntityTypeId;
            $videoSearch->userId = $this->currentUser->getId();
            $videoSearch->params = (isset($_SESSION['search_params'])) ? $_SESSION['search_params'] : array();
            if (!empty($videoSearch->params)) $videoSearch->save(); 
                else $this->_redirect($this->_page->_user->getUserPath('videossearch'));
        }
        unset($this->params['searchtodel']);
        unset($this->params['search_name']);
        $this->params['preset'] = 'old';
    }

    if (!empty($this->params['saved'])) {
        $videoSearch = new Warecorp_Video_Search($this->params['saved']);
        if (isset($videoSearch->params) && is_array($videoSearch->params)) {
            $this->params = array_merge($this->params, $videoSearch->params);
        }
    } else {
        $videoSearch = new Warecorp_Video_Search();
    }

    $template = 'users/videogallery/'.VIDEOMODEFOLDER.'searchresult.tpl';

    $this->view->formRemember = $formRemember;
    $this->view->isResultPage = true;
    $this->view->savedSearches = $videoSearch->getSavedSearchesAssoc($this->currentUser->getId(), $_video->EntityTypeId);

    if (isset($this->params['preset'])) {
        if ($this->params['preset'] == 'old') {
            if (!isset($_SESSION['search_params'])) $this->_redirect($this->_page->_user->getUserPath('videossearch'));
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
                $videosSearch = new Warecorp_Video_Search();
                $videosSearch->setKeywords($keywords, true);
                $videosSearch->setWhoUploaded($whoUploaded);
                switch (Warecorp_Video_Search::$whoUploadedList['titles'][$whoUploaded]) {
                    case 'Friends': //no break
                    case 'My Groups':  //no break
                    case 'My Group Families':
                        $data['user'] = $this->_page->_user;
                        $videosSearch->setWhoUploadedParams($data);
                        break;
                }
                $videosSearch->searchByKeywords();
                $videosSearch->searchByWhoUploaded();
                $videosSearch->getIntersection();
                break;
            case 'country':
                $sort = isset($this->params['sort'])?$this->params['sort']:1;
                $videosSearch = new Warecorp_Video_Search();
                $videosSearch->setCountry(floor($this->params['id']));
                $videosSearch->searchByCountry();
                break;
            case 'city':
                $sort = isset($this->params['sort'])?$this->params['sort']:1;
                $videosSearch = new Warecorp_Video_Search();
                $videosSearch->setCity(floor($this->params['id']));
                $videosSearch->searchByCity();
                break;
            case 'tag':
                $sort = isset($this->params['sort'])?$this->params['sort']:4;
                $videosSearch = new Warecorp_Video_Search();
                $tagobj = new Warecorp_Data_Tag($this->params['id']);
                if ($tagobj->id !== null) $videosSearch->setKeywords($tagobj->name);
                $videosSearch->searchByKeywords();
                break;
            default:
                unset($_SESSION['search_params']);
                $this->_redirect($this->_page->_user->getUserPath('videossearch'));
                break;
        }

        $videosSearch->setOrder($sort);
        $videosSearch->setCurrentPage($page);
        $videosSearch->setListSize($items_per_page);
        $videosList = $videosSearch->getSearchResult();
        $P = new Warecorp_Common_PagingProduct($videosSearch->getCount(), $items_per_page, $this->_page->_user->getUserPath('videossearch').'preset/old');
        $paging = $P->makePaging($page);
        $this->view->keywords = $keywords;
        $this->view->whoUploaded = $whoUploaded;
        $this->view->whoUploadedByString = Warecorp_Video_Search::$whoUploadedList['titles'][$whoUploaded];
        $this->view->paging = $paging;
        $this->view->sortList = Warecorp_Video_Search::$sortList['titles'];
        $this->view->sort = $sort;
    } elseif (isset($this->params['view'])) {
        $videosList = array();
        switch ($this->params['view']) {
            case 'cities':
            case 'countries' :
                $allCountries = Warecorp_Location::getCountriesListAssoc();
                $keys = array_keys($allCountries);
                $countriesVideosCounts = Warecorp_Video_Search::getCountriesVideosCount();
                $countries = array();
                foreach ($keys as $key) {
                    if (isset($countriesVideosCounts[$key])) $countries[$key] = array('name'=>$allCountries[$key], 'cnt'=>$countriesVideosCounts[$key]);
                        else $countries[$key] = array('name'=>$allCountries[$key], 'cnt'=>0);
                }
                $onCol = ceil(count($countries)/4);
                $this->view->allCountries = $countries;
                $this->view->onCol = $onCol;

                $template = 'users/videogallery/allcountries.tpl';
                if ($this->params['view'] == 'cities') $type = 'view'; else $type = 'preset';
                break;
            case 'state':
                $state = Warecorp_Location_State::create($this->params['id']);
                if ($state->id === null) $this->_redirect($user->getUserPath('videossearch'));
                $allCities = $state->getCitiesListAssoc();
                $keys = array_keys($allCities);
                $citiesVideosCounts = Warecorp_Video_Search::getCitiesVideosCount($state->id);
                $cities = array();
                foreach ($keys as $key) {
                    if (isset($citiesVideosCounts[$key])) $cities[$key] = array('name'=>$allCities[$key], 'cnt'=>$citiesVideosCounts[$key]);
                        //else $cities[$key] = array('name'=>$allCities[$key], 'cnt'=>0);
                }
                //$onCol = ceil(count($allCities)/4);
                $onCol = ceil(count($cities)/4);

                $this->view->country = $state->getCountry();
                $this->view->state = $state;
                $this->view->allCities = $cities;
                $this->view->onCol = $onCol;
                   $template = 'users/videogallery/allcities.tpl';
                break;
            case 'country':
                $country = Warecorp_Location_Country::create(isset($this->params['id'])?$this->params['id']:1);
                $allStates = $country->getStatesListAssoc();
                $keys = array_keys($allStates);
                $statesVideosCounts = Warecorp_Video_Search::getStatesVideosCount($country->id);
                foreach ($keys as $key) {
                    if (isset($statesVideosCounts[$key])) $states[$key] = array('name'=>$allStates[$key], 'cnt'=>$statesVideosCounts[$key]);
                        else $states[$key] = array('name'=>$allStates[$key], 'cnt'=>0);
                }
                $onCol = ceil(count($allStates)/4);
                $this->view->country = $country;
                $this->view->allStates = $states;
                $this->view->onCol = $onCol;
                $template = 'users/videogallery/allstates.tpl';
                $type = 'view';
                break;
        }
    } else {
        $template = 'users/videogallery/'.VIDEOMODEFOLDER.'search.tpl';
        $countryList = Warecorp_Video_Search::getTopCountries(12);
        $cityList = Warecorp_Video_Search::getTopCities(12);

        $tagsList = new Warecorp_User_Tag_List($this->currentUser->getId());
        $tags = $tagsList->returnAsAssoc()->setCurrentPage(1)->setListSize(30)->getVideoTagsByRating();
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

        $videosSearch = new Warecorp_Video_Search();
        $data['user'] = $this->_page->_user;
        $videosSearch->setWhoUploadedParams($data);
        $videosSearch->setOrder(1);
        $videosSearch->setCurrentPage(1);
        $videosSearch->setListSize(5);

        $videosSearch->setWhoUploaded(2);
        $videosSearch->searchByWhoUploaded();
        $videosList['friends'] = $videosSearch->getSearchResult();

        $videosSearch->setWhoUploaded(3);
        $videosSearch->searchByWhoUploaded();
        $videosList['groups'] = $videosSearch->getSearchResult();

        $videosSearch->setWhoUploaded(4);
        $videosSearch->searchByWhoUploaded();
        $videosList['families'] = $videosSearch->getSearchResult();

        $videosSearch->setWhoUploaded(1);
        $videosSearch->searchByWhoUploaded();
        $videosList['anyone'] = $videosSearch->getSearchResult();

        $this->view->countryList = $countryList;
        $this->view->cityList = $cityList;
        $this->view->tags = $tags;
        $this->view->isResultPage = false;
    }

    $this->view->form = $form;
    $this->view->whoUploadedList = $whoUploadedList;
    $this->view->videosList = $videosList;
    $this->view->user = $this->_page->_user;
    $this->view->type = $type;
    $this->view->bodyContent = $template;
    $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
