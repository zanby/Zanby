<?php
Warecorp::addTranslation("/modules/users/videogallery/xajax/action.videogalleryEditVideoDo.php.xml");
$objResponse = new xajaxResponse();

$gallery = Warecorp_Video_Gallery_Factory::loadById($this->params['gallery_id']);
$video = Warecorp_Video_Factory::loadById($this->params['video_id']);

if ( $gallery->getId() !== null &&
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) &&
     $video->getSource() != Warecorp_Video_Enum_VideoSource::NONVIDEO ) {

    $form = new Warecorp_Form('editPhotoForm', 'post', $this->currentUser->getUserPath('videogalleryEditVideoDo'));
    $embed = false;
    if (($video->getSource() !== Warecorp_Video_Enum_VideoSource::OWN) && isset($this->params['source']) && !empty($this->params['customSrc'])) {
        $source = empty($this->params['source'])?'':$this->params['source'];
        $customSrc = $this->params['customSrc'];
        $customSrcImg = empty($this->params['customSrcImg'])?'':$this->params['customSrcImg'];
        $error = Warecorp_Video_Abstract::getEmbedData(&$source, &$customSrc, &$customSrcImg);
        if (!empty($error)) $form->addCustomErrorMessage($error);
            else $embed = true;
    }
    $form->addRule('title', 'required', Warecorp::t('Enter please title'));

    if ( $form->validate($this->params) ) {
        $video->setTitle($this->params["title"]);
        $video->setDescription($this->params["description"]);
        if ($embed && ($video->getSource() !== Warecorp_Video_Enum_VideoSource::OWN)) {
            $video->setCustomSrc($customSrc);
            $video->setCustomSrcImg($customSrcImg);
            $video->setSource($source);
        } elseif ($video->getSource() !== Warecorp_Video_Enum_VideoSource::OWN && !empty($this->params['customSrcImg'])) {
            $video->setCustomSrcImg($this->params['customSrcImg']);
        }
        $video->save();
        $video->deleteTags();
        $video->addTags($this->params['tags']);

        if (SINGLEVIDEOMODE) {
            $gallery->setTitle($this->params["title"]);
            $gallery->setPrivate((isset($this->params["isPrivate"]) && $this->params["isPrivate"] == 0) ? 0 : 1);
            $gallery->save();
        }
        $objResponse->addScript('popup_window.close();');
        $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentUser->getUserPath('videogalleryView/id').$video->getId().'/');
    } else {
        $video->setTitle($this->params["title"]);
        $video->setDescription($this->params["description"]);
        if (SINGLEVIDEOMODE) {
            $gallery->setPrivate((isset($this->params["isPrivate"]) && $this->params["isPrivate"] == 0) ? 0 : 1);
        }
        $tags_str = $this->params['tags'];

        $this->view->form = $form;
        $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
        $this->view->gallery = $gallery;
        $this->view->video = $video;
        $this->view->videoTags = $tags_str;
        $this->view->JsApplication = $this->params['JsApplication'];
        $content = $this->view->getContents('users/videogallery/'.VIDEOMODEFOLDER.'xajax.edit.video.tpl');
        $objResponse->addAssign('editPhotoPanelContent', 'innerHTML', $content);
        $objResponse->addScript("tinyMCE.execCommand('mceAddControl', true, 'videoDescription');");
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}

$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;
