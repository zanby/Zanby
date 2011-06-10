<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attach.photo.update.php.xml');
    $objResponse = new xajaxResponse(); 
    
    $galleriesList = $this->currentGroup->getGalleries()->setAssocValue('id')->returnAsAssoc(true)->getList();

    $lstPhotos = Warecorp_Photo_List_Factory::loadByOwner($this->currentGroup);
    if ( sizeof($galleriesList) != 0 ) $lstPhotos->setGalleryId($galleriesList);
    $lstPhotos->setCurrentPage($currentPage);
    $lstPhotos->setListSize($perPage);
    $photos = $lstPhotos->getList();
    $photosCount = $lstPhotos->getCount();

    $this->view->photos = $photos;
    $this->view->currentCount =count($photos);
    $this->view->total =$photosCount;
    $this->view->perPage =$perPage;
    $this->view->pagesCount =ceil($photosCount/$perPage);
    $this->view->currentPage =$currentPage;
    
    $this->view->rangeStart =($currentPage-1)*$perPage+1;
    $this->view->rangeEnd =($currentPage-1)*$perPage+count($photos);
    
    $content = $this->view->getContents('groups/calendar/ajax/action.event.attach.photo.thumbs.tpl');
    $objResponse->addAssign('a_gallery_thumbs', 'innerHTML', $content);
