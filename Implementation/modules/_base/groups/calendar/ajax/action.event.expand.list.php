<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.expand.list.php.xml');
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
    $objEvent = new Warecorp_ICal_Event($uid);
    if ( null === $objEvent->getId() ) {
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
        
    $objList = new Warecorp_List_Item($listId);
    $lstLists = $objEvent->getLists()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
    
    if ( sizeof($lstLists) != 0 ) {
        $objResponse->addAssign("lists_list",'innerHTML', '');
        $this->view->event_id = $objEvent->getId();
        $this->view->objEvent = $objEvent;
        
        foreach ( $lstLists as &$objCurrList ) {
            if ( $objCurrList->getId() == $listId ) {
                $this->params['listid'] = $listId;
                $this->listsViewAction();
                
                $content = $this->view->getContents('groups/calendar/action.event.view.template.list.expanded.tpl');  
                $objResponse->addCreate("lists_list", "div", "list_{$objCurrList->getId()}");
                $objResponse->addAssign("list_{$listId}",'innerHTML', $content);
                $objResponse->addScript("xajax_list_view_add_form({$objCurrList->getId()});");
            } else {
                $this->view->list = $objCurrList;
                
                $content = $this->view->getContents('groups/calendar/action.event.view.template.list.collapsed.tpl');
                $objResponse->addCreate("lists_list", "div", "list_{$objCurrList->getId()}");
                $objResponse->addAssign("list_{$objCurrList->getId()}",'innerHTML', $content);
            }
        }
    }
    
