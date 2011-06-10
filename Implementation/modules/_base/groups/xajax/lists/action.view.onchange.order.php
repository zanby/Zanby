<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.view.onchange.order.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;

    $objResponse = new xajaxResponse();
    
    $list_id = isset($list_id) ? (int)$list_id : 0;
    $list = new Warecorp_List_Item($list_id);
    
    if (!$AccessManager->canViewList($list, $this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'view';

    if (isset($order) && isset($list_id)) {
        $_SESSION['list_view'][$list_id]['order'] = $order;
        $this->listsViewRefresh($objResponse, $list_id);
    }
