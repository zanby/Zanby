<?php
    Warecorp::addTranslation("/modules/users/avatar/action.avatarUpload.php.xml");
if (isset($this->params['swf']) && ($this->params['swf'] == 1)) {            
    $user = new Warecorp_User('id', floor($_REQUEST['user']));
    if ($user->getId() === null) exit(Warecorp::t('Profile photo not uploaded'));
    $avatarListObj = new Warecorp_User_Avatar_List($user->getId());
    $avatarsCount = $avatarListObj->getCount();
    if ($avatarsCount >= 12) exit(Warecorp::t('Profile photo not uploaded'));
    if (isset($_FILES['Filedata']) && $_FILES['Filedata']["error"] == 0) {
        if (filesize($_FILES["Filedata"]["tmp_name"]) > AVATARS_SIZE_LIMIT) {
            exit(Warecorp::t('Profile photo uploaded'));
        }        
        $data = Warecorp_File_Item::isImage($_FILES['Filedata']["name"], $_FILES['Filedata']["tmp_name"]); 
        if ($data === false) exit(Warecorp::t('Profile photo not uploaded'));
        $new_avatar = new Warecorp_User_Avatar();
        $new_avatar->setUserId($user->getId());
        $new_avatar->setByDefault(0);
        $new_avatar->save();           
        $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES['Filedata']["tmp_name"], UPLOAD_BASE_PATH."/upload/user_avatars/".md5($user->getId().$new_avatar->getId())."_orig.jpg", $data[0], $data[1], true);
    }   
    exit(Warecorp::t('Profile photo uploaded'));
}
if (isset($this->params['upload_type']) && ($this->params['upload_type'] == 'swfupload')) {
    $this->_page->showAjaxAlert(Warecorp::t('Profile photos uploaded'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    $this->_redirect($this->currentUser->getUserPath("avatars"));
}
    
$form = new Warecorp_Form('auForm', 'POST', $this->_page->_user->getUserPath('avatarupload/upload/1'));
$avatarListObj = new Warecorp_User_Avatar_List($this->_page->_user->getId());
$avatarsCount = $avatarListObj->getCount();
$count = 0;
$valid = false;
if (!empty($this->params['upload']) && empty($this->params['do'])) {
    $form->addCustomErrorMessage(Warecorp::t("Upload failed. Each file's size must be less than %sMb", 2));
}
$_max_size = AVATARS_SIZE_LIMIT;
$_max_size = is_int($_max_size) ? sprintf("%01.1f", $_max_size/1024/1024)."M" : $_max_size;
foreach($_FILES as $i => $FILE) {
   if ($avatarsCount < 12){
        if (!empty($FILE['name']) && $FILE["error"] == 0 ){
            if (filesize($FILE["tmp_name"]) > AVATARS_SIZE_LIMIT) {
                $form->addCustomErrorMessage(Warecorp::t("File %s is too big.  Max filesize is %s", array($FILE["name"], $_max_size)));
                continue;
            }

            $data = Warecorp_File_Item::isImage($FILE["name"], $FILE["tmp_name"]);
            if ($data === false) {
                $form->addCustomErrorMessage(Warecorp::t("%s file is not image", $FILE["name"]));
                continue;
            }

            $new_avatar = new Warecorp_User_Avatar();

            $new_avatar->setUserId($this->_page->_user->getId());
            $new_avatar->setByDefault(0);
            $new_avatar->save();

            //create thumbnail
            $r0 = Warecorp_Image_Thumbnail::makeThumbnail($FILE["tmp_name"], UPLOAD_BASE_PATH."/upload/user_avatars/".md5($this->_page->_user->getId().$new_avatar->getId())."_orig.jpg", $data[0], $data[1], true);
            //$r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/user_avatars/".md5($this->_page->_user->getId().$new_avatar->getId())."_orig.jpg", $data[0], $data[1], true);
            $valid = true;
            $avatarsCount++;
        } else {
            if (!empty($FILE['name'])) {
                switch ($FILE['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $form->addCustomErrorMessage(Warecorp::t("File %s is too big. Max filesize is %s", array($FILE["name"], $_max_size)));
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
   } else break;
}

if ($count == 20) {
    $form->addCustomErrorMessage(Warecorp::t("Please select files to upload"));
}
if ($valid === false) {
    $this->view->form = $form;
    $this->view->errors = array(Warecorp::t('Please select files to upload')); 
    $this->view->SWFUploadID = session_id();
    $this->view->user = $this->_page->_user;
    $this->view->avatarsLeft = 12-$avatarListObj->getCount();
    $this->view->bodyContent = 'users/avatar/avatar_upload.tpl';
} else {
    $this->_page->showAjaxAlert(Warecorp::t('Profile photos uploaded'));
    $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    $this->_redirect($this->currentUser->getUserPath("avatars"));
}
