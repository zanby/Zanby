<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryUnShareFriendDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$user = new Warecorp_User('id', $userId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canUnShareGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

    $gallery->unshare($user);
    
    $objResponse->addScript($application.'.showShareNew(null);');
} else {
	$objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
    //$objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
