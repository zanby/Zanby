<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.unshare.php.xml');
    $objResponse = new xajaxResponse();

    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
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
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($id);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;
    }
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
        $_SESSION['_calendar_']['_confirmPage_']['confirmMode'] = 'ERROR';
        $_SESSION['_calendar_']['_confirmPage_']['eventId'] = null;
        $_SESSION['_calendar_']['_confirmPage_']['confirmMessage'] = Warecorp::t('We are sorry, event was not found');
        $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.action.confirm'));    
        return $objResponse;
    }
    
    if ( !$handle ) {
        $objRootEvent = new Warecorp_ICal_Event($objEvent->getRootId());
        
        if ( Warecorp_ICal_AccessManager_Factory::create()->canManageEvent($objEvent, $this->currentGroup, $this->_page->_user) ) {
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
                ->setContext($this->currentGroup)
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

            $linkAjaxShareGroups = "xajax_doEventUnShare('".$objEvent->getId()."', '".$objEvent->getUid()."', '".$groupId."', 'group', document.getElementById('group_id').options[document.getElementById('group_id').selectedIndex].value); return false;";
            $linkAjaxShareUsers = "xajax_doEventUnShare('".$objEvent->getId()."', '".$objEvent->getUid()."', '".$groupId."', 'user', document.getElementById('user_id').options[document.getElementById('user_id').selectedIndex].value); return false;";
            
            $this->view->groupsList = $sharedGroups;
            $this->view->familySharedWith = $familySharedWith;
            $this->view->friendsList = $sharedUsers;
            $this->view->event_id = $id;
            $this->view->uid = $uid;
            $this->view->objEvent = $objEvent;
            $this->view->linkAjaxShareGroups = $linkAjaxShareGroups;
            $this->view->linkAjaxShareUsers = $linkAjaxShareUsers;
        
            $Content = $this->view->getContents('groups/calendar/ajax/action.event.unshare.tpl');
            
            $popup_window = Warecorp_View_PopupWindow::getInstance();
            $popup_window->title(Warecorp::t("Unshare Event"));
            $popup_window->content($Content);
            $popup_window->width(400)->height(350)->open($objResponse);
       
        } else {
            $allFamilyGroups = false;
            if ( false !== ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($groupId)) ) {
                $groupId = $familyId;
                $allFamilyGroups = true;
            }
            $objUsedContext = Warecorp_Group_Factory::loadById($groupId);
            if ( $objRootEvent->getSharing()->isShared($objUsedContext) && Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($objUsedContext, $this->_page->_user) ) {
                if ( $allFamilyGroups && !Warecorp_Share_Entity::hasShareException($familyId, $objEvent->getId(), $objEvent->EntityTypeId, $this->currentGroup->getId()) ) {
                    Warecorp_Share_Entity::addShareException($familyId, $objEvent->getId(), $objEvent->EntityTypeId, $this->currentGroup->getId());
                    $refs = Warecorp_ICal_Event_List_Standard::getListByRootId($objEvent()->getId());
                    if ( sizeof($refs) != 0 ) {
                        foreach ( $refs as &$ref ) {
                            if ( !Warecorp_Share_Entity::hasShareException($familyId, $ref->getId(), $ref->EntityTypeId, $this->currentGroup->getId()) )
                            Warecorp_Share_Entity::addShareException($familyId, $ref->getId(), $ref->EntityTypeId, $this->currentGroup->getId());
                        }
                    }
                }
                $objEvent->getSharing()->delete($objUsedContext);
                $objResponse->addScript('popup_window.close();');
                $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.list.view'));            
            } else {
                $objResponse->addRedirect($this->currentGroup->getGroupPath('calendar.list.view'));
            }        
        }
        
    }     
    else {
        if ( $mode == 'user' ) {
            $objContext = new Warecorp_User('id', $handle);
            if ( null !== $objContext->getId() ) {
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
            elseif ( null !== $objContext->getId() ) {
                $objEvent->getSharing()->delete($objContext);
            } 
        }
        $objResponse->addScript('popup_window.close();');
        $objResponse->addScript('document.location.reload();');
        
        $this->_page->showAjaxAlert(Warecorp::t('Event was unshared'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    }      
