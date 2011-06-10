<?php

$currentGallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
$thumb = $currentGallery->getPhotos()->getLastPhoto();

$objResponse = new xajaxResponse();

if (!empty($gallery_id))
{
  $objResponse->addAssign("gallery_".$cloneId."_".$gallery_index,"innerHTML", "<img src='".$thumb->setWidth(119)->setHeight(89)->getImage()."'>");
  
  $objResponse->addScript("var myPhotosObj = WarecorpDDblockApp.getObjByID('$cloneId');");
  $objResponse->addScript("myPhotosObj.galleries[".$gallery_index."] = ".$gallery_id.";");
  $objResponse->addScript("popup_window.close();");
}
