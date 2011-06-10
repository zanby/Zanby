<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyVideoContentBlock/action.updateThumbsArea.php.xml');

$objResponse = new xajaxResponse();

$galleriesList = new Warecorp_Video_List_Group($this->currentGroup->getId());
$galleriesList = $galleriesList->setCurrentPage($pageNum+1)->setListSize($perPage);

$_list = $galleriesList->getList();
$_count = $galleriesList->getCount(); 

$this->view->currentCount =count($_list);
$this->view->total =$_count;
$this->view->perPage =$perPage;
$this->view->pagesCount =ceil($_count/$perPage);
$this->view->currentPage =$pageNum;
$this->view->cloneId =$cloneId;
$this->view->a_thumbs_hash =$_list;

$objResponse->addAssign('thumbs_area', 'innerHTML', $this->view->getContents('content_objects/ddFamilyVideoContentBlock/avatar_thumbs_block.tpl'));
