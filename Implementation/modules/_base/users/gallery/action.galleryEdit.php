<?php                                          
    Warecorp::addTranslation("/modules/users/gallery/action.galleryEdit.php.xml");

	$this->_page->Xajax->registerUriFunction("edit_photo", "/users/galleryEditGallPhoto/");
	$this->_page->Xajax->registerUriFunction("edit_photo_do", "/users/galleryEditGallPhotoDo/");
	$this->_page->Xajax->registerUriFunction("cancel_edit_photo", "/users/galleryCancelEditGallPhoto/");
	$this->_page->Xajax->registerUriFunction("delete_photo", "/users/galleryDeleteGallPhotoDo/");
	$this->_page->Xajax->registerUriFunction("upload_photo", "/users/galleryUploadPhoto/");
	$this->_page->Xajax->registerUriFunction("upload_photo_do", "/users/galleryUploadPhotoDo/");
    $this->_page->Xajax->registerUriFunction("share_group", "/users/galleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/users/galleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/users/galleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/users/galleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/users/galleryShowShareHistory/");
	$this->_page->Xajax->registerUriFunction("unshare_group_do", "/users/galleryUnShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("unshare_friend_do", "/users/galleryUnShareFriendDo/");
	$this->_page->Xajax->registerUriFunction("image_rotate", "/users/imageRotate/");
    $this->_page->Xajax->registerUriFunction("editshowpage", "/users/editshowpage/");

$gallery_id = isset($this->params['gallery']) ? floor($this->params['gallery']) : 0;
$action = isset($this->params['faction']) ? $this->params['faction'] : "";               
$items_per_page = 10;
$page = empty($this->params['page'])?1:floor($this->params['page']);
$paging_url = '#null';

if ($gallery_id == 0 || !Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id)) {
	$this->_page->showAjaxAlert(Warecorp::t('Incorrect Gallery'));
    $this->_redirect($this->currentUser->getUserPath('photos'));
}

$gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);

if ( !Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
	$this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentUser->getUserPath('photos'));
}

$gEditForm = new Warecorp_Form('galleryEditForm', 'post', $this->currentUser->getUserPath('galleryedit/gallery').$gallery->getId().'/');
$gEditForm->addRule('title', 'required', Warecorp::t('Enter please gallery title'));

$photosListObj = $gallery->getPhotos()->setCurrentPage($page)->setListSize($items_per_page);
$photosList = $photosListObj->getList();
$P = new Warecorp_Common_PagingProduct($photosListObj->getCount(), $items_per_page, $paging_url);
$this->view->infoPaging = $P->makeInfoPaging($page);
$this->view->paging = $P->makeAjaxLinkPaging($page, "xajax_editshowpage('","', '".$gallery_id."'); return false;");

if ($action != "save"){
    $capacity = $this->_page->_user->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE); 
    $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
    $this->view->percent = $percent;
	$this->view->gEditForm = $gEditForm;
    $this->view->SWFUploadID = session_id();
    $this->view->gallery_id = $gallery_id;
    $this->view->gallery = $gallery;
    $this->view->photoslist = $photosList;
	$this->view->page = $page;
    $this->view->expand = isset($this->params['expand'])?$this->params['expand']:null;
    $this->view->bodyContent = 'users/gallery/edit.tpl';
} else {
    /**
     * remove gallery and all photos + sharing
     */
    $remove = ( isset($this->params["remove"]) && $this->params["remove"] == "1" ) ? 1 : 0;
    if ($remove == 1) {
        $gallery->delete();
        $this->_redirect($this->currentUser->getUserPath('photos'));
    } 
    /**
     * update gallery and photos
     */
    else {
    	if ( $gEditForm->validate($this->params) ) {
		    $gallery->setPrivate((isset($this->params["isPrivate"]) && $this->params["isPrivate"] == 0) ? 0 : 1);
		    $gallery->setTitle($this->params["title"]);
		    $gallery->save();
            if ( FACEBOOK_USED ) {
                $paramsFB = array(
                    'title' => htmlspecialchars($gallery->getTitle()), 
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );                                                 
                $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentUser->getUserPath('galleryView/id').$gallery->getId()."/");
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_PHOTO, $paramsFB);    
                Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
            }
		    $this->_redirect($this->currentUser->getUserPath('photos'));
    	} else {
    		//$photosList = $gallery->getPhotos()->getList();
            $capacity = $this->_page->_user->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE); 
            $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
            $this->view->percent = $percent;
		    $this->view->gEditForm = $gEditForm;
            $this->view->SWFUploadID = session_id();
		    $this->view->gallery_id = $gallery_id;
		    $this->view->gallery = $gallery;
            $this->view->page = $page;
            $this->view->expand = isset($this->params['expand'])?$this->params['expand']:null;
		    $this->view->photoslist = $photosList;
		    $this->view->bodyContent = 'users/gallery/edit.tpl';
    	}
    }
}
