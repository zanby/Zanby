<?php
Warecorp::addTranslation('/modules/groups/promotion/action.brandgalleryuploadsave.php.xml');

if (isset($this->params['swf']) && ($this->params['swf'] == 1)) {
    if (Warecorp_Group_Standard::isGroupExists('id', floor($_REQUEST['group']))) {
        $group = Warecorp_Group_Factory::loadById(floor($_REQUEST['group']));    
        if (isset($_FILES['Filedata']) && $_FILES['Filedata']["error"] == 0) {
            if (filesize($_FILES["Filedata"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
                exit('brand uploaded');                       
            }          
            $data = Warecorp_File_Item::isImage($_FILES["Filedata"]["name"], $_FILES["Filedata"]["tmp_name"]);
            if ($data !== false){
                $new_photo = new Warecorp_Group_BrandPhoto_Item();
                $new_photo->setGroupId($group->getId());
                $new_photo->setDescription("");
                $new_photo->save();
                $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["Filedata"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_brand/".md5($new_photo->getId())."_orig.jpg", $data[0], $data[1], true);                
            }
        }
    }
    exit('brand uploaded');    
}

$objResponse = new xajaxResponse ( ) ;
if (isset($this->params['upload_type']) && ($this->params['upload_type'] == 'swfupload')) {
    $objResponse->addRedirect($this->currendGroup('brandgallery'));
    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);  
    print $objResponse; exit;      
//    print "<script>parent.document.location = '/en/brandgallery/';</script>";exit;
}

if (!empty($this->params['upload']) && empty($this->params['upload_type'])) {
    $errors[] = Warecorp::t("Upload failed. Each file's size must be less than 2Mb");
}
$_max_size = IMAGES_SIZE_LIMIT;
$_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
if (!empty($_FILES["brand_image"]) && $_FILES["brand_image"]["error"] == 0){
    if (filesize($_FILES["brand_image"]["tmp_name"]) > IMAGES_SIZE_LIMIT) {
        $errors[] = Warecorp::t("File %s is too big.  Max filesize is ",$_FILES["brand_image"]['name']).$_max_size;        
    } else {    
        $data = Warecorp_File_Item::isImage($_FILES["brand_image"]["name"], $_FILES["brand_image"]["tmp_name"]);
        
        if ($data !== false){
            $new_photo = new Warecorp_Group_BrandPhoto_Item();
            $new_photo->setGroupId($this->currentGroup->getId());
            $new_photo->setDescription("");
            $new_photo->save();

            $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["brand_image"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_brand/".md5($new_photo->getId())."_orig.jpg", $data[0], $data[1], true);
            $objResponse->addRedirect($this->currendGroup('brandgallery'));
            $sContentHeader = "Content-type: text/xml;";
            if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
            if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
            header($sContentHeader);  
            print $objResponse; exit; 
    /*        print "<script>parent.document.location = '/en/brandgallery/';</script>";
            exit;*/
        } else {$errors[] = $_FILES["brand_image"]['name'].Warecorp::t(" file is not image");}
    }
} else {
    if (!empty($_FILES["brand_image"]["name"])) {
        switch ($_FILES["brand_image"]["error"]) {
            case UPLOAD_ERR_INI_SIZE:
                $errors[] = Warecorp::t("File %s is too big. Max filesize is ",$_FILES["brand_image"]['name']).$_max_size;
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
    } else {if (empty($errors)) $errors[] = Warecorp::t('Please select correct file to upload');}
}

if (empty($errors)) $errors[] = Warecorp::t('unknown error');
$this->view->errors = $errors;
$out = $this->view->getContents('_design/form/form_errors_summary.tpl');
$objResponse->addClear('error', 'innerHTML');
$objResponse->addAssign('error', 'innerHTML', $out);
$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);  
print $objResponse; exit;

/*print "Error. select another image";

exit;*/
