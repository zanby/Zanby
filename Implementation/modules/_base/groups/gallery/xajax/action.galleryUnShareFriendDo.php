<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryUnShareFriendDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$user = new Warecorp_User('id', $userId);

if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canUnShareGallery($gallery, $user, $this->_page->_user) ) {
	
    $gallery->unshare($user);
    $objResponse->addScript($application.'.showShareNew(null);');
} else {
	$objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
    //$objResponse->showAjaxAlert(Warecorp::t('<font size="3">You can not unshare this gallery</font>'));
}
