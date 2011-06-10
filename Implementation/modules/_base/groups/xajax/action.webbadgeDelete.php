<?php
Warecorp::addTranslation('/modules/groups/xajax/action.webbadgeDelete.php.xml');

$objResponse = new xajaxResponse ( ) ;

$id = floor($id);

//if (Warecorp_User::isUserExists ( 'id', floor ( $friendId ) )) {
	$this->view->webbadgeId = $id;
	$Content = $this->view->getContents ( 'groups/promotion/webbadgeDelete.tpl' ) ;
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Delete WebBadge"));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);

//} else {
//    $this->_redirect ( $BASE_HTTP_HOST ) ;
//}
?>
