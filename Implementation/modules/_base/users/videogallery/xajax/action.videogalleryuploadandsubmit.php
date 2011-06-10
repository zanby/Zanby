<?php
    Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryuploadandsubmit.php.xml");

    $objResponse = new xajaxResponse();
    if (Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id)) {
        $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
        $objResponse->addScript('emptyErrors();');
        $objResponse->addScript('document.getElementById("error").style.display = "none";');
        if (empty($galleryTitle)) {
            $objResponse->addScript('addSWFError("'. Warecorp::t("Enter please collection title").'");');
            $errors[] = Warecorp::t("Enter please collection title");
        }
        if (empty($filescount)) {
            $objResponse->addScript('addSWFError("'. Warecorp::t("Select please files to upload") .'");');
            $errors[] = Warecorp::t("Select please files to upload");
        }
        if (empty($errors)) {
            $gallery->setTitle($galleryTitle);
            //$gallery->setIsCreated(1);
            $gallery->save();
            $objResponse->addScript('uploadandsubmit(function(){document.uploadVideosForm.submit();});');
        } else {
            $objResponse->addScript('showErrors();');
/*            $this->view->errors = $errors;
            $content = $this->view->getContents('_design/form/form_errors_summary.tpl');
            $objResponse->addClear('swferror', 'innerHTML');
            $objResponse->addAssign('swferror', 'innerHTML', $content);*/   
        }
    } else {
        $objResponse->addRedirect($this->_page->_user->getUserPath('videos'));
    }
