<?php
Warecorp::addTranslation('/modules/groups/calendar/action.member.view.php.xml');
    
    $AccessManager = Warecorp_ICal_AccessManager_Factory::create();
    
    $this->view->Warecorp_ICal_AccessManager = $AccessManager;

    if ( null === $this->_page->_user->getId() && !$AccessManager->canAnonymousViewEvents($this->currentGroup) ) $this->_redirectToLogin();
    if ( false == $AccessManager->canViewEvents($this->currentGroup, $this->_page->_user) ) {
        $this->view->errorMessage = Warecorp::t('Sorry, you can not view this calendar');
        $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
        return ;
    }

    /**
    * Register Ajax Functions
    */
    $this->_page->Xajax->registerUriFunction( "bookmarkit", "/ajax/bookmarkit/" );
    $this->_page->Xajax->registerUriFunction( "addbookmark", "/ajax/addbookmark/" );
    $this->_page->Xajax->registerUriFunction( "addToFriends", "/ajax/addToFriends/" );
    $this->_page->Xajax->registerUriFunction( "addToFriendsDo", "/ajax/addToFriendsDo/" );
    //
    $this->_page->Xajax->registerUriFunction( "doCancelEvent", "/groups/calendarEventCancel/" );
    $this->_page->Xajax->registerUriFunction( "doAttendeeEvent", "/groups/calendarEventAttendee/" );
    $this->_page->Xajax->registerUriFunction( "viewAttendeeEvent", "/groups/calendarEventAttendeeView/" );

    
    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    $objRequest = $this->getRequest();

    if ( null === $objRequest->getParam('mode', null) || !in_array(strtolower($objRequest->getParam('mode', '')), array('active', 'expired')) ) {
        $mode = 'active';
    } else {
        $mode = strtolower($objRequest->getParam('mode'));
    }
    if ( !$AccessManager->isHostPrivileges($this->currentGroup, $this->_page->_user) ) {
        $mode = 'active';
    }
    
    /**
    * Detect Category from Hierarchy
    */
    $h = Warecorp_Group_Hierarchy_Factory::create();
    $h->setGroupId($this->currentGroup->getId());  
    $h->addSystemHierarchy();

    $objHierarchy = Warecorp_Group_Hierarchy::getGroupDefaultHierarchy($this->currentGroup->getId());

    $lstGroups = array();
    $showSectionName = true;
    if ( $objRequest->getParam('level') == 'category' ) {
        $arrPath = $objHierarchy->getPath($objRequest->getParam('uid'), 'id, name, type', true, true);
        array_shift($arrPath);
        $arrHierarhyTree = $objHierarchy->getCategoryTree($objRequest->getParam('uid'));
        if ( sizeof($arrHierarhyTree) != 0 ) {
            foreach ( $arrHierarhyTree as $cat ) {
                if ( $cat['type'] == 'item' ) {
                    $objTmpGroup = Warecorp_Group_Factory::loadById($cat['group_id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                    $lstGroups[] = $objTmpGroup;
                }
            }
        }
        $node = $objHierarchy->getNode($objRequest->getParam('uid'));
        $sectionName = $node['name'];
    } elseif ( $objRequest->getParam('level') == 'group' ) {
        $arrPath = $objHierarchy->getPath($objRequest->getParam('uid'), 'id, name, type', false, true);
        array_shift($arrPath);
        $node = $objHierarchy->getNode($objRequest->getParam('uid'));
        $objTmpGroup = Warecorp_Group_Factory::loadById($node['group_id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        $lstGroups[] = $objTmpGroup;

        $node['name'] = $objTmpGroup->getName();
        $arrPath[] = $node;
        $sectionName = $objTmpGroup->getName();
        $showSectionName = false;

    }
    if ( sizeof($arrPath) != 0  ) {
        foreach ( $arrPath as &$path ) {
            if ( $path['type'] == 'item' ) {
                $path['url'] = $this->currentGroup->getGroupPath('calendar.member.view/level/group/uid/'.$path['id']);
            } else {
                $path['url'] = $this->currentGroup->getGroupPath('calendar.member.view/level/category/uid/'.$path['id']);
            }
        }
    }

    /**
     * Initialization global objects that is used in script 
     */
    $lstEventsObj = new Warecorp_ICal_Event_List();
    $lstEventsObj->setTimezone($currentTimezone);
    $tz = date_default_timezone_get();
    date_default_timezone_set($currentTimezone);
    $objNowDate = new Zend_Date();
    date_default_timezone_set($tz);        
    /**
     * Group Events
     */
    $lstGroupEvents = array();
    $lstGroupEventIds = array();
    if ( sizeof($lstGroups) != 0 ) {
        //if ( $objHierarchy->getHierarchyType() == Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM ) usort($lstGroups, 'sortGroupsByName');
        foreach ( $lstGroups as &$objGroup ) {
            if ( $AccessManager->canViewEvents($objGroup, $this->_page->_user) ) {
                $objEvents = new Warecorp_ICal_Event_List_Standard();
                $objEvents->setTimezone($currentTimezone);
                $objEvents->setOwnerIdFilter($objGroup->getId());
                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                // privacy
                if ( $AccessManager->canViewPublicEvents($objGroup, $this->_page->_user) && $AccessManager->canViewPrivateEvents($objGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( $AccessManager->canViewPublicEvents($objGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( $AccessManager->canViewPrivateEvents($objGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                if ( $AccessManager->canViewSharedEvents($objGroup, $this->_page->_user) ) {
                    $objEvents->setSharingFilter(array(0,1));
                } else {
                    $objEvents->setSharingFilter(array(0));
                }
                
                if ( $mode == 'active' ) {
                    $objEvents->setCurrentEventFilter(true);
                    $objEvents->setExpiredEventFilter(false);
                } else {
                    $objEvents->setCurrentEventFilter(false);
                    $objEvents->setExpiredEventFilter(true);    
                }
                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                
                if ( sizeof($arrEvents) ) {
                    foreach ( $arrEvents as &$ev ) {
                        $lstGroupEventIds[] = $ev->getId();
                        /**
                         * Find the event first date
                         */      
			            /**
			             * Find the event first date
			             */         
			            $strFirstDate = $lstEventsObj->findFirstEventDate($ev, $objNowDate);
			            if ( null !== $strFirstDate ) {
			                $ev->setTimezone($currentTimezone);
			                $ev->setDtstart($strFirstDate);
			            }                        
                    }
                }
			    /**
			     * Sort events by date
			     */    
			    if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
			        if ( $mode == 'active' ) usort($arrEvents, "eventDateCmpDesc");
			        else usort($arrEvents, "eventDateCmpAsc"); 
			    } else {
			        if ( $mode == 'active' ) usort($arrEvents, "eventDateCmpDescAnonymous");
			        else usort($arrEvents, "eventDateCmpAscAnonymous"); 
			    }
                
                $lstGroupEventItem = array();
                $lstGroupEventItem['objGroup'] = $objGroup;
                $lstGroupEventItem['lstEvents'] = $arrEvents;
                
                $lstGroupEvents[] = $lstGroupEventItem;
            }
        }
    }
    
    $lstTags = new Warecorp_ICal_Event_List_Tag();
    $lstTags->setEntityIdsFilter($lstGroupEventIds);


    $this->view->lstGroupEvents = $lstGroupEvents;
    $this->view->arrPath = $arrPath; 
    $this->view->sectionName = $sectionName;
    $this->view->currentTimezone = $currentTimezone;
    $this->view->viewMode = $mode;
    $this->view->lstTags = $lstTags;
    $this->view->showSectionName = $showSectionName; 
    
    $this->view->bodyContent = 'groups/calendar/action.list.view.family.member.tpl';


    /**
    * +-------------------------------------------------------------------
    * |
    * |
    * +-------------------------------------------------------------------
    */
    function eventDateCmpAsc($event1, $event2)
    {
        return $event1->getDtstartValue() < $event2->getDtstartValue();
    }
    function eventDateCmpDesc($event1, $event2)
    {
        return $event1->getDtstartValue() > $event2->getDtstartValue();
    }
    function eventDateCmpAscAnonymous($event1, $event2)
    {
        return $event1->getOriginalDtstartValue() < $event2->getOriginalDtstartValue();
    }
    function eventDateCmpDescAnonymous($event1, $event2)
    {
        return $event1->getOriginalDtstartValue() > $event2->getOriginalDtstartValue();
    }    
    function sortGroupsByName($objGroup1, $objGroup2) 
    {
        return strcmp($objGroup1->getName(), $objGroup2->getName());
    }
