<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryAddVideoDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$video = Warecorp_Video_Factory::loadById($videoId);

if ( $gallery->getId() !== null && 
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canCopyGallery($gallery, $gallery->getOwner(), $this->_page->_user) ) {

    switch ( $data['mode'] ) {
        case 1 : // add photo to exists gallery
            $new_gallery = Warecorp_Video_Gallery_Factory::loadById($data['galleryId']);
            if ( $new_gallery->getId() !== null ) {
                $video->copy($new_gallery);
                $gallery->saveImportHistory($this->_page->_user, Warecorp_Video_Enum_ImportActionType::MERGE_VIDEO, $new_gallery->getId(), $video->getId());

                $importHistory = $gallery->getImportHistory($this->_page->_user, $video->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('users/videogallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);

                $objResponse->showAjaxAlert(Warecorp::t('Video added'));
            }
            break;
        case 2: // add photo to new gallery
            $data['galleryName'] = empty($data['galleryName'])?'':trim($data['galleryName']);
            if (empty($data['galleryName'])) {
                $errors = Warecorp::t('Please name new gallery');
                $this->view->errors = $errors;
                $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
                $objResponse->addAssign('errors', 'innerHTML', $errorcontent);
            } else {
                $new_gallery = Warecorp_Video_Gallery_Factory::createByOwner($this->_page->_user);
                $new_gallery->setOwnerType("user");
                $new_gallery->setOwnerId($this->_page->_user->getId());
                $new_gallery->setCreatorId($this->_page->_user->getId());
                $new_gallery->setTitle($data['galleryName']);
                $new_gallery->setDescription("");
                $new_gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
                $new_gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
                $new_gallery->setSize(0);
                $new_gallery->setIsCreated(1);
                $new_gallery->setPrivate(0);
                $new_gallery->save();
                $video->copy($new_gallery);
                $gallery->saveImportHistory($this->_page->_user, Warecorp_Video_Enum_ImportActionType::SAVE_VIDEO, $new_gallery->getId(), $video->getId());

                $importHistory = $gallery->getImportHistory($this->_page->_user, $video->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('users/videogallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
                $objResponse->addScript('popup_window.close();');
                $objResponse->showAjaxAlert(Warecorp::t('Video added'));
            }
            break;
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
