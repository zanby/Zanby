<?php
    Warecorp::addTranslation("/modules/users/avatar/action.avatarMakePrimary.php.xml");
	$avatar_id = isset($this->params['avatar']) ? (int)floor($this->params['avatar']) : 0;

	$avatarListObj = new Warecorp_User_Avatar_List($this->currentUser->getId());
	$avatarListObj->returnAsAssoc();
	$avatarsList = $avatarListObj->getList();

	if ($avatar_id == 0) {
		$avatar = $this->currentUser->getAvatar();
		if ($avatar->getId() != 0){
		    $avatar->setByDefault(0);
		    $avatar->save();
		}
		$this->_redirect($this->currentUser->getUserPath("avatars"));
	}

	if (! key_exists($avatar_id, $avatarsList)){
	    $this->_redirectError(Warecorp::t("Error. Invalid Profile Photo id."));
	}

	//clear current default avatar
	$avatar = $this->currentUser->getAvatar();
	if ($avatar->getId() != 0){
	    $avatar->setByDefault(0);
	    $avatar->save();
	}

	//set new default avatar
	$avatar = new Warecorp_User_Avatar($avatar_id);
	$avatar->setByDefault(1);
	$avatar->save();

	$this->_redirect($this->currentUser->getUserPath("avatars"));
