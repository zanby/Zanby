<?php
ob_start();
Warecorp::addTranslation("/modules/users/gallery/action.galleryCreate.php.xml");

$this->_page->Xajax->registerUriFunction("uploadandsubmit", "/users/galleryuploadandsubmit/");
$this->_page->Xajax->registerUriFunction("delete_gallery", "/users/galleryDeleteGallery/");

if ( !Warecorp_Photo_AccessManager_Factory::create()->canCreateGallery($this->currentUser, $this->_page->_user) ) {
    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
	$this->_redirect($this->currentUser->getUserPath('photos'));
}

$step = isset($this->params['step']) ? floor($this->params['step']) : 1;
if ($step >= 1 && $step <= 4){

    $capacity = $this->currentUser->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
	$percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
    if (isset($this->params['upload_type']) && $this->params['upload_type'] === "swfupload") {
        $gallery_id = isset($this->params['gallery_id']) ? floor($this->params['gallery_id']) : 0;
        if ($gallery_id != 0){
            if ( FACEBOOK_USED ) {
                $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
                $paramsFB = array(
                    'title' => htmlspecialchars($gallery->getTitle()), 
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );                                                 
                $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentUser->getUserPath('galleryView/id').$gallery_id."/");
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO, $paramsFB);    
                Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
            }
            $this->_redirect($this->currentUser->getUserPath('galleryedit/gallery').$gallery_id."/");
        }
        else
            $this->_redirect($this->currentUser->getUserPath('photos'));
    }
    if ($percent >= 100) {
        $percent = 100;
        $step = 5;
    }

    switch ($step) {
        case 5:
            $this->view->percent = $percent;
            $this->view->bodyContent = 'users/gallery/limiterror.tpl';
            break;
        case 1:
            $galleries = $this->currentUser->getGalleries()
                              ->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                              ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                              ->returnAsAssoc()->getList();
            $this->view->percent = $percent;
            $this->view->galleries = $galleries;
            $this->view->bodyContent = 'users/gallery/create_step1.tpl';
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
                if (!Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user)){
                    $this->_page->showAjaxAlert(Warecorp::t('Access Denied'));
                	$this->_redirect($this->currentUser->getUserPath('photos'));
                }
                $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('gallerycreate/step/3').'gallery/'.$gallery->getId().'/');
            } else {
                $gallery = Warecorp_Photo_Gallery_Factory::createByOwner($this->currentUser);
                $gallery->setOwnerType("user");
                $gallery->setOwnerId($this->currentUser->getId());
                $gallery->setCreatorId($this->_page->_user->getId());
                $gallery->setTitle(Warecorp::t('untitled'));
                $gallery->setDescription("");
                $gallery->setCreateDate(new Zend_Db_Expr('NOW()'));
                $gallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
                $gallery->setSize(0);
                $gallery->setPrivate(0);
                $gallery->setIsCreated(0);
                $gallery->save();
                $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('gallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                $this->view->new = true;
            }
            $this->view->gallery = $gallery;
            $this->view->form = $form;
            $this->view->percent = $percent;
            $this->view->SWFUploadID = session_id();
            $this->view->bodyContent = 'users/gallery/create_step2.tpl';
            break;
        case 4:
        	$gallery = Warecorp_Photo_Gallery_Factory::loadById($_REQUEST['galleryId']);
            if (isset($_FILES['Filedata']) && $_FILES['Filedata']["error"] == 0) {
                $capacity = $this->currentUser->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
                $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
                if ($percent >= 100) exit;
                //$handle = fopen(DOC_ROOT.'/upload/upload.log', 'w');
                //fwrite($handle, "");
                //fclose($handle);
                if (filesize($_FILES["Filedata"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
                    exit('photos upload failed');
                }
                $data = Warecorp_File_Item::isImage($_FILES["Filedata"]["name"], $_FILES["Filedata"]["tmp_name"]);
                if ($data === false) {
                    header("HTTP/1.1 200 OK");
                    print "ERROR: File '".$_FILES['Filedata']['name']."' is invalid.";
                    exit;
                }
                $new_photo = Warecorp_Photo_Factory::createByOwner($this->currentUser);
                $new_photo->setGalleryId($_REQUEST['galleryId']);
                $new_photo->setCreatorId($this->_page->_user->getId());
                $new_photo->setCreateDate(new Zend_Db_Expr('NOW()'));
                $new_photo->setTitle($_FILES['Filedata']["name"]);
                $new_photo->save();
                //create thumbnail
                $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES['Filedata']["tmp_name"], $new_photo->getPath() . "_orig.jpg", $data[0], $data[1], true);
            }

        	break;
        case 3:

            $gallery_id = isset($this->params['gallery_id']) ? floor($this->params['gallery_id']) : 0;

            if ( ($gallery_id != 0 && !Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id))) {
                $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
            }
            if ($gallery_id == 0) {
                if (isset($this->params['gallery'])) {
                    $gallery = Warecorp_Photo_Gallery_Factory::loadById(floor($this->params['gallery']));
                    if (isset($this->params['new'])) {
                        $this->view->new = true;
                        $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('gallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                    } else {
                        $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('gallerycreate/step/3').'gallery/'.$gallery->getId().'/');
                    }
                    $form->addCustomErrorMessage(Warecorp::t('Upload files failed. Each file\'s size must be less then %sMb', 2));
                    $this->view->form = $form;
                    $this->view->gallery = $gallery;
                    $this->view->percent = $percent;
                    $this->view->bodyContent = 'users/gallery/create_step2.tpl';
                    break;
                } else $this->_redirect($this->currentUser->getUserPath('photos'));
            }
            $valid = false;
            $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
            $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('gallerycreate/step/3').'gallery/'.$gallery->getId().'/');
            if (isset($this->params['new'])) {
                $form = new Warecorp_Form('uploadPhotosForm', 'post', $this->currentUser->getUserPath('gallerycreate/step/3/new/1').'gallery/'.$gallery->getId().'/');
                $form->addRule('gallery_title', 'required', Warecorp::t('Enter please gallery title'));
                if (!$form->validate($this->params)) {
                    $this->view->new = true;
                    $this->view->form = $form;
                    $this->view->gallery = $gallery;
                    $this->view->percent = $percent;
                    $this->view->bodyContent = 'users/gallery/create_step2.tpl';
                    break;
                }
            }
            $count = 0;
            $_max_size = IMAGES_SIZE_LIMIT;
            $_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
	        for( $i = 1; $i <= 20; $i++ ){
	            if (!empty($_FILES["img_$i"]['name']) && $_FILES["img_$i"]["error"] == 0 ){
                    $ext = strtolower(substr($_FILES["img_$i"]['name'],1 + strrpos($_FILES["img_$i"]['name'], ".")));
                    if (filesize($_FILES["img_$i"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
                        $form->addCustomErrorMessage(Warecorp::t("File %s is too big.  Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size)));
                        continue;
                    }
                    $data = Warecorp_File_Item::isImage($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"]);
                    if (!$data) {
                        $form->addCustomErrorMessage("File ".$_FILES["img_$i"]["name"]." is not image");
                        continue;
                    }
	                if ($data === false) continue;

	                $new_photo = Warecorp_Photo_Factory::createByOwner($this->currentUser);
	                $new_photo->setGalleryId($gallery_id);
	                $new_photo->setCreatorId($this->_page->_user->getId());
	                $new_photo->setCreateDate(new Zend_Db_Expr('NOW()'));
	                $new_photo->setTitle($_FILES["img_$i"]["name"]);
	                $new_photo->save();
	                $valid = true;
	                $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], $new_photo->getPath()."_orig.jpg", $data[0], $data[1], true);
                    $capacity = $this->currentUser->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
                    $percent1 = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
                    if ($percent1 >= 100) {
                        break;
                    }
	            } else {
                    if (!empty($_FILES["img_$i"]['name'])) {
                        switch ($_FILES["img_$i"]['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                $form->addCustomErrorMessage(Warecorp::t("File %s is too big. Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size)));
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
                $this->view->bodyContent = 'users/gallery/create_step2.tpl';
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
                    $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentUser->getUserPath('galleryView/id').$gallery_id."/");
                    $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_PHOTO, $paramsFB);    
                    Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);             
                }
	            $this->_redirect($this->currentUser->getUserPath('galleryedit/gallery').$gallery_id."/");
            }
            break;
    }
} else {
    $this->_redirect($this->currentUser->getUserPath('profile'));
}
