<?php
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    
    $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('videogalleryUploadVideoDo'));
    $this->view->uploadPhotosForm = $form;
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    $this->view->versionSwitcher = 0;
    $this->view->gallery = $gallery;    
    $content = $this->view->getContents('users/videogallery/xajax.upload.videos.tpl');
    
    //$objResponse->addAssign('uploadPanelContent', 'innerHTML', $content);
/*    $this->view->errors = array('Please select files to upload');
    $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
    $objResponse->addClear('swferror', 'innerHTML');
    $objResponse->addAssign('swferror', 'innerHTML', $errorcontent); */     
    //$objResponse->addScript('YAHOO.util.Dom.get("uploadPanel").style.display = "";');
    $objResponse->addScript('turnOnSWFUpload();');
    //$objResponse->addScript('PGEApplication.uploadPanel.show();');

    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->title('');
    $popup_window->content($content);
    $popup_window->width(450)->height(350)->open($objResponse);   


}
