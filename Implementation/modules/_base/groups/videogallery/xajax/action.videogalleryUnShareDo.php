<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryUnShareDo.php.xml');
$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$unshared = false;

if ( $gallery && $gallery->getId() && Warecorp_Video_AccessManager_Factory::create()->canUnShareGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    if ( $this->currentGroup->getGroupType() === 'family'                                                                   &&
        Warecorp_Share_Entity::isShareExists($this->currentGroup->getId(), $gallery->getId(), $gallery->EntityTypeId)       &&
        Warecorp_Video_AccessManager_Factory::create()->canUnshareGalleryToAllFamilyGroups($gallery, $this->currentGroup, $this->_page->_user)
    ) {
            $gallery->unshare($this->currentGroup, true);
            $unshared = true;
    }
    else {
        $gallery->unshare($this->currentGroup, false);
        $unshared = true;
    }

    if ( $unshared ) {
        if ( $this->currentGroup->getGroupType() !== 'family' ) {
            /**Set Exception to Share if gallery shared from family**/
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
            /**Set Exception to Share if gallery shared from family**/
        }
    
        $this->_page->showAjaxAlert(Warecorp::t('Video is unshared'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();

        $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
    }
    else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
    }
} else {
    $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
}
