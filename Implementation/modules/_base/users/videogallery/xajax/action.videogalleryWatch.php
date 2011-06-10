<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryWatch.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentUser, $this->_page->_user) ) {
        $gallery->watch($this->_page->_user);
     }
$gallery->saveImportHistory($this->_page->_user, Warecorp_Video_Enum_ImportActionType::WATCH_GALLERY, null, null);

$importHistory = $gallery->getImportHistory($this->_page->_user);
$this->view->importHistory = $importHistory;
$importContent = $this->view->getContents('users/videogallery/template.import.history.tpl');
$objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
$objResponse->addScript("document.getElementById('wColLink').style.display = 'none';");
if (SINGLEVIDEOMODE){
    $objResponse->showAjaxAlert(Warecorp::t('Video Watched'));
}else{
    $objResponse->showAjaxAlert(Warecorp::t('Collection watched'));
}
