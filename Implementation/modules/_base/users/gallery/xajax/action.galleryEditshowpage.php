<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryEditshowpage.php.xml");
    $objResponse = new xajaxResponse();
    if ($gallery_id == 0 || !Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id)) {
        $objResponse->showAjaxAlert(Warecorp::t('Access Denied'));
        $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
        return $objResponse;
    }

    $items_per_page = 10; 
    $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);

    if ( !Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
        $objResponse->showAjaxAlert(Warecorp::t('Access Denied'));
        $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
        return $objResponse;
    }

    $photosListObj = $gallery->getPhotos();
    $paging_url = '#null';
    $P = new Warecorp_Common_PagingProduct($photosListObj->getCount(), $items_per_page, $paging_url);
    if ($expand_mode == 'all') {
        $form = new Warecorp_Form('editPhotoForm', 'post', $this->currentUser->getUserPath('galleryEditGallPhotoDo'));
        $this->view->form = $form;
    }
    $this->view->expand = $expand_mode;
    $this->view->page = $page;
    $this->view->infoPaging = $P->makeInfoPaging($page);
    $this->view->paging = $P->makeAjaxLinkPaging($page, "xajax_editshowpage('","', '".$gallery_id."', '".$expand_mode."'); return false;");
    $photosList = $photosListObj->setCurrentPage($page)->setListSize($items_per_page)->getList();
    $this->view->gallery = $gallery;
    $this->view->photoslist = $photosList;
    $capacity = $this->_page->_user->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE); 
    $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
    $this->view->percent = $percent;
    $content = $this->view->getContents('users/gallery/template.edit.photos.rows.tpl');
    $objResponse->addAssign('photosRows', 'innerHTML', $content);
    $objResponse->addScript('location.hash="#";');
    if ($expand_mode == 'all') {
        $objResponse->addScript('AddTMControlsForAllDescriptions();');
    }
