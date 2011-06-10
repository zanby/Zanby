<?php
    Warecorp::addTranslation("/modules/ajax/action.bookmarkit.php.xml");
$objResponse = new xajaxResponse();
$form = new Warecorp_Form('bmForm', 'POST', 'javascript:void(0);');

$bookmarkListObj = new Warecorp_User_BookmarkService_List($this->_page->_user->getId());
$userBookmarkServicesList = $bookmarkListObj->getList();
if (!empty($userBookmarkServicesList)) {
	reset($userBookmarkServicesList);
	$service = current($userBookmarkServicesList);
	$service = $service->getId();
	$this->view->service = $service;
}
$this->view->form = $form;
$this->view->user = $this->_page->_user;
$this->view->userBookmarkServicesList = $userBookmarkServicesList;
if (isset($_SERVER["HTTP_REFERER"])) 
	$referer = $_SERVER["HTTP_REFERER"];
	else $referer = SITE_NAME_AS_FULL_DOMAIN;
$this->view->bookmarkUrl = $referer;
$this->view->bookmarkTitle = Warecorp::t('Zanby');

$content = $this->view->getContents('users/bookmarkit.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Add Bookmark'));
$popup_window->content($content);
$popup_window->width(465)->height(350)->open($objResponse);
