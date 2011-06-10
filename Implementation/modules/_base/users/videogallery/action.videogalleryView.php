<?php
if (SINGLEVIDEOMODE){
    $tmbOnPage = 5; //15; 
}else{
    $tmbOnPage = 5; //36;
}

if ( isset($this->params['id']) && $this->params['id'] && Warecorp_Video_Standard::isVideoExists($this->params['id'])) {
    $user = $this->currentUser;
    $video = Warecorp_Video_Factory::loadById($this->params['id']);
    $gallery = $video->getGallery();

    if (!(($gallery->getOwnerType() == 'user' && $gallery->getOwnerId() == $user->getId()) || ($gallery->isShared($user)) || ($gallery->isWatched($user)))) {
        $this->_redirect($this->currentUser->getUserPath('videos'));
    }
    if ( !Warecorp_Video_AccessManager_Factory::create()->canViewGallery($gallery, $this->currentUser, $this->_page->_user) ) {
        $this->_redirect($this->currentUser->getUserPath('videos'));
    }    
    $isFriend = Warecorp_User_Friend_Item::isUserFriend($this->currentUser->getId(), $this->_page->_user->getId());
    $importHistory = $gallery->getImportHistory($this->_page->_user, $video->getId());

    $this->_page->Xajax->registerUriFunction("share_group", "/users/videogalleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/users/videogalleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/users/videogalleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/users/videogalleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("add_gallery", "/users/videogalleryAddGallery/");
    $this->_page->Xajax->registerUriFunction("watchCollection", "/users/videogalleryWatch/");
    $this->_page->Xajax->registerUriFunction("stop_watching_do", "/users/videogalleryStopWatchingDo/");     
    $this->_page->Xajax->registerUriFunction("add_gallery_do", "/users/videogalleryAddGalleryDo/");
    $this->_page->Xajax->registerUriFunction("add_photo", "/users/videogalleryAddVideo/");
    $this->_page->Xajax->registerUriFunction("add_photo_do", "/users/videogalleryAddVideoDo/");
    $this->_page->Xajax->registerUriFunction("add_comment_do", "/users/videogalleryAddCommentDo/");
    $this->_page->Xajax->registerUriFunction("update_comment_do", "/users/videogalleryUpdateCommentDo/");
    $this->_page->Xajax->registerUriFunction("delete_comment_do", "/users/videogalleryDeleteCommentDo/");
    $this->_page->Xajax->registerUriFunction("edit_photo", "/users/videogalleryEditVideo/");
    $this->_page->Xajax->registerUriFunction("delete_photo", "/users/videogalleryDeleteVideo/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/users/videogalleryShowShareHistory/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/users/videogalleryUnShareDo/");
    # prev/next
    $this->_page->Xajax->registerUriFunction("show_share_history", "/users/videogalleryShowShareHistory/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/users/videogalleryUnShareDo/");

    $this->_page->Xajax->registerUriFunction("show_tmb_page", "/users/videogalleryShowTmbPage/");
    $this->_page->Xajax->registerUriFunction("unshare_group_do", "/users/videogalleryUnShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("unshare_friend_do", "/users/videogalleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");       
    $this->_page->Xajax->registerUriFunction("moveto", "/users/videogalleryMoveTo/");
    $this->_page->Xajax->registerUriFunction("moveto_do", "/users/videogalleryMoveToDo/");
    $this->_page->Xajax->registerUriFunction("viewCounter", "/ajax/viewsCounting/");

    $this->view->video = $video;
    $this->view->gallery = $gallery;

    $this->view->tags = $video->setForceDbTags()->getTagsList();

    $this->view->comments = $video->getCommentsList();

    Warecorp_Video_Gallery_Abstract::setGalleryViewed($gallery, $this->_page->_user);

    //if user is owner of gallery - display all of pictures.
    if (SINGLEVIDEOMODE){
        $videoListObj = new Warecorp_Video_List_User($this->currentUser->getId());
        $videoListObj->setOrder('tbl.creation_date DESC');
    }else{
        $videoListObj = $gallery->getVideos();
    }
	$this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
    $this->view->isFriend = $isFriend;
    $this->view->importHistory = $importHistory;    
    $videoListObj->setListSize($tmbOnPage);
    $page = floor(isset($this->params['page'])?$this->params['page']:1);
    $page = ($page < 1)?1:$page;    
    $videoListObj->setCurrentPage($page);
    $this->view->videosList = $videoListObj->getList();
    $this->view->tmbCurrentPage = $page;
    $this->view->tmbOnPage = $tmbOnPage;
    $this->view->tmpCountVideos = $videoListObj->getCount();    
    $this->view->tmbCountPage = ceil($videoListObj->getCount()/$tmbOnPage);
    $this->view->bodyContent = 'users/videogallery/'.VIDEOMODEFOLDER.'video_list.tpl';
} else {
    $this->_redirect($this->currentUser->getUserPath('videos'));
}
