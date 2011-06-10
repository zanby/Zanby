<?php
    Warecorp::addTranslation("/modules/users/action.galleryEdit.php.xml");

$gallery_id = isset($this->params['gallery']) ? floor($this->params['gallery']) : 0;
$action = isset($this->params['action']) ? $this->params['action'] : "";

$galleries = $this->currentUser->getArtifacts()->getGalleriesListAssoc();
if (! key_exists((int)$gallery_id, $galleries) || $gallery_id == 0){
    $this->_redirectError(Warecorp::t("Error. Invalid gallery id."));
}
unset($galleries);

if ($action != "save"){
    $gallery = new Warecorp_Photo_Gallery($gallery_id);
    $photosList      = $gallery->getPhotosList();
    $galleryShareList = $gallery->getShareListAssoc();
    $groupList = $this->currentUser->getGroups()->returnAsAssoc()->getList();

    /*
    foreach ($photosList as &$photo){
        $photo->tags = implode(", ", $photo->getTagsList());
    }
    */

    $this->view->gallery_id = $gallery_id;
    $this->view->gallery = $gallery;

    $this->view->photoslist = $photosList;
    $this->view->grouplist = $groupList;
    $this->view->gallerysharelist = $galleryShareList;



    $this->view->bodyContent = 'users/gallery/edit.tpl';
} else {
    $gallery = new Warecorp_Photo_Gallery($gallery_id);
    $photosList = $gallery->getPhotosListAssoc();
    //save photos details
    foreach($photosList as $key=>$value){
        $photo = new Warecorp_Photo_Item($key);

        if (isset($this->params["delete_".$key])) {
            $photo->delete();
            unset($photo);
            continue;
        } else {
            $photo->title           = $this->params["title_".$key];
            $photo->description     = $this->params["description_".$key];
            $photo->additionalInfo  = $this->params["additionalInfo_".$key];
        echo "<pre>";
        print_r($photo);
            $photo->save();
            unset($photo);
        }
    }
    //save sharing data
    $groupsList = $this->currentUser->getGroups()->returnAsAssoc()->getList();
    foreach ($groupsList as $key=>$value){
        if (isset($this->params["share_".$key])){
            $gallery->shareGallery($key);
        } else {
            $gallery->unshareGallery($key);
        }
    }

    //if gallery empty - remove it
    if ($gallery->getPhotosCount() == 0){
        $gallery->deleteAllShare();
        $gallery->delete();
    }
    if (isset($this->params["isPrivate"]) && $this->params["isPrivate"] == 0){
        $gallery->isPrivate = 0;
    } else {
        $gallery->isPrivate = 1;
    }
    if (isset($this->params["title"])){
        $gallery->title = $this->params["title"];
    }
    $gallery->save();
    unset($gallery);

    $this->_redirect("/".$this->_page->Locale."/photos/");

}
