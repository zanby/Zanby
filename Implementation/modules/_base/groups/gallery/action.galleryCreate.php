<?php
Warecorp::addTranslation('/modules/groups/gallery/action.galleryCreate.php.xml');

$this->_page->Xajax->registerUriFunction("uploadandsubmit", "/groups/galleryuploadandsubmit/");
$this->_page->Xajax->registerUriFunction("delete_gallery", "/groups/galleryDeleteGallery/");
if ( !Warecorp_Photo_AccessManager_Factory::create()->canCreateGallery($this->currentGroup, $this->_page->_user) ) {
	$this->_page->showAjaxAlert('Access Denied');
	$this->_redirect($this->currentGroup->getGroupPath('photos'));
}

$step = isset($this->params['step']) ? floor($this->params['step']) : 1;
if ($step >= 1 && $step <= 4){
	$capacity = $this->currentGroup->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
	$percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
    if (isset($this->params['upload_type']) && $this->params['upload_type'] === "swfupload") {
        $gallery_id = isset($this->params['gallery_id']) ? floor($this->params['gallery_id']) : 0;
        if ($gallery_id != 0) {
            if (isset($_SESSION["swfupload"]) && $_SESSION["swfupload"][$gallery_id+0]) {
                $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
                $photos = (isset($_SESSION["swfupload"][$gallery->getId()]["photos"])) ? $_SESSION["swfupload"][$gallery->getId()]["photos"] : null;
                $new = (isset($_SESSION["swfupload"][$gallery->getId()]["new"])) ? (bool)$_SESSION["swfupload"][$gallery->getId()]["new"] : false;

                if ( FACEBOOK_USED ) {
                    $paramsFB = array(
                        'title' => htmlspecialchars($gallery->getTitle()), 
                        'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                    );                                                 
                    $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('galleryView/id').$gallery->getId()."/");
                    $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO, $paramsFB);    
                    Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
                }
                /** Send notification to host **/
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", $new ? "NEW" : "CHANGES", count($photos) > 1 ? true : false, $photos );
                
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', $new ? "NEW" : "CHANGES");
//                    $mail->addParam('section', "PHOTO");
//                    $mail->addParam('chObject', $gallery);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', count($photos) > 1 ? true : false);
//                    $mail->addParam('items', $photos);
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/

                $_SESSION["swfupload"] = null;
                unset($_SESSION["swfupload"]);
            }
			$this->_redirect($this->currentGroup->getGroupPath('galleryedit/gallery').$gallery_id."/");
        } else $this->_redirect($this->currentGroup->getGroupPath('photos'));
    }

    if ($percent >= 100) {
        $percent = 100;
        $step = 5;
    }
	switch ($step) {
        case 5:
            $this->view->percent = $percent;
            $this->view->bodyContent = 'groups/gallery/limiterror.tpl';
            break;
		case 1:
			$galleries = $this->currentGroup->getGalleries()
			->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
			->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
			->returnAsAssoc()->getList();
			$this->view->percent = $percent;
			$this->view->galleries = $galleries;
			$this->view->bodyContent = 'groups/gallery/create_step1.tpl';
			break;
		case 2:
			$gallery_id = isset($this->params['gallery']) ? floor($this->params['gallery']) : 0;
			/**
             * if $gallery = 0 - create new gallery
             */
			if ( $gallery_id != 0 ) {
				if ( !Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id) ) {
					$this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
				}
				$gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
                if (!Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user)){
                    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
                	$this->_redirect($this->currentGroup->getGroupPath('photos'));
                }
				$form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('gallerycreate/step/3').'gallery/'.$gallery->getId().'/');
			} else {
                $gallery = Warecorp_Photo_Gallery_Factory::createByOwner($this->currentGroup);
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
                $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('gallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                $this->view->new = true;

			}

            $this->view->gallery = $gallery;
            $this->view->SWFUploadID = session_id();
			$this->view->form = $form;
			$this->view->percent = $percent;
			$this->view->bodyContent = 'groups/gallery/create_step2.tpl';
			break;
        case 4:
            $gallery = Warecorp_Photo_Gallery_Factory::loadById($_REQUEST['galleryId']);
            if (isset($_FILES['Filedata']) && $_FILES['Filedata']["error"] == 0) {
                $capacity = $this->currentGroup->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
                $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
                if ($percent >= 100) exit;

                if (filesize($_FILES["Filedata"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
                    exit(Warecorp::t('photos upload failed'));
                }
                $data = Warecorp_File_Item::isImage($_FILES["Filedata"]["name"], $_FILES["Filedata"]["tmp_name"]);
                if ($data === false) {
                    header("HTTP/1.1 200 OK");
                    print "ERROR: File '".$_FILES['Filedata']['name']."' is invalid.";
                    exit;
                }
                $new_photo = Warecorp_Photo_Factory::createByOwner($this->currentGroup);
                $new_photo->setGalleryId($_REQUEST['galleryId']);
                $new_photo->setCreatorId($this->_page->_user->getId());
                $new_photo->setCreateDate(new Zend_Db_Expr('NOW()'));
                $new_photo->setTitle($_FILES['Filedata']["name"]);
                $new_photo->save();

                $_SESSION["swfupload"][$gallery->getId()]["photos"][] = $new_photo->getTitle();

                //create thumbnail
                $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES['Filedata']["tmp_name"], $new_photo->getPath() . "_orig.jpg", $data[0], $data[1], true);
            }
            break;
		case 3:

            $photos = array();
			$gallery_id = isset($this->params['gallery_id']) ? (int)floor($this->params['gallery_id']) : 0;

			if ( ($gallery_id != 0 && !Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id))) {
				$this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
			}
			
            if ($gallery_id == 0) {
                if (isset($this->params['gallery'])) {
                    $gallery = Warecorp_Photo_Gallery_Factory::loadById(floor($this->params['gallery']));
                    if (isset($this->params['new'])) {
                        $this->view->new = true;
                        $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('gallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                    } else {
                        $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('gallerycreate/step/3').'gallery/'.$gallery->getId().'/');
                    }
                    $form->addCustomErrorMessage(Warecorp::t("Upload files failed. Each file's size must be less then 2Mb"));
                    $this->view->form = $form;
                    $this->view->gallery = $gallery;
                    $this->view->percent = $percent;
                    $this->view->bodyContent = 'groups/gallery/create_step2.tpl';
                    break;
                } else $this->_redirect($this->currentGroup->getGroupPath('photos'));
            }
            $valid = false;
            $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
            $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('gallerycreate/step/3').'gallery/'.$gallery->getId().'/');
            if (isset($this->params['new'])) {
                $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentGroup->getGroupPath('gallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                $form->addRule('gallery_title', 'required', Warecorp::t('Enter please gallery title'));
                if (!$form->validate($this->params)) {
                    $this->view->new = true;
                    $this->view->form = $form;
                    $this->view->gallery = $gallery;
                    $this->view->percent = $percent;
                    $this->view->bodyContent = 'groups/gallery/create_step2.tpl';
                    break;
                }
            }
            $count = 0;
            $_max_size = IMAGES_SIZE_LIMIT;
            $_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
            for( $i = 1; $i <= 20; $i++ ){
                if (!empty($_FILES["img_$i"]['name']) && $_FILES["img_$i"]["error"] == 0 ){
                    $ext = strtolower(substr($_FILES["img_$i"]['name'],1 + strrpos($_FILES["img_$i"]['name'], ".")));
                    $data = Warecorp_File_Item::isImage($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"]);

                    if (filesize($_FILES["img_$i"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
                        $form->addCustomErrorMessage(Warecorp::t("File ").$_FILES["img_$i"]["name"]
                                    .Warecorp::t(" is too big.  Max filesize is ").$_max_size);
                        continue;
                    }

                    if (!$data) {
                        $form->addCustomErrorMessage(Warecorp::t("File ").$_FILES["img_$i"]["name"]
                                    .Warecorp::t(" is not image"));
                        continue;
                    }

                    $new_photo = Warecorp_Photo_Factory::createByOwner($this->currentGroup);
                    $new_photo->setGalleryId($gallery_id);
                    $new_photo->setCreatorId($this->_page->_user->getId());
                    $new_photo->setCreateDate(new Zend_Db_Expr('NOW()'));
                    $new_photo->setTitle($_FILES["img_$i"]["name"]);
                    $new_photo->save();
                    $photos[] = $new_photo->getTitle();
                    $valid = true;
                    $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], $new_photo->getPath()."_orig.jpg", $data[0], $data[1], true);
                    $capacity = $this->currentGroup->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
                    $percent1 = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
                    if ($percent1 >= 100) {
                        break;
                    }
                } else {
                    if (!empty($_FILES["img_$i"]['name'])) {
                        switch ($_FILES["img_$i"]['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                $form->addCustomErrorMessage(Warecorp::t("File ").$_FILES["img_$i"]["name"]
                                            .Warecorp::t(" is too big. Max filesize is ").$_max_size);
                            case UPLOAD_ERR_FORM_SIZE:
                                //$form->addCustomErrorMessage("File ".$_FILES["img_$i"]["name"]." is too big. Max filesize is ".$_max_size);
                                break;
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
            if ($valid === false) {
                $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
                $this->view->form = $form;
                if (isset($this->params['new'])) {
                    $this->view->new = true;
                }
                $this->view->galleryTitle = $this->params['gallery_title'];
                $this->view->gallery = $gallery;
                $this->view->percent = $percent;
                $this->view->bodyContent = 'groups/gallery/create_step2.tpl';
                break;
            } else {
                $gallery->setTitle($this->params['gallery_title']);
                $gallery->setIsCreated(1);
                $gallery->save();

                if ( FACEBOOK_USED ) {
                    $paramsFB = array(
                        'title' => htmlspecialchars($gallery->getTitle()), 
                        'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                    );                                                 
                    $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('galleryView/id').$gallery_id."/");
                    $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO, $paramsFB);    
                    Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
                }
                /** Send notification to host **/ 
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", isset($this->params['new']) ? "NEW" : "CHANGES", count($photos) > 1 ? true : false, $photos );
                                              
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', isset($this->params['new']) ? "NEW" : "CHANGES");
//                    $mail->addParam('section', "PHOTO");
//                    $mail->addParam('chObject', $gallery);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', count($photos) > 1 ? true : false);
//                    $mail->addParam('items', $photos);
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/

                $this->_redirect($this->currentGroup->getGroupPath('galleryedit/gallery').$gallery_id."/");
            }
			break;
	}
} else {
	$this->_redirect($this->currentGroup->getGroupPath('summary'));
}
