<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryuploadandsubmit.php.xml");

    $objResponse = new xajaxResponse();
    if (Warecorp_Photo_Gallery_Abstract::isGalleryExists($gallery_id)) {
        $gallery = Warecorp_Photo_Gallery_Factory::loadById($gallery_id);
        $objResponse->addScript('emptyErrors();');
        if (empty($galleryTitle)) {
            $objResponse->addScript('addSWFError("'.Warecorp::t("Enter please gallery title").'");');
            $errors[] = Warecorp::t("Enter please gallery title");
        }
        if (empty($filescount)) {
            $objResponse->addScript('addSWFError("'.Warecorp::t("Select please files to upload").'");');
            $errors[] = Warecorp::t("Select please files to upload");
        }
        if (empty($errors)) {
            $gallery->setTitle($galleryTitle);
            $gallery->setIsCreated(1);
            $gallery->save();
            if($filescount == -1) {
                $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
                return;
            }
            $objResponse->addScript('uploadandsubmit(function(){document.uploadPhotosForm.submit();});');
        } else {
            $objResponse->addScript('showErrors();');
/*            $this->view->errors = $errors;
            $content = $this->view->getContents('_design/form/form_errors_summary.tpl');
            $objResponse->addClear('swferror', 'innerHTML');
            $objResponse->addAssign('swferror', 'innerHTML', $content);*/
        }
    } else {
        $objResponse->addRedirect($this->_page->_user->getUserPath('photos'));
    }
