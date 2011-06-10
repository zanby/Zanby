<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.deleteHierarchy.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    
    $objResponse = new xajaxResponse();
    $this->view->h = $h;
    $this->view->curr_hid = $curr_hid;
    $content = $this->view->getContents('groups/hierarchy/hierarchy.delete.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Delete Hierarchy'));
    $popup_window->content($content);
    $popup_window->width(306)->height(350)->open($objResponse);
