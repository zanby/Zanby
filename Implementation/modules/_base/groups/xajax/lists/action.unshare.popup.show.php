<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.unshare.popup.show.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

    $list = new Warecorp_List_Item($list_id);
    
    if (!$AccessManager->canUnshareList($list, $this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }

    $groupsList = $list->getSharedGroups();
    $friendsList = $list->getSharedUsers();
    $this->view->groupsList = $groupsList;
    $this->view->friendsList = $friendsList;
    $this->view->list = $list;

    $content = $this->view->getContents('groups/lists/unshare.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Unshare List"));
    $popup_window->content($content);
    $popup_window->width(500)->height(350)->open($objResponse);
