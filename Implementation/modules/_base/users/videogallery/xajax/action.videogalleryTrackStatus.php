<?php
    Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryTrackStatus.php.xml");
$objResponse = new xajaxResponse();

if (empty($this->params['gallery'])) {
    $objResponse->addRedirect($this->currentUser->getUserPath('videos'));
}
$gallery = Warecorp_Video_Gallery_Factory::loadById(floor($this->params['gallery']));
if ($gallery->getId() === null) $objResponse->addRedirect($this->currentUser->getUserPath('videos'));

if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentUser->getUserPath('videos'));
}

$videoProcessesList = $gallery->getActiveVideoProcesses();
if (empty($videoProcessesList))
    $objResponse->addRedirect($this->currentUser->getUserPath('videogalleryedit/gallery').$gallery->getId());

$this->view->processesList = $videoProcessesList;
$content = $this->view->getContents('users/videogallery/xajax.track.status.content.tpl');
$objResponse->addAssign('trackStatusContent', 'innerHTML', $content);
$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;
