<?php
    Warecorp::addTranslation("/modules/users/xajax/action.deleteFriend.php.xml");
$objResponse = new xajaxResponse ( ) ;

if (Warecorp_User::isUserExists ( 'id', floor ( $friendId ) )) {
	$this->view->friendId = $friendId ;
	$Content = $this->view->getContents ( 'users/friends_delete.tpl' ) ;
	
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Delete Friend" ));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);

} else {
    $this->_redirect ( $BASE_HTTP_HOST ) ;
}
