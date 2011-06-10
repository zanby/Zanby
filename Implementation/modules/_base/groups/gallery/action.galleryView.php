<?php
Warecorp::addTranslation('/modules/groups/gallery/action.galleryView.php.xml');

if ( !Warecorp_Photo_AccessManager_Factory::create()->canViewGalleries($this->currentGroup, $this->_page->_user) ) {
    $this->_redirect($this->currentGroup->getGroupPath('summary'));
}

$tmbOnPage = 36; //how tumbnails display on page

if ( isset($this->params['id']) && $this->params['id'] && Warecorp_Photo_Standard::isPhotoExists($this->params['id']) ) {
    $photo = Warecorp_Photo_Factory::loadById($this->params['id']);
    $gallery = $photo->getGallery();

	if ( !Warecorp_Photo_AccessManager_Factory::create()->canViewGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
        $this->_redirect($this->currentGroup->getGroupPath('photos'));
	}
    $importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());
    
    $this->_page->Xajax->registerUriFunction("share_group", "/groups/galleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/groups/galleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/groups/galleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/groups/galleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("add_gallery", "/groups/galleryAddGallery/");
    $this->_page->Xajax->registerUriFunction("add_gallery_do", "/groups/galleryAddGalleryDo/");
    $this->_page->Xajax->registerUriFunction("add_photo", "/groups/galleryAddPhoto/");
    $this->_page->Xajax->registerUriFunction("add_photo_do", "/groups/galleryAddPhotoDo/");
    $this->_page->Xajax->registerUriFunction("add_comment_do", "/groups/galleryAddCommentDo/");
    $this->_page->Xajax->registerUriFunction("update_comment_do", "/groups/galleryUpdateCommentDo/");
    $this->_page->Xajax->registerUriFunction("delete_comment_do", "/groups/galleryDeleteCommentDo/");
    $this->_page->Xajax->registerUriFunction("edit_photo", "/groups/galleryEditPhoto/");
    $this->_page->Xajax->registerUriFunction("delete_photo", "/groups/galleryDeletePhoto/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/groups/galleryShowShareHistory/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/groups/galleryUnShareDo/");
	$this->_page->Xajax->registerUriFunction("unshare_group_do", "/groups/galleryUnShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("unshare_friend_do", "/groups/galleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("publish", "/groups/galleryPublish/");
    $this->_page->Xajax->registerUriFunction("publish_do", "/groups/galleryPublishDo/");
    $this->_page->Xajax->registerUriFunction("moveto", "/groups/galleryMoveTo/");
    $this->_page->Xajax->registerUriFunction("moveto_do", "/groups/galleryMoveToDo/");
    
	$this->view->photo = $photo;
    $this->view->gallery = $gallery;
    
    $this->_page->Xajax->registerUriFunction("show_tmb_page", "/groups/galleryShowTmbPage/");

    $this->view->tags = $photo->setForceDbTags()->getTagsList();
    
	$this->view->comments = $photo->getCommentsList();

    Warecorp_Photo_Gallery_Abstract::setGalleryViewed($gallery, $this->_page->_user);
    
    //if user is owner of gallery - display all of pictures.
    $photoListObj = $gallery->getPhotos();
    $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
    $this->view->importHistory = $importHistory;
    $photoListObj->setListSize($tmbOnPage);	
    $page = floor(isset($this->params['page'])?$this->params['page']:1);
    $page = ($page < 1)?1:$page;
    $photoListObj->setCurrentPage($page);
    $this->view->photosList = $photoListObj->getList();
	$this->view->tmbCurrentPage = $page;
    $this->view->tmbOnPage = $tmbOnPage;
    $this->view->tmpCountPhotos = $photoListObj->getCount();
    $this->view->tmbCountPage = ceil($photoListObj->getCount()/$tmbOnPage);    		
    $this->view->bodyContent = 'groups/gallery/photo_list.tpl';
} else {
    $this->_redirect($this->currentGroup->getGroupPath('photos'));
}
