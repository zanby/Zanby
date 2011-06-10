<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryuploadandsubmit.php.xml');

    $objResponse = new xajaxResponse();
    if (Warecorp_Video_Gallery_Abstract::isGalleryExists($gallery_id)) {
        $gallery = Warecorp_Video_Gallery_Factory::loadById($gallery_id);
        $objResponse->addScript('emptyErrors();');
        $objResponse->addScript('document.getElementById("error").style.display = "none";');
        if (empty($galleryTitle)) {
            $text_info = Warecorp::t('Enter please collection title');
            $objResponse->addScript("addSWFError('".$text_info."');");
            $errors[] = $text_info;
        }
        if (empty($filescount)) {
            $text_info = Warecorp::t('Select please files to upload');
            $objResponse->addScript("addSWFError('".$text_info."');");
            $errors[] = $text_info;
        }
        if (empty($errors)) {
            $gallery->setTitle($galleryTitle);
            //$gallery->setIsCreated(1);
            $gallery->save();

            $_SESSION["swfupload"][$gallery->getId()]["videonew"] = 1;
            $objResponse->addScript('uploadandsubmit(function(){document.uploadVideosForm.submit();});');
        } else {
            $objResponse->addScript('showErrors();');            
/*            $this->view->errors = $errors;
            $content = $this->view->getContents('_design/form/form_errors_summary.tpl');
            $objResponse->addClear('swferror', 'innerHTML');
            $objResponse->addAssign('swferror', 'innerHTML', $content);*/   
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
    }
