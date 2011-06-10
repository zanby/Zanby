<?php
    Warecorp::addTranslation("/modules/users/avatar/action.avatarDelete.php.xml");
	$avatar_id = isset($this->params['avatar']) ? (int)floor($this->params['avatar']) : 0;
	if ($avatar_id === 0) $this->_redirect($this->_page->_user->getUserPath("avatars"));
	$avatarListObj = new Warecorp_User_Avatar_List($this->_page->_user->getId());
	$avatarListObj->returnAsAssoc();
	$avatarsList = $avatarListObj->getList();
	
	if (! key_exists($avatar_id, $avatarsList)){
	    $this->_redirectError(Warecorp::t("Error. Invalid Profile Photo id."));
	}
	
	$avatar = new Warecorp_User_Avatar($avatar_id);
	$avatar->delete();
	
	
	$this->_redirect($this->currentUser->getUserPath("avatars"));