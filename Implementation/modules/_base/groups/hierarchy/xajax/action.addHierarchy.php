<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.addHierarchy.php.xml');

    $objResponse = new xajaxResponse();
    $content = $this->view->getContents('groups/hierarchy/hierarchy.add.popup.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Add Hierarchy'));
    $popup_window->content($content);
    $popup_window->width(306)->height(350)->open($objResponse);

	$objResponse->addScript('YAHOO.util.Dom.get("hname").focus();');
