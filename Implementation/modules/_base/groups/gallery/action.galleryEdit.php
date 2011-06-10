<?php
Warecorp::addTranslation('/modules/groups/gallery/action.galleryEdit.php.xml');

	$this->_page->Xajax->registerUriFunction("edit_photo", "/groups/galleryEditGallPhoto/");
	$this->_page->Xajax->registerUriFunction("edit_photo_do", "/groups/galleryEditGallPhotoDo/");
	$this->_page->Xajax->registerUriFunction("cancel_edit_photo", "/groups/galleryCancelEditGallPhoto/");
	$this->_page->Xajax->registerUriFunction("delete_photo", "/groups/galleryDeleteGallPhotoDo/");
	$this->_page->Xajax->registerUriFunction("upload_photo", "/groups/galleryUploadPhoto/");
	$this->_page->Xajax->registerUriFunction("upload_photo_do", "/groups/galleryUploadPhotoDo/");
	
    $this->_page->Xajax->registerUriFunction("share_group", "/groups/galleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/groups/galleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/groups/galleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/groups/galleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/groups/galleryShowShareHistory/");
	$this->_page->Xajax->registerUriFunction("unshare_group_do", "/groups/galleryUnShareGroupDo/");
	$this->_page->Xajax->registerUriFunction("unshare_friend_do", "/groups/galleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("editshowpage", "/groups/editshowpage/");
	
$gallery_id = isset($this->params['gallery']) ? floor($this->params['gallery']) : 0;
$action = isset($this->params['faction']) ? $this->params['faction'] : "";
$items_per_page = 10;
$page = empty($this->params['page'])?1:floor($this->params['page']);
$paging_url = '#null';

if ($gallery_id == 0 || !Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id)) {
	$this->_page->showAjaxAlert(Warecorp::t('Incorrect Gallery'));
    $this->_redirect($this->currentGroup->getGroupPath('photos'));
}

$gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);

if ( !Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
	$this->_page->showAjaxAlert('Access Denied');
    $this->_redirect($this->currentGroup->getGroupPath('photos'));
}

$gEditForm = new Warecorp_Form('galleryEditForm', 'post', $this->currentGroup->getGroupPath('galleryedit/gallery').$gallery->getId().'/');
$gEditForm->addRule('title', 'required', Warecorp::t('Enter please gallery title'));

$photosListObj = $gallery->getPhotos()->setCurrentPage($page)->setListSize($items_per_page);
$photosList = $photosListObj->getList();
$P = new Warecorp_Common_PagingProduct($photosListObj->getCount(), $items_per_page, $paging_url);
$this->view->infoPaging = $P->makeInfoPaging($page);
$this->view->paging = $P->makeAjaxLinkPaging($page, "xajax_editshowpage('","', '".$gallery_id."'); return false;");

if ($action != "save"){
    $capacity = $this->currentGroup->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE); 
    $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
	$this->view->gEditForm = $gEditForm;
    $this->view->percent = $percent;
    $this->view->SWFUploadID = session_id();     
    $this->view->gallery_id = $gallery_id;
    $this->view->gallery = $gallery;
    $this->view->photoslist = $photosList;
    $this->view->page = $page;
    $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
    $this->view->expand = isset($this->params['expand'])?$this->params['expand']:null;   
    $this->view->bodyContent = 'groups/gallery/edit.tpl';
} else {
    /**
     * remove gallery and all photos + sharing
     */
    $remove = ( isset($this->params["remove"]) && $this->params["remove"] == "1" ) ? 1 : 0;   
    if ($remove == 1 && Warecorp_Photo_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentGroup, $this->_page->_user)) {
        $gallery->delete();

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "DELETE", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "DELETE");
//            $mail->addParam('section', "PHOTO");
//            $mail->addParam('chObject', $gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->addParam('items', array());
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/

        $this->_redirect($this->currentGroup->getGroupPath('photos'));
    }
    /**
     * update gallery and photos
     */
    else {
    	if ( $gEditForm->validate($this->params) ) {
		    $gallery->setPrivate((isset($this->params["isPrivate"]) && $this->params["isPrivate"] == 0) ? 0 : 1);
		    $gallery->setTitle($this->params["title"]);
            if (!empty($this->params['owner'])) {
                $group = Warecorp_Group_Factory::loadById(intval($this->params['owner']));
                if (!empty($group) && $group->getId() == $this->currentGroup->getId() && Warecorp_Photo_AccessManager_Factory::create()->canCreateGallery($group, $this->_page->_user)) {
                    $gallery->setOwnerId($this->currentGroup->getId());
				}
			}
		    $gallery->save();
            
            if ( FACEBOOK_USED ) {
                $paramsFB = array(
                    'title' => htmlspecialchars($gallery->getTitle()), 
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );                                                 
                $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('galleryView/id').$gallery->getId()."/");
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_PHOTO, $paramsFB);    
                Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
            }
            
		    /** Send notification to host **/
            $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "CHANGES", false );
            
//            if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                $mail->setSender($this->currentGroup);
//                $mail->addRecipient($this->currentGroup->getHost());
//                $mail->addParam('Group', $this->currentGroup);
//                $mail->addParam('action', "CHANGES");
//                $mail->addParam('section', "PHOTO");
//                $mail->addParam('chObject', $gallery);
//                $mail->addParam('User', $this->_page->_user);
//                $mail->addParam('isPlural', false);
//                $mail->addParam('items', array());
//                $mail->sendToPMB(true);
//                $mail->send();
//            }
            /** --- **/

		    $this->_redirect($this->currentGroup->getGroupPath('photos'));
    	} else {
    		$photosList = $gallery->getPhotos()->getList();
            $capacity = $this->currentGroup->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE); 
            $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
		    $this->view->gEditForm = $gEditForm;
            $this->view->percent = $percent;
            $this->view->SWFUploadID = session_id();             
		    $this->view->gallery_id = $gallery_id;
		    $this->view->gallery = $gallery;
            $this->view->page = $page;
            $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();
            $this->view->expand = isset($this->params['expand'])?$this->params['expand']:null;             
		    $this->view->photoslist = $photosList;
		    $this->view->bodyContent = 'groups/gallery/edit.tpl';    		
    	}
    }
}
