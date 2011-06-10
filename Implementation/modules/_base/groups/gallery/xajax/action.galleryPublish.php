<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryPublish.php.xml');

    $objResponse = new xajaxResponse() ;
    
    if (empty($galleryId)) return;

    if (!Warecorp_Photo_AccessManager_Factory::create()->canPublishGallery($galleryId, $this->currentGroup, $this->_page->_user)) return;

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
    
    $content = $this->view->getContents('groups/gallery/xajax.publish.tpl'); 
    
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t("Publish Gallery"));
    $popup_window->content($content);
    $popup_window->width(450)->height(150)->open($objResponse);
