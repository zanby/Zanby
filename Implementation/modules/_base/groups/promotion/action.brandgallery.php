<?php
Warecorp::addTranslation('/modules/groups/promotion/action.brandgallery.php.xml');

$this->_page->Xajax->registerUriFunction("brand_gallery_upload_image", "/groups/brandgalleryupload/");
$this->_page->Xajax->registerUriFunction("branditemDelete", "/groups/branditemDelete/");
$this->_page->Xajax->registerUriFunction("branditemDeleteDo", "/groups/branditemDeleteDo/");
$this->_page->Xajax->registerUriFunction("loadbrandimage", "/groups/loadbrandimage/");

$oBrandPhotosList = new Warecorp_Group_BrandPhoto_List($this->currentGroup->getId());

$list = $oBrandPhotosList->returnAsAssoc(false)->getList();
$this->view->brandPhotosList = $list;

//$tPhoto = $oBrandPhotosList->setCurrentPage(1)->setListSize(1)->setOrder('id')->returnAsAssoc(false)->getList();

if (sizeof($list))
$currentBrandPhoto = $list[0];
else
$currentBrandPhoto = null;

$this->view->currentBrandPhoto = $currentBrandPhoto;
$this->view->SWFUploadID = session_id();
$this->view->menuContent = '';
$this->view->bodyContent = 'groups/promotion/brandgallery.tpl';
