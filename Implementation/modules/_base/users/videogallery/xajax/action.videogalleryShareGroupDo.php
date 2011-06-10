<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryShareGroupDo.php.xml");
$objResponse = new xajaxResponse();

$allGroupsSharing = false;
if ( false != ($familyId = Warecorp_Share_Entity::isSharedFamilyWith($groupId)) ) {
    $allGroupsSharing = true;
    $groupId = $familyId;
}

$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
$group = Warecorp_Group_Factory::loadById($groupId);
$shared = false;

if ( $gallery && $gallery->getId() && $group && $group->getId() && Warecorp_Video_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {
    if ( $allGroupsSharing && Warecorp_Video_AccessManager_Factory::create()->canShareGalleryToAllFamilyGroups($gallery, $group, $this->_page->_user) ) {
        $gallery->share($group, true);
        $shared = true;
    }
    elseif ( !$allGroupsSharing ) {
        $gallery->share($group, false);
        $shared = true;
    }

    if ( $shared ) {
        //$gallery->saveShareHistory($this->_page->_user, $group->getName(), 'group', array($group->getId()));
        /**
         * show or hide show history link
         */
    /*    if ( $application == 'PGLApplication' ) {
            $Script = '';
            $Script .= 'if ( YAHOO.util.Dom.get("showHistory'.$gallery->getId().'") ) {';
            $Script .= '  YAHOO.util.Dom.get("showHistory'.$gallery->getId().'").value = "'.(($gallery->isShareHistoryExists())?1:0).'"';
            $Script .= '}';
            $objResponse->addScript($Script);
        } else {
            if ( $gallery->isShareHistoryExists() ) $objResponse->addScript($application.'.enableSaherHistoryView();');
            else $objResponse->addScript($application.'.disableSaherHistoryView();');
        }*/
        /**
         * show ajax alert
         */
        if (SINGLEVIDEOMODE){
            $objResponse->showAjaxAlert(Warecorp::t('Video is shared'));
        }else{
            $objResponse->showAjaxAlert(Warecorp::t('Collection is shared'));
        }
        
        $objResponse->addScript($application.'.showShareNew(null);');

        //$objResponse->addScript($application.'.showShareNew();');
    }
    else {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
