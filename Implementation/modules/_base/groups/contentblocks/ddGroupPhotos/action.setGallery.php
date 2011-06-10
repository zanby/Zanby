<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupPhotos/action.setGallery.php.xml');

$currentGallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
$thumb = $currentGallery->getPhotos()->getLastPhoto();

$objResponse = new xajaxResponse();

if (!empty($gallery_id))
{
  $objResponse->addAssign("gallery_".$cloneId."_".$gallery_index,"innerHTML", "<img src='".$thumb->setWidth(119)->setHeight(89)->getImage()."'>");
  
  $objResponse->addScript("var groupPhotosObj = WarecorpDDblockApp.getObjByID('$cloneId');");
  $objResponse->addScript("groupPhotosObj.galleries[".$gallery_index."] = ".$gallery_id.";");
  $objResponse->addScript("popup_window.close();");
}
