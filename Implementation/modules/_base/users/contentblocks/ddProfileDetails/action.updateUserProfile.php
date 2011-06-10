<?php
Warecorp::addTranslation("/modules/users/contentblocks/ddProfileDetails/action.updateUserProfile.php.xml");
$objResponse = new xajaxResponse();

if (preg_match("/^([a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,})*$/", $realname)) {

    $gender = htmlspecialchars($gender);

    $this->_page->_user->setRealname(htmlspecialchars($realname));
    if (strtolower($gender) == 'male' || strtolower($gender) == 'female' || strtolower($gender) == 'unselected') {
        $this->_page->_user->setGender($gender);
    }

    $this->_page->_user->deleteTags();
    $this->_page->_user->addTags($tags);

    $this->_page->_user->save();
} else {
    $objResponse->addScript("alert('".Warecorp::t("Real Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.). The first symbol should be the letter.")."');");
    $objResponse->addScript("setEditMode('".$cloneId."');");
}
   $objResponse->addScript('WarecorpDDblockApp.updateProfiles();');

