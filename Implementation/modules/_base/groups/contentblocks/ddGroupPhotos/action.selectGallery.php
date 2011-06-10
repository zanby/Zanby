<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupPhotos/action.selectGallery.php.xml');

$objResponse = new xajaxResponse();

$galleries = $this->currentGroup->getGalleries()->setPrivacy(array(0,1))->setSharingMode('both')->getList();
//print_r($galleries);
if (!empty($galleries))
{
     $thumb = $galleries[0]->getPhotos()->getLastPhoto();
}
else
{
  $thumb = '';
}

$smarty_vars = array(
"gallery_hash"  => $galleries,
"image_preview" => empty($thumb)?'':$thumb->setWidth(119)->setHeight(89)->getImage(),
"preview_title" => empty($thumb)?'':$thumb->getTitle(),
"preview_nid"   => empty($thumb)?0:$thumb->getId(),
"div_id"        => "gallery_".$cloneId."_".$gallery_index,
"index_id"      => $cloneId,
"onclickattr"   => "xajax_set_gallery(getElementById('gallery_select').value, '$cloneId', $gallery_index); return false;");



$this->view->assign($smarty_vars);
$content = $this->view->getContents('content_objects/ddGroupPhotos/choose_gallery.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Select Gallery'));
$popup_window->content($content);
$popup_window->width(240)->height(300)->open($objResponse);
