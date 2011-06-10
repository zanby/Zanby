<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryPublish.php.xml');

    $objResponse = new xajaxResponse() ;
    
    if (empty($galleryId)) return;

    if (!Warecorp_Video_AccessManager_Factory::create()->canPublishGallery($galleryId, $this->currentGroup, $this->_page->_user)) return;

    $theUptakeFamily = Warecorp_Group_Factory::loadByGroupUID('theuptake');
    $newsGroups = $theUptakeFamily
            ->getGroups()
            ->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))
            ->setExcludeGroupIds(Warecorp_Group_Family_Group_EnumUID::getInArrayMode(Warecorp_Group_Family_Group_EnumUID::THEUPTAKE_SPECIAL_GROUPS))
            ->returnAsAssoc()
            ->getList();
    $newsGroups[$theUptakeFamily->getId()] = $theUptakeFamily->getName();
    $this->view->newsGroups = $newsGroups;
    $this->view->JsApplication = 'PGPLApplication';     
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    if (SINGLEVIDEOMODE) {
        $popup_window->title(Warecorp::t("Publish Story"));
    }else{
        $popup_window->title(Warecorp::t("Publish Collection"));
    }    
    $content = $this->view->getContents('groups/videogallery/'.VIDEOMODEFOLDER.'xajax.publish.tpl'); 
    
    $popup_window->content($content);
    $popup_window->width(450)->height(150)->open($objResponse);
