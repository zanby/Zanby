<?php
    Warecorp::addTranslation("/modules/users/action.galleryCreate.php.xml");

$step = isset($this->params['step']) ? floor($this->params['step']) : 1;
//print_r($this->params);
if ($step >= 1 && $step <= 3){

    $capacity = $this->currentUser->getArtifacts()->getGalleriesSize("mb");
    $percent=floor($capacity/0.2);//percent from 20MB
    switch ($step) {
        case 1:
            $galleries = $this->currentUser->getArtifacts()->getGalleriesListAssoc();

            $this->view->percent = $percent;
            $this->view->galleries = $galleries;

            $this->view->bodyContent = 'users/gallery/create_step1.tpl';
            break;
        case 2:
            $gallery_id = isset($this->params['gallery']) ? (int)floor($this->params['gallery']) : 0;
            //if $gallery = 0 - create new gallery
            $galleries = $this->currentUser->getArtifacts()->getGalleriesListAssoc();
            if (! key_exists($gallery_id, $galleries) && $gallery_id != 0){
                $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
            }

            if ($gallery_id != 0){
                $gallery = new Warecorp_Photo_Gallery($gallery_id);
                $this->view->gallery = $gallery;
            }

            $this->view->percent = $percent;
            $this->view->bodyContent = 'users/gallery/create_step2.tpl';
            break;

        case 3:
            $gallery_id = isset($this->params['gallery']) ? (int)floor($this->params['gallery']) : 0;
            //if $gallery = 0 - create new gallery
            $galleries = $this->currentUser->getArtifacts()->getGalleriesListAssoc();
            if (! key_exists($gallery_id, $galleries) && $gallery_id != 0){
                $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
            }
            //check gallery title
            $gallery_title = isset($this->params['gallery_title']) ? $this->params['gallery_title'] : "";
            if ($gallery_id == 0 && !$gallery_title){
                $this->view->error = Warecorp::t("Input gallery title");
                $this->view->percent = $percent;
                $this->view->bodyContent = 'users/gallery/create_step2.tpl';
            } elseif($gallery_id == 0) {
                //save new gallery
                $new_gallery = new Warecorp_Photo_Gallery();
                $new_gallery->ownerType     = "user";
                $new_gallery->owner         = $this->currentUser->getId();
                $new_gallery->creator       = $this->currentUser->getId();
                $new_gallery->title         = $gallery_title;
                $new_gallery->description   = "";
                $new_gallery->createDate    = date("d.m.Y H:i:s");
                $new_gallery->isPrivate     = 0;
                $new_gallery->save();

                for($i = 1; $i<=20; $i++){
                    if ($_FILES["img_$i"]["error"] == 0){
                        $data = Warecorp_File_Item::isImage($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"]);
                        if ($data === false) continue;

                        $new_photo = new Warecorp_Photo_Item();
                        $new_photo->gallery     = $new_gallery->id;
                        $new_photo->creator     = $this->currentUser->getId();
                        $new_photo->createDate  = date("d.m.Y H:i:s");
                        $new_photo->title       = $_FILES["img_$i"]["name"];
                        $new_photo->save();

                        //create thumbnail
                        $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_orig.jpg", $data[0], $data[1], true);
                        $r1 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_small.jpg", 25, 25, true);
                        $r2 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_medium.jpg", 100, 100, true);
                        $r3 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_big.jpg", 350, 350, true);

                        /** TODO block under */
                        if ($r1 != "ok" || $r2 != "ok" || $r3 != "ok") {
                            print "lazha here"; exit;
                        }
                    }
                }
                $this->_redirect("/".$this->_page->Locale."/galleryedit/gallery/".$new_gallery->id."/");
            } else {
                //save photos to existing gallery
                for($i = 1; $i<=20; $i++) {
                    if ($_FILES["img_$i"]["error"] == 0){
                        $data = Warecorp_File_Item::isImage($_FILES["img_$i"]["name"], $_FILES["img_$i"]["tmp_name"]);
                        if ($data === false) continue;

                        $new_photo = new Warecorp_Photo_Item();
                        $new_photo->galleryId   = $gallery_id;
                        $new_photo->creator     = $this->currentUser->getId();
                        $new_photo->createDate  = date("d.m.Y H:i:s");
                        $new_photo->title       = $_FILES["img_$i"]["name"];
                        $new_photo->save();

                        //create thumbnail
                        $r0 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_orig.jpg", $data[0], $data[1], true);
                        $r1 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_small.jpg", 25, 25, true);
                        $r2 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_medium.jpg", 100, 100, true);
                        $r3 = Warecorp_Image_Thumbnail::makeThumbnail($_FILES["img_$i"]["tmp_name"], UPLOAD_BASE_PATH."/upload/gallery_photos/".md5($new_photo->id)."_big.jpg", 350, 350, true);

                        /** TODO block under */
                        if ($r1 != "ok" || $r2 != "ok" || $r3 != "ok") {
                            print "lazha here"; exit;
                        }

                    }
                }
                $this->_redirect("/".$this->_page->Locale."/galleryedit/gallery/".$gallery_id."/");
            }
            break;
    }
} else {
    $this->_redirect($this->currentUser->getUserPath('profile'));
}
