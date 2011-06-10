<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupPhotos/action.loadGallery.php.xml');

$objResponse = new xajaxResponse();

$currentGallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
$thumbs = $currentGallery->getPhotos()->getList();
$thumb = $currentGallery->getPhotos()->getLastPhoto();

$smarty_vars = array(
"thumbs_hash" => $thumbs,

);

$this->view->assign($smarty_vars);
$gallery_thumbs_content = $this->view->getContents('content_objects/ddPicture/thumbs_block.tpl');

$objResponse->addAssign("gallery_thumbs","innerHTML", $gallery_thumbs_content);

if (isset($thumbs[0]))
{
    $objResponse->addAssign("image_preview","src", $thumb->setWidth(120)->setHeight(120)->getImage());
    $objResponse->addAssign("image_preview_title","innerHTML", $thumb->getTitle());
    $objResponse->addAssign("image_preview","name", $thumb->getId());
}

$galleries = $this->currentGroup->getGalleries()->setPrivacy(0)->setSharingMode('own')->getList();

$visible = "hidden";

foreach ($galleries as &$gallery)
{
    if ($gallery->getId() == $gallery_id) {$visible = "visible"; break;}
}

$objResponse->addAssign("add_photo_block","style.visibility", $visible);
$objResponse->addScript("window.top.xajax_upload_image_close();");
