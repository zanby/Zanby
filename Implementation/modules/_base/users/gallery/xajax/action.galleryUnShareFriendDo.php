<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryUnShareFriendDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$user = new Warecorp_User('id', $userId);
// dump($userId);
// dump($galleryId);
// dump((int)Warecorp_Photo_AccessManager_Factory::create()->canUnShareGallery($gallery, $user, $this->_page->_user));
if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canUnShareGallery($gallery, $user, $this->_page->_user) ) {
	
    $gallery->unshare($user);
    $objResponse->addScript($application.'.showShareNew();');
} else {
	$objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
    //$objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
