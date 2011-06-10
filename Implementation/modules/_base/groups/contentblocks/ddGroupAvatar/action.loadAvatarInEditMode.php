<?php
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupAvatar/action.loadAvatarInEditMode.php.xml');

$objResponse = new xajaxResponse();

$script='';

$avatarListObj = new Warecorp_Group_Avatar_List($this->currentGroup->getId());
$avatarListObj->returnAsAssoc();
$avatarsList = $avatarListObj->getList();

if (key_exists($avatar_id, $avatarsList)){

    //clear current default avatar
    $avatar = $this->currentGroup->getAvatar();
    $old_avatar_id = $avatar->getId();
    if ( !empty($old_avatar_id) )
    {
        $avatar->setByDefault(0);
        $avatar->save();
    }

    //set new default avatar
    if (!empty($avatar_id))
    {
        $avatar = new Warecorp_Group_Avatar($avatar_id);
        $avatar->setByDefault(1);
        $avatar->save();
    }
    $script = 'WarecorpDDblockApp.updateGroupAvatars();';
}

$objResponse->addScript($script);