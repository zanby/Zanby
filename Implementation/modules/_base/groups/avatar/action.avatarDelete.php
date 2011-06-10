<?php
    Warecorp::addTranslation('/modules/groups/avatar/action.avatarDelete.php.xml');

    $avatar_id = isset($this->params['avatar']) ? (int)floor($this->params['avatar']) : 0;
	if ($avatar_id === 0) $this->_redirect($this->currentGroup->getUserPath("avatars"));
    $avatarsListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
    $avatarsListObj->returnAsAssoc();
    $avatarsList = $avatarsListObj->getList();
    
    if (! key_exists($avatar_id, $avatarsList)){
        $this->_redirectError(Warecorp::t('Error. Invalid Profile Photo id.'));
    }

    $avatar = new Warecorp_Group_Avatar($avatar_id);
    $avatar->delete();

    
    $this->_redirect($this->currentGroup->getGroupPath("avatars"));
