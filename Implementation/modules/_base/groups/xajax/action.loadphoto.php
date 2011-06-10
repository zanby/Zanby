<?php
Warecorp::addTranslation('/modules/groups/xajax/action.loadphoto.php.xml');

    $objResponse = new xajaxResponse();
    if (Warecorp_Photo_Item::isPhotoExists($photoId, $galleryId)) {
        $photo = new Warecorp_Photo_Item($photoId);
        $objResponse->addAssign("xa_createDate", "innerHTML", $photo->createDate);
        $objResponse->addAssign("xa_creator_login", "innerHTML", $photo->getCreator()->getLogin());
        $objResponse->addAssign("xa_title", "innerHTML", $photo->title);
        $objResponse->addAssign("xa_description", "innerHTML", $photo->description);
        $objResponse->addAssign("xa_additionalInfo", "innerHTML", $photo->additionalInfo);
        $objResponse->addAssign("xa_photo_path", "src", $photo->photo_path."_big.jpg");
        $objResponse->addAssign("xa_photo_id", "href", "javascript:openWin('{$photo->id}');");
        $objResponse->addAssign("xa_tags", "innerHTML", Warecorp_Common_TagString::makeTagString($photo->getTagsList(),"/tag="," "));
    }
