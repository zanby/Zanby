<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryUnShareDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canUnShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {
	
    $gallery->unshare($this->currentUser);
	
	$this->_page->showAjaxAlert(Warecorp::t('Gallery is unshared'));
	$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
	
	$objResponse->addRedirect($this->currentUser->getUserPath('photos'));
} else {
	$objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
	//$objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
