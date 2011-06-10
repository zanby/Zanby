<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryDeleteGallery.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canDeleteGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    
    $gallery->delete();
    
    if ($new == false){

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "DELETE", false );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "DELETE");
//            $mail->addParam('section', "PHOTO");
//            $mail->addParam('chObject', $gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->addParam('items', array());
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/

        $this->_page->showAjaxAlert(Warecorp::t('Gallery deleted'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    }

    $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
} else {
    if ($new == false){
        $objResponse->showAjaxAlert(Warecorp::t('You can not delete this gallery'));  
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
    }
}
