<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryUnShareGroupDo.php.xml");

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$group = Warecorp_Group_Factory::loadById($groupId);

if ( $gallery->getId() !== null && 
    Warecorp_Video_AccessManager_Factory::create()->canUnShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {
	$gallery->unshare($group);
	
    $objResponse->addScript($application.'.showShareNew(null);');
} else {
    $objResponse->addRedirect($this->_page->_user->getUserPath('videos'));
    //$objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
