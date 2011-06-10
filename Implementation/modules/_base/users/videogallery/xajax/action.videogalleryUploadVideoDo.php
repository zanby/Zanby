<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryUploadVideoDo.php.xml");
$objResponse = new xajaxResponse();
$items_per_page = 10;

if ($this->params['versionSwitcher'] == '1') {
    $gallery = Warecorp_Video_Gallery_Factory::loadById($this->params['gallery_id']);
    $valid = false;
    $source = empty($this->params['source'])?'':$this->params['source'];
    $customSrc = empty($this->params['customSrc'])?'':$this->params['customSrc'];
    $customSrcImg = empty($this->params['customSrcImg'])?'':$this->params['customSrcImg'];
    $error = Warecorp_Video_Abstract::getEmbedData(&$source, &$customSrc, &$customSrcImg);
    if (empty($error) && !empty($customSrc)) {
        $new_video = Warecorp_Video_Factory::createByOwner($this->currentUser);
        $new_video->setGalleryId($gallery->getId());
        $new_video->setCreatorId($this->_page->_user->getId());
        $new_video->setCreateDate(new Zend_Db_Expr('NOW()'));
        $new_video->setFilename($customSrc);
        $new_video->setTitle($customSrc);
        $new_video->setCustomSrc($customSrc);
        $new_video->setSource($source);
        if (!empty($customSrcImg)) $new_video->setCustomSrcImg($customSrcImg);
        $new_video->save();
        $valid = true;
    }
    if (!$valid) {
        $this->view->errors = $error;
        $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
        $objResponse->addAssign('error', 'innerHTML', $errorcontent);
        $objResponse->addScript("document.getElementById('error').style.display = '';");
    } else {
        $videosListObj = $gallery->getVideos();
        $paging_url = '#null';
        $P = new Warecorp_Common_PagingProduct($videosListObj->getCount(), $items_per_page, $paging_url);
        $objResponse = $this->videoeditshowpageAction($P->totPages, $gallery->getId());
        $objResponse->addScript('popup_window.close();');
    }
    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);
    print $objResponse; exit;
}

if (isset($this->params['upload_type']) && $this->params['upload_type'] === "swfupload") {
    $gallery = Warecorp_Video_Gallery_Factory::loadById($this->params['gallery_id']);
    $videosListObj = $gallery->getVideos();
    $paging_url = '#null';
    $P = new Warecorp_Common_PagingProduct($videosListObj->getCount(), $items_per_page, $paging_url);
    $objResponse = $this->videoeditshowpageAction($P->totPages, $gallery->getId());

    if (USE_VIDEO_SUSPENDED_PROCESSING) {
        $content = $this->view->getContents('users/videogallery/xajax.upload.track.status.tpl');
        $objResponse->addAssign('uploadPanelContent', 'innerHTML', $content);
        $objResponse->addScript('sendStatusRequest()'); 
    } else {
        $objResponse->addScript('popup_window.close();');
    }

    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);
    print $objResponse; exit;
}
 
if (empty($this->params['gallery_id'])) {
    $errors = Warecorp::t('Upload files failed. Each file\'s size must be less then %sMb', 2);
    $this->view->errors = $errors;
    $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
    $objResponse->addAssign('error', 'innerHTML', $errorcontent);
    $objResponse->addScript("document.getElementById('error').style.display = '';");
    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);
    print $objResponse; exit;
}

$gallery = Warecorp_Video_Gallery_Factory::loadById($this->params['gallery_id']);


if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $valid = false;
    $count = 0;
    $_max_size = VIDEOS_SIZE_LIMIT;
    $_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
    for ($i = 1; $i <= 20; $i ++) {
        if (!empty($_FILES["img_$i"]['name']) && $_FILES["img_$i"]["error"] == 0 ){

            if (filesize($_FILES["img_$i"]["tmp_name"]) > VIDEOS_SIZE_LIMIT) {
                $errors[] = Warecorp::t("File %s is too big.  Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size));
                continue;
            }
            if (!Warecorp_File_Item::isVideo($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"])) {
                $errors[] = Warecorp::t("File %s is not video", $_FILES["img_$i"]["name"]);
                continue;
            }
            $new_video = Warecorp_Video_Factory::createByOwner($this->currentUser);
            $new_video->setGalleryId($gallery->getId());
            $new_video->setCreatorId($this->_page->_user->getId());
            $new_video->setCreateDate(new Zend_Db_Expr('NOW()'));
            $new_video->setFilename($_FILES["img_$i"]["name"]);
            $new_video->setSize(filesize($_FILES["img_$i"]["tmp_name"]));
            $new_video->setTitle($_FILES["img_$i"]["name"]);
            $new_video->setFile($_FILES["img_$i"]);
            $new_video->save();
            $valid = true;
        } else {
            if (!empty($_FILES["img_$i"]['name'])) {
                switch ($_FILES["img_$i"]['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errors[] = Warecorp::t("File %s is too big. Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size));
                    case UPLOAD_ERR_NO_FILE:
                        $errors[] = Warecorp::t("Please select correct file for upload.");
                        break;
                    default:
                        $errors[] = Warecorp::t("Upload failed");
                        break;
                }
            } else {$count++;}
        }
    }
    if ($count == 20) {
        $errors[] = Warecorp::t("Please select files to upload");
    }
    if ($valid === false) {
        $this->view->errors = $errors;
        $errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
        $objResponse->addAssign('error', 'innerHTML', $errorcontent);
        //$objResponse->addScript("document.getElementById('error').style.display = '';");
    } else {
        $videosListObj = $gallery->getVideos();
        $paging_url = '';
        $P = new Warecorp_Common_PagingProduct($videosListObj->getCount(), $items_per_page, $paging_url);
        $objResponse = $this->videoeditshowpageAction($P->totPages, $gallery->getId());

        if (USE_VIDEO_SUSPENDED_PROCESSING) {
            $content = $this->view->getContents('users/videogallery/xajax.upload.track.status.tpl');
            $objResponse->addAssign('uploadPanelContent', 'innerHTML', $content);
            $objResponse->addScript('sendStatusRequest()');
        } else {
            $objResponse->addScript('popup_window.close();');
            $objResponse->showAjaxAlert('Videos uploaded');
        }
    }
} else {
    $objResponse->addScript('popup_window.close();');
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}

$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;
