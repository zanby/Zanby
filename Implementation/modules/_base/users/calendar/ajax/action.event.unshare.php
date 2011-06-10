<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.unshare.php.xml");
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

    if ( !$handle ) {
        if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentUser, $this->_page->_user) ) {
            $sharedGroups = $objEvent->getSharing()
                                     ->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)
                                     ->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP)
                                     ->getList();
            $sharedUsers = $objEvent->getSharing()
                                     ->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)
                                     ->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER)
                                     ->getList();

            $familySharingList = new Warecorp_Share_List_Family();
            $familySharingList
                ->setUser($this->_page->_user)
                ->returnAsAssoc(true)
                ->setContext($this->_page->_user)
                ->setEntity($objEvent->getId(), $objEvent->EntityTypeId);

            $familySharedWith   = $familySharingList->getListSharedFamilies();
            if ( $familySharedWith ) {
                $familySharedWithObject = array();
                foreach ( $familySharedWith as $id => $name ) {
                    $family = new Warecorp_Group_Family('id', $id);
                    if ( $family->getId() )
                        $familySharedWithObject[$id] = $family;
                }
                $familySharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familySharedWith);
                $sharedGroups = (array)$familySharedWithObject + (array)$sharedGroups;
            }

            $linkAjaxShareGroups = "xajax_doEventUnShare('".$objEvent->getId()."', '".$objEvent->getUid()."', 'group', document.getElementById('group_id').options[document.getElementById('group_id').selectedIndex].value); return false;";
            $linkAjaxShareUsers = "xajax_doEventUnShare('".$objEvent->getId()."', '".$objEvent->getUid()."', 'user', document.getElementById('user_id').options[document.getElementById('user_id').selectedIndex].value); return false;";

            $this->view->groupsList = $sharedGroups;
            $this->view->familySharedWith = $familySharedWith;
            $this->view->friendsList = $sharedUsers;
            $this->view->event_id = $id;
            $this->view->uid = $uid;
            $this->view->objEvent = $objEvent;
            $this->view->linkAjaxShareGroups = $linkAjaxShareGroups;
            $this->view->linkAjaxShareUsers = $linkAjaxShareUsers;

            $Content = $this->view->getContents('users/calendar/ajax/action.event.unshare.tpl');

            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Unshare Event"));
            $popup_window->content($Content);
            $popup_window->width(400)->height(300)->open($objResponse);

        } elseif( $objEvent->getSharing()->isShared($this->_page->_user) ) {
            $objEvent->getSharing()->delete($this->_page->_user);
            $objResponse->addScript('popup_window.close();');
            $objResponse->addRedirect($this->currentUser->getUserPath('calendar.list.view'));
        }
    }
    else {
        if ( $mode == 'user' ) {
            $objContext = new Warecorp_User('id', $handle);
            if ( null !== $objContext->getId() && $this->currentUser->getId() != $objContext->getId() ) {
                $objEvent->getSharing()->delete($objContext);
            }
        } else {
            $allFamilyGroups = false;
            if ( false !== ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($handle)) ) {
                $allFamilyGroups = true;
                $handle = $familyId;
            }
            $objContext = Warecorp_Group_Factory::loadById($handle);
            if ( $allFamilyGroups && Warecorp_ICal_AccessManager_Factory::create()->canUnshareEventToAllFamilyGroups($objEvent, $objContext, $this->_page->_user) ) {
                $objEvent->getSharing()->delete($objContext, true);
            }
            elseif ( !$allFamilyGroups && null !== $objContext->getId() ) {
                $objEvent->getSharing()->delete($objContext);
            }
        }
        $objResponse->addScript('popup_window.close();');
        $objResponse->addScript('document.location.reload();');

        $this->_page->showAjaxAlert('Event was unshared');
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        //$objResponse->addRedirect($this->_page->_user->getUserPath('calendar') );
    }
