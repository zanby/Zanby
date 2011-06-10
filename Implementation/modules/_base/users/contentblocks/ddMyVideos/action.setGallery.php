<?php

$currentGallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
$thumb = $currentGallery->getVideos()->getLastVideo();

$objResponse = new xajaxResponse();

if (!empty($gallery_id))
{
  $objResponse->addAssign("gallery_".$cloneId."_".$gallery_index,"innerHTML", "<img src='".$thumb->getCover()->setWidth(119)->setHeight(89)->getImage()."'>");
  
  $objResponse->addScript("var myVideosObj = WarecorpDDblockApp.getObjByID('$cloneId');");
  $objResponse->addScript("myVideosObj.galleries[".$gallery_index."] = ".$gallery_id.";");
  $objResponse->addScript("popup_window.close();");
}
