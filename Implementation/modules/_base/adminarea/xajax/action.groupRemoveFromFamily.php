<?php
    Warecorp::addTranslation('/modules/adminarea/xajax/action.groupRemoveFromFamily.php.xml');
    $objResponse = new xajaxResponse();
    
    $group = Warecorp_Group_Factory::loadById($groupId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
    if (!$group->getId()) {
        $objResponse->addRedirect($this->admin->getAdminPath('groups'));
    }
    $family = Warecorp_Group_Factory::loadById($familyId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
    if (!$family->getId()) {
        $objResponse->addRedirect($this->admin->getAdminPath('groupFamilyMembership').'/id/' . $groupId);
    }
    $this->view->group = $group;
    $this->view->family = $family;
    $Content = $this->view->getContents('adminarea/xajax/action.groupRemoveFromFamily.tpl');
    
    error_log($Content);
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->content($Content);
    $popup_window->title(Warecorp::t('Remove Group from Family'));
    $popup_window->width(500)->height(350)->fixed(0)->open($objResponse);
