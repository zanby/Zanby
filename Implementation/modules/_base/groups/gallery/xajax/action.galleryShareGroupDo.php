<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryShareGroupDo.php.xml');
$objResponse = new xajaxResponse();

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($groupId)) ) {
    $allGroupsSharing = true;
    $groupId = $familyId;
}

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$group   = Warecorp_Group_Factory::loadById($groupId);
$shared = false;

if ( !empty($gallery) && $gallery->getId() && $group && $group->getId() && Warecorp_Photo_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    if ( $allGroupsSharing && Warecorp_Photo_AccessManager_Factory::create()->canShareGalleryToAllFamilyGroups($gallery, $group, $this->_page->_user) ) {
        $gallery->share($group, true);
        $shared = true;
    }
    elseif ( !$allGroupsSharing ) {
        $gallery->share($group, false);
        $shared = true;
    }
	
    if ( $shared ) {
        $gallery->saveShareHistory($this->_page->_user, $group->getName(), 'group', array($group->getId()));
        /**
         * show or hide show history link
         */
        if ( $application == 'PGLApplication' ) {
            $Script = '';
            $Script .= 'if ( YAHOO.util.Dom.get("showHistory'.$gallery->getId().'") ) {';
            $Script .= '  YAHOO.util.Dom.get("showHistory'.$gallery->getId().'").value = "'.(($gallery->isShareHistoryExists())?1:0).'"';
            $Script .= '}';
            $objResponse->addScript($Script);
        } else {
            if ( $gallery->isShareHistoryExists() ) $objResponse->addScript($application.'.enableSaherHistoryView();');
            else $objResponse->addScript($application.'.disableSaherHistoryView();');
        }
        /**
         * show ajax alert
         */
        $objResponse->showAjaxAlert(Warecorp::t('Gallery is shared'));
        $objResponse->addScript($application.'.showShareNew(null);');
    } else {
            $objResponse->showAjaxAlert(Warecorp::t('You can not share this gallery'));
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not share this gallery'));
}
