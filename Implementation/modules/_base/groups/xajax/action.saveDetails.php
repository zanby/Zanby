<?php
Warecorp::addTranslation('/modules/groups/xajax/action.saveDetails.php.xml');

    $objResponse = new xajaxResponse();
    $error = false;
    $groupId = isset($params["groupId"])?floor($params["groupId"]):0;
    if (!$groupId || $this->currentGroup->getId() != $groupId){
        $error = true;
    }
    if (isset($params['_wf__dForm'])) $_REQUEST['_wf__dForm'] = $params['_wf__dForm'];

    $Group = new Warecorp_Group_Simple("id", $groupId);

    /**
     * create form and form rules
     */
    $form = new Warecorp_Form('dForm', 'post', 'javasript:void(0);');
    $form->addRule('categoryId',    'nonzero', Warecorp::t('Choose category'));
    $form->addRule('countryId',     'nonzero', Warecorp::t('Choose country'));
    $form->addRule('city',          'callback',  Warecorp::t('Enter please City'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityRequired',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'city' => ((isset($params['city'])) ? $params['city'] : null)
            )
        )
    );
    $form->addRule('city',          'callback',      Warecorp::t('City name is incorrect. Choose it from autocomplete list.'),
        array(
            'func' => 'Warecorp_Form_Validation::isCityInvalid',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'city' => ((isset($params['city'])) ? $params['city'] : null)
            )
        )
    );
    $form->addRule('zipcode',       'callback',      Warecorp::t('Enter please Zip code'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'zipcode' => ((isset($params['zipcode'])) ? $params['zipcode'] : null)
            )
        )
    );
    $form->addRule('zipcode',       'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'),
        array(
            'func' => 'Warecorp_Form_Validation::isZipcodeInvalid',
            'params' => array(
                'countryId' => ((isset($params['countryId'])) ? $params['countryId'] : null),
                'zipcode' => ((isset($params['zipcode'])) ? $params['zipcode'] : null)
            )
        )
    );
    $form->addRule('gname',         'required',     Warecorp::t('Enter Group Name'));
    $form->addRule('gname',         'regexp',       Warecorp::t('Enter correct Group Name'), array('regexp' => "/^[a-zA-Z0-9]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
    $form->addRule('gname',         'rangelength',  Warecorp::t('Enter correct Group Name (%s-%s characters)', array(3,100)), array('min' => 3, 'max' => 100));
    $form->addRule('gname',         'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($params['gname'])?$params['gname']:null, 'excludeIds' => $params["groupId"])));

    $form->addRule('gemail',        'required',     Warecorp::t('Enter Group Address'));
    $form->addRule('gemail',        'maxlength',    Warecorp::t('Group Address too long (max %s)',60), array('max' =>  61 + strlen(DOMAIN_FOR_GROUP_EMAIL)));
    $form->addRule('gemail',        'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
    $form->addRule('gemail',        'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($params['gemail'])?array('key' =>'group_path', 'value'=> $params['gemail'], 'exclude'=>$Group->getPath()):null));

    $form->addRule('description',   'required',     Warecorp::t('Enter Description'));
    $form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description'), array('max' => 2000));

    /**
     * if group path changed - need reload page
     */
    if ($Group->getPath() != trim($params['gemail'])) $redirect = true;
    else $redirect = false;

    $recivedGemail = $params['gemail'];
    /**
     * add optional form rules
     */
    if ( !empty($params['gemail']) ) {
        $params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
        $form->addRule('gemail',    'email',        Warecorp::t('Enter correct Group Email'));
    }

    if ( !empty($params['gname']) ) {
        $name = preg_replace("/\s{1,}/","-", strtolower(trim($params['gname'])));
        $form->addRule('gname',         'callback', Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>$params['gname'], 'excludeIds' => $params["groupId"])));
    }

    if ( isset($params['hjoin']) && $params['hjoin'] == "2" ) {
        $form->addRule('jcode',   'required',       Warecorp::t('Enter Invitation Code'));
        if (!empty($params['jcode'])) {
            $params['jcode'] = trim($params['jcode']);
        }
    }
    $flag = false;
    $autocompletScript = null;
    /**
     * process form
     */
    if ( $form->validate($params) ) {
        $Group->setCategoryId   ( $params['categoryId'] );
        $Group->setName         ( $params['gname'] );
        $Group->setPath         ( $recivedGemail);
        $Group->setMembersName  ( $params['membersName'] );
        $Group->setDescription  ( $params['description'] );
        $Group->setIsPrivate    ( $params['gtype'] );
        $approveAllMembers      = ($params['hjoin'] != 1 && $Group->getJoinMode() == 1)?true:false;
        $Group->setJoinMode     ( $params['hjoin'] );
        $Group->setJoinCode     ( ($params['hjoin'] == 2) ? $params['jcode'] : null );
        $Group->setJoinNotifyMode( empty($params['jnotify']) ? 0 : 1 );

        $country = Warecorp_Location_Country::create($params['countryId']);

        $cityChanged = false;

        if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
            if ( strpos($params['zipcode'], ",") ) $locationInfo = $country->getZipcodeByACFullInfo($params['zipcode']);
            else $locationInfo = $country->getZipcodeByACInfo($params['zipcode']);
            $Group->setZipcode  ( $locationInfo['zipcode'] );
            if ($Group->getCityId() != $locationInfo['city_id'] ) $cityChanged = true;
            $Group->setCityId   ( $locationInfo['city_id'] );
        } else {
            $locationInfo = $country->getCityByACInfo($params['city']);
            $Group->setZipcode  ( '' );
            if ($Group->getCityId() != $locationInfo['city_id'] ) $cityChanged = true;
            $Group->setCityId   ( $locationInfo['city_id'] );
        }

        $city = Warecorp_Location_City::create($locationInfo['city_id']);
        $Group->setLatitude( $city->getLatitude() );
        $Group->setLongitude( $city->getLongitude() );

        if ($approveAllMembers) {
            $groupMembers = $Group->getMembers();
            $pendingMembers = $groupMembers->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_PENDING)->getList();
            foreach($pendingMembers as $member) $groupMembers->approveMember($member);
        }
          $Group->save();
          $Group->deleteTags();
          $Group->addTags($params['tags']);
        /*
         * Update regional hierarchies
         * @author Pavel Shutin
         */
          if ($cityChanged && $Group instanceof Warecorp_Group_Simple) {
              $Group->updateHierarchies();
          }

        if ( FACEBOOK_USED ) {
            $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
            if ( !$cache->load('timerGroup_'.$Group->getId()) ) {
                $paramsFB = array(
                    'title' => htmlspecialchars($Group->getName()),
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );
                $action_links[] = array('text' => 'View Group', 'href' => $this->currentGroup->getGroupPath('summary/'));
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_UPDATE_GROUP, $paramsFB);
                $result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
                if ( false === $result && '' != $js = Warecorp_Facebook_Feed::getJsResponse() ) $objResponse->addScript($js);
                $cache->save('1', 'timerGroup_'.$Group->getId(), array(), 86400);
           }
        }

          $flag = true;
          $this->view->visibility = false;
          $objResponse->addScript('TitltPaneAppGroupSettingsGroupDetails.hide();');
    } else {
        $Group->setCategoryId   ( $params['categoryId'] );
        $Group->setName         ( $params['gname'] );
        $Group->setPath         ( $recivedGemail);
        $Group->setMembersName  ( $params['membersName'] );
        $Group->setDescription  ( $params['description'] );
        $Group->setIsPrivate    ( $params['gtype'] );
        $approveAllMembers      = ($params['hjoin'] != 1 && $Group->getJoinMode() == 1)?true:false;
        $Group->setJoinMode     ( $params['hjoin'] );
        $Group->setJoinCode     ( ($params['hjoin'] == 2) ? $params['jcode'] : null );

        $autocompletScript = 'initCityAutocomplete();initZipAutocomplete();';
        $this->view->cityStr = $params['city'];
        $this->view->zipStr = $params['zipcode'];
        $this->view->visibility = true;
        $redirect = false;
    }
    /**
     * create countries list
     */
    $countries = Warecorp_Location::getCountriesListAssoc(true);
    /**
     * create categories
     */
    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = $allCategoriesObj->returnAsAssoc()->getList();


    $this->view->countryId = $params['countryId'];
    $this->view->countries = $countries;
    $this->view->categories = $allCategories;
    $this->view->tags = $params['tags'];
    $this->view->form = $form;
    $this->view->currentGroup = $Group;

    if ($redirect === true) {
        $newGroup = new Warecorp_Group_Simple("id", $groupId);
        $objResponse->addRedirect($newGroup->getGroupPath('settings'));
    }
    if ($error === true) $objResponse->addRedirect(BASE_URL);

    $Content = $this->view->getContents('groups/settings.details.tpl');
    $objResponse->addClear( "GroupSettingsGroupDetails_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsGroupDetails_Content", "innerHTML", $Content );
    if ($flag == true) $objResponse->showAjaxAlert('Changes saved');

    if ( $autocompletScript ) $objResponse->addScript($autocompletScript);
