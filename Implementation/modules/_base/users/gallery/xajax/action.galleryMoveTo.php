<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryMoveTo.php.xml");
    $objResponse = new xajaxResponse() ;

    if (empty($photoId)) return;

    if (!Warecorp_Photo_AccessManager_Factory::create()->canUploadPhotos($this->currentUser, $this->_page->_user)) return;

    $photo = Warecorp_Photo_Factory::loadById($photoId);

    if (!$photo->getId()) return;

    $galleriesList = Warecorp_Photo_Gallery_List_Factory::load($this->_page->_user);
    $galleriesList = $galleriesList
                ->setSharingMode(array(Warecorp_Photo_Enum_SharingMode::OWN))
                ->setWatchingMode(array(Warecorp_Photo_Enum_WatchingMode::OWN))
                ->returnAsAssoc()
                ->setExcludeIds(array($photo->getGalleryId()))
                ->getList();

    $this->view->galleries = $galleriesList;
    $this->view->JsApplication = 'PGPLApplication';
    $content = $this->view->getContents('users/gallery/xajax.moveto.tpl');
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Move To Gallery" ));
    $popup_window->content($content);
    $popup_window->width(450)->height(150)->open($objResponse);
