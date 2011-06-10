<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddPicture/action.loadGallery.php.xml');

$objResponse = new xajaxResponse();

$currentGallery = new Warecorp_Photo_Gallery($gallery_id);
$thumbs = $currentGallery->getPhotosList();
$smarty_vars = array(
"thumbs_hash" => $thumbs,
"my_only" => $my_only
);

$this->view->assign($smarty_vars);
$gallery_thumbs_content = $this->view->getContents('content_objects/ddPicture/thumbs_block.tpl');

$objResponse->addAssign("gallery_thumbs","innerHTML", $gallery_thumbs_content);

if (isset($thumbs[0]))
{
    $objResponse->addAssign("image_preview","src", $thumbs[0]->getMedium());
    $objResponse->addAssign("image_preview_title","innerHTML", $thumbs[0]->title);
    $objResponse->addAssign("image_preview","name", $thumbs[0]->id);
}

if ($my_only)
{
    $galleries = $this->_page->_user->getArtifacts()->getGalleriesList(null,50,true);
}
else
{
    $galleries = $this->_page->_user->getArtifacts()->getGalleriesList();
}


$visible = "hidden";

foreach ($galleries as &$gallery)
{
    if ($gallery->id == $gallery_id) {$visible = "visible"; break;}
}

$objResponse->addAssign("add_photo_block","style.visibility", $visible);
$objResponse->addScript("window.top.xajax_upload_image_close();");
