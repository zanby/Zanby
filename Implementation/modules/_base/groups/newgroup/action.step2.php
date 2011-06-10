<?php
    Warecorp::addTranslation('/modules/groups/newgroup/action.step2.php.xml');
    
    if ( !($this->currentGroup instanceof Warecorp_Group_Family) ) { $this->_redirect($this->currentGroup->getGroupPath('summary')); }
    
    $membersList = $this->currentGroup->getGroups()->setTypes(array('simple', 'family'));
    $isHostPrivileges = ($membersList->isCoowner($this->_page->_user)) || ($this->currentGroup->getMembers()->isHost($this->_page->_user));
    if ( !$isHostPrivileges ) $this->_redirect($this->currentGroup->getGroupPath('members'));
    $showPending = $isHostPrivileges && ($this->currentGroup->getJoinMode() == 1);
    
    /**
     * 
     */    
    if ( !isset($_SESSION['newgroupMember']) || !isset($_SESSION['newgroupMember']['step1']) ) {
        $this->_redirect($this->currentGroup->getGroupPath('membersAddStep1'));
    }
    
    $newGroup = &$_SESSION['newgroupMember'];
    $this->_page->Xajax->registerUriFunction("saveTempData", "/groups/membersSaveTempData/");
    
    $form = new Warecorp_Form('form_step2', 'POST', $this->currentGroup->getGroupPath('membersAddStep2'));
    $form->addRule('group_name',     'required',     Warecorp::t('Enter Group Name'));
    $form->addRule('group_name',     'regexp',       Warecorp::t("Group Name may consist of a-Z, 0-9, ', -, underscores, space, and dot (.)"), array('regexp' => "/^[a-zA-Z0-9][a-zA-Z0-9_'\s\-\.]*$/"));
    $form->addRule('group_name',     'rangelength',  Warecorp::t('Enter correct Group Name'), array('min' => 3, 'max' => 255));
    $form->addRule('group_name',     'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($params['group_name'])?$params['group_name']:null)));
    $form->addRule('gemail',        'required',     Warecorp::t('Enter Group Address'));
    $form->addRule('gemail',        'maxlength',    Warecorp::t('Group Address too long (max %s)',60), array('max' => 61 + strlen(DOMAIN_FOR_GROUP_EMAIL)));
    $form->addRule('gemail',        'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
    $form->addRule('gemail',        'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($this->params['gemail'])?array('key' =>'group_path', 'value'=> $this->params['gemail']):null));
    $form->addRule('description',   'required',     Warecorp::t('Enter Description'));
    $form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description'), array('max' => 2000));
    
    if (!empty($this->params['gemail'])) {
        $this->params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
        $form->addRule('gemail',    'email',        Warecorp::t('Enter correct Group Email'));
    }
    
    if ( !empty($this->params['group_name']) ) {
        $this->params['group_name'] = trim($this->params['group_name']);
        $form->addRule('group_name', 'callback', Warecorp::t('Group name already used or invalid'), array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => array('key'=>'name', 'value'=>trim($this->params['group_name']))));
    }
    
    if ( isset($this->params['hjoin']) && $this->params['hjoin'] == "2" ) {
        $form->addRule('jcode',   'required',     Warecorp::t('Enter Invitation Code'));
        if (!empty($this->params['jcode'])) {
            $this->params['jcode'] = trim($this->params['jcode']);
        }
    }
    
    if ( isset($newGroup["tempData"][2]) && !$form->isPostBack() ){
        $this->params['group_name']      = $newGroup["tempData"][2]["group_name"];
        $this->params['mcalled']        = $newGroup["tempData"][2]["mcalled"];
        $this->params['gemail']         = $newGroup["tempData"][2]["gemail"];
        $this->params['description']    = $newGroup["tempData"][2]["description"];
        $this->params['tags']           = $newGroup["tempData"][2]["tags"];
        $this->params['hjoin']          = $newGroup["tempData"][2]["hjoin"];
        $this->params['jcode']          = $newGroup["tempData"][2]["jcode"];
        $this->params['gtype']          = $newGroup["tempData"][2]["gtype"];
    }
    
    if ( $form->validate($this->params) ) {
        $this->params['group_name']      = trim($this->params['group_name']);
        $this->params['gemail']         = trim(str_replace('@'.DOMAIN_FOR_GROUP_EMAIL, '', $this->params['gemail']));
        $this->params['description']    = trim($this->params['description']);    
        
        /* Save New Group */
        $Group = new Warecorp_Group_Simple();
        $Group->setGroupType( Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE );
        $Group->setCategoryId( $newGroup['categoryId'] );
        $Group->setZipcode( $newGroup['zipcodeClear'] );
        $Group->setCityId( $newGroup['cityId'] );
        $Group->setName(trim($this->params['group_name']) );
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
        
        /* join group to family */
        $this->currentGroup->getGroups()->addGroup($Group->getId(), 'active');
        
        /* reload group with ALL data */
        $Group = Warecorp_Group_Factory::loadById($Group->getId(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        
        /* Send message to host of new group */
        $mail = new Warecorp_Mail_Template('template_key', 'CREATE_NEW_GROUP_THANK');
        $mail->setSender($Group);
        $mail->setHeader('Sender', '"'.htmlspecialchars($Group->getName()).'" <'.$Group->getGroupEmail().'>');
        $mail->setHeader('Reply-To', '"'.htmlspecialchars($Group->getName()).'" <'.$Group->getGroupEmail().'>');
        $mail->addRecipient($this->_page->_user);
        $mail->addParam('Group', $Group);
        $mail->addParam('invite', false);
        $mail->sendToPMB(false);
        $mail->send();
        
        /**/
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());        
        
        /* go to group summary page */
        unset($newGroup, $_SESSION['newgroupMember']);
        $this->_redirect($this->currentGroup->getGroupPath('members'));
        
    } elseif ( isset($this->params['gemail']) ) {
        $this->params['gemail'] = trim(str_replace('@'.DOMAIN_FOR_GROUP_EMAIL, '', $this->params['gemail']));
    }
    
    $Category   = new Warecorp_Group_Category($newGroup['categoryId']);
    $groupColl  = new Warecorp_Group_List();
    $groupInThisCity = $groupColl->countByCityAndCaterory($newGroup['cityId'], $newGroup['categoryId']);
    
    $group = array();
    
    $group['group_name']     = (isset($this->params['group_name'])) ? $this->params['group_name'] : ((isset($newGroup['group_name'])) ? $newGroup['group_name'] : null);
    $group['mcalled']       = (isset($this->params['mcalled'])) ? $this->params['mcalled'] : ((isset($newGroup['mcalled'])) ? $newGroup['mcalled'] : null);
    $group['gemail']        = (isset($this->params['gemail'])) ? $this->params['gemail'] : ((isset($newGroup['gemail'])) ? $newGroup['gemail'] : null);
    $group['description']   = (isset($this->params['description'])) ? $this->params['description'] : ((isset($newGroup['description'])) ? $newGroup['description'] : null);
    $group['tags']          = (isset($this->params['tags'])) ? $this->params['tags'] : ((isset($newGroup['tags'])) ? $newGroup['tags'] : null);
    $group['hjoin']         = (isset($this->params['hjoin'])) ? $this->params['hjoin'] : ((isset($newGroup['hjoin'])) ? $newGroup['hjoin'] : null);
    $group['jcode']         = (isset($this->params['jcode'])) ? $this->params['jcode'] : ((isset($newGroup['jcode'])) ? $newGroup['jcode'] : null);
    $group['gtype']         = (isset($this->params['gtype'])) ? $this->params['gtype'] : ((isset($newGroup['gtype'])) ? $newGroup['gtype'] : null);

    $this->view->step = '2';
    $this->view->stepscount = '3';    
    $country = Warecorp_Location_Country::create($newGroup['countryId']);
    $state = Warecorp_Location_State::create($newGroup['stateId']);
    $city = Warecorp_Location_City::create($newGroup['cityId']);
    $this->view->country = $country->name;
    $this->view->state = $state->name;
    $this->view->city = $city->name;
    
    $this->view->form = $form;
    $this->view->group = $group;
    $this->view->Category = $Category;
    $this->view->groupInThisCity = $groupInThisCity;
    
    /**
     * 
     */    
    $this->view->showPending = isset($showPending)?$showPending:null;
    $this->view->bodyContent = 'groups/newgroup/step2.tpl';
