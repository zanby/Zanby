<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryShareGroup.php.xml");
$objResponse = new xajaxResponse();

$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);
if (empty($groupId)) $groupId = null;

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $groupsList = $this->_page->_user->getGroups()
                ->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))
                ->returnAsAssoc()
                ->getList();

    $groupsSharedWith = $gallery->getGroupsSharedWith();

    foreach($groupsList as $id=>$name) {
        if (!Warecorp_Group_AccessManager::canUsePhotos($id, $this->_page->_user)) 
            unset($groupsList[$id]);
    }
    $groupsNotSharedWith = array_diff_key($groupsList, $groupsSharedWith);

    $friendsListObj = new Warecorp_User_Friend_List();
    $friendsListObj->setUserId($this->_page->_user->getId());
    $friendsList = $friendsListObj->returnAsAssoc()->getList();

    $usersSharedWith = $gallery->getUsersSharedWith();
    $friendsList = array_flip($friendsList);
    $usersNotSharedWith = array_keys(array_diff_key($friendsList, $usersSharedWith));
  
    foreach ($usersNotSharedWith as &$item) {
        $item = new Warecorp_User('id', $item);
    }
    
	if (empty($groupId)) {
        if (!empty($groupsNotSharedWith)) {
        	reset($groupsNotSharedWith);
        	$groupId = key($groupsNotSharedWith);
        }
    }else{
		if (!isset($groupsNotSharedWith[$groupId])) $groupId = null;
	}

    $familySharingList = new Warecorp_Share_List_Family();
    $familySharingList
        ->setUser($this->_page->_user)
        ->returnAsAssoc(true)
        ->setContext($this->_page->_user)
        ->setEntity($gallery->getId(), $gallery->EntityTypeId);

    $familyNotSharedWith = $familySharingList
        ->getListNotSharedFamilies();
    $familyNotSharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familyNotSharedWith);
    $groupsNotSharedWith = (array)$familyNotSharedWith + (array)$groupsNotSharedWith;

    $familySharedWith   = $familySharingList
        ->getListSharedFamilies();
    $familySharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familySharedWith);
    $groupsSharedWith = (array)$familySharedWith + (array)$groupsSharedWith;

    $this->view->selectedGroup = $groupId;
    $this->view->groupsSharedWith = $groupsSharedWith;
    $this->view->groupsNotSharedWith = $groupsNotSharedWith;
    $this->view->usersSharedWith = $usersSharedWith;
    $this->view->usersNotSharedWith = $usersNotSharedWith;
    $this->view->gallery = $gallery;
    $this->view->JsApplication = $application;

    $Content = $this->view->getContents('users/videogallery/'.VIDEOMODEFOLDER.'xajax.share.new.tpl');
    $popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($Content);

    if (SINGLEVIDEOMODE){
        $popup_window->title(Warecorp::t('Share Video'));
    }else{
        $popup_window->title(Warecorp::t('Share Collection'));
    }
    $popup_window->width(450)->height(350)->open($objResponse);

} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
