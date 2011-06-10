<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryPublishDo.php.xml');

    $objResponse = new xajaxResponse() ;
    
    $application = empty($application)?'PGPLApplication':$application;
    
    if (empty($galleryId) || empty($groupId)) return;

    if (!Warecorp_Video_AccessManager_Factory::create()->canPublishGallery($galleryId, $this->currentGroup, $this->_page->_user)) return;

    $theUptakeFamily = Warecorp_Group_Factory::loadByGroupUID('theuptake');
    
    $newsGroups = $theUptakeFamily
            ->getGroups()
            ->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))
            ->setExcludeGroupIds(Warecorp_Group_Family_Group_EnumUID::getInArrayMode(Warecorp_Group_Family_Group_EnumUID::THEUPTAKE_SPECIAL_GROUPS))
            ->returnAsAssoc()
            ->getList();
    $newsGroups[$theUptakeFamily->getId()] = $theUptakeFamily->getName();            
    $newsGroups = array_keys(is_array($newsGroups)?$newsGroups:array());
    
    if (!in_array($groupId, $newsGroups)) return;
    $gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
    
    $group = Warecorp_Group_Factory::loadById($groupId);
    
    $newGallery = Warecorp_Video_Gallery_Factory::createByOwner($group);
    $newGallery->setOwnerType("group");
    $newGallery->setOwnerId($group->getId());
    $newGallery->setCreatorId($gallery->getCreatorId());
    $newGallery->setTitle($gallery->getTitle());
    $newGallery->setDescription($gallery->getDescription());
    $newGallery->setCreateDate(new Zend_Db_Expr('NOW()'));
    $newGallery->setUpdateDate(new Zend_Db_Expr('NOW()'));
    $newGallery->setSize(0);
    $newGallery->setPrivate(0);
    $newGallery->setIsCreated(1);
    $newGallery->setIsPublished(1);
    $newGallery->save();
    $gallery->copy($newGallery);
    $gallery->publish($newGallery);
    
    //$newGallery->share($theUptakeFamily);
    if (SINGLEVIDEOMODE) {
        $objResponse->showAjaxAlert(Warecorp::t('Story Published'));
    }else{
        $objResponse->showAjaxAlert(Warecorp::t('Collection Published'));    
    }            
  
    
         

