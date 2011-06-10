<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryAddGalleryDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentUser, $this->_page->_user) ) {

	switch ( $data['mode'] ) {
		case 1 : // add to exists gallery
			$new_gallery = Warecorp_Photo_Gallery_Factory::loadById($data['galleryId']);
			if ( $new_gallery->getId() !== null ) {
	            $gallery->copy($new_gallery, true);
	            $gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::MERGE_GALLERY, $new_gallery->getId(), null);

	            $importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());
	            $this->view->importHistory = $importHistory;
	            $importContent = $this->view->getContents('users/gallery/template.import.history.tpl');
	            $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);

	            $objResponse->showAjaxAlert(Warecorp::t('Gallery added'));
			}
			break;
	    case 2: // add to new gallery
            $data['galleryName'] = empty($data['galleryName'])?'':mb_substr(trim($data['galleryName']), 0, 100, 'UTF-8');
	        if (empty($data['galleryName'])) {
                $errors = Warecorp::t('Please name new gallery');
                $this->view->errors = $errors;
                $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
                $objResponse->addAssign('errors', 'innerHTML', $errorcontent);
                $objResponse->addAssign('TB_ajaxContent', 'style.height', '270px');
            } else {
                $new_gallery = Warecorp_Photo_Gallery_Factory::createByOwner($this->_page->_user);
	            $new_gallery->setOwnerType("user");
	            $new_gallery->setOwnerId($this->_page->_user->getId());
	            $new_gallery->setCreatorId($this->_page->_user->getId());
	            $new_gallery->setTitle($data['galleryName']);
	            $new_gallery->setDescription("");
	            $new_gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
	            $new_gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
	            $new_gallery->setSize(0);
	            $new_gallery->setPrivate(0);
                $new_gallery->setIsCreated(1);
	            $new_gallery->save();
	            $gallery->copy($new_gallery, true);
	            $gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::SAVE_GALLERY, $new_gallery->getId(), null);

	            $importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('users/gallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
	            $objResponse->addScript('popup_window.close();');
	            $objResponse->showAjaxAlert(Warecorp::t('Gallery added'));
            }
	        break;
	    case 3 : // watch gallery
	    	$gallery->watch($this->_page->_user);
	    	$gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::WATCH_GALLERY, null, null);

            $importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());
            $this->view->importHistory = $importHistory;
            $importContent = $this->view->getContents('users/gallery/template.import.history.tpl');
            $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
	    	
	    	$objResponse->showAjaxAlert(Warecorp::t('Gallery watched'));
	        break;
	}
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
