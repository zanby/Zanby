<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryUploadPhotoDo.php.xml");
$objResponse = new xajaxResponse();
$items_per_page = 10;
if (isset($this->params['upload_type']) && $this->params['upload_type'] === "swfupload") {
    $gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery_id']);
    $photosListObj = $gallery->getPhotos();
    $paging_url = '#null';
    $P = new Warecorp_Common_PagingProduct($photosListObj->getCount(), $items_per_page, $paging_url);
    $objResponse = $this->editshowpageAction($P->totPages, $gallery->getId());
    $objResponse->addScript('popup_window.close();');
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

$gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery_id']);


if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $valid = false;
    $count = 0;
    $_max_size = IMAGES_SIZE_LIMIT;
    $_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
    for ($i = 1; $i <= 20; $i ++) {
        if (!empty($_FILES["img_$i"]['name']) && $_FILES["img_$i"]["error"] == 0 ){
            if (filesize($_FILES["img_$i"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
                $errors[] = Warecorp::t("File %s is too big.  Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size));
                continue;
            }
            $data = Warecorp_File_Item::isImage($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"]); 
            if (!$data) {
                $errors[] = Warecorp::t("File %s is not image", $_FILES["img_$i"]["name"]);
                continue;
            }

            if ($data === false) continue;
            $new_photo = Warecorp_Photo_Factory::createByOwner($this->currentUser);
            $new_photo->setGalleryId($gallery->getId());
            $new_photo->setCreatorId($this->_page->_user->getId());
            $new_photo->setCreateDate(new Zend_Db_Expr('NOW()'));
            $new_photo->setTitle($_FILES["img_$i"]["name"]);
            $new_photo->save();
            $valid = true;
            $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], $new_photo->getPath() . "_orig.jpg", $data[0], $data[1], true);
            $capacity = $this->currentUser->getGalleries()->getTotalSize(Warecorp_Photo_Enum_SizeUnit::MBYTE);
            $percent = floor($capacity * 100 / TOTAL_PHOTOS_LIMIT);
            if ($percent >= 100) {
                break;
            }
        } else {
            if (!empty($_FILES["img_$i"]['name'])) {
                switch ($_FILES["img_$i"]['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errors[] = Warecorp::t("File %s is too big. Max filesize is %s", array($_FILES["img_$i"]["name"], $_max_size));
                    case UPLOAD_ERR_FORM_SIZE:
                        //$form->addCustomErrorMessage("File ".$_FILES["img_$i"]["name"]." is too big. Max filesize is ".$_max_size);
                        break;
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
        $photosListObj = $gallery->getPhotos();
        $paging_url = '';
        $P = new Warecorp_Common_PagingProduct($photosListObj->getCount(), $items_per_page, $paging_url);
        $objResponse = $this->editshowpageAction($P->totPages, $gallery->getId());
        $objResponse->addScript('popup_window.close();');
        $objResponse->showAjaxAlert(Warecorp::t('Photos uploaded'));
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
