<?php
$objResponse = new xajaxResponse();

$script='';

$avatarListObj = new Warecorp_User_Avatar_List($this->_page->_user->getId());
$avatarListObj->returnAsAssoc();
$avatarsList = $avatarListObj->getList();

if (key_exists($avatar_id, $avatarsList)){

    //clear current default avatar
    $avatar = $this->_page->_user->getAvatar();
    $old_avatar_id = $avatar->getId();
    if ( !empty($old_avatar_id) ){
        $avatar->setByDefault(0);
        $avatar->save();
    }

    //set new default avatar

    if (!empty($avatar_id))
    {
        $avatar = new Warecorp_User_Avatar($avatar_id);
        $avatar->setByDefault(1);
        $avatar->save();
    }
    $script = 'WarecorpDDblockApp.updatePictures();';
}
$objResponse->addScript($script);
