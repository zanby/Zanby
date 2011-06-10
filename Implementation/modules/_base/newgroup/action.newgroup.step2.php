<?php
    Warecorp::addTranslation('/modules/newgroup/action.newgroup.step2.php.xml');

    if ( !isset($_SESSION['newgroup']) || !isset($_SESSION['newgroup']['step1']) ) {
        $this->_redirect('/'.LOCALE.'/newgroup/step1/');
    }

    //Display breadcrumb
    $this->_page->breadcrumb[Warecorp::t('Groups')] = BASE_URL."/".$this->_page->Locale."/groups/index/";
    $this->_page->breadcrumb[Warecorp::t('Start a group')] = "";

    $newGroup = &$_SESSION['newgroup'];
    $this->_page->Xajax->registerUriFunction("saveTempData", "/newgroup/saveTempData/");

    $form = new Warecorp_Form('form_step2', 'POST', '/'.$this->_page->Locale.'/newgroup/step2/');
    $form->addRule('name',          'required',     Warecorp::t('Enter Group Name'));
    $form->addRule('name',          'regexp',       Warecorp::t('Group Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z0-9][a-zA-Z0-9_'\s\-\.]*$/"));
    $form->addRule('name',          'rangelength',  Warecorp::t('Enter correct Group Name (%s-%s characters)', array(3, 100)), array('min' => 3, 'max' => 100));
    $form->addRule('name',          'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($params['name'])?$params['name']:null)));
    $form->addRule('gemail',        'required',     Warecorp::t('Enter Group Address'));
    $form->addRule('gemail',        'maxlength',    Warecorp::t('Group Address too long (max %s)', array(60)), array('max' => 61 + strlen(DOMAIN_FOR_GROUP_EMAIL)));
    $form->addRule('gemail',        'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
    $form->addRule('gemail',        'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($this->params['gemail'])?array('key' =>'group_path', 'value'=> $this->params['gemail']):null));
    $form->addRule('description',   'required',     Warecorp::t('Enter Description'));
    $form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description'), array('max' => 2000));

    if (!empty($this->params['gemail'])) {
        $this->params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
        $form->addRule('gemail',    'email',        Warecorp::t('Enter correct Group Email'));
    }

    if (!empty($this->params['name'])) {
        $this->params['name'] = trim($this->params['name']);
        $form->addRule('name', 'callback', Warecorp::t('Group name already used or invalid'), array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => array('key'=>'name', 'value'=>trim($this->params['name']))));
    }

    if (isset($this->params['hjoin']) && $this->params['hjoin'] == "2") {
        $form->addRule('jcode',   'required',     Warecorp::t('Enter Invitation Code'));
        if (!empty($this->params['jcode'])) {
            $this->params['jcode'] = trim($this->params['jcode']);
        }
    }

    if ( isset($newGroup["tempData"][2]) && !$form->isPostBack() ){
        $this->params['name']       = $newGroup["tempData"][2]["name"];
        $this->params['mcalled']    = $newGroup["tempData"][2]["mcalled"];
        $this->params['gemail']     = $newGroup["tempData"][2]["gemail"];
        $this->params['description']= $newGroup["tempData"][2]["description"];
        $this->params['tags']       = $newGroup["tempData"][2]["tags"];
        $this->params['hjoin']      = $newGroup["tempData"][2]["hjoin"];
        $this->params['jcode']      = $newGroup["tempData"][2]["jcode"];
        $this->params['gtype']      = $newGroup["tempData"][2]["gtype"];
    }

    if ( $form->validate($this->params) ) {
        $this->params['name']           = trim($this->params['name']);
        $this->params['gemail']         = trim(str_replace('@'.DOMAIN_FOR_GROUP_EMAIL, '', $this->params['gemail']));
        $this->params['description']    = trim($this->params['description']);

        /* Save New Group */
        $Group = new Warecorp_Group_Simple();
        $Group->setGroupType( Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE );
        $Group->setCategoryId( $newGroup['categoryId'] );
        $Group->setZipcode( $newGroup['zipcodeClear'] );
        $Group->setCityId( $newGroup['cityId'] );
        $Group->setName( trim($this->params['name']) );
        $Group->setPath( trim($this->params['gemail']) );
        $Group->setMembersName( trim($this->params['mcalled']) );
        $Group->setDescription( trim($this->params['description']) );
        $Group->setIsPrivate( $this->params['gtype'] );
        $Group->setJoinMode( $this->params['hjoin'] );
        $Group->setJoinCode( ($this->params['hjoin'] == 2) ? trim($this->params['jcode']) : null );
        $Group->setJoinNotifyMode(1);
        $Group->setGroupPaymentType( 'basic' );

        /* save geocoding data */
        $city = Warecorp_Location_City::create( $newGroup["cityId"] );
        $Group->setLatitude( $city->getLatitude() );
        $Group->setLongitude( $city->getLongitude() );

        $Group->save();

        /* save tags */
        $Group->addTags( trim($this->params['tags']) );

        /* seve user as host of group */
        $Group->getMembers()->addMember( $this->_page->_user->getId(), 'host' );

        /**
         * if EIA add group to family
         */
        if ( defined('IMPLEMENTATION_TYPE') && IMPLEMENTATION_TYPE == 'EIA' ) {
            if ( defined('IMPLEMENTATION_FAMILY_GROUP_UID') && IMPLEMENTATION_FAMILY_GROUP_UID ) {
                $Family = Zend_Registry::get("globalGroup");
                $Family->getGroups()->addGroup($Group->getId(), 'active');
                if ( $this->_page->_user->getId() != $Family->getHost()->getId() ) {
                    $Group->getMembers()->addMember($Family->getHost()->getId(), 'cohost');
                }
            }
        }

        $Group = Warecorp_Group_Factory::loadById($Group->getId(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        if ( FACEBOOK_USED ) {
            $params = array(
                'title' => htmlspecialchars($Group->getName()),
                'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
            );
            $action_links[] = array('text' => 'View Group', 'href' => $Group->getGroupPath('summary/'));
            $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_GROUP, $params);
            Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);

        }

        $Group->sendThank( $this->_page->_user );

//        //  Send message to host of new group
//        $mail = new Warecorp_Mail_Template('template_key', 'CREATE_NEW_GROUP_THANK');
//        $mail->setSender($Group);
//        $mail->setHeader('Sender', '"'.htmlspecialchars($Group->getName()).'" <'.$Group->getGroupEmail().'>');
//        $mail->setHeader('Reply-To', '"'.htmlspecialchars($Group->getName()).'" <'.$Group->getGroupEmail().'>');
//        $mail->addRecipient($this->_page->_user);
//        $mail->addParam('Group', $Group);
//        $mail->addParam('invite', false);
//        $mail->sendToPMB(false);
//        $mail->send();

        /**/
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());

        /* go to group summary page */
        unset($newGroup, $_SESSION['newgroup']);
        $this->_redirect($Group->getGroupPath('summary'));

    } elseif ( isset($this->params['gemail']) ) {
        $this->params['gemail'] = trim(str_replace('@'.DOMAIN_FOR_GROUP_EMAIL, '', $this->params['gemail']));
    }

    $Category = new Warecorp_Group_Category($newGroup['categoryId']);

    $groupColl = new Warecorp_Group_List();
    $groupInThisCity = $groupColl->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)->countByCityAndCaterory($newGroup['cityId'], $newGroup['categoryId']);

    $group = array();
    $group['name']          = (isset($this->params['name'])) ? $this->params['name'] : ((isset($newGroup['name'])) ? $newGroup['name'] : null);
    $group['mcalled']       = (isset($this->params['mcalled'])) ? $this->params['mcalled'] : ((isset($newGroup['mcalled'])) ? $newGroup['mcalled'] : null);
    $group['gemail']        = (isset($this->params['gemail'])) ? $this->params['gemail'] : ((isset($newGroup['gemail'])) ? $newGroup['gemail'] : null);
    $group['description']   = (isset($this->params['description'])) ? $this->params['description'] : ((isset($newGroup['description'])) ? $newGroup['description'] : null);
    $group['tags']          = (isset($this->params['tags'])) ? $this->params['tags'] : ((isset($newGroup['tags'])) ? $newGroup['tags'] : null);
    $group['hjoin']         = (isset($this->params['hjoin'])) ? $this->params['hjoin'] : ((isset($newGroup['hjoin'])) ? $newGroup['hjoin'] : null);
    $group['jcode']         = (isset($this->params['jcode'])) ? $this->params['jcode'] : ((isset($newGroup['jcode'])) ? $newGroup['jcode'] : null);
    $group['gtype']         = (isset($this->params['gtype'])) ? $this->params['gtype'] : ((isset($newGroup['gtype'])) ? $newGroup['gtype'] : null);

    $this->view->step = '2';
    $this->view->stepscount = '2';

    $country    = Warecorp_Location_Country::create($newGroup['countryId']);
    $state      = Warecorp_Location_State::create($newGroup['stateId']);
    $city       = Warecorp_Location_City::create($newGroup['cityId']);
    $this->view->country = $country->name;
    $this->view->state = $state->name;
    $this->view->city = $city->name;

    $this->view->form = $form;
    $this->view->group = $group;
    $this->view->Category = $Category;
    $this->view->groupInThisCity = $groupInThisCity;
    $this->view->bodyContent = 'newgroup/step2.tpl';
