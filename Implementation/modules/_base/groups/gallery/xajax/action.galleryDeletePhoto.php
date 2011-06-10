<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryDeletePhoto.php.xml');

$objResponse = new xajaxResponse();
    
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);
$photo = Warecorp_Photo_Factory::loadById($photoId);

if ( $gallery->getId() !== null && 
     $photo->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canDeletePhoto($photo, $this->currentGroup, $this->_page->_user) ) {
  
    $photo->delete();

    /** Send notification to host **/
    $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "CHANGES", false );
     
//    if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//        $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//        $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//        $mail->setSender($this->currentGroup);
//        $mail->addRecipient($this->currentGroup->getHost());
//        $mail->addParam('Group', $this->currentGroup);
//        $mail->addParam('action', "CHANGES");
//        $mail->addParam('section', "PHOTO");
//        $mail->addParam('chObject', $gallery);
//        $mail->addParam('User', $this->_page->_user);
//        $mail->addParam('isPlural', false);
//        $mail->addParam('items', array());
//        $mail->sendToPMB(true);
//        $mail->send();
//    }
    /** --- **/

     $photos = $gallery->getPhotos()->returnAsAssoc()->getList();
     if ( sizeof($photos) == 0 ) {
        $this->_page->showAjaxAlert(Warecorp::t('Photo deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));     	
     } else {
     	$photos = array_keys($photos);
     	$photoId = $photos[0];
        $this->_page->showAjaxAlert(Warecorp::t('Photo Deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentGroup->getGroupPath('galleryView/id').$photoId.'/');
     }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not delete photo'));  
}
