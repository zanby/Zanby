<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryTrackStatus.php.xml');

$objResponse = new xajaxResponse();

if (empty($this->params['gallery'])) {
    $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
}
$gallery = Warecorp_Video_Gallery_Factory::loadById(floor($this->params['gallery']));
if ($gallery->getId() === null) $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));

if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}

$videoProcessesList = $gallery->getActiveVideoProcesses();
if (empty($videoProcessesList))
    $objResponse->addRedirect($this->currentGroup->getGroupPath('videogalleryedit/gallery').$gallery->getId().'/');

$this->view->processesList = $videoProcessesList;
$content = $this->view->getContents('groups/videogallery/xajax.track.status.content.tpl');
$objResponse->addAssign('trackStatusContent', 'innerHTML', $content);
$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;
