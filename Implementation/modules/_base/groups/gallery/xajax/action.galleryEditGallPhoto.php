<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryEditGallPhoto.php.xml');

$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && $photo->getId() !== null && Warecorp_Photo_AccessManager_Factory::create()->canEditPhoto($photo, $this->currentGroup, $this->_page->_user) ) {
    $form = new Warecorp_Form('editPhotoForm'.$photo->getId(), 'post', $this->currentGroup->getGroupPath('galleryEditGallPhotoDo'));
    $this->view->form = $form;
    $this->view->gallery = $gallery;
    $this->view->photo = $photo;
    $content = $this->view->getContents('groups/gallery/template.edit.photo.edit.tpl');
    $objResponse->addAssign('photoContent'.$photo->getId(), 'innerHTML', $content);
}
