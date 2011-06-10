<?php

$objResponse = new xajaxResponse();

$currentGallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
$thumbs = $currentGallery->getVideos()->getList();
$thumb = $currentGallery->getVideos()->getLastVideo();

$smarty_vars = array(
"thumbs_hash" => $thumbs,
"my_only" => $my_only
);

$this->view->assign($smarty_vars);
$gallery_thumbs_content = $this->view->getContents('content_objects/ddPicture/thumbs_block.tpl');

$objResponse->addAssign("gallery_thumbs","innerHTML", $gallery_thumbs_content);

if (isset($thumbs[0]))
{
    $objResponse->addAssign("image_preview","src", $thumb->getCover()->setWidth(120)->setHeight(120)->getImage());
    $objResponse->addAssign("image_preview_title","innerHTML", $thumb->getTitle());
    $objResponse->addAssign("image_preview","name", $thumb->getId());
}

if ($my_only)
{
    $galleries = $this->_page->_user->getVideoGalleries()->setPrivacy(0)->setSharingMode('own')->getList(); //getGalleriesList(null,50,true);
}
else
{
    $galleries = $this->_page->_user->getVideoGalleries()->setPrivacy(0)->getList();
}


$visible = "hidden";

foreach ($galleries as &$gallery)
{
    if ($gallery->getId() == $gallery_id) {$visible = "visible"; break;}
}

$objResponse->addAssign("add_video_block","style.visibility", $visible);
$objResponse->addScript("window.top.xajax_upload_video_close();");
