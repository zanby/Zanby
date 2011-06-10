<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryShareGroup.php.xml');
$objResponse = new xajaxResponse () ;

$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
if (empty($groupId)) $groupId = null;

if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

    $groupsList = $this->_page->_user->getGroups()
                ->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))
                ->setExcludeIds($this->currentGroup->getId())
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
        ->setContext($this->currentGroup)
        ->setEntity($gallery->getId(), $gallery->EntityTypeId);

    $familyNotSharedWith = $familySharingList
        ->getListNotSharedFamilies();
    $familyNotSharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familyNotSharedWith);
    $groupsNotSharedWith = (array)$familyNotSharedWith + (array)$groupsNotSharedWith;

    $familySharedWith   = $familySharingList
        ->getListSharedFamilies();
    $familySharedWith = Warecorp_Share_List_Family::prepeareArrayKeys($familySharedWith);
    $groupsSharedWith = (array)$familySharedWith + (array)$groupsSharedWith;

    $this->view->familyNotSharedWith = $familyNotSharedWith;
    $this->view->selectedGroup = $groupId;
    $this->view->groupsSharedWith = $groupsSharedWith;
    $this->view->groupsNotSharedWith = $groupsNotSharedWith;
    $this->view->usersSharedWith = $usersSharedWith;
    $this->view->usersNotSharedWith = $usersNotSharedWith;         
	$this->view->gallery = $gallery;
	$this->view->JsApplication = $application;
    $this->view->AccessManager = Warecorp_Photo_AccessManager_Factory::create();

	$Content = $this->view->getContents('groups/gallery/xajax.share.new.tpl');
	
	$popup_window = Warecorp_View_PopupWindow::getInstance();        
    $popup_window->content($Content);
    $popup_window->title(Warecorp::t('Share Gallery'));
    $popup_window->width(500)->height(350)->fixed(0)->open($objResponse);
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
