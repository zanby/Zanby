<?php
Warecorp::addTranslation('/modules/groups/xajax/action.loadavatar.php.xml');

    $avatarId = floor($avatarId);
    $objResponse = new xajaxResponse();

    $avatar = new Warecorp_Group_Avatar($avatarId);
    $avatar->setGroupId($this->currentGroup->getId());
    if ($avatar->isExists()) {
    	$objResponse->addAssign("xa_avatar_path", "src", $avatar->setWidth(175)->setHeight(215)->setBorder(1)->getImage());
        if ($avatar->getId() == $this->currentGroup->getAvatar()->getId()){
            $objResponse->addAssign("xa_delete", "style.visibility", "visible");
            $objResponse->addAssign("xa_setprimary", "style.visibility", "hidden");
        } else {
            $objResponse->addAssign("xa_delete", "style.visibility", "hidden");
            $objResponse->addAssign("xa_setprimary", "style.visibility", "visible");
            $objResponse->addAssign("xa_makeprimary", "href", $this->currentGroup->getGroupPath("avatarmakeprimary/avatar/".$avatar->getId()));
        }
        if ($avatar->getId() === 0) {
        	$objResponse->addAssign("xa_deleteurl", "style.visibility", "hidden");
        	$objResponse->addAssign("xa_deletelink", "style.display", "none");
        } else {
        	$objResponse->addAssign("xa_deletelink", "style.display", "");
        	$objResponse->addAssign("xa_deleteurl", "style.visibility", "visible");
        }
        $objResponse->addAssign("xa_deleteurl", "href", $this->currentGroup->getGroupPath("avatardelete/avatar/".$avatar->getId()));
    }
