<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddFamilyIcons/action.uploadBGIOK.php.xml');

$avatarListObj = new Warecorp_Group_BrandPhoto_List($this->currentGroup->getId());
$avatars = $avatarListObj->getList();


        if ( isset($_FILES['edit']['name']["img_1"]) && $_FILES['edit']["error"]["img_1"] == 0){
            if (filesize($_FILES['edit']["tmp_name"]["img_1"]) > IMAGES_SIZE_LIMIT) {                
                continue;                        
            }            
            $data = Warecorp_File_Item::isImage($_FILES['edit']["name"]["img_1"], $_FILES['edit']["tmp_name"]["img_1"]);
            if ($data === false) continue;

            $new_photo = new Warecorp_Group_BrandPhoto_Item();
            $new_photo->setGroupId($this->currentGroup->getId());
            $new_photo->setDescription("");
            $new_photo->save();

            //create thumbnail
            $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES['edit']["tmp_name"]["img_1"], UPLOAD_BASE_PATH."/upload/gallery_brand/".md5($new_photo->getId())."_orig.jpg", $data[0], $data[1], true);
            
            if ($r0 != "ok") {
                //error
                $out = $this->view->getContents('content_objects/ddFamilyIcons/upload_avatar_error.tpl');
                print $out; exit;
            }
        }


$this->view->error = 'Upload complete';
$this->view->close = 1;

 $this->view->cloneId = $this->params['cloneId'];
 
if (empty($avatars)){
    $this->view->refresh = true;
   
}

$out = $this->view->getContents('content_objects/ddFamilyIcons/upload_avatar_error.tpl');

print $out; exit;
