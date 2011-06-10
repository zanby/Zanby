<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyVideoContentBlock/action.selectAvatar.php.xml');
$objResponse = new xajaxResponse();

$galleriesList = new Warecorp_Video_List_Group($this->currentGroup->getId());
$galleriesList = $galleriesList->setCurrentPage($pageNum+1)->setListSize($perPage);

$_list = $galleriesList->getList();
$_count = $galleriesList->getCount(); 

$this->view->currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup) ;
$this->view->currentCount =count($_list);
$this->view->total =$_count;
$this->view->perPage =$perPage;
$this->view->pagesCount =ceil($_count/$perPage);
$this->view->currentPage =$pageNum;
$this->view->cloneId =$cloneId;
$this->view->a_thumbs_hash =$_list;
$this->view->a_thumbs_content =$this->view->getContents('content_objects/ddFamilyVideoContentBlock/avatar_thumbs_block.tpl');

$content = $this->view->getContents('content_objects/ddFamilyVideoContentBlock/choose_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Select video'));
$popup_window->content($content);
$popup_window->width(400)->height(300)->open($objResponse);
