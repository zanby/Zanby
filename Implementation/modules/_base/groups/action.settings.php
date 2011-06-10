<?php
    Warecorp::addTranslation('/modules/groups/action.settings.php.xml');
    
    if (empty($this->userHasHostPriveleges)) { $this->_redirect(BASE_URL.'/'.LOCALE.'/'); }

    /**
     * Register ajax functions
     */
    $this->_page->Xajax->registerUriFunction("privileges_location_show", "/groups/showLocation/");
    $this->_page->Xajax->registerUriFunction("privileges_location_hide", "/groups/hideLocation/");

    $this->_page->Xajax->registerUriFunction("privileges_account_type_show", "/groups/showAccount/");
    $this->_page->Xajax->registerUriFunction("privileges_account_type_hide", "/groups/hideAccount/");

    $this->_page->Xajax->registerUriFunction("privileges_privileges_show", "/groups/showPrivileges/");
    $this->_page->Xajax->registerUriFunction("privileges_privileges_hide", "/groups/hidePrivileges/");
    $this->_page->Xajax->registerUriFunction("privileges_privileges_save", "/groups/savePrivileges/");
    $this->_page->Xajax->registerUriFunction("privileges_privileges_familysave", "/groups/saveFamilyPrivileges/");
    $this->_page->Xajax->registerUriFunction("privileges_user_delete",     "/groups/userdeletePrivileges/");
    $this->_page->Xajax->registerUriFunction("privileges_user_add",        "/groups/useraddPrivileges/");
    $this->_page->Xajax->registerUriFunction("privileges_user_ac_logins",       "/groups/autocompleteLogins/");
    $this->_page->Xajax->registerUriFunction("privileges_user_ac_members",       "/groups/autocompleteMembers/");


    $this->_page->Xajax->registerUriFunction("privileges_cohosts_show", "/groups/showCohosts/");
    $this->_page->Xajax->registerUriFunction("privileges_cohosts_hide", "/groups/hideCohosts/");
    $this->_page->Xajax->registerUriFunction("privileges_add_cohost", "/groups/addCohost/");
    $this->_page->Xajax->registerUriFunction("privileges_delete_cohost", "/groups/deleteCohost/");

    $this->_page->Xajax->registerUriFunction("privileges_group_details_show", "/groups/showDetails/");
    $this->_page->Xajax->registerUriFunction("privileges_group_details_hide", "/groups/hideDetails/");
    $this->_page->Xajax->registerUriFunction("privileges_group_details_save", "/groups/saveDetails/");
    $this->_page->Xajax->registerUriFunction("privileges_familygroup_details_save", "/groups/saveFamilyDetails/");


    $this->_page->Xajax->registerUriFunction("privileges_resign_show", "/groups/showResign/");
    $this->_page->Xajax->registerUriFunction("privileges_resign_hide", "/groups/hideResign/");
    $this->_page->Xajax->registerUriFunction("privileges_resign_change_host", "/groups/resignChangeHost/");
    $this->_page->Xajax->registerUriFunction("privileges_resign_send_form_show", "/groups/resignShowSendForm/");
    $this->_page->Xajax->registerUriFunction("privileges_resign_handle", "/groups/resignHandleSendForm/");

    $this->_page->Xajax->registerUriFunction("privileges_transfer_show", "/groups/showTransfer/");
    $this->_page->Xajax->registerUriFunction("privileges_transfer_hide", "/groups/hideTransfer/");
    $this->_page->Xajax->registerUriFunction("privileges_transfer_do", "/groups/doTransfer/");

    $this->_page->Xajax->registerUriFunction("privileges_deletegroup_show", "/groups/showGroupDelete/");
    $this->_page->Xajax->registerUriFunction("privileges_deletegroup_hide", "/groups/hideGroupDelete/");
    $this->_page->Xajax->registerUriFunction("privileges_deletegroupstep1", "/groups/deleteGroupStep1/");
    $this->_page->Xajax->registerUriFunction("privileges_deletegroupstep2", "/groups/deleteGroupStep2/");
    $this->_page->Xajax->registerUriFunction("privileges_deletegroupstep3", "/groups/deleteGroupStep3/");
    $this->_page->Xajax->registerUriFunction("changehost", "/groups/changehost/");
    $this->_page->Xajax->registerUriFunction("convertfamily", "/groups/convertFamily/");

    $this->_page->Xajax->registerUriFunction("detectCountry", "/ajax/detectCountry/");
    $this->_page->Xajax->registerUriFunction("autoCompleteCity", "/ajax/autoCompleteCity/");
    $this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");


    //  END {Register XAJAX Functions}
    //_________________________________________________________________________

    $this->_page->setTitle('Summary');

    $visible = isset($this->params["visible"])?$this->params["visible"]:"";
    $visible2 = isset($this->params["visibleat"])?$this->params["visibleat"]:"";

    $this->view->visibility_details = $visible;
    $this->view->visibility_account_type = $visible2;
    $this->view->groupId = $this->currentGroup->getId();
    $this->view->groupType = $this->currentGroup->getGroupType();

    $this->view->group = $this->currentGroup;
    $this->view->currentUser = $this->_page->_user;

    /** If group contain any value in groupUID then group is special and can't be deleted **/
    /** According to the bug #6543 **/
    $this->view->groupCanBeDeleted = !(bool)trim($this->currentGroup->getGroupUID());

    $this->view->secLeft = $this->_page->_user->getMembershipExpired() - $this->_page->_user->getNowTimeStamp();
    $this->view->daysLeft = intval(($this->_page->_user->getMembershipExpired() - $this->_page->_user->getNowTimeStamp())/86400);

    $this->view->bodyContent = 'groups/settings.tpl';
