<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryShowTmbPage.php.xml');

$objResponse = new xajaxResponse () ;
$tmbOnPage = 36; //how tumbnails display on page
$page = floor($page);
$galleryId = floor($galleryId);

$gallery =  Warecorp_Photo_Gallery_Factory::loadById($galleryId);


$photoListObj = $gallery->getPhotos();

$photoListObj->setListSize($tmbOnPage);
$photoListObj->setCurrentPage($page);

$this->view->gallery = $gallery;
$this->view->photosList = $photoListObj->getList();
$this->view->tmbCurrentPage = $page;
$this->view->tmbOnPage = $tmbOnPage;
$this->view->tmpCountPhotos = $photoListObj->getCount();
$this->view->user = $this->_page->_user;
$this->view->tmbCountPage = ceil($photoListObj->getCount()/$tmbOnPage);
$content = $this->view->getContents('groups/gallery/xajax.showtmb.tpl');


//$content = "";
$objResponse->addAssign('tmbPanel', 'innerHTML', $content);
