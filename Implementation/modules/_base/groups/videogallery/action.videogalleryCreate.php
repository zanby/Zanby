<?php
Warecorp::addTranslation('/modules/groups/videogallery/action.videogalleryCreate.php.xml');

$this->_page->Xajax->registerUriFunction("uploadandsubmit", "/groups/videogalleryuploadandsubmit/");
$this->_page->Xajax->registerUriFunction("delete_gallery", "/groups/videogalleryDeleteGallery/");
if ( !Warecorp_Video_AccessManager_Factory::create()->canCreateGallery($this->currentGroup, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
    $this->_redirect($this->currentGroup->getGroupPath('videos'));
}

$step = isset($this->params['step']) ? floor($this->params['step']) : 1;
if ($step >= 1 && $step <= 4){

    if ((isset($this->params['upload_type']) && $this->params['upload_type'] === "swfupload") && (isset($this->params['versionSwitcher']) && ($this->params['versionSwitcher'] == '0'))) {
        $gallery_id = isset($this->params['gallery_id']) ? floor($this->params['gallery_id']) : 0;
        if ($gallery_id != 0) {
            if (isset($_SESSION["swfupload"]) && $_SESSION["swfupload"][$gallery_id+0]) {
                $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
                $items = $_SESSION["swfupload"][$gallery->getId()]["items"];
                $new = (isset($_SESSION["swfupload"][$gallery->getId()]["videonew"])) ? (bool)$_SESSION["swfupload"][$gallery->getId()]["videonew"] : false;
                if ( $new ) {
                    $_SESSION['NEW_VIDEO_UPLOADED'] = 1;
                }
                if ( $new || (!$new && !isset($_SESSION['NEW_VIDEO_UPLOADED'])) ) {
                    
                    if ( FACEBOOK_USED ) {
                        $paramsFB = array(
                            'title' => htmlspecialchars($gallery->getTitle()), 
                            'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                        );                                                 
                        $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('videogalleryView/id').$gallery->getId()."/");
                        $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_VIDEO, $paramsFB);    
                        Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
                    }

                    /** Send notification to host **/
                    $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", $new ? "NEW" : "CHANGES", count($items) > 1 ? true : false, $items );
                    
//                    if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                        $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                        $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                        $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                        $mail->setSender($this->currentGroup);
//                        $mail->addRecipient($this->currentGroup->getHost());
//                        $mail->addParam('Group', $this->currentGroup);
//                        $mail->addParam('action', $new ? "NEW" : "CHANGES");
//                        $mail->addParam('section', "VIDEO");
//                        $mail->addParam('chObject', $gallery);
//                        $mail->addParam('User', $this->_page->_user);
//                        $mail->addParam('isPlural', count($items) > 1 ? true : false);
//                        $mail->addParam('items', $items);
//                        $mail->sendToPMB(true);
//                        $mail->send();
//                    }
                    /** --- **/
                }
                elseif ( !$new && isset($_SESSION['NEW_VIDEO_UPLOADED']) ) {
                    unset($_SESSION['NEW_VIDEO_UPLOADED']);
                }

                $_SESSION["swfupload"] = null;
                unset($_SESSION["swfupload"]);
            }
            $this->_redirect($this->currentGroup->getGroupPath('videogallerycreatetrackstatus/gallery').$gallery_id."/");
            //$this->_redirect($this->currentGroup->getGroupPath('videogalleryedit/gallery').$gallery_id."/");
        } else
            $this->_redirect($this->currentGroup->getGroupPath('videos'));
    }
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    switch ($step) {
        case 1:
            if (SINGLEVIDEOMODE) {
                $gallery = Warecorp_Video_Gallery_Factory::createByOwner($this->currentGroup);
                $gallery->setOwnerType("group");
                $gallery->setOwnerId($this->currentGroup->getId());
                $gallery->setCreatorId($this->_page->_user->getId());
                $gallery->setTitle('untitled');
                $gallery->setDescription("");
                $gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
                $gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
                $gallery->setSize(0);
                $gallery->setPrivate(0);
                $gallery->setIsCreated(0);
                $gallery->save();
                $this->_redirect($this->currentGroup->getGroupPath('videogallerycreate/step/2/gallery/'.$gallery->getId()));
            }
            $galleries = $this->currentGroup->getVideoGalleries()
                              ->setSharingMode(Warecorp_Video_Enum_SharingMode::OWN)
                              ->setWatchingMode(Warecorp_Video_Enum_WatchingMode::OWN)
                              ->returnAsAssoc()->getList();
            $this->view->galleries = $galleries;
            $this->view->bodyContent = 'groups/videogallery/create_step1.tpl';
            break;
        case 2:
            $gallery_id = isset($this->params['gallery']) ? floor($this->params['gallery']) : 0;
            /**
             * if $gallery = 0 - create new gallery
             */
            if ( $gallery_id != 0 ) {
                if ( !Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id) ) {
                    $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
                }
                $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
                if (!Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user)){
                    $this->_page->showAjaxAlert('Access Denied');
                	$this->_redirect($this->currentGroup->getGroupPath('videos'));
                }
                $form = new Warecorp_Form('uploadVideosForm', 'post', $this->currentGroup->getGroupPath('videogallerycreate/step/3').'gallery/'.$gallery->getId().'/');
            } else {
                $gallery = Warecorp_Video_Gallery_Factory::createByOwner($this->currentGroup);
                $gallery->setOwnerType("group");
                $gallery->setOwnerId($this->currentGroup->getId());
                $gallery->setCreatorId($this->_page->_user->getId());
                $gallery->setTitle('untitled');
                $gallery->setDescription("");
                $gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
                $gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
                $gallery->setSize(0);
                $gallery->setPrivate(0);
                $gallery->setIsCreated(0);
                $gallery->save();
                $form = new Warecorp_Form('uploadVideosForm', 'post', $this->currentGroup->getGroupPath('videogallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                $this->view->new = true;
            }

            $this->view->gallery = $gallery;
            $this->view->form = $form;
            $this->view->versionSwitcher = 0;
            $this->view->SWFUploadID = session_id();
            $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'create_step2.tpl';
            break;
        case 4:
            $gallery = Warecorp_Video_Gallery_Factory::loadById($_REQUEST['galleryId']);
            if (isset($_FILES['Filedata']) && $_FILES['Filedata']["error"] == 0) {
                if (filesize($_FILES["Filedata"]["tmp_name"]) > VIDEOS_SIZE_LIMIT) {
                    exit('videos upload failed');
                }
                $data = Warecorp_File_Item::isVideo($_FILES["Filedata"]["name"], $_FILES["Filedata"]["tmp_name"]);
                if ($data === false) exit;
                $new_video = Warecorp_Video_Factory::createByOwner($this->currentGroup);
                $new_video->setGalleryId($_REQUEST['galleryId']);
                $new_video->setCreatorId($this->_page->_user->getId());
                $new_video->setCreateDate(new Zend_Db_Expr('NOW()'));
                $new_video->setFilename($_FILES["Filedata"]["name"]);
                $new_video->setSize(filesize($_FILES["Filedata"]["tmp_name"]));
                $new_video->setTitle($_FILES['Filedata']["name"]);
                $new_video->setFile($_FILES['Filedata']);
                $new_video->save();
                $_SESSION["swfupload"][$gallery->getId()]["items"][] = $new_video->getTitle();
                if (SINGLEVIDEOMODE) {
                    $gallery = Warecorp_Video_Gallery_Factory::loadById(floor($_REQUEST['galleryId']));
                    if ($gallery->getId() === null) exit('gallery title not set');
                    $gallery->setTitle($_FILES['Filedata']["name"]);
                    $gallery->save();
                }
/*                $r0 = Warecorp_Video_FFMpeg::makeConversion($_FILES['Filedata']["tmp_name"], $new_video->getPath() . "_orig.flv");
                $new_video->setLength($r0['length']);
                $new_video->save();*/
                //create thumbnail
                //$r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES['Filedata']["tmp_name"], $new_photo->getPath() . "_orig.jpg", $data[0], $data[1], true);
            }
//            fclose($handle);

            break;
        case 3:

            $items = array();
            $gallery_id = isset($this->params['gallery_id']) ? floor($this->params['gallery_id']) : 0;

            if ( ($gallery_id != 0 && !Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id))) {
                $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
            }

            if ($gallery_id == 0) {
                if (isset($this->params['gallery'])) {
                    $gallery = Warecorp_Video_Gallery_Factory::loadById(floor($this->params['gallery']));
                    if (isset($this->params['new'])) {
                        $this->view->new = true;
                        $form = new Warecorp_Form('uploadVideosForm', 'post', $this->currentGroup->getGroupPath('videogallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                    } else {
                        $form = new Warecorp_Form('uploadVideosForm', 'post', $this->currentGroup->getGroupPath('videogallerycreate/step/3').'gallery/'.$gallery->getId().'/');
                    }
                    $form->addCustomErrorMessage(Warecorp::t("Upload files failed. Each file's size must be less then 2Mb"));
                    $this->view->form = $form;
                    $this->view->versionSwitcher = isset($this->params['versionSwitcher'])?$this->params['versionSwitcher']:0;
                    $this->view->gallery = $gallery;
                    $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'create_step2.tpl';
                    break;
                } else $this->_redirect($this->currentGroup->getGroupPath('videos'));
            }
            $valid = false;
            $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
            $form = new Warecorp_Form('uploadVideosForm', 'post', $this->currentGroup->getGroupPath('videogallerycreate/step/3').'gallery/'.$gallery->getId().'/');
            if (isset($this->params['new'])) {
                $form = new Warecorp_Form('uploadVideosForm', 'post', $this->currentGroup->getGroupPath('videogallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                $form->addRule('gallery_title', 'required', Warecorp::t('Enter please gallery title'));
                if (!$form->validate($this->params)) {
                    $this->view->new = true;
                    $this->view->form = $form;
                    $this->view->versionSwitcher = isset($this->params['versionSwitcher'])?$this->params['versionSwitcher']:0;
                    $this->view->gallery = $gallery;
                    $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'create_step2.tpl';
                    break;
                }
            }
            $count = 0;

            if ($this->params['versionSwitcher'] == '1') {
                $source = empty($this->params['source'])?'':$this->params['source'];
                $customSrc = empty($this->params['customSrc'])?'':$this->params['customSrc'];
                $customSrcImg = empty($this->params['customSrcImg'])?'':$this->params['customSrcImg'];
                $errors = Warecorp_Video_Abstract::getEmbedData(&$source, &$customSrc, &$customSrcImg);
                if (!empty($errors)) $form->addCustomErrorMessage($errors);
                    elseif (!empty($customSrc)) {
                        $new_video = Warecorp_Video_Factory::createByOwner($this->currentGroup);
                        $new_video->setGalleryId($gallery_id);
                        $new_video->setCreatorId($this->_page->_user->getId());
                        $new_video->setCreateDate(new Zend_Db_Expr('NOW()'));
                        $new_video->setFilename($customSrc);
                        $new_video->setTitle($customSrc);
                        $new_video->setCustomSrc($customSrc);
                        $new_video->setSource($source);
                        if (!empty($customSrcImg)) $new_video->setCustomSrcImg($customSrcImg);
                        $new_video->save();
                        $valid = true;
                        $gallName = $customSrc;
                    }
            } else {
                $_max_size = VIDEOS_SIZE_LIMIT;
                $_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
                for( $i = 1; $i <= 20; $i++ ){
                    if (!empty($_FILES["img_$i"]['name']) && $_FILES["img_$i"]["error"] == 0 ){

                        if (filesize($_FILES["img_$i"]["tmp_name"]) > VIDEOS_SIZE_LIMIT) {
                            $form->addCustomErrorMessage(Warecorp::t("File %s is too big.  Max filesize is ",array($_FILES["img_$i"]["name"]).$_max_size));
                            continue;
                        }
                        $ext = strtolower(substr($_FILES["img_$i"]['name'],1 + strrpos($_FILES["img_$i"]['name'], ".")));
                        if (!Warecorp_File_Item::isVideo($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"])) {
                            $form->addCustomErrorMessage(Warecorp::t("File %s is not video",array($_FILES["img_$i"]["name"])));
                            continue;
                        }
                        $data = Warecorp_File_Item::isVideo($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"]);
                        if ($data === false) continue;

                        $new_video = Warecorp_Video_Factory::createByOwner($this->currentGroup);
                        $new_video->setGalleryId($gallery_id);
                        $new_video->setCreatorId($this->_page->_user->getId());
                        $new_video->setCreateDate(new Zend_Db_Expr('NOW()'));
                        $new_video->setFilename($_FILES["img_$i"]["name"]);
                        $new_video->setSize(filesize($_FILES["img_$i"]["tmp_name"]));
                        $new_video->setTitle($_FILES["img_$i"]["name"]);
                        $new_video->setFile($_FILES["img_$i"]);
                        $new_video->save();
                        $items[] = $new_video->getTitle();
                        $gallName = $_FILES["img_$i"]["name"];
                        $valid = true;
                    } else {
                        if (!empty($_FILES["img_$i"]['name'])) {
                            switch ($_FILES["img_$i"]['error']) {
                                case UPLOAD_ERR_INI_SIZE:
                                    $form->addCustomErrorMessage(Warecorp::t("File %s is too big. Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size)));
                                case UPLOAD_ERR_NO_FILE:
                                    $form->addCustomErrorMessage(Warecorp::t("Please select correct file for upload."));
                                    break;
                                default:
                                    $form->addCustomErrorMessage(Warecorp::t("Upload failed"));
                                    break;
                            }
                        } else {$count++;}
                    }
                }
                if ($count == 20) {
                    $form->addCustomErrorMessage(Warecorp::t("Please select files to upload"));
                }
            }
            if ($valid === false) {
                $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
                $this->view->form = $form;
                if (isset($this->params['new'])) {
                    $this->view->new = true;
                }
                $this->view->galleryTitle = $this->params['gallery_title'];
                $this->view->versionSwitcher = isset($this->params['versionSwitcher'])?$this->params['versionSwitcher']:0;
                $this->view->gallery = $gallery;
                $this->view->bodyContent = 'groups/videogallery/'.VIDEOMODEFOLDER.'create_step2.tpl';
                break;
            } else {
                if (SINGLEVIDEOMODE) {
                    if (!empty($gallName))
                        $gallery->setTitle($gallName);
                }else{
                    $gallery->setTitle($this->params['gallery_title']);
                }
                $new = !$gallery->getIsCreated();
                $gallery->setIsCreated(1);
                $gallery->save();
                if ( FACEBOOK_USED ) {
                    $paramsFB = array(
                        'title' => htmlspecialchars($gallery->getTitle()), 
                        'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                    );                                                 
                    $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('videogalleryView/id').$gallery->getId()."/");
                    $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_VIDEO, $paramsFB);    
                    Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
                }
                if ( $new ) {
                    $_SESSION['NEW_VIDEO_UPLOADED'] = 1;
                }
                if ( $new || ( !$new && !isset($_SESSION['NEW_VIDEO_UPLOADED']) ) ) {
                    /** Send notification to host **/
                    $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", $new ? "NEW" : "CHANGES", count($items) > 1 ? true : false, $items );
                    
//                    if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                        $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                        $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                        $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                        $mail->setSender($this->currentGroup);
//                        $mail->addRecipient($this->currentGroup->getHost());
//                        $mail->addParam('Group', $this->currentGroup);
//                        $mail->addParam('action', $new ? "NEW" : "CHANGES");
//                        $mail->addParam('section', "VIDEO");
//                        $mail->addParam('chObject', $gallery);
//                        $mail->addParam('User', $this->_page->_user);
//                        $mail->addParam('isPlural', count($items) > 1 ? true : false);
//                        $mail->addParam('items', $items);
//                        $mail->sendToPMB(true);
//                        $mail->send();
//                    }
                    /** --- **/
                }
                elseif ( !$new && isset($_SESSION['NEW_VIDEO_UPLOADED']) ) {
                    unset($_SESSION['NEW_VIDEO_UPLOADED']);
                }

                if ($this->params['versionSwitcher'] == '1') {
                    $this->_redirect($this->currentGroup->getGroupPath('videogalleryedit/gallery').$gallery_id."/");
                }
                $this->_redirect($this->currentGroup->getGroupPath('videogallerycreatetrackstatus/gallery').$gallery_id."/");
                //$this->_redirect($this->currentGroup->getGroupPath('videogalleryedit/gallery').$gallery_id."/");
            }
            break;
    }
} else {
    $this->_redirect($this->currentGroup->getGroupPath('summary'));
}
