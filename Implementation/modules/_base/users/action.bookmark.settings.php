<?php
Warecorp::addTranslation("/modules/users/action.bookmark.settings.php.xml");

$list = new Warecorp_Bookmark_List();
$bookmarkServicesList = $list->getList();

$form = new Warecorp_Form('bookmarkSettings', 'post', '/'.$this->_page->Locale.'/bookmarks/');

if ($form->validate($this->params)) {

    $service = new Warecorp_User_BookmarkService($this->_page->_user->getId());
    $service->removAll();
    unset($service);
    foreach ($bookmarkServicesList as &$service){
        if (isset($this->params["bservice_".$service->getId()])){
            $bookmark = new Warecorp_User_BookmarkService($this->_page->_user->getId(), $service->getId());
            $bookmark->save();
            unset($bookmark);
        }
    }
//        $property->width = 500;
//        $property->height = 300;
    $this->_page->showAjaxAlert(Warecorp::t('Saved'));
}

$bookmarkListObj = new Warecorp_User_BookmarkService_List($this->_page->_user->getId());
$bookmarkListObj->returnAsAssoc();
$userBookmarkServicesList = $bookmarkListObj->getList();

$this->view->bookmarkServicesList = $bookmarkServicesList;
$this->view->userBookmarkServicesList = $userBookmarkServicesList;
$this->view->form = $form;
$this->view->bodyContent = 'users/bookmark.settings.tpl';
