<?php
Warecorp::addTranslation('/modules/groups/xajax/action.declinemember.php.xml');

    $objResponse = new xajaxResponse();
    $template = 'groups/memberDecline.tpl';
    $this->view->all = $all;
    $this->view->link = $link;
    $Content = $this->view->getContents ( $template ) ;
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Decline Member'));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
