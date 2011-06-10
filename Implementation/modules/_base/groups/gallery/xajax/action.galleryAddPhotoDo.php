<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryAddPhotoDo.php.xml');
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && 
     $photo->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canCopyGallery($gallery, $this->currentGroup, $this->_page->_user)  &&
	 $photo->getCreatorId() == $this->_page->_user->getId()) {

	switch ( $data['mode'] ) {
	    case 1 : // add photo to exists gallery
	        $new_gallery = Warecorp_Photo_Gallery_Factory::loadById($data['galleryId']);
	        if ( $new_gallery->getId() !== null ) {
	        	$photo->copy($new_gallery, true);
	        	$gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::MERGE_PHOTO, $new_gallery->getId(), $photo->getId());

                $importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('groups/gallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
	        	
	        	$objResponse->showAjaxAlert(Warecorp::t('Photo added'));
	        }
	        break;
	    case 2: // add photo to new gallery
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
                $new_gallery->setIsCreated(1);
	            $new_gallery->setPrivate(0);
	            $new_gallery->save();
	            $photo->copy($new_gallery, true);
	            $gallery->saveImportHistory($this->_page->_user, Warecorp_Photo_Enum_ImportActionType::SAVE_PHOTO, $new_gallery->getId(), $photo->getId());
	            
                $importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());
                $this->view->importHistory = $importHistory;
                $importContent = $this->view->getContents('groups/gallery/template.import.history.tpl');
                $objResponse->addAssign('importHistoryBlock', 'innerHTML', $importContent);
	            $objResponse->addScript('popup_window.close();'); 
	            $objResponse->showAjaxAlert(Warecorp::t('Photo added'));
            }
	        break;
	}

} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not add photo'));  
}

