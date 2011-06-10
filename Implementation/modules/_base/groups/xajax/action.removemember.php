<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.removemember.php.xml');

    $objResponse = new xajaxResponse();
    $template = 'groups/memberRemove.tpl';
    $this->view->link = $link;
    $Content = $this->view->getContents ( $template ) ;

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Remove Member'));
    $popup_window->content($Content);
    $popup_window->width(400)->height(80)->open($objResponse);
