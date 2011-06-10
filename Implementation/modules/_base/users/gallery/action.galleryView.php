<?php

$tmbOnPage = 36; //how tumbnails display on page

if ( isset($this->params['id']) && $this->params['id'] && Warecorp_Photo_Standard::isPhotoExists($this->params['id'])) {
	$user = $this->currentUser;
	$photo = Warecorp_Photo_Factory::loadById($this->params['id']);
	$gallery = $photo->getGallery();

	if (!(($gallery->getOwnerType() == 'user' && $gallery->getOwnerId() == $user->getId()) || ($gallery->isShared($user)) || ($gallery->isWatched($user)))) {
		$this->_redirect($this->currentUser->getUserPath('photos'));
	}
	if ( !Warecorp_Photo_AccessManager_Factory::create()->canViewGallery($gallery, $this->currentUser, $this->_page->_user) ) {
		$this->_redirect($this->currentUser->getUserPath('photos'));
	}	
	$isFriend = Warecorp_User_Friend_Item::isUserFriend($this->currentUser->getId(), $this->_page->_user->getId());
	$importHistory = $gallery->getImportHistory($this->_page->_user, $photo->getId());

	$this->_page->Xajax->registerUriFunction("share_group", "/users/galleryShareGroup/");
	$this->_page->Xajax->registerUriFunction("share_group_do", "/users/galleryShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("share_friend", "/users/galleryShareFriend/");
	$this->_page->Xajax->registerUriFunction("share_friend_do", "/users/galleryShareFriendDo/");
	$this->_page->Xajax->registerUriFunction("add_gallery", "/users/galleryAddGallery/");
	$this->_page->Xajax->registerUriFunction("add_gallery_do", "/users/galleryAddGalleryDo/");
	$this->_page->Xajax->registerUriFunction("add_photo", "/users/galleryAddPhoto/");
	$this->_page->Xajax->registerUriFunction("add_photo_do", "/users/galleryAddPhotoDo/");
	$this->_page->Xajax->registerUriFunction("add_comment_do", "/users/galleryAddCommentDo/");
	$this->_page->Xajax->registerUriFunction("update_comment_do", "/users/galleryUpdateCommentDo/");
	$this->_page->Xajax->registerUriFunction("delete_comment_do", "/users/galleryDeleteCommentDo/");
	$this->_page->Xajax->registerUriFunction("edit_photo", "/users/galleryEditPhoto/");
	$this->_page->Xajax->registerUriFunction("delete_photo", "/users/galleryDeletePhoto/");
	$this->_page->Xajax->registerUriFunction("show_share_history", "/users/galleryShowShareHistory/");
	$this->_page->Xajax->registerUriFunction("unshare_do", "/users/galleryUnShareDo/");
	# prev/next
	$this->_page->Xajax->registerUriFunction("show_share_history", "/users/galleryShowShareHistory/");
	$this->_page->Xajax->registerUriFunction("unshare_do", "/users/galleryUnShareDo/");

	$this->_page->Xajax->registerUriFunction("show_tmb_page", "/users/galleryShowTmbPage/");
	$this->_page->Xajax->registerUriFunction("unshare_group_do", "/users/galleryUnShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("unshare_friend_do", "/users/galleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("moveto", "/users/galleryMoveTo/");
    $this->_page->Xajax->registerUriFunction("moveto_do", "/users/galleryMoveToDo/");


	$this->view->photo = $photo;
	$this->view->gallery = $gallery;

	$this->view->tags = $photo->setForceDbTags()->getTagsList();

	//if (isset($this->params['message'])) $photo->addComment($this->params['message']);
	$this->view->comments = $photo->getCommentsList();

	Warecorp_Photo_Gallery_Abstract::setGalleryViewed($gallery, $this->_page->_user);

	//if user is owner of gallery - display all of pictures.

	$photoListObj = $gallery->getPhotos();

	$this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
	$this->view->isFriend = $isFriend;
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
	$this->view->bodyContent = 'users/gallery/photo_list.tpl';
} else {
	$this->_redirect($this->currentUser->getUserPath('photos'));
}
