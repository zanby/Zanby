<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryEditshowpage.php.xml');

    $objResponse = new xajaxResponse();
    if ($gallery_id == 0 || !Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id)) {
        $objResponse->showAjaxAlert( Warecorp::t('Access Denied')); 
        $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
        return $objResponse;
    }
                  
    $items_per_page = 10; 
    $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
    
    if ( !Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
        $objResponse->showAjaxAlert(Warecorp::t('Access Denied')); 
        $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
        return $objResponse;
    }   
     
    $videosListObj = $gallery->getVideos();    
    $paging_url = '#null';
    $P = new Warecorp_Common_PagingProduct($videosListObj->getCount(), $items_per_page, $paging_url);
    if ($expand_mode == 'all') {
        $form = new Warecorp_Form('editPhotoForm', 'post', $this->currentGroup->getGroupPath('videogalleryEditGallVideoDo'));
        $this->view->form = $form;    
    }
    $this->view->expand = $expand_mode;
    $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
    $this->view->page = $page;
    $this->view->infoPaging = $P->makeInfoPaging($page);
    $this->view->paging = $P->makeAjaxLinkPaging($page, "xajax_editshowpage('","', '".$gallery_id."', '".$expand_mode."'); return false;");
    $videosList = $videosListObj->setCurrentPage($page)->setListSize($items_per_page)->getList();
    $this->view->gallery = $gallery;
    $this->view->videoslist = $videosList;
    $content = $this->view->getContents('groups/videogallery/'.VIDEOMODEFOLDER.'template.edit.videos.rows.tpl');
    $objResponse->addAssign('videosRows', 'innerHTML', $content);
    $objResponse->addScript('location.hash="#";');
    if ($expand_mode == 'all') {
        $objResponse->addScript('AddTMControlsForAllDescriptions();');
    }    
