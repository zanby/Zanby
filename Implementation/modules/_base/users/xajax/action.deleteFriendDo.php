<?php
    Warecorp::addTranslation("/modules/users/xajax/action.deleteFriendDo.php.xml");
$objResponse = new xajaxResponse ( ) ;
if (Warecorp_User::isUserExists ( 'id', floor ( $friendId ) )) {
    $friend = new Warecorp_User_Friend_Item ( $this->_page->_user->getId (), $friendId ) ;

    if ($friend->delete ()) {
        $objResponse->addScript ( "popup_window.close();" ) ;
        $this->_page->showAjaxAlert(Warecorp::t('Deleted'));

        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->_page->_user->getUserPath('friends'));
    }
} else {
    $this->_redirect ( $BASE_HTTP_HOST ) ;
}
