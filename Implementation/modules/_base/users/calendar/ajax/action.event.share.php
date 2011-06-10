<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.share.php.xml");
    $objResponse = new xajaxResponse();

    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }

    //FIXME определить , какая таймзона является дефолтовой
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того,
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    /**
    * Check event
    */
    if ( !$id || !$uid ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($id);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }

    if ( !Warecorp_ICal_AccessManager_Factory::create()->canShareEvent($objEvent, $this->currentUser, $this->_page->_user ) ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, you can not share this event');
        $objResponse->addRedirect($this->currentUser->getUserPath('calendar.action.confirm'));
        return $objResponse;
    }

    if ( !$handle ) {
        $sharedGroups = $objEvent->getSharing()
                                 ->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)
                                 ->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP)
                                 ->getList();
        $sharedUsers = $objEvent->getSharing()
                                 ->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)
                                 ->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER)
                                 ->getList();

       /**
        * Check user access for sharing events
        * Redmine bug #2895
        */
        $groupsList = $this->_page->_user->getGroups()->returnAsAssoc()->setExcludeIds($sharedGroups)->getList();
        $groupsCount = 0;
        foreach ( $groupsList as $groupId => $groupName ) {
            $objGroup = Warecorp_Group_Factory::loadById($groupId);
            if ( Warecorp_ICal_AccessManager_Factory::create()->canCreateEvent($objGroup, $this->_page->_user) ) {
                ++$groupsCount;
            }
            else {
                unset($groupsList[$groupId]);
            }
        }

        //$groupsCount = $this->_page->_user->getGroups()->setExcludeIds(array())->getCount();

        $familySharingList = new Warecorp_Share_List_Family();
        $familySharingList
            ->setUser($this->_page->_user)
            ->returnAsAssoc(true)
            ->setContext($this->_page->_user)
            ->setEntity($objEvent->getId(), $objEvent->EntityTypeId);

        $familyNotSharedWith = $familySharingList->getListNotSharedFamilies();
        $familyNotSharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familyNotSharedWith);
        $groupsList = (array)$familyNotSharedWith + (array)$groupsList;

        $friends = new Warecorp_User_Friend_List();
        $friendsList = $friends->returnAsAssoc(false)->setExcludeIds($sharedUsers)->setUserId($this->_page->_user->getId())->getList();
        $friendsCount = $friends->setExcludeIds(array())->setUserId($this->_page->_user->getId())->getCount();


        $linkAjaxShareGroups = "xajax_doEventShare('".$objEvent->getId()."', '".$objEvent->getUid()."', 'group', document.getElementById('group_id').options[document.getElementById('group_id').selectedIndex].value); return false;";
        $linkAjaxShareUsers = "xajax_doEventShare('".$objEvent->getId()."', '".$objEvent->getUid()."', 'user', document.getElementById('friend_id').options[document.getElementById('friend_id').selectedIndex].value); return false;";

        $this->view->groupsList = $groupsList;
        $this->view->friendsList = $friendsList;
        $this->view->event_id = $id;
        $this->view->uid = $uid;
        $this->view->objEvent = $objEvent;
        $this->view->linkAjaxShareGroups = $linkAjaxShareGroups;
        $this->view->linkAjaxShareUsers = $linkAjaxShareUsers;
        $this->view->groupsCount = $groupsCount;
        $this->view->friendsCount = $friendsCount;

        $Content = $this->view->getContents('users/calendar/ajax/action.event.share.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Share Event"));
        $popup_window->content($Content);
        $popup_window->width(400)->height(350)->open($objResponse);
    }
    else {
        /*
        if ( $objEvent->getId() != $objEvent->getRootId() ) {
            $objEvent = new Warecorp_ICal_Event($objEvent->getRootId());
        }
        */
        if ( $mode == 'user' ) {
            $objContext = new Warecorp_User('id', $handle);
            if ( null !== $objContext->getId() && $this->currentUser->getId() != $objContext->getId() ) {
                $objEvent->getSharing()->add($objContext);
            }
        } else {
            $allFamilyGroupSharing = false;
            if ( false !== ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($handle)) ) {
                $allFamilyGroupSharing = true;
                $handle = $familyId;
            }

            $objContext = Warecorp_Group_Factory::loadById($handle);
            if ( $objContext && $objContext->getId() ) {
                if ( $allFamilyGroupSharing && Warecorp_ICal_AccessManager_Factory::create()->canShareEventToAllFamilyGroups($objEvent, $objContext, $this->_page->_user) ) {
                    $objEvent->getSharing()->add($objContext, true, true);
                }
                elseif ( !$allFamilyGroupSharing ) {
                    $objEvent->getSharing()->add($objContext);
                }
            }

        }
        $objResponse->addScript('popup_window.close();');
        $objResponse->addScript('document.location.reload();');
    }
