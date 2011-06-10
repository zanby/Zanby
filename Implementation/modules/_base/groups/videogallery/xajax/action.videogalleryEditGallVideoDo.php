<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryEditGallVideoDo.php.xml');

$objResponse = new xajaxResponse();

$gallery = Warecorp_Video_Gallery_Factory::loadById($this->params['gallery_id']);
$video = Warecorp_Video_Factory::loadById($this->params['video_id']);

if ( $gallery->getId() !== null &&
     $video->getId() !== null &&
     Warecorp_Video_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentGroup, $this->_page->_user) &&
     $video->getSource() != Warecorp_Video_Enum_VideoSource::NONVIDEO) {

    $form = new Warecorp_Form('editPhotoForm'.$video->getId(), 'post', $this->currentGroup->getGroupPath('videogalleryEditGallVideoDo'));
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
    if (isset($this->params['_wf__editPhotoForm'])) $_REQUEST['_wf__editPhotoForm'.$video->getId()] = '1';
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

        $this->view->gallery = $gallery;
        $this->view->video = $video;
        $this->view->sourceEnum = Warecorp_Video_Enum_VideoSource::getInstance();
        if (SINGLEVIDEOMODE) {
            $gallery->setTitle($this->params["title"]);
            if (!empty($this->params['owner'])) {
                $group = Warecorp_Group_Factory::loadById(intval($this->params['owner']));
                if (!empty($group) && $group->getId() == $this->currentGroup->getId() && Warecorp_Video_AccessManager_Factory::create()->canCreateGallery($group, $this->_page->_user)) {
                    $gallery->setOwnerId($this->currentGroup->getId());
                }
            }
            $gallery->setPrivate((isset($this->params["isPrivate"]) && $this->params["isPrivate"] == 0) ? 0 : 1);
            $gallery->save();
            if ( FACEBOOK_USED ) {
                $paramsFB = array(
                    'title' => htmlspecialchars($gallery->getTitle()),
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );
                $action_links[] = array('text' => 'View Gallery', 'href' => $this->currentGroup->getGroupPath('videogalleryView/id').$gallery->getId()."/");
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_PHOTO, $paramsFB);
                Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
            }
            $this->view->form = $form;
            $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
            $content = $this->view->getContents('groups/videogallery/'.VIDEOMODEFOLDER.'template.edit.video.edit.tpl');
            $objResponse->addRedirect($this->currentGroup->getGroupPath('videos'));
        }else{
            $content = $this->view->getContents('groups/videogallery/template.edit.video.view.tpl');
        }
        $objResponse->addAssign('photoContent'.$video->getId(), 'innerHTML', $content);
        $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));

        /** Send notification to host **/
        $this->currentGroup->sendNewDataIsUploaded( $this->_page->_user, $gallery, "VIDEO", "CHANGES", false, array($video->getTitle()) );
        
//        if ($this->currentGroup->getPrivileges()->getSendEmail()) {
//            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_NEW_DATA_IS_UPLOADED');
//            $mail->setHeader('Sender', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->currentGroup->getName()).'" <'.$this->currentGroup->getGroupEmail().'>');
//            $mail->setSender($this->currentGroup);
//            $mail->addRecipient($this->currentGroup->getHost());
//            $mail->addParam('Group', $this->currentGroup);
//            $mail->addParam('action', "CHANGES");
//            $mail->addParam('section', "VIDEO");
//            $mail->addParam('chObject', $gallery);
//            $mail->addParam('User', $this->_page->_user);
//            $mail->addParam('isPlural', false);
//            $mail->addParam('items', array($video->getTitle()));
//            $mail->sendToPMB(true);
//            $mail->send();
//        }
        /** --- **/

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
        $this->view->AccessManager = Warecorp_Video_AccessManager_Factory::create();
        $content = $this->view->getContents('groups/videogallery/'.VIDEOMODEFOLDER.'template.edit.video.edit.tpl');
        $objResponse->addAssign('photoContent'.$video->getId(), 'innerHTML', $content);
        $objResponse->addScript("tinyMCE.execCommand('mceAddControl', true, 'videoDescription".$video->getId()."');");
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}

$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;
