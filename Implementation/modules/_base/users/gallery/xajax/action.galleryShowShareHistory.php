<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryShowShareHistory.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canViewShareHistoryGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $history = $gallery->getShareHistory($this->_page->_user);
    $form = new Warecorp_Form('unshareForm', 'post', $this->currentUser->getUserPath('galleryShowShareHistoryDo'));

    $this->view->unshareForm = $form;
    $this->view->gallery = $gallery;
    $this->view->history = $history;
    $this->view->JsApplication = $application;
    $content = $this->view->getContents('users/gallery/xajax.share.history.tpl');

    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($content);
    $popup_window->title(Warecorp::t('Share History'));
    $popup_window->width(450)->height(350)->open($objResponse);

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
