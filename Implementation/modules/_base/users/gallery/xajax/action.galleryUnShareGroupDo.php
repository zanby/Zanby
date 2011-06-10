<?php
Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryUnShareGroupDo.php.xml");
$objResponse = new xajaxResponse();

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($groupId)) ) {
    $allGroupsSharing = true;
    $groupId = $familyId;
}

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$group = Warecorp_Group_Factory::loadById($groupId);

if ( !empty($gallery) && $gallery->getId() !== null && !empty($group) && null !== $group->getId() ) {

    if ( false === $allGroupsSharing && Warecorp_Photo_AccessManager_Factory::create()->canUnShareGallery($gallery, $group, $this->_page->_user) ) {

        $gallery->unshare($group, false);
        
        $objResponse->addScript($application.'.showShareNew(null);');
    } elseif ($allGroupsSharing && Warecorp_Photo_AccessManager_Factory::create()->canUnshareGalleryToAllFamilyGroups($gallery, $group, $this->_page->_user)) {
        
        $gallery->unshare($group, true);

        $objResponse->addScript($application.'.showShareNew(null);');
        $objResponse->showAjaxAlert(Warecorp::t('Gallery unshared'));
    } else {
        $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
    }
}
else {
    $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
}