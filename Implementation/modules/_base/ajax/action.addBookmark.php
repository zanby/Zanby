<?php
Warecorp::addTranslation("/modules/ajax/action.addBookmark.php.xml");

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Add Bookmark'));
$popup_window->width(465)->height(350);

$objResponse = new xajaxResponse();
$form = new Warecorp_Form('bmForm', 'POST', 'javascript:void(0);');
if (isset($params['_wf__bmForm'])) {
            $_REQUEST['_wf__bmForm'] = $params['_wf__bmForm'];
}
$bookmarkListObj = new Warecorp_User_BookmarkService_List($this->_page->_user->getId());
$form->addRule('bservice',       'required', Warecorp::t('Please choose bookmark service'));
$form->addRule('bookmark_url',   'required', Warecorp::t('Please enter Bookmark URL'));
$form->addRule('bookmark_title', 'required', Warecorp::t('Please enter Bookmark Title'));

if ($form->validate($params)) {
	$bookmarkListObj->returnAsAssoc();
    $userBookmarkServicesList = $bookmarkListObj->getList();

    if ($params["bservice"] && in_array($params["bservice"], $userBookmarkServicesList) ){
    	$bookmarkService = new Warecorp_Bookmark_Item(floor($params["bservice"]));

        $url = $bookmarkService->getUrl();
        $url = str_replace("%URL%", $params["bookmark_url"], $url);
        $url = str_replace("%TITLE%", $params["bookmark_title"], $url);       		
        $objResponse->addScript("popup_window.close()");
		$objResponse->addScriptCall('bookmark_redirect', $url);
    }
} else {
	$userBookmarkServicesList = $bookmarkListObj->getList();
	
	$this->view->form = $form;
	$this->view->user = $this->_page->_user;
	$this->view->userBookmarkServicesList = $userBookmarkServicesList;	
	
	$this->view->service = $params['bservice'];
	$this->view->bookmarkUrl = $params['bookmark_url'];	
	$this->view->bookmarkTitle = $params['bookmark_title'];	
	$content = $this->view->getContents('users/bookmarkit.tpl'); 	
	$popup_window->content($content);
    $popup_window->open($objResponse);
}
