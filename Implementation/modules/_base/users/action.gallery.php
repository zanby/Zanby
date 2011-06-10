<?php

    if ( isset($this->params['galleryid']) && $this->params['galleryid'] && Warecorp_Photo_Gallery::isGalleryExists($this->params['galleryid']) ) {
        $gallery = new Warecorp_Photo_Gallery($this->params['galleryid']);
        $photosList = $gallery->getPhotosList();
        if ( sizeof($photosList) == 0 ) {
            $this->_redirect($this->currentUser->getUserPath('photos'));
        }
        $currentPhoto = $photosList[0];
        $tags = $currentPhoto->getTagsList();
        //        if ( isset($this->params['photoid']) && $this->params['photoid'] && Warecorp_Photo_Item::isPhotoExists($this->params['photoid'], $this->params['galleryid']) ) {
//        }
        $this->view->gallery = $gallery;
        $this->view->tags = Warecorp_Common_TagString::makeTagString($currentPhoto->getTagsList(),"/tag="," ");
        $this->view->photosList = $photosList;
        $this->view->currentPhoto = $currentPhoto;
        $this->view->bodyContent = 'users/gallery/photo_list.tpl';
    } else {
        $this->_redirect($this->currentUser->getUserPath('profile'));
    }
