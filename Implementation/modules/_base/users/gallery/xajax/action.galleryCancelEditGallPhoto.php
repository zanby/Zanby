<?php
$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && $photo->getId() !== null  ) {
    $tags = $photo->getTagsList();
    $tags_str = array();
    if ( sizeof($tags) != 0 ) {
        foreach ( $tags as $tag ) $tags_str[] = $tag->getPreparedTagName();
    }
    $tags_str = join(' ', $tags_str);

    $this->view->gallery = $gallery;
    $this->view->photo = $photo;
    $this->view->photoTags = $tags_str;
    $content = $this->view->getContents('users/gallery/template.edit.photo.view.tpl');
    $objResponse->addAssign('photoContent'.$photo->getId(), 'innerHTML', $content);
}
