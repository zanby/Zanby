<?php
Warecorp::addTranslation('/modules/groups/calendar/action.hiararchy.view.php.xml');

    $AccessManager = Warecorp_ICal_AccessManager_Factory::create();

    $this->view->Warecorp_ICal_AccessManager = $AccessManager;

    if ( null === $this->_page->_user->getId() && !$AccessManager->canAnonymousViewEvents($this->currentGroup) ) $this->_redirectToLogin();
    if ( false == $AccessManager->canViewEvents($this->currentGroup, $this->_page->_user) ) {
        $this->view->errorMessage = Warecorp::t('Sorry, you can not view this calendar');
        $this->view->bodyContent = 'groups/calendar/action.event.error.message.tpl';
        return ;
    }

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
    
    $mode = 'active';
    /**
    * @desc 
    */
    $h = Warecorp_Group_Hierarchy_Factory::create();
    $h->setGroupId($this->currentGroup->getId());  
    $h->addSystemHierarchy();

    $eventIds = array();  
    
    $objHierarchy = Warecorp_Group_Hierarchy::getGroupDefaultHierarchy($this->currentGroup->getId());
    //$arrHierarhyTree = $objHierarchy->getHierarchyTree();
    $arrHierarhyTree = $objHierarchy->getHierarchyTreeWithEvents(null, $currentTimezone, $this->_page->_user, $eventIds);
     

    $globalCategories = Warecorp_Group_Hierarchy::prepareTreeToPreviewWithEvents($objHierarchy, $arrHierarhyTree, null, $currentTimezone, $this->_page->_user, $eventIds);    
    $this->view->globalCategories = $globalCategories;
    
    $lstTags = new Warecorp_ICal_Event_List_Tag();
    $lstTags->setEntityIdsFilter($eventIds);

    $this->view->cols = 2;
    $this->view->minGrgoupInSection = 2;
    $this->view->tree = $arrHierarhyTree;

    $totalCount = 0;
    foreach ($globalCategories as $main) {
        foreach ($main as $level1) {
            if (is_array($level1['categories'])) {
                foreach ($level1['categories'] as $cat) {
                    $totalCount += $cat['countOfEvents'];
                }
            }
        }
    }
    $this->view->countOfEvents = $totalCount;
    $this->view->curr_hid = $objHierarchy->getId();
    $this->view->current_hierarchy = $objHierarchy;
    $this->view->menuContent = null;
    
    $this->view->viewMode = $mode;
    $this->view->lstTags = $lstTags;
    $this->view->bodyContent = 'groups/calendar/action.list.view.family.hierarchy.tpl';
    

