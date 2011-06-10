<?php
Warecorp::addTranslation('/modules/groups/avatar/action.avatar.php.xml');

    $avatarsListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
    $avatarsList = $avatarsListObj->getList();
    $currentAvatar = $this->currentGroup->getAvatar();
    $this->view->avatarsList = $avatarsList;
    $this->view->group = $this->currentGroup;
    $this->view->currentAvatar = $currentAvatar;
    $this->view->avatarsLeft = 12-$avatarsListObj->getCount();
    $this->view->bodyContent = 'groups/avatar/avatars_list.tpl';
