<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryUnShareGroupDo.php.xml');
$objResponse = new xajaxResponse();

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($groupId)) ) {
    $allGroupsSharing = true;
    $groupId = $familyId;
}

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$group   = Warecorp_Group_Factory::loadById($groupId);
                  
if ( !empty($gallery) && $gallery->getId() !== null && !empty($group) && null !== $group->getId() ) {
         
    if ( false === $allGroupsSharing && Warecorp_Photo_AccessManager_Factory::create()->canUnShareGallery($gallery, $group, $this->_page->_user) ) {
                                     
        $gallery->unshare($group, false);

        $objResponse->addScript($application.'.showShareNew(null);');
    } elseif ($allGroupsSharing && Warecorp_Photo_AccessManager_Factory::create()->canUnshareGalleryToAllFamilyGroups($gallery, $group, $this->_page->_user)) {   
        $gallery->unshare($group, true);
        Warecorp_Share_Entity::removeShareException($group->getId(), $gallery->getId(), $gallery->EntityTypeId);
        $objResponse->addScript($application.'.showShareNew(null);');
        $objResponse->showAjaxAlert(Warecorp::t('Gallery unshared'));
    } elseif ($allGroupsSharing && 
                !Warecorp_Photo_AccessManager_Factory::create()->canUnshareGalleryToAllFamilyGroups($gallery, $group, $this->_page->_user) && 
                ($this->currentGroup->getMembers()->isHost($this->_page->_user->getId()) 
                    || $this->currentGroup->getMembers()->isCoHost($this->_page->_user->getId()))
    ) {
            $families = $this->currentGroup->getFamilyGroups()->returnAsAssoc(true)->getList();
            if ( !empty($families) ) {
                $gallerySharedToFamilies = Warecorp_Share_Entity::whichFamiliesSharedFrom($gallery->getId(), $gallery->EntityTypeId);
                if ( !empty($gallerySharedToFamilies) ) {
                    $familiesToException = array_intersect(array_keys($families), $gallerySharedToFamilies);
                    if ( !empty($familiesToException) ) {
                        foreach ( $familiesToException as $familyId ) {
                            if ( !Warecorp_Share_Entity::hasShareException($familyId, $gallery->getId(), $gallery->EntityTypeId, $this->currentGroup->getId()) ) {
                                Warecorp_Share_Entity::addShareException($familyId, $gallery->getId(), $gallery->EntityTypeId, $this->currentGroup->getId());
                            }
                        }
                    }
                }
            }
        $this->_page->showAjaxAlert('Gallery is unshared');
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
    } else {  
        $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
    }
}
else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not unshare this gallery'));
}
