<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupAvatar/action.uploadAvatarOK.php.xml');

$objResponse = new xajaxResponse();
$avatarListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
$avatars = $avatarListObj->getList();
$avatarsCount = $avatarListObj->getCount();

if (isset($this->params['upload_type']) && ($this->params['upload_type'] == 'swfupload')) { 

	$cloneId = $this->params['cloneId'];
    $objResponse->addScript("xajax_load_avatars( false, '$cloneId' );");
    
    //$objResponse->addScript('xajax_load_avatars();');
    $sContentHeader = "Content-type: text/xml;";
    if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
    if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
    header($sContentHeader);  
    print $objResponse; exit;     
}

$count = 0;
$valid = false;
if (!empty($this->params['upload']) && empty($this->params['upload_type'])) {
    $errors[] = Warecorp::t("Upload failed. Each file's size must be less than 2Mb");
}
$_max_size = AVATARS_SIZE_LIMIT;
$_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
for($i = 1; $i<=20; $i++){
    if ($avatarsCount < 12){

        if ( !empty($_FILES['edit']['name']["img_$i"]) && $_FILES['edit']["error"]["img_$i"] == 0){
            if (filesize($_FILES['edit']["tmp_name"]["img_$i"]) > AVATARS_SIZE_LIMIT) {
                $errors[] = Warecorp::t("File ")
                            .$_FILES['edit']['name']["img_$i"].Warecorp::t(" is too big.  Max filesize is ")
                            .$_max_size;
                continue;                        
            }            
            
            $data = Warecorp_File_Item::isImage($_FILES['edit']["name"]["img_$i"], $_FILES['edit']["tmp_name"]["img_$i"]);
            if ($data === false) {
                $errors[] = $_FILES['edit']['name']["img_$i"].Warecorp::t(" file is not image");
                continue;                
            }

            $new_avatar = new Warecorp_Group_Avatar();
            
            $new_avatar->setGroupId($this->currentGroup->getId());
            $new_avatar->setByDefault(0);
            $new_avatar->save();

            //create thumbnail
            $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES['edit']["tmp_name"]["img_$i"], UPLOAD_BASE_PATH."/upload/group_avatars/".md5($this->currentGroup->getId().$new_avatar->getId())."_orig.jpg", $data[0], $data[1], true);
            $valid = true;
            $avatarsCount++;
        } else {
            if (!empty($_FILES['edit']["name"]["img_$i"])) {
                switch ($_FILES['edit']["error"]["img_$i"]) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errors[] = Warecorp::t("File ")
                            .$_FILES['edit']['name']["img_$i"].Warecorp::t(" is too big. Max filesize is ").$_max_size;
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

    }else break;

}

if ($valid === false) {
    
    if (empty($errors)) {
        $errors[] = Warecorp::t("Please select correct files to upload");
    }
    $this->view->errors = $errors;
    $out = $this->view->getContents('_design/form/form_errors_summary.tpl');
    $objResponse->addClear('error', 'innerHTML');
    $objResponse->addAssign('error', 'innerHTML', $out);         
} else { 
    if (empty($avatars)){
        $refresh = true;
        $cloneId = $this->params['cloneId'];
        $objResponse->addScript('xajax_load_avatars('.$refresh.','.$cloneId.');');
    } else {
        $objResponse->addScript('xajax_load_avatars();');
    }
} 
$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);  
print $objResponse; exit;
