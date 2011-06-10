<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryStopWatchingDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canStopWatchingGallery($gallery, $this->currentUser, $this->_page->_user) ) {

	$gallery->stopWatch($this->_page->_user);
	
	$this->_page->showAjaxAlert(Warecorp::t('Watching stopped'));
	$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
	
	$objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not stop watchin'));  
}