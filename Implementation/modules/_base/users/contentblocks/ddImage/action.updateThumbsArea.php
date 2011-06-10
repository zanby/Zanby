<?php

$objResponse = new xajaxResponse();

//thumbs
$avatars = array();

$galleriesList = $this->currentUser->getGalleries()->setAssocKey('view.id')->setAssocValue('view.id')->returnAsAssoc()->getList();
if (count($galleriesList) == 0 ) $galleriesList = array(0);
$photosList = Warecorp_Photo_List_Factory::loadByOwner($this->currentUser);
$photosList->setGalleryId($galleriesList);
$photosList = $photosList->setCurrentPage($pageNum+1)->setListSize($perPage);

$_list = $photosList->getList();
$_count = $photosList->getCount(); 


$this->view->currentCount = count($_list);
$this->view->total = $_count;
$this->view->perPage = $perPage;
$this->view->pagesCount = ceil($_count/$perPage);
$this->view->currentPage = $pageNum;
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_hash = $_list;

$objResponse->addAssign('a_gallery_thumbs_ddImage', 'innerHTML', $this->view->getContents('content_objects/ddImage/avatar_thumbs_block.tpl'));
