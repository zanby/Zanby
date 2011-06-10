<?php
Warecorp::addTranslation('/modules/groups/videogallery/action.videogalleryView.php.xml');

if ( !Warecorp_Video_AccessManager_Factory::create()->canViewGalleries($this->currentGroup, $this->_page->_user) ) {
    $this->_redirect($this->currentGroup->getGroupPath('summary'));
}

if (SINGLEVIDEOMODE) {
    $tmbOnPage = 5;//15; 
} else {
    $tmbOnPage = 5;//36;
}

if ( isset($this->params['id']) && $this->params['id'] && Warecorp_Video_Standard::isVideoExists($this->params['id'])) {
    $video = Warecorp_Video_Factory::loadById($this->params['id']);
    $gallery = $video->getGallery();

    if ( !Warecorp_Video_AccessManager_Factory::create()->canViewGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
        $this->_redirect($this->currentGroup->getGroupPath('videos'));
    }        
    $importHistory = $gallery->getImportHistory($this->_page->_user, $video->getId());

    $this->_page->Xajax->registerUriFunction("share_group", "/groups/videogalleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/groups/videogalleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/groups/videogalleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/groups/videogalleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("add_gallery", "/groups/videogalleryAddGallery/");
    $this->_page->Xajax->registerUriFunction("add_gallery_do", "/groups/videogalleryAddGalleryDo/");
    $this->_page->Xajax->registerUriFunction("add_photo", "/groups/videogalleryAddVideo/");
    $this->_page->Xajax->registerUriFunction("add_photo_do", "/groups/videogalleryAddVideoDo/");
    $this->_page->Xajax->registerUriFunction("add_comment_do", "/groups/videogalleryAddCommentDo/");
    $this->_page->Xajax->registerUriFunction("update_comment_do", "/groups/videogalleryUpdateCommentDo/");
    $this->_page->Xajax->registerUriFunction("delete_comment_do", "/groups/videogalleryDeleteCommentDo/");
    $this->_page->Xajax->registerUriFunction("edit_photo", "/groups/videogalleryEditVideo/");
    $this->_page->Xajax->registerUriFunction("delete_photo", "/groups/videogalleryDeleteVideo/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/groups/videogalleryShowShareHistory/");
    $this->_page->Xajax->registerUriFunction("unshare_do", "/groups/videogalleryUnShareDo/");
    $this->_page->Xajax->registerUriFunction("unshare_group_do", "/groups/videogalleryUnShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("unshare_friend_do", "/groups/videogalleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("publish", "/groups/videogalleryPublish/");
    $this->_page->Xajax->registerUriFunction("publish_do", "/groups/videogalleryPublishDo/");
    $this->_page->Xajax->registerUriFunction("moveto", "/groups/videogalleryMoveTo/");
    $this->_page->Xajax->registerUriFunction("moveto_do", "/groups/videogalleryMoveToDo/");
    $this->_page->Xajax->registerUriFunction("viewCounter", "/ajax/viewsCounting/");
    

    $this->view->video = $video;
    $this->view->gallery = $gallery;
    
    $this->_page->Xajax->registerUriFunction("show_tmb_page", "/groups/videogalleryShowTmbPage/");

    $this->view->tags = $video->setForceDbTags()->getTagsList();
    
    //if (isset($this->params['message'])) $photo->addComment($this->params['message']);
    $this->view->comments = $video->getCommentsList();

    Warecorp_Video_Gallery_Abstract::setGalleryViewed($gallery, $this->_page->_user);

    //if user is owner of gallery - display all of pictures.
    if (SINGLEVIDEOMODE){
        $videoListObj = new Warecorp_Video_List_Group($this->currentGroup->getId());
        $videoListObj->setOrder('tbl.creation_date DESC');
    }else{
        $videoListObj = $gallery->getVideos();
    }    
    $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
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
    $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'video_list.tpl';
} else {
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}
