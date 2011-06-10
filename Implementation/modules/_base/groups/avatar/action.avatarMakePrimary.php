<?php
    Warecorp::addTranslation('/modules/groups/avatar/action.avatarMakePrimary.php.xml');

    $avatar_id = isset($this->params['avatar']) ? (int)floor($this->params['avatar']) : 0;

    $avatarsListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
    $avatarsListObj->returnAsAssoc();
    $avatarsList = $avatarsListObj->getList();

	if ($avatar_id == 0) {
		$avatar = $this->currentGroup->getAvatar();
		if ($avatar->getId() != 0){
		    $avatar->setByDefault(0);
		    $avatar->save();
		}
		$this->_redirect($this->currentGroup->getGroupPath("avatars"));
	}
	
    if (! key_exists($avatar_id, $avatarsList)){
        $this->_redirectError(Warecorp::t('Error. Invalid Profile Photo id.'));
    }

    //clear current default avatar
    $avatar = $this->currentGroup->getAvatar();

    if ($avatar->getId() != 0){
        $avatar->setByDefault(0);
        $avatar->save();
    }

    //set new default avatar
    $avatar = new Warecorp_Group_Avatar($avatar_id);
    $avatar->setByDefault(1);
    $avatar->save();

    $this->_redirect($this->currentGroup->getGroupPath("avatars"));
