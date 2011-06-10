<?php
    $this->_page->Xajax->registerUriFunction( "doAvatarLoadFromGalleries", "/users/doAvatarLoadFromGalleries/" );
    $this->_page->Xajax->registerUriFunction( "avatarLoadFromGalleries", "/users/avatarLoadFromGalleries/" );
    $this->_page->Xajax->registerUriFunction( "updateAttachPhoto", "/users/calendarEventAttachPhotoUpdate/" );
    $this->_page->Xajax->registerUriFunction( "chooseAttachPhoto", "/users/calendarEventAttachPhotoChoose/" );    
	$avatarListObj = new Warecorp_User_Avatar_List($this->currentUser->getId());
	$avatarsList = $avatarListObj->getList();
//print_r($avatarsList); exit;
	$currentAvatar = $this->currentUser->getAvatar();
	$this->view->avatarsList = $avatarsList;
	$this->view->user = $this->currentUser;
	$this->view->currentAvatar = $currentAvatar;
	$this->view->avatarsLeft = 12-$avatarListObj->getCount();
	$this->view->bodyContent = 'users/avatar/avatars_list.tpl';
