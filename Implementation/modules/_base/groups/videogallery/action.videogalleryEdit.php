<?php
Warecorp::addTranslation('/modules/groups/videogallery/action.videogalleryEdit.php.xml');

    $this->_page->Xajax->registerUriFunction("edit_photo", "/groups/videogalleryEditGallVideo/");
    $this->_page->Xajax->registerUriFunction("edit_photo_do", "/groups/videogalleryEditGallVideoDo/");
    $this->_page->Xajax->registerUriFunction("cancel_edit_photo", "/groups/videogalleryCancelEditGallVideo/");
    $this->_page->Xajax->registerUriFunction("delete_photo", "/groups/videogalleryDeleteGallVideoDo/");
    $this->_page->Xajax->registerUriFunction("upload_photo", "/groups/videogalleryUploadVideo/");
    $this->_page->Xajax->registerUriFunction("upload_photo_do", "/groups/videogalleryUploadVideoDo/");

    $this->_page->Xajax->registerUriFunction("share_group", "/groups/videogalleryShareGroup/");
    $this->_page->Xajax->registerUriFunction("share_group_do", "/groups/videogalleryShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("share_friend", "/groups/videogalleryShareFriend/");
    $this->_page->Xajax->registerUriFunction("share_friend_do", "/groups/videogalleryShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("show_share_history", "/groups/videogalleryShowShareHistory/");
    $this->_page->Xajax->registerUriFunction("unshare_group_do", "/groups/videogalleryUnShareGroupDo/");
    $this->_page->Xajax->registerUriFunction("unshare_friend_do", "/groups/videogalleryUnShareFriendDo/");
    $this->_page->Xajax->registerUriFunction("editshowpage", "/groups/videoeditshowpage/");

if (SINGLEVIDEOMODE) {
        $form = new Warecorp_Form('editPhotoForm', 'post', $this->currentGroup->getGroupPath('videogalleryEditGallVideoDo'));
        $this->view->form = $form;
}
$gallery_id = isset($this->params['gallery']) ? floor($this->params['gallery']) : 0;
$action = isset($this->params['faction']) ? $this->params['faction'] : "";
$items_per_page = 10;
$page = empty($this->params['page'])?1:floor($this->params['page']);
$paging_url = '#null';

if ($gallery_id == 0 || !Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id)) {
    $this->_page->showAjaxAlert(Warecorp::t('Incorrect Gallery'));
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}

$gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id); 

if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}

$gEditForm = new Warecorp_Form('galleryEditForm', 'post', $this->currentGroup->getGroupPath('videogalleryedit/gallery').$gallery->getId().'/');
$gEditForm->addRule('title', 'required', Warecorp::t('Enter please gallery title'));

$videosListObj = $gallery->getVideos()->setCurrentPage($page)->setListSize($items_per_page);
$videosList = $videosListObj->getList();

if (SINGLEVIDEOMODE){
    if (!isset($videosList[0]))
        $this->_redirect($this->currentGroup->getGroupPath('videos'));
	if ($videosList[0]->getSource() == 'nonvideo'){
		if(!defined('ALLOW_EDIT_NONVIDEO_VIDEO')) {
        	$this->_redirect($this->currentGroup->getGroupPath('videos'));
		}elseif(ALLOW_EDIT_NONVIDEO_VIDEO !== 1){
            $this->_redirect($this->currentGroup->getGroupPath('videos'));
		}
	}
}else{
	if (count($videosList) == 1 && $videosList[0]->getSource() == 'nonvideo') {
        $this->_redirect($this->currentGroup->getGroupPath('videos'));
	}
}

$P = new Warecorp_Common_PagingProduct($videosListObj->getCount(), $items_per_page, $paging_url);
$this->view->infoPaging = $P->makeInfoPaging($page);
$this->view->paging = $P->makeAjaxLinkPaging($page, "xajax_editshowpage('","', '".$gallery_id."'); return false;");

if ($action != "save"){
	
    $this->view->gEditForm = $gEditForm;
    $this->view->SWFUploadID = session_id();
    $this->view->gallery_id = $gallery_id;
    $this->view->gallery = $gallery;
    $this->view->videoslist = $videosList;
    $this->view->page = $page;
    $this->view->expand = isset($this->params['expand'])?$this->params['expand']:null;
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
    $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'edit.tpl';
} else {
    /**
     * remove gallery and all photos + sharing
     */
    $remove = ( isset($this->params["remove"]) && $this->params["remove"] == "1" ) ? 1 : 0;
    if ($remove == 1) {
        $gallery->delete();

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", "DELETE", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "DELETE");
//            $mail->addParam('section', "VIDEO");
//            $mail->addParam('chObject', $gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/

        $this->_redirect($this->currentGroup->getGroupPath('videos'));
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
                if (!empty($group) && $group->getId() == $this->currentGroup->getId() && Warecorp_Video_AccessManager_Factory::create()->canCreateGallery($group, $this->_page->_user)) {
                    $gallery->setOwnerId($this->currentGroup->getId());
				}
			}
            $gallery->save();
            if ( isset($_SESSION['NEW_VIDEO_UPLOADED']) ) {
                unset($_SESSION['NEW_VIDEO_UPLOADED']);
            }
            else {
               if ( FACEBOOK_USED ) {
                    $paramsFB = array(
                        'title' => htmlspecialchars($gallery->getTitle()), 
                        'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                    );                                                 
                    $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('videogalleryView/id').$gallery->getId()."/");
                    $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_VIDEO, $paramsFB);    
                    Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
                }                
                
                /** Send notification to host **/
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", "CHANGES", false );
                
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', "CHANGES");
//                    $mail->addParam('section', "VIDEO");
//                    $mail->addParam('chObject', $gallery);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', false);
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/
            }

            $this->_redirect($this->currentGroup->getGroupPath('videos'));
        } else {
            //$photosList = $gallery->getPhotos()->getList();
            $this->view->gEditForm = $gEditForm;
            $this->view->SWFUploadID = session_id();
            $this->view->gallery_id = $gallery_id;
            $this->view->gallery = $gallery;
            $this->view->page = $page;
            $this->view->expand = isset($this->params['expand'])?$this->params['expand']:null;
            $this->view->videosList = $videosList;
            $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'edit.tpl';
        }
    }
}
