<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryuploadandsubmit.php.xml');

    $objResponse = new xajaxResponse();
    if (Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id)) {
        $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
        $objResponse->addScript('emptyErrors();');
        if (empty($galleryTitle)) {
            $text_info = Warecorp::t('Enter please gallery title');
            $objResponse->addScript("addSWFError('".$text_info."');");
            $errors[] = Warecorp::t("Enter please gallery title");
        }
        if (empty($filescount)) {
            $text_info = Warecorp::t('Select please files to upload');
            $objResponse->addScript("addSWFError('".$text_info."');");
            $errors[] = Warecorp::t("Select please files to upload");
        }
        if (empty($errors)) {
            $gallery->setTitle($galleryTitle);
            $gallery->setIsCreated(1);
            $gallery->save();
            if($filescount == -1) {

                /** Send notification to host **/
                $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "PHOTO", "NEW", false );
                
//                if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//                    $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//                    $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//                    $mail->setSender($this->currentGroup);
//                    $mail->addRecipient($this->currentGroup->getHost());
//                    $mail->addParam('Group', $this->currentGroup);
//                    $mail->addParam('action', "NEW");
//                    $mail->addParam('section', "PHOTO");
//                    $mail->addParam('chObject', $gallery);
//                    $mail->addParam('User', $this->_page->_user);
//                    $mail->addParam('isPlural', false);
//                    $mail->addParam('items', array());
//                    $mail->sendToPMB(true);
//                    $mail->send();
//                }
                /** --- **/

                $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
                return;
            }

            $_SESSION["swfupload"][$gallery->getId()]["new"] = 1;
            $objResponse->addScript('uploadandsubmit(function(){document.uploadPhotosForm.submit();});');
        } else {
            $objResponse->addScript('showErrors();');
/*            $this->view->errors = $errors;
            $content = $this->view->getContents('_design/form/form_errors_summary.tpl');
            $objResponse->addClear('swferror', 'innerHTML');
            $objResponse->addAssign('swferror', 'innerHTML', $content); */  
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('photos'));
    }
