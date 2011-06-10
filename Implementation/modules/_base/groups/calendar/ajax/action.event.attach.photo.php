<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attach.photo.php.xml');
    $objResponse = new xajaxResponse();

    if ( $handle === null ) {
        
        if ( null !== $photoId ) {
            $currentImage = Warecorp_Photo_Factory::loadById($photoId);
        } else {
            $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentGroup);
        }

        $currentPage     = 1;
        $perPage        = 20;
        
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
        
        $this->view->currentImage = $currentImage;
        $content = $this->view->getContents('groups/calendar/ajax/action.event.attach.photo.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Select picture from all your galleries"));
        $popup_window->content($content);
        $popup_window->width(400)->height(350)->open($objResponse);
        
    } else {
        if ( $handle ) {
            $objResponse->addAssign('EventPictureBlockNONE', 'style.display', 'none');
            $objResponse->addAssign('EventPictureBlock', 'style.display', ''); 
            $picture = Warecorp_Photo_Factory::loadById($handle);
            $objResponse->addAssign('EventImageObj', 'src', $picture->setWidth(75)->setHeight(75)->getImage());       
        } else {
            $objResponse->addAssign('EventPictureBlockNONE', 'style.display', '');
            $objResponse->addAssign('EventPictureBlock', 'style.display', 'none');        
        }
        $objResponse->addAssign('event_picture_id', 'value', floor($handle));
        $objResponse->addScript("popup_window.close()");
    }
