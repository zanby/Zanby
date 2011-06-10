<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryAddGallery.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if (Warecorp_Photo_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentUser, $this->_page->_user) && 
    $gallery->getId() !== null ) {
    
	$galleries = $this->_page->_user->getGalleries()
	                  ->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
	                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
	                  ->getList();
	
	$this->view->gallery = $gallery;
	$this->view->photo = $photo;
	$this->view->galleries = $galleries;
	$this->view->JsApplication = $application;
	$Content = $this->view->getContents('users/gallery/xajax.add.gallery.tpl');

    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($Content);
    $popup_window->title(Warecorp::t('Add Gallery to My Photos'));
    $popup_window->width(450)->height(200)->open($objResponse);

	$Script = '';
	$Script .= 'if ( YAHOO.util.Dom.get("addGalleryMode1") ) YAHOO.util.Dom.get("addGalleryMode1").checked = true;';
	$objResponse->addScript($Script); 

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
