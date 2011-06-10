<?php
Warecorp::addTranslation('/modules/groups/xajax/action.branditemsDelete.php.xml');

$objResponse = new xajaxResponse ( ) ;

$id = floor($id);

$this->view->branditemId = $id;
$Content = $this->view->getContents ( 'groups/promotion/branditemDelete.tpl' ) ;

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t("Delete Brand Item"));
$popup_window->content($Content);
$popup_window->width(500)->height(350)->open($objResponse);
