<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryAddPhoto.php.xml');
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && 
     $photo->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentGroup, $this->_page->_user)  &&
	 $photo->getCreatorId() == $this->_page->_user->getId()) {

	$galleries = $this->_page->_user->getGalleries()
	                  ->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
	                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
	                  ->getList();
	
	$this->view->gallery = $gallery;
	$this->view->photo = $photo;
	$this->view->galleries = $galleries;
	$this->view->JsApplication = $application;
	$Content = $this->view->getContents('groups/gallery/xajax.add.photo.tpl');
	
    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($Content);
    $popup_window->title(Warecorp::t('Add Selected Photo to My Photos'));
    $popup_window->width(500)->height(200)->open($objResponse);

	$Script = '';
	$Script .= 'if ( YAHOO.util.Dom.get("addPhotoMode1") ) YAHOO.util.Dom.get("addPhotoMode1").checked = true;';
	$objResponse->addScript($Script);  

} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not add photo'));  
}                  

