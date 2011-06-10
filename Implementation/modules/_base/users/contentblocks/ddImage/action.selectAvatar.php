<?php
Warecorp::addTranslation("/modules/users/contentblocks/ddImage/action.selectAvatar.php.xml");
$objResponse = new xajaxResponse();

//main avatar
$avatarId = intval($avatarId);
$currentImage = Warecorp_Photo_Factory::loadById($avatarId);

if ($currentImage->getId() && $currentImage->getGallery()->getOwner()->getId() != $this->currentUser->getId()) {
    $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentUser);
}

//thumbs
$avatars = array();

$galleriesList = $this->currentUser->getGalleries()->setAssocKey('view.id')->setAssocValue('view.id')->returnAsAssoc()->getList();
$photosList = Warecorp_Photo_List_Factory::loadByOwner($this->currentUser);
if (empty($galleriesList)) $galleriesList = array(0);
$photosList->setGalleryId($galleriesList);
$photosList = $photosList->setCurrentPage($pageNum+1)->setListSize($perPage);

$_list = $photosList->getList();
$_count = $photosList->getCount(); 



$this->view->currentImage = $currentImage;
$this->view->currentCount = count($_list);
$this->view->total = $_count;
$this->view->perPage = $perPage;
$this->view->pagesCount = ceil($_count/$perPage);
$this->view->currentPage = $pageNum;
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_hash = $_list;

$content = $this->view->getContents('content_objects/ddImage/choose_avatar.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t("Select picture from all your galleries"));
$popup_window->content($content);
$popup_window->width(400)->height(280)->open($objResponse);
