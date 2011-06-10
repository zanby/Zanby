<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.attach.photo.php.xml");
    $objResponse = new xajaxResponse();

    if ( $handle === null ) {
        
        if ( null !== $photoId ) $currentImage = Warecorp_Photo_Factory::loadById($photoId);
        else $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentUser);
        
        $currentPage     = 1;
        $perPage        = 20;
        
        $galleriesList = $this->currentUser->getGalleries()->setAssocValue('id')->returnAsAssoc(true)->getList();

        $lstPhotos = Warecorp_Photo_List_Factory::loadByOwner($this->currentUser);
        if ( sizeof($galleriesList) != 0 ) $lstPhotos->setGalleryId($galleriesList);
        $lstPhotos->setCurrentPage($currentPage);
        $lstPhotos->setListSize($perPage);
        $photos = $lstPhotos->getList();
        $photosCount = $lstPhotos->getCount();

        $this->view->photos = $photos;
        $this->view->currentCount = count($photos);
        $this->view->total = $photosCount;
        $this->view->perPage = $perPage;
        $this->view->pagesCount = ceil($photosCount/$perPage);
        $this->view->currentPage = $currentPage;
        
        $this->view->rangeStart = ($currentPage-1)*$perPage+1;
        $this->view->rangeEnd = ($currentPage-1)*$perPage+count($photos);
        
        $this->view->currentImage = $currentImage;
        $content = $this->view->getContents('users/calendar/ajax/action.event.attach.photo.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Select picture from all your galleries"));
        $popup_window->content($content);
        $popup_window->width(400)->height(350)->open($objResponse);

        
        /*
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Select picture from all your galleries"));
        $popup_window->content($content)->height(400)->width(500)->open($objResponse);
        */
        
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

/*
$avatars = array();
$galleriesList = $this->currentUser->getGalleries()->getList();

foreach ( $galleriesList as &$v ) {
    $tmp = $v->getPhotos()->getList();
    foreach ($tmp as &$_v)
    {
        $avatars[$_v->getId()] = $_v;
    }
}
$avatars_tmp = array();
$counter=0;
foreach ($avatars as &$_v)
{
    $counter++;
    if ($counter > $pageNum*$perPage && $counter <= $pageNum*$perPage+$perPage)
    {
        $avatars_tmp[$_v->getId()] = $avatars[$_v->getId()];
    }
}

$this->view->currentImage = Warecorp_Photo_Factory::createByOwner($this->currentUser) ;
$this->view->currentCount = count($avatars_tmp);
$this->view->total = count($avatars);
$this->view->perPage = $perPage;
$this->view->pagesCount = ceil(count($avatars)/$perPage);
$this->view->currentPage = $pageNum;
$this->view->cloneId = $cloneId;
$this->view->a_thumbs_hash = $avatars_tmp;
$this->view->a_thumbs_content = $this->view->getContents('users/calendar/avatar_thumbs_block.tpl');


$content = $this->view->getContents('users/calendar/choose_avatar.tpl');

$params->id = 'ddImagePopup';
$params->width = "400px";
$params->modal = true;
$params->close = true;
$params->title = "Select picture from all your galleries";
$params->draggable = false;
$objResponse->addProcessMessage($content, $params);

*/
