<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryShareFriend.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {

	$friendsListObj = new Warecorp_User_Friend_List();
	$friendsListObj->setUserId($this->_page->_user->getId());
	$friendsList = $friendsListObj->getList();

	$this->view->gallery = $gallery;
	$this->view->friendsList = $friendsList;
	$this->view->JsApplication = $application;
	$Content = $this->view->getContents('users/gallery/xajax.share.friend.tpl');

	$Script = "";
	$Script .= 'if ( YAHOO.util.Dom.get("shareFriendMode1") ) YAHOO.util.Dom.get("shareFriendMode1").checked = true;';
	$Script .= 'YAHOO.util.Dom.get("shareFriendMode2AddFields").style.display = "none";';
	$objResponse->addScript($Script);

    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($Content);
    $popup_window->title(Warecorp::t('Share Gallery'));
    $popup_window->width(450)->height(350)->fixed(0)->open($objResponse);

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
