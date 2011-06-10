<?php
$objResponse = new xajaxResponse () ;
if (SINGLEVIDEOMODE){
    $tmbOnPage = 15; 
}else{
    $tmbOnPage = 36;
}
$page = floor($page);
$galleryId = floor($galleryId);

$gallery =  Warecorp_Video_Gallery_Factory::loadById($galleryId);

if (SINGLEVIDEOMODE){
    $videoListObj = new Warecorp_Video_List_User($this->currentUser->getId());
    $videoListObj->setOrder('tbl.creation_date DESC');
}else{
    $videoListObj = $gallery->getVideos();
}

$videoListObj->setListSize($tmbOnPage);
$videoListObj->setCurrentPage($page);

$this->view->gallery = $gallery;
$this->view->videosList = $videoListObj->getList();
$this->view->tmbCurrentPage = $page;
$this->view->tmbOnPage = $tmbOnPage;
$this->view->tmpCountVideos = $videoListObj->getCount();
$this->view->user = $this->_page->_user;
$this->view->tmbCountPage = ceil($videoListObj->getCount()/$tmbOnPage);
$content = $this->view->getContents('users/videogallery/'.VIDEOMODEFOLDER.'xajax.showtmb.tpl');


//$content = "";
//$objResponse->addAssign('tmbPanel', 'innerHTML', $content);
if (SINGLEVIDEOMODE){
    if (sizeof($videoListObj->getList()) >= $tmbOnPage){
        $objResponse->addScript('videoList.response();');
    }
    $objResponse->addAssign('addVideoList'.$page, 'innerHTML', $content);
} else {
    $objResponse->addAssign('tmbPanel', 'innerHTML', $content);
}
