<?php
Warecorp::addTranslation('/modules/groups/theme/xajax/action.copyAvatarOK.php.xml');
 
$objResponse = new xajaxResponse(); 
   
if (Warecorp_Photo_Standard::isPhotoExists($avatar_id)) {
    $currentImage = Warecorp_Photo_Factory::loadById($avatar_id);
    if (! Warecorp_Photo_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentGroup, $this->_page->_user)) {
        $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentGroup);
    }

    if (file_exists($currentImage->getPath().'_orig.jpg')){         

        while(file_exists($filepath = UPLOAD_BASE_PATH."/upload/theme_background_images/".$this->currentGroup->getId()."_g".time().md5($currentImage->getId() . 'zbphoto').'_orig.jpg' )){}  
        $backgroundUrl = str_replace(UPLOAD_BASE_PATH, UPLOAD_BASE_URL, $filepath);
        
        copy($currentImage->getPath().'_orig.jpg', $filepath);
        list($width, $height, $type, $attr) = getimagesize($filepath); 
        Warecorp_Image_Thumbnail::makeThumbnail($filepath, str_replace('_orig.','_medium.',$filepath), ceil(270*$width/590), ceil(270*$height/590), true);
        Warecorp_Image_Thumbnail::makeThumbnail($filepath, str_replace('_orig.','_small.',$filepath), 37, ceil(37*$height/$width), true);
    }
    //$objResponse->addScript('ThemeApplication.removeBackgroundImage();'); 
    $objResponse->addScript('ThemeApplication.applyBackgroundImage("'.$currentImage->getTitle().'", "'.$backgroundUrl.'")'); 
}
//$objResponse->showAjaxAlert('Changes saved'); 
