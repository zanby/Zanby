<?php
$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);
$photo->setForceDbTags(true);

if ( $gallery->getId() !== null && $photo->getId() !== null && Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    $form = new Warecorp_Form('editPhotoForm'.$photo->getId(), 'post', $this->currentUser->getUserPath('galleryEditGallPhotoDo'));
    $this->view->form = $form;
    $this->view->gallery = $gallery;
    $this->view->photo = $photo;
    $content = $this->view->getContents('users/gallery/template.edit.photo.edit.tpl');
    $objResponse->addAssign('photoContent'.$photo->getId(), 'innerHTML', $content);
}
