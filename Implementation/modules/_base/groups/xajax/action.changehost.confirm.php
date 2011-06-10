<?php
Warecorp::addTranslation('/modules/groups/xajax/action.changehost.confirm.php.xml');

    $objResponse = new xajaxResponse();
    $template = 'groups/changehost.confirm.tpl';
    $this->view->link = $link;
    $Content = $this->view->getContents ( $template ) ;
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Change Host'));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
