<?php
Warecorp::addTranslation("/modules/users/contentblocks/action.getblockcontent.php.xml");

$objResponse = new xajaxResponse();

/* CO, CO Themes, CO Layout*/
$cfgCOLayout = Warecorp_Config_Loader::getInstance()->getAppConfig('COLT/cfg.layout.xml')->{'layout'};

if ($targetId == "ddTarget1") {

    $blockColumn = $cfgCOLayout->target1_template_for_co;
    //$blockColumn = "narrow";
}
if ($targetId == "ddTarget2") {

    $blockColumn = $cfgCOLayout->target2_template_for_co;
    //$blockColumn = "wide";
}

if (empty($blockColumn) || ! in_array($blockColumn, array(
    'narrow' ,
    'wide'))) {
    $blockColumn = $cfgCOLayout->default_template_for_co;
}

$script = '';
$content = '';
$dateObj = new Zend_Date();
$dateObj->setTimezone($this->_page->_user->getTimezone());
$this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
$this->view->cloneId = $CloneElID;

$defHeadline = "<strong>" . Warecorp::t('This is the default headline') . "</strong>";
$smarty_vars = array();

switch ($ContentType) {
    //-------------------------------------------------------------------------------------
    /*case 'ddMogulus':
     $_width = (($blockColumn == 'wide')?407:157);
     $_height = (($blockColumn == 'wide')?407:157);

     $script  = "document.getElementById('light_".$CloneElID."').innerHTML='';";
     $script .= 'document.oldWriteFunction = document.write;';
     $script .= "document.write = function(str){document.getElementById('light_".$CloneElID."').innerHTML += str;};";
     $script .= "tmpScript= document.createElement('script');";
     $script .= "tmpScript.src = 'http://www.mogulus.com/scripts/playerv2.js?channel=theuptake&layout=playerEmbedDefault&backgroundColor=0xeff4f9&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=1&paddingRight=3&paddingTop=6&paddingBottom=1&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=false&embedEnabled=true&chatEnabled=false&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=false&initialIsMute=false&initialVolume=4&width=".$_width."&height=".$_height."&wmode=window';";
     $script .= "tmpScript.onload = function(){document.write = document.oldWriteFunction;};";
     $script .= "document.getElementById('light_".$CloneElID."').appendChild(tmpScript);";

     $content = $this->view->getContents('content_objects/ddMogulus/preview_mode_' . $blockColumn . '.tpl');

    break;
    //-------------------------------------------------------------------------------------
    case 'ddIframe':
    $content = $this->view->getContents('content_objects/ddIframe/preview_mode_' . $blockColumn . '.tpl');

    break;      */


    //-------------------------------------------------------------------------------------
    case 'ddScript':

     if (! isset($Data['Data'])) {
        $Data['Data'] = array();
        $Data['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
        $Data['Data']['custom_height'] = 0;
     }

     if (empty($Data['Data']['custom_height'])) $Data['Data']['custom_height'] = 0;

     if ( empty($Data['Data']['jscontent']) || empty($editMode)) {
         $filename = SCRIPTING_UPLOAD_PATH."/".md5('user').md5($this->currentUser->getId()).$Data['Data']['unique_code'].'.dat';
         if (file_exists($filename) && filesize($filename)>0 ) {
             $handle = fopen($filename, "a+");
             $contents = fread($handle, filesize($filename));
             fclose($handle);
             $fileurl = SCRIPTING_UPLOAD_URL."/".md5('user').md5($this->currentUser->getId()).$Data['Data']['unique_code'].'.html';
         } else {
            $contents = '';
            $fileurl = '';
         }
    } else {
            $contents = $Data['Data']['alt_src'];
            $fileurl = '';
    }

        $Data['Data']['alt_src'] = $contents;
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headlineAbsent = true;";
        $script .= "ddContentObj.altSrc = '" . str_replace(array("\\","'","\n","\r"),array("\\\\","\'","",""),$contents) . "';";
        $script .= "ddContentObj.uniqueCode = '".$Data['Data']['unique_code']."';";
        $script .= "ddContentObj.customHeight = '".(empty($Data['Data']['custom_height'])?0:intval($Data['Data']['custom_height']))."';";

        $this->view->cloneId = $CloneElID;
        $this->view->assign($Data['Data']);
        $this->view->contents = $contents;

     if (! empty($editMode)) {
         $content = $this->view->getContents('content_objects/ddScript/compose_mode_edit_' . $blockColumn . '.tpl');
     } else {
         $this->view->fileurl = $fileurl;
         $content = $this->view->getContents('content_objects/ddScript/preview_mode_' . $blockColumn . '.tpl');
    }

    break;
    //-------------------------------------------------------------------------------------

    case 'ddImage':
        $avatar_id = (isset($Data['Data']['avatarId']) ? intval($Data['Data']['avatarId']) : 0);
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.avatarId = " . $avatar_id . ";";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['avatarId'] = 0;
        }
        if (Warecorp_Photo_Standard::isPhotoExists($avatar_id)) {
            $currentImage = Warecorp_Photo_Factory::loadById($avatar_id);
            if (! Warecorp_Photo_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentUser, $this->_page->_user)) {
                $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentUser);
            }
        } else {
            $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentUser);
        }
        $this->view->currentImage = $currentImage;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddImage/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddImage/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddImage/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyPhotos':// print $Data['Data']['gallery_show_as_icons'];
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.galleryCount = '" . (isset($Data['Data']['gallery_count']) ? $Data['Data']['gallery_count'] : 3) . "';";
        $script .= "ddContentObj.galleryType = '" . (isset($Data['Data']['gallery_type']) ? $Data['Data']['gallery_type'] : 1) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.galleryShowAsIcons = '" . (isset($Data['Data']['gallery_show_as_icons']) ? $Data['Data']['gallery_show_as_icons'] : 0) . "';";
        $script .= "ddContentObj.galleryShowThumbnailsCount = '" . (isset($Data['Data']['gallery_show_thumbnails_count']) ? $Data['Data']['gallery_show_thumbnails_count'] : 20) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['gallery_count'] = 3;
            $Data['Data']['gallery_type'] = 1;
            $Data['Data']['gallery_show_as_icons'] = 0;
            $Data['Data']['gallery_show_thumbnails_count'] = 20;
            $Data['Data']['headline'] = $defHeadline;
        }
        for ($i = 0; $i < $Data['Data']['gallery_count']; $i ++) {
            $script .= "ddContentObj.galleries[" . $i . "] = '" . (isset($Data['Data']['galleries'][$i]) ? $Data['Data']['galleries'][$i] : 0) . "';";
        }
        $gallery_hash = array();
        $thumbnails = array();
        if ($Data['Data']['gallery_type'] == 1) {
            $galleries = $this->currentUser->getGalleries()->setPrivacy(0)->setSharingMode('own')->getList();
            $_gall_num = 0;
            for ($i = 0; $i < $Data['Data']['gallery_count']; $i ++) {
                if (empty($galleries)) {
                    $gallery_hash[$i] = NULL;
                } else if ($_gall_num > count($galleries) - 1) {
                    $gallery_hash[$i] = NULL;
                } else {
                    $gallery_hash[$i] = $galleries[$_gall_num];
                    $_gall_num ++;
                    //opt
                    if (!empty($Data['Data']['gallery_show_as_icons'])){
                        //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                        $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                        foreach ($thumbs as &$_tv) {
                            $thumbnails[] = $_tv;
                        }
                    }
                    //opt
                }
            }
        } else {
            for ($i = 0; $i < $Data['Data']['gallery_count']; $i ++) {
                if (! empty($Data['Data']['galleries'][$i]) && Warecorp_Photo_Gallery_Abstract::isGalleryExists($Data['Data']['galleries'][$i])) {
                    $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::loadById($Data['Data']['galleries'][$i]);
                    //opt
                    if (!empty($Data['Data']['gallery_show_as_icons'])){
                        $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                        //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                        foreach ($thumbs as &$_tv) {
                            $thumbnails[] = $_tv;
                        }
                    }
                    //opt

                } else {
                    $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::createByOwner($this->_page->_user);
                }
            }
        }
        // If show as icons selected - we make shuffled array with limit
        if (! empty($Data['Data']['gallery_show_thumbnails_count'])) {
            shuffle($thumbnails);
            while (count($thumbnails) > $Data['Data']['gallery_show_thumbnails_count']) {
                $_tmp = array_pop($thumbnails);
            }
        }
        foreach ($thumbnails as $_k => &$_v) {
            $_v = Warecorp_Photo_Factory :: loadById($_v);
        }

        Warecorp::loadSmartyPlugin('modifier.escape.php');

        foreach ($thumbnails as $_k => &$_v) {
            $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
            $created->setTimezone($this->_page->_user->getTimezone());
            $tooltip_text = '<b>' . $_v->getTitle() . '</b><br>' . $_v->getDescription() . '<br>'. Warecorp::t('Shared by %s on %s', array($_v->getCreator()->getLogin(), $created->toString('MMM d, hh:mm a zz')));
            $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddMyPhotos_' . $CloneElID . '_' . $_k . '", text:"' . smarty_modifier_escape($tooltip_text, 'javascript') . ' ", width:"250px"});';
        }
        $this->view->thumbnails = $thumbnails;
        if (empty($Data['Data']['gallery_show_as_icons'])) {
            foreach ($gallery_hash as $_k => &$_v) {
                if ( $_v ) {
                    $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
                    $created->setTimezone($this->_page->_user->getTimezone());
                    $tooltip_text = '<b>' . $_v->getTitle() . '</b><br>' . $_v->getDescription() . '<br>'. Warecorp::t('Shared by %s on %s', array($_v->getCreator()->getLogin(), $created->toString('MMM d, hh:mm a zz')));
                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddMyPhotosG_' . $CloneElID . '_' . $_k . '", text:"' . smarty_modifier_escape($tooltip_text, 'javascript') . ' ", width:"250px"});';
                }
            }
        }
        $this->view->gallery_hash = $gallery_hash;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyPhotos/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddMyPhotos/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyPhotos/light_block_' . $blockColumn . '.tpl');
        }
        break;

    //-------------------------------------------------------------------------------------
    case 'ddMyVideos':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.galleryCount = '" . (isset($Data['Data']['gallery_count']) ? $Data['Data']['gallery_count'] : 3) . "';";
        $script .= "ddContentObj.galleryType = '" . (isset($Data['Data']['gallery_type']) ? $Data['Data']['gallery_type'] : 1) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.galleryShowAsIcons = '" . (isset($Data['Data']['gallery_show_as_icons']) ? $Data['Data']['gallery_show_as_icons'] : 0) . "';";
        $script .= "ddContentObj.galleryShowThumbnailsCount = '" . (isset($Data['Data']['gallery_show_thumbnails_count']) ? $Data['Data']['gallery_show_thumbnails_count'] : 20) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['gallery_count'] = 3;
            $Data['Data']['gallery_type'] = 1;
            $Data['Data']['gallery_show_as_icons'] = 0;
            $Data['Data']['gallery_show_thumbnails_count'] = 20;
            $Data['Data']['headline'] = $defHeadline;
        }
        for ($i = 0; $i < $Data['Data']['gallery_count']; $i ++) {
            $script .= "ddContentObj.galleries[" . $i . "] = '" . (isset($Data['Data']['galleries'][$i]) ? $Data['Data']['galleries'][$i] : 0) . "';";
        }
        $gallery_hash = array();
        $thumbnails = array();
        if ($Data['Data']['gallery_type'] == 1) {
            $galleries = $this->currentUser->getVideoGalleries()->setPrivacy(0)->setSharingMode('own')->getList();
            $_gall_num = 0;
            for ($i = 0; $i < $Data['Data']['gallery_count']; $i ++) {
                if (empty($galleries)) {
                    $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::createByOwner($this->_page->_user);
                } else {
                    if ($_gall_num > count($galleries) - 1) {
                        $_gall_num = 0;
                    }
                    $gallery_hash[$i] = $galleries[$_gall_num];
                    $_gall_num ++;
                    //opt
                    if (!empty($Data['Data']['gallery_show_as_icons'])){
                        //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                        $thumbs = $gallery_hash[$i]->getVideos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                        foreach ($thumbs as &$_tv) {
                            $thumbnails[] = $_tv;
                        }
                    }
                    //opt
                }
            }
        } else {
            for ($i = 0; $i < $Data['Data']['gallery_count']; $i ++) {
                if (! empty($Data['Data']['galleries'][$i]) && Warecorp_Video_Gallery_Abstract::isGalleryExists($Data['Data']['galleries'][$i])) {
                    $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::loadById($Data['Data']['galleries'][$i]);
                    //opt
                    if (!empty($Data['Data']['gallery_show_as_icons'])){
                        $thumbs = $gallery_hash[$i]->getVideos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                        //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                        foreach ($thumbs as &$_tv) {
                            $thumbnails[] = $_tv;
                        }
                    }
                    //opt
                } else {
                    $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::createByOwner($this->_page->_user);
                }
            }
        }
        // If show as icons selected - we make shuffled array with limit
        if (! empty($Data['Data']['gallery_show_thumbnails_count'])) {
            shuffle($thumbnails);
            while (count($thumbnails) > $Data['Data']['gallery_show_thumbnails_count']) {
                $_tmp = array_pop($thumbnails);
            }
        }
        foreach ($thumbnails as $_k => &$_v) {
            $_v = Warecorp_Video_Factory :: loadById($_v);
        }
        foreach ($thumbnails as $_k => &$_v) {
            $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
            $created->setTimezone($this->_page->_user->getTimezone());
            $tooltip_text = '<b>' . $_v->getTitle() . '</b><br>' . $_v->getDescription() . '<br>'. Warecorp::t('Shared by %s on %s', array($_v->getCreator()->getLogin(), $created->toString('MMM d, hh:mm a zz')));
            $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddMyVideos_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"250px"});';
        }
        $this->view->thumbnails = $thumbnails;
        if (empty($Data['Data']['gallery_show_as_icons'])) {
            foreach ($gallery_hash as $_k => &$_v) {
                $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
                $created->setTimezone($this->_page->_user->getTimezone());
                $tooltip_text = '<b>' . $_v->getTitle() . '</b><br>' . $_v->getDescription() . '<br>'.Warecorp::t('Shared by %s on %s', array($_v->getCreator()->getLogin(), $created->toString('MMM d, hh:mm a zz')));
                $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddMyVideosG_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"250px"});';
            }
        }
        $this->view->gallery_hash = $gallery_hash;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyVideos/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddMyVideos/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyVideos/light_block_' . $blockColumn . '.tpl');
        }
        break;

    //-------------------------------------------------------------------------------------
    case 'ddPicture':
        $this->_page->_user->loadDefaultAvatar();
        $_tmp = $this->_page->_user->getAvatar()->getId();
        if (! empty($_tmp)) {
            $avatar_id = $this->_page->_user->getAvatar()->getId();
        } else {
            $avatar_id = 0;
        }
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.avatarId = " . $avatar_id . ";";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $this->view->currentAvatar = $this->_page->_user->getAvatar();
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->assign($Data['Data']);
        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddPicture/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddPicture/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddPicture/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddProfileDetails':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $userInfo = new Warecorp_User('id', $this->_page->_user->getId());
        $script .= "ddContentObj.hide[0] = '" . (isset($Data['Data']['hide'][0]) ? $Data['Data']['hide'][0] : 0) . "';";
        $script .= "ddContentObj.hide[1] = '" . (isset($Data['Data']['hide'][1]) ? $Data['Data']['hide'][1] : ($userInfo->getIsBirthdayPrivate() ? 1 : 0)) . "';";
        $script .= "ddContentObj.hide[2] = '" . (isset($Data['Data']['hide'][2]) ? $Data['Data']['hide'][2] : ($userInfo->getIsGenderPrivate() ? 1 : 0)) . "';";
        $script .= "ddContentObj.hide[3] = '" . (isset($Data['Data']['hide'][3]) ? $Data['Data']['hide'][3] : 0) . "';";
        $script .= "ddContentObj.hide[4] = '" . (isset($Data['Data']['hide'][4]) ? $Data['Data']['hide'][4] : 0) . "';";
        $script .= "ddContentObj.hide[5] = '" . (isset($Data['Data']['hide'][5]) ? $Data['Data']['hide'][5] : 0) . "';";
        $userInfo->setForceDbTags();
        $this->view->userInfo = $userInfo;
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
        }
        if (empty($Data['Data'])) {
            $Data['Data'] = array(
                'hide' => array());
            $Data['Data']['hide'][0] = 0;
            $Data['Data']['hide'][1] = ($userInfo->getIsBirthdayPrivate() ? 1 : 0);
            $Data['Data']['hide'][2] = ($userInfo->getIsGenderPrivate() ? 1 : 0);
            $Data['Data']['hide'][3] = 0;
            $Data['Data']['hide'][4] = 0;
            $Data['Data']['hide'][5] = 0;
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->assign($Data['Data']);

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddProfileDetails/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddProfileDetails/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddProfileDetails/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddProfileDetailsAT':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $userInfo = new Warecorp_User('id', $this->_page->_user->getId());
       // $_up = new AT_User_Profile();
        //$userInfoExtra = $userInfo->loadById ($this->_page->_user->getId());
        $script .= "ddContentObj.hide[0] = '" . (isset($Data['Data']['hide'][0]) ? $Data['Data']['hide'][0] : 0) . "';";
        $script .= "ddContentObj.hide[1] = '" . (isset($Data['Data']['hide'][1]) ? $Data['Data']['hide'][1] : ($userInfo->getIsBirthdayPrivate() ? 1 : 0)) . "';";
        $script .= "ddContentObj.hide[2] = '" . (isset($Data['Data']['hide'][2]) ? $Data['Data']['hide'][2] : ($userInfo->getIsGenderPrivate() ? 1 : 0)) . "';";
        $script .= "ddContentObj.hide[3] = '" . (isset($Data['Data']['hide'][3]) ? $Data['Data']['hide'][3] : 0) . "';";
        $script .= "ddContentObj.hide[4] = '" . (isset($Data['Data']['hide'][4]) ? $Data['Data']['hide'][4] : 0) . "';";
        $script .= "ddContentObj.hide[5] = '" . (isset($Data['Data']['hide'][5]) ? $Data['Data']['hide'][5] : 0) . "';";
        $script .= "ddContentObj.hide[6] = '" . (isset($Data['Data']['hide'][6]) ? $Data['Data']['hide'][6] : 0) . "';";
        $script .= "ddContentObj.hide[7] = '" . (isset($Data['Data']['hide'][7]) ? $Data['Data']['hide'][7] : 0) . "';";
        $script .= "ddContentObj.hide[8] = '" . (isset($Data['Data']['hide'][8]) ? $Data['Data']['hide'][8] : 0) . "';";
        $script .= "ddContentObj.hide[9] = '" . (isset($Data['Data']['hide'][9]) ? $Data['Data']['hide'][9] : 0) . "';";

        $this->view->userInfo = $userInfo;
       // $this->view->userInfoExtra = $userInfoExtra;

        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
        }
        if (empty($Data['Data'])) {
            $Data['Data'] = array(
                'hide' => array());
            $Data['Data']['hide'][0] = 0;
            $Data['Data']['hide'][1] = ($userInfo->getIsBirthdayPrivate() ? 1 : 0);
            $Data['Data']['hide'][2] = ($userInfo->getIsGenderPrivate() ? 1 : 0);
            $Data['Data']['hide'][3] = 0;
            $Data['Data']['hide'][4] = 0;
            $Data['Data']['hide'][5] = 0;
            $Data['Data']['hide'][6] = 0;
            $Data['Data']['hide'][7] = 0;
            $Data['Data']['hide'][8] = 0;
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->assign($Data['Data']);
        $this->view->userAffiliation = AT_User_Enum_UserAffiliation::getList();

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddProfileDetailsAT/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddProfileDetailsAT/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddProfileDetailsAT/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyVideoContentBlock':
        $video_id = (isset($Data['Data']['videoId']) ? intval($Data['Data']['videoId']) : 0);
        $content = (isset($Data['Data']['Content']) ? $Data['Data']['Content'] : '<p align="center">'. Warecorp::t('Click Edit button to change this text').'</p>');
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.innerText = '" . $content . "';";
        $script .= "ddContentObj.videoId = " . $video_id . ";";
        $script .= "tinyMCE.CloneElID = '" . $CloneElID . "';";
        //$script .= "ddContentObj.headlineAbsent = true;";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['innerText'] = $content;
            $Data['Data']['Content'] = $content;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['videoId'] = 0;
        }

        if (Warecorp_Video_Standard::isVideoExists($video_id)) {
            $currentImage = Warecorp_Video_Factory::loadById($video_id);
            if (! Warecorp_Video_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentUser, $this->_page->_user)) {
                $currentImage = Warecorp_Video_Factory::createByOwner($this->currentUser);
            }
        } else {
            $currentImage = Warecorp_Video_Factory::createByOwner($this->currentUser);
        }

        $this->view->video = $currentImage;

        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }
        $this->view->cloneId = $CloneElID;

        if (! empty($editMode)) {
            $content = $this->view->getContents('content_objects/ddMyVideoContentBlock/compose_mode_edit_' . $blockColumn . '.tpl');

       //     $script .= 'ddContentObj.mceEditorID2 = tinyMCEInit("tinyMCE_' . $CloneElID . '");';

            $theme = Warecorp_Theme::loadThemeFromDB($this->currentUser);
            $theme->prepareFonts();

            if (empty($Data['Style']['backgroundColor'])){
                if ($theme->fillColorTransparent){
                    $_tBackgroundColor = 'transparent';
                }else{
                    $_tBackgroundColor = $theme->fillColor;
                }
            }else{
                $_tBackgroundColor = $Data['Style']['backgroundColor'];
            }

            $_tTextColor = $theme->bodyTextColor;
            $_tFontStyle = $theme->bodyTextFontFamily;
            if ($theme->fillColorTransparent){
                $script .= 'ddContentObj.mceEditorBCD = "transparent";';
            }else{
                $script .= 'ddContentObj.mceEditorBCD = "'. $theme->fillColor .'";';
            }
            $script .= 'ddContentObj.mceEditorBC = "'. $_tBackgroundColor .'";';
    //        $script .= 'applyThemeToTinyMCE(ddContentObj.mceEditorID2, "'.$_tBackgroundColor.'" ,"'.$_tTextColor.'" ,"'.$_tFontStyle.'");';

    //        $script .= 'document.getElementById("tinyMCE_' . $CloneElID . '_div_wait").style.display = "none";';
    //        $script .= 'document.getElementById("tinyMCE_' . $CloneElID . '_div").style.display = "block";';

        } else {
            $content = $this->view->getContents('content_objects/ddMyVideoContentBlock/compose_mode_view_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyGroups':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.not_new_groups = " . (isset($Data['Data']['not_new_groups']) ? "[".join(',', $Data['Data']['not_new_groups'])."]" : "[]") . ";";
        $script .= "ddContentObj.auto_disp_simple = ". ((isset($Data['Data']['auto_disp_simple']) && $Data['Data']['auto_disp_simple']) ? 1 : 0 ) .";";
        $script .= "ddContentObj.auto_disp_family = ". ((isset($Data['Data']['auto_disp_family']) && $Data['Data']['auto_disp_family']) ? 1 : 0 ) .";";
        if (! isset($Data['Data'])) {
            $Data['Data']['headline'] = $defHeadline;
        }
        if ( !isset($Data['Data']['not_new_groups']) ) {
            $Data['Data']['not_new_groups'] = array();
            $Data['Data']['auto_disp_family'] = 0;
            $Data['Data']['auto_disp_simple'] = 0;
        }
        $groupsList = $this->_page->_user->getGroups()->setTypes('simple')->getList(); //getGroupsList(null,'simple');
        $familyGroupsList = $this->_page->_user->getGroups()->setTypes('family')->getList(); //getGroupsList(null,'family');
        //$first_time = isset($Data['Data']['unhide']) ? false : true;
        $groups_empty = true;
        $family_groups_empty = true;
        $gUids = 'ddContentObj.group_uids = [';
        foreach ($groupsList as $_k => &$_v) {
            if (! empty($Data['Data']['unhide'][$_v->getId()]) ||
                ( $Data['Data']['auto_disp_simple']  &&  !in_array($_v->getId(), $Data['Data']['not_new_groups']) )
            ) {
                $script .= "ddContentObj.unhide[" . $_k . "] = '" . $_v->getId() . "';";
                $Data['Data']['unhide'][$_v->getId()] = 1;
                $groups_empty = false;
            }
            $gUids .= "'{$_v->getId()}',";
        }
        $gUids = trim($gUids, ',').'];';
        $fUids = 'ddContentObj.family_uids = [';
        foreach ($familyGroupsList as $_k => &$_v) {
            if (! empty($Data['Data']['family_unhide'][$_v->getId()]) ||
                ( $Data['Data']['auto_disp_family']  &&  !in_array($_v->getId(), $Data['Data']['not_new_groups']) )
            ) {
                $script .= "ddContentObj.family_unhide[" . $_k . "] = '" . $_v->getId() . "';";
                $Data['Data']['family_unhide'][$_v->getId()] = 1;
                $family_groups_empty = false;
            }
            $fUids .= "'{$_v->getId()}',";
        }
        $fUids = trim($fUids, ',').'];';
        $script .= $gUids.$fUids;
        $this->view->groupsList = $groupsList;
        $this->view->familyGroupsList = $familyGroupsList;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
            $this->view->groups_empty = $groups_empty;
            $this->view->family_groups_empty = $family_groups_empty;
        }
        if (! empty($editMode)) {
            $content = $this->view->getContents('content_objects/ddMyGroups/compose_mode_edit_' . $blockColumn . '.tpl');
        } else {
            $content = $this->view->getContents('content_objects/ddMyGroups/compose_mode_view_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddRSSFeed':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.rssTitle = '" . (isset($Data['Data']['rss_title']) ? $Data['Data']['rss_title'] : '') . "';";
        $script .= "ddContentObj.rssUrl = '" . (isset($Data['Data']['rss_url']) ? $Data['Data']['rss_url'] : '') . "';";
        $script .= "ddContentObj.rssMaxLines = '" . (! empty($Data['Data']['rss_max_lines']) ? $Data['Data']['rss_max_lines'] : 5) . "';";
        $script .= "ddContentObj.rssView = '" . (! empty($Data['Data']['rss_view']) ? $Data['Data']['rss_view'] : 0) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        
        $script .= "ddContentObj.rssHeaderColor = '" . (isset($Data['Data']['rss_header_color']) ? $Data['Data']['rss_header_color'] : '') . "';";
        $script .= "ddContentObj.rssHeaderFont = '" . (isset($Data['Data']['rss_header_font']) ? $Data['Data']['rss_header_font'] : '') . "';";
        $script .= "ddContentObj.rssDescriptionFont = '" . (isset($Data['Data']['rss_description_font']) ? $Data['Data']['rss_description_font'] : '') . "';";
        $script .= "ddContentObj.rssDescriptionColor = '" . (isset($Data['Data']['rss_description_color']) ? $Data['Data']['rss_description_color'] : '') . "';";
        $script .= "ddContentObj.rssDescriptionFontSize = '" . (isset($Data['Data']['rss_description_font_size']) ? $Data['Data']['rss_description_font_size'] : '') . "';";
        $script .= "ddContentObj.rssHeaderFontSize = '" . (isset($Data['Data']['rss_header_font_size']) ? $Data['Data']['rss_header_font_size'] : '') . "';";

        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['rss_title'] = isset($Data['Data']['rss_title']) ? $Data['Data']['rss_title'] : '';
            $Data['Data']['rss_url'] = isset($Data['Data']['rss_url']) ? $Data['Data']['rss_url'] : '';
            $Data['Data']['rss_max_lines'] = (! empty($Data['Data']['rss_max_lines']) ? $Data['Data']['rss_max_lines'] : 5);
            $Data['Data']['rss_view'] = (! empty($Data['Data']['rss_view']) ? $Data['Data']['rss_view'] : 0);
            $Data['Data']['headline'] = $defHeadline;
        }
        
        $Data['Data']['rss_header_color'] = (! empty($Data['Data']['rss_header_color']) ? $Data['Data']['rss_header_color'] : '');
        $Data['Data']['rss_header_font'] = (! empty($Data['Data']['rss_header_font']) ? $Data['Data']['rss_header_font'] : '');
        $Data['Data']['rss_description_color'] = (! empty($Data['Data']['rss_description_color']) ? $Data['Data']['rss_description_color'] : '');
        $Data['Data']['rss_description_font'] = (! empty($Data['Data']['rss_description_font']) ? $Data['Data']['rss_description_font'] : '');
        $Data['Data']['rss_header_font_size'] = (! empty($Data['Data']['rss_header_font_size']) ? $Data['Data']['rss_header_font_size'] : '');
        $Data['Data']['rss_description_font_size'] = (! empty($Data['Data']['rss_description_font_size']) ? $Data['Data']['rss_description_font_size'] : '');
        
        $this->view->assign($Data['Data']);

 if (false && strpos($Data['Data']['rss_url'],BASE_HTTP_HOST)>0){
    $this->view->isInternalFeed = 1;
 } elseif (empty($editMode)) {
 			$item = $Data;
              	$item['Data']['rss_url'] = str_replace(array('feed://','feed:http'), array('http://','http'), $item['Data']['rss_url']);
                $smarty_vars["title"] = empty($item['Data']['rss_title']) ? '' : $item['Data']['rss_title'];

                try {$feed = Warecorp_Feed_Reader::import($item['Data']['rss_url']);
                } catch (Exception $e) {
                    $feed = array();
                }

                if (is_object($feed)) {

                    $smarty_vars["title"] = empty($item['Data']['rss_title']) ? $feed->getTitle() : $item['Data']['rss_title'];

                    $rss_hash = array();
                    $count = 0;
                    foreach ($feed as $feeditem) {

                        $count++;
                        if ($count>intval($item['Data']['rss_max_lines'])) break;
                        
                        $record["href"] = $feeditem->getLink();
                        $record["title"] = $feeditem->getTitle();
                        $record["description"] = $feeditem->getDescription();
                        $record["content"] = ($item['Data']['rss_view']>0)?$feeditem->getContent():'';

                        if ($item['Data']['rss_view'] == 2)
                        {
                            $record["description"] = urldecode($record["description"]);
                            $pattern = "/\<img[^\>]*\>/i";
                            $record["description"] = preg_replace($pattern, '', $record["description"]);

                            $pattern = "/\<object.*object\>/ims";
                            $record["description"] = preg_replace($pattern, '', $record["description"]);
                        }

                        Zend_Registry::set("_temporaryCORSSWidth", 0);
                       // Zend_Registry::set("_temporaryCORSSCol", $content_item['template_type']);

                        if (is_string($record["description"])) {
                            $pattern = "/(\<object)([^\>]*)(width=\")([0-9]*)(\")([^\>]*)(\>)/i";
                            $record["description"] = preg_replace_callback($pattern, 'Warecorp_CO_Content::prcw', $record["description"]);
                            $pattern = "/(\<object)([^\>]*)(height=\")([0-9]*)(\")([^\>]*)(\>)/i";
                            $record["description"] = preg_replace_callback($pattern, 'Warecorp_CO_Content::prch', $record["description"]);
                        }

                        $rss_hash[] = $record;
                    }
                    $smarty_vars["rss_hash"] = $rss_hash;
                }
    }

    $this->view->assign($smarty_vars);

    if (! empty($editMode)) {
        $content = $this->view->getContents('content_objects/ddRSSFeed/compose_mode_edit_' . $blockColumn . '.tpl');
    } else {
        $content = $this->view->getContents('content_objects/ddRSSFeed/compose_mode_view_' . $blockColumn . '.tpl');
    }
    break;
    //-------------------------------------------------------------------------------------
    case 'ddMyDocuments':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['headline'] = $defHeadline;
            $script .= "ddContentObj.documents = new Array(); ddContentObj.documents[0] = '0';";
            $lvars = array();
            $documents_ids_tpl = array();
            $documents_ids_tpl[] = 0;
            $lvars['documents_ids'] = $documents_ids_tpl;
            $this->view->assign($lvars);
        } else {
            //$script .= "documentsObj.documentsHeadline = '" . (isset($Data['Data']['documents_headline']) ? $Data['Data']['documents_headline'] : 'Default Documents Headline') . "';";
            $lvars = array();
            $documents_ids_tpl = array();
            $documents_ids_tpl = $Data['Data']['items'];
            $lvars['documents_ids'] = $documents_ids_tpl;
            $lvars['headline'] = (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline);
            $lvars['documents_objects'] = array();

            foreach ($lvars['documents_ids'] as $_k => &$_v) {
                //$tooltip_text = "Click to select document";
                //$script .= 'ddContentObj.documents[' . $_k . '] = ' . $_v . ';';
                if (! empty($_v) && Warecorp_Document_Item::isDocumentExists($_v)) {
                    $tooltip_text = Warecorp::t("Click to select document");
                    $script .= 'ddContentObj.documents[' . $_k . '] = ' . $_v . ';';

                    $_doc = new Warecorp_Document_Item($_v);
                    if (Warecorp_Document_AccessManager_Factory::create()->canViewDocument($_doc, $this->currentUser, $this->_page->_user)) {
                        $lvars['documents_objects'][$_k] = $_doc;
                        $string = $lvars['documents_objects'][$_k]->getDescription();
                        $length = 50;
                        $etc = '...';
                        if (strlen($string) > $length) {
                            $length -= strlen($etc);
                            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
                            $string = substr($string, 0, $length) . $etc;
                        }
                        $tooltip_text = $lvars['documents_objects'][$_k]->getOriginalName() . '<br>' . $lvars['documents_objects'][$_k]->getFileSize() . ' | ' . $lvars['documents_objects'][$_k]->getFileExt() . '<br />' . (empty($string) ? '' : $string . '<br />') . '<br />'. Warecorp::t('Created by <a href=\"%s/\">%s</a> on %s', array($lvars['documents_objects'][$_k]->getCreator()->getUserPath('profile'), $lvars['documents_objects'][$_k]->getCreator()->getLogin(), $lvars['documents_objects'][$_k]->getCreationDate()));
                    } else {
                        unset($lvars['documents_ids'][$_k]);
                    }

                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, width:"350px", context:"document_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' "});';

                } else {
                    if (empty($editMode)) {
                        unset ($lvars['documents_ids'][$_k]);
                    } else {
                        $tooltip_text = "Click to select document";
                        $script .= 'ddContentObj.documents[' . $_k . '] = ' . $_v . ';';
                        $lvars['documents_ids'][$_k] = 0;
                        $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, width:"200px", context:"document_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' "});';
                    }
                }
                //$script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, width:"350px", context:"document_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' "});';
            }

            $this->view->assign($lvars);
            $this->view->currentUser = $this->currentUser;
        }
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyDocuments/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $this->view->disable_click = true;
                $content = $this->view->getContents('content_objects/ddMyDocuments/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyDocuments/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyLists':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.listDisplayType = '" . (isset($Data['Data']['list_display_type']) ? $Data['Data']['list_display_type'] : 0) . "';";
        $script .= "ddContentObj.listToDisplay = '" . (isset($Data['Data']['list_to_display']) ? $Data['Data']['list_to_display'] : 0) . "';";
        $script .= "ddContentObj.listDefaultIndexSort = '" . (isset($Data['Data']['list_default_index_sort']) ? $Data['Data']['list_default_index_sort'] : 0) . "';";
        $script .= "ddContentObj.listDefaultSort = '" . (isset($Data['Data']['list_default_sort']) ? $Data['Data']['list_default_sort'] : 0) . "';";
        $script .= "ddContentObj.listDisplayNumberInEachCategory = '" . (isset($Data['Data']['list_display_number_in_each_category']) ? $Data['Data']['list_display_number_in_each_category'] : 3) . "';";
        $script .= "ddContentObj.listShowSummaries = '" . (isset($Data['Data']['list_show_summaries']) ? $Data['Data']['list_show_summaries'] : 1) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.listCategoriesToDisplay = new Array();";
        $displayCategories = array();
        if (! empty($Data['Data']['list_categories_to_display'])) {
            foreach ($Data['Data']['list_categories_to_display'] as &$_v) {
                $script .= "ddContentObj.listCategoriesToDisplay[ddContentObj.listCategoriesToDisplay.length] = " . $_v . ";";
                $displayCategories[$_v] = 1;
            }
        }
        $listsCategories = Warecorp_List_Item::getListTypesListAssoc();
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['list_categories_to_display'] = array();
            $Data['Data']['list_display_type'] = 0;
            $Data['Data']['list_to_display'] = 0;
            $Data['Data']['list_default_index_sort'] = 1;
            $Data['Data']['list_default_sort'] = 1;
            $Data['Data']['list_display_number_in_each_category'] = 3;
            $Data['Data']['list_show_summaries'] = 1;
            $Data['Data']['headline'] = $defHeadline;
        }
        if (! empty($Data['Data']['list_to_display']) && ! empty($Data['Data']['list_display_type'])) {
            $this->view->currentList = new Warecorp_List_Item($Data['Data']['list_to_display']);
        }
        $this->view->assign('aSort', array(
            1 => 'rankdesc' ,
            2 => 'createdesc' ,
            3 => 'createasc'));
        $this->view->listsCategories = $listsCategories;
        $this->view->displayCategories = $displayCategories;
        $this->view->assign($Data['Data']);
        $list = new Warecorp_List_List($this->currentUser);
        $this->view->listsList = $list;

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyLists/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddMyLists/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyLists/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyFriends':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.displayType = '" . (isset($Data['Data']['display_type']) ? $Data['Data']['display_type'] : 0) . "';";
        $script .= "ddContentObj.defaultIndexSort = '" . (isset($Data['Data']['default_index_sort']) ? $Data['Data']['default_index_sort'] : 2) . "';";
        $script .= "ddContentObj.displayNumberInEachRegion = '" . (isset($Data['Data']['display_number_in_each_region']) ? $Data['Data']['display_number_in_each_region'] : 3) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['display_type'] = 0;
            $Data['Data']['default_index_sort'] = 2;
            $Data['Data']['display_number_in_each_region'] = 3;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['hide'] = array();
        }
        $this->view->assign($Data['Data']);
        if ($Data['Data']['default_index_sort'] == 1) {
            $friends = $this->_page->_user->getFriendsList()->setOrder('created DESC')->getList();
            $countriesList = array();
            $result = array();
            foreach ($friends as &$friend) {
                if (! isset($result[$friend->getFriend()->getCity()->getState()->getCountry()->name]) || count($result[$friend->getFriend()->getCity()->getState()->getCountry()->name]) < $Data['Data']['display_number_in_each_region']) {
                    $result[$friend->getFriend()->getCity()->getState()->getCountry()->name][] = $friend;
                    $countriesList[$friend->getFriend()->getCity()->getState()->getCountry()->id] = $friend->getFriend()->getCity()->getState()->getCountry()->name;
                }
            }
            $counter = 0;
            foreach ($countriesList as $_k => &$_v) {
                if (! empty($Data['Data']['hide'][$_k])) {
                    $script .= "ddContentObj.hide[" . $counter . "] = '" . $_k . "';";
                    $Data['Data']['hide'][$_k] = 1;
                } else {
                    $script .= "ddContentObj.hide[" . $counter . "] = '0';";
                    $Data['Data']['hide'][$_k] = 0;
                }
                $counter ++;
            }
            $this->view->countriesList = $countriesList;
            $this->view->friendsSortedByCountry = $result;
            foreach ($result as &$_country) {
                foreach ($_country as &$_v) {
                    $created = new Zend_Date($_v->getFriend()->getRegisterDate(), Zend_Date::ISO_8601, 'en');
                    $created->setTimezone($this->_page->_user->getTimezone());
                    $tooltip_text = '<b>' . $_v->getFriend()->getLogin() . '</b><br>' . $_v->getFriend()->getCity()->name . '&nbsp;' . $_v->getFriend()->getCity()->getState()->name . '<br>'. Warecorp::t('Member since %s', array($created->toString('MMM d, hh:mm a zz')));
                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_v->getFriend()->getId() . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_v->getFriend()->getId() . '", {hidedelay:100, context:"friends_' . $CloneElID . '_' . $_v->getFriend()->getId() . '", text:"' . $tooltip_text . ' ", width:"220px"});';
                }
            }
        } else {
            $friends = $this->_page->_user->getFriendsList()->setListSize($Data['Data']['display_number_in_each_region'])->setCurrentPage(1)->setOrder('created DESC')->getList();
            foreach ($friends as $_k => &$_v) {
                $created = new Zend_Date($_v->getFriend()->getRegisterDate(), Zend_Date::ISO_8601, 'en');
                $created->setTimezone($this->_page->_user->getTimezone());
                $tooltip_text = '<b>' . $_v->getFriend()->getLogin() . '</b><br>' . $_v->getFriend()->getCity()->name . '&nbsp;' . $_v->getFriend()->getCity()->getState()->name . '<br>' .Warecorp::t('Member since %s', array($created->toString('MMM d, hh:mm a zz')));
                $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_v->getFriend()->getId() . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_v->getFriend()->getId() . '", {hidedelay:100, context:"friends_' . $CloneElID . '_' . $_v->getFriend()->getId() . '", text:"' . $tooltip_text . ' ", width:"220px"});';
            }
        }
        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyFriends/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddMyFriends/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyFriends/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddContentBlock':
        $content = (isset($Data['Data']['Content']) ? $Data['Data']['Content'] : '<p align="center">'. Warecorp::t('Click Edit button to change this text'). '</p>');
        $content = str_replace("'","\'",$content);
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.innerText = '" . $content . "';";
        $script .= "ddContentObj.headlineAbsent = true;";
        $script .= "tinyMCE.CloneElID = '" . $CloneElID . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['innerText'] = $content;
            $Data['Data']['Content'] = $content;
        }
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }
        if (! empty($editMode)) {
            $content = $this->view->getContents('content_objects/ddContentBlock/compose_mode_edit_' . $blockColumn . '.tpl');
            $script .= 'ddContentObj.mceEditorID = tinyMCEInit("tinyMCE_' . $CloneElID . '");';

            $theme = Warecorp_Theme::loadThemeFromDB($this->currentUser);
            $theme->prepareFonts();

            if (empty($Data['Style']['backgroundColor'])){
                if ($theme->fillColorTransparent){
                    $_tBackgroundColor = 'transparent';
                }else{
                    $_tBackgroundColor = $theme->fillColor;
                }
            }else{
                $_tBackgroundColor = $Data['Style']['backgroundColor'];
            }

            $_tTextColor = $theme->bodyTextColor;
            $_tFontStyle = $theme->bodyTextFontFamily;
            if ($theme->fillColorTransparent){
                $script .= 'ddContentObj.mceEditorBCD = "transparent";';
            }else{
                $script .= 'ddContentObj.mceEditorBCD = "'. $theme->fillColor .'";';
            }
            $script .= 'ddContentObj.mceEditorBC = "'. $_tBackgroundColor .'";';
            $script .= 'applyThemeToTinyMCE(ddContentObj.mceEditorID, "'.$_tBackgroundColor.'" ,"'.$_tTextColor.'" ,"'.$_tFontStyle.'");';

            $script .= 'document.getElementById("tinyMCE_' . $CloneElID . '_div_wait").style.display = "none";';
            $script .= 'document.getElementById("tinyMCE_' . $CloneElID . '_div").style.display = "block";';
        } else {
            $content = $this->view->getContents('content_objects/ddContentBlock/compose_mode_view_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyDiscussions':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.discussions_show_thread_summaries = '" . (isset($Data['Data']['discussionsShowThreadSummaries']) ? $Data['Data']['discussionsShowThreadSummaries'] : 0) . "';";
        $script .= "ddContentObj.discussions_show_thread_summaries2 = '" . (isset($Data['Data']['discussionsShowThreadSummaries2']) ? $Data['Data']['discussionsShowThreadSummaries2'] : 0) . "';";
        $script .= "ddContentObj.discussions_display_most_active = '" . (isset($Data['Data']['discussionsDisplayMostActive']) ? $Data['Data']['discussionsDisplayMostActive'] : 1) . "';";
        $script .= "ddContentObj.discussions_display_most_recent = '" . (isset($Data['Data']['discussionsDisplayMostRecent']) ? $Data['Data']['discussionsDisplayMostRecent'] : 1) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.discussions_show_threads_number = '" . (isset($Data['Data']['discussionsShowThreadsNumber']) ? $Data['Data']['discussionsShowThreadsNumber'] : 0) . "';";
        $script .= "ddContentObj.discussions_threads = new Array();";
        if (! empty($Data['Data']['discussionsThreads'])) {
            foreach ($Data['Data']['discussionsThreads'] as $_k => &$_v) {
                if ( is_array($_v) ) {
                    $script .= "ddContentObj.discussions_threads[" . $_k . "] = new Array();";
                    //discussion
                    $script .= "ddContentObj.discussions_threads[" . $_k . "][0] = " . $_v[0] . ";";
                    //topic
                    $script .= "ddContentObj.discussions_threads[" . $_k . "][1] = " . $_v[1] . ";";
                }
            }
        } else {// $script .= "familyDiscussionsObj.discussions_threads[0][0] = '0';";
// $script .= "familyDiscussionsObj.discussions_threads[0][1] = '0';";
        }
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['discussionsThreads'] = array(
                0 => '0' ,
                1 => '0');
            $Data['Data']['discussionsShowThreadSummaries'] = 0;
            $Data['Data']['discussionsShowThreadSummaries2'] = 0;
            $Data['Data']['discussionsDisplayMostActive'] = 1;
            $Data['Data']['discussionsDisplayMostRecent'] = 1;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['discussionsShowThreadsNumber'] = 0;
        }
        $discussions = array();
        $discussionList = new Warecorp_DiscussionServer_DiscussionList();
        $groupsList = $this->currentUser->getGroups()->/*setTypes(array(
            'family' ,
            'simple'))->*/getList();
        //print_r($groupsList);
        foreach ($groupsList as &$_v) {
            $tmpList = $discussionList->findByGroupId($_v->getId());
            foreach ($tmpList as &$_vv) {
                $discussions[] = $_vv;
            }
        }
        $gropNames = array();
        foreach ($discussions as &$_discussion) {
            $_group = Warecorp_Group_Factory::loadById($_discussion->getGroupId());
            //not empty discussions
            if ($_discussion->hasTopics()) {
                $_discussion->setGroup($_group);
                $gropNames[$_discussion->getGroupId()] = $_group->getName();
            }
        }
        $this->view->groupNames = $gropNames;
        $this->view->discussionList = $discussions;
        $this->view->assign($Data['Data']);

        //if tabs present
        if (! empty($Data['Data']['discussionsShowThreadsNumber']) && (! empty($Data['Data']['discussionsDisplayMostActive']) || ! empty($Data['Data']['discussionsDisplayMostRecent']))) {
            $topicsList = new Warecorp_DiscussionServer_TopicList();
            $this->view->topicsList = $topicsList;
            $fGroupsList = array();
            $fGroupsList = array_keys($this->currentUser->getGroups()->returnAsAssoc(true)->getList());
            //print_r($fGroupsList);die;
            $this->view->fGroupsList = $fGroupsList;
        }
        $this->view->gFactory = new Warecorp_Group_Factory;

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyDiscussions/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddMyDiscussions/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyDiscussions/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddMyEvents':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.eventDisplayStyle = '" . (isset($Data['Data']['event_display_style']) ? $Data['Data']['event_display_style'] : 1) . "';";
        $script .= "ddContentObj.eventsFuteredDisplayNumber = '" . (isset($Data['Data']['events_futered_display_number']) ? $Data['Data']['events_futered_display_number'] : 1) . "';";
        $script .= "ddContentObj.eventsDisplayNumber = '" . (isset($Data['Data']['events_display_number']) ? $Data['Data']['events_display_number'] : 3) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.eventsShowSummaries = '" . (isset($Data['Data']['events_show_summaries']) ? $Data['Data']['events_show_summaries'] : 0) . "';";
        $script .= "ddContentObj.eventsShowCalendar = '" . (isset($Data['Data']['events_show_calendar']) ? $Data['Data']['events_show_calendar'] : 1) . "';";
        $script .= "ddContentObj.eventsShowVenues = '" . (isset($Data['Data']['events_show_venues']) ? $Data['Data']['events_show_venues'] : 0) . "';";
        $script .= "ddContentObj.eventsThreads = new Array();";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['event_display_style'] = 1;
            $Data['Data']['events_futered_display_number'] = 1;
            $Data['Data']['events_display_number'] = 3;
            $Data['Data']['events_show_summaries'] = 0;
            $Data['Data']['events_show_calendar'] = 1;
            $Data['Data']['events_show_venues'] = 0;
            $Data['Data']['headline'] = $defHeadline;
        }
        if (!empty($Data['Data']['events_threads']) && $Data['Data']['events_threads'] != 'undefined') {
            foreach ($Data['Data']['events_threads'] as $_k => &$_v) {
                $script .= "ddContentObj.eventsThreads[" . $_k . "] = " . $_v . ";";
            }
        } else {
            $Data['Data']['events_threads'][0] = 0;
            $script .= "ddContentObj.eventsThreads[0] = 0;";
        }
        $this->view->assign($Data['Data']);

        $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';
        $this->view->currentTimezone = $currentTimezone;

//        $Data['Data']['event_display_style'] = 2;
        //---------------------------------
        if (! empty($Data['Data']['event_display_style'])) {
            //=======================================================================================
            //Automatically rotate featured events
            //=======================================================================================
            if ($Data['Data']['event_display_style'] == 1) {

                $objRequest = $this->getRequest();
                $defaultTimezone = date_default_timezone_get();
                date_default_timezone_set($currentTimezone);
                $objDateNow = new Zend_Date();
                date_default_timezone_set($defaultTimezone);

                $objRequest->setParam('year', $objDateNow->toString('yyyy'));
                $objRequest->setParam('month', $objDateNow->toString('MM'));

                $objRequest->setParam('year', ( floor($objRequest->getParam('year')) < 1970 ) ? 1970 : floor($objRequest->getParam('year')) );
                $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2038 ) ? 2038 : floor($objRequest->getParam('year')) );
                $objRequest->setParam('month', ( floor($objRequest->getParam('month')) < 1 ) ? 1 : floor($objRequest->getParam('month')) );
                $objRequest->setParam('month', ( floor($objRequest->getParam('month')) > 12 ) ? 12 : floor($objRequest->getParam('month')) );

                /**
                * Build dates
                */
                date_default_timezone_set($currentTimezone);
                $strPeriodStartDate =  sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01T000000';
                $periodStartDateM = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                $periodStartDate = clone $objDateNow;
                //$periodStartDate->setHour(0)->setMinute(0)->setSecond(0);
                //new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                $periodStartEnd = clone $periodStartDateM;
                $periodStartEnd->add(1, Zend_Date::MONTH);
                $periodStartEndM = clone $periodStartDateM;
                $periodStartEndM->add(1, Zend_Date::MONTH);

                $objEvents = new Warecorp_ICal_Event_List_Standard();
                $objEvents->setTimezone($currentTimezone);
                $objEvents->setOwnerIdFilter($this->_page->_user->getId());
                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
                $objEvents->setShowCopyFilter(true);

                // privacy
                if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
                  //  $objEvents->setSharingFilter(array(0,1));
                //} else {
                   $objEvents->setSharingFilter(array(0));
                //}
               // $objEvents->setCurrentEventFilter(true);
                $objEvents->setExpiredEventFilter(false);

                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->_page->_user);
                 //print_r($arrEvents);
                $objEventList = new Warecorp_ICal_Event_List();
                $objEventList->setTimezone($currentTimezone);

                //$periodStartDate->sub(7, Zend_Date::DAY);
                $periodStartDateM->sub(7, Zend_Date::DAY);
                $periodStartEnd->add(7, Zend_Date::DAY);

             // print $periodStartDate->toString('yyyy-MM-ddTHHmmss');
                $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                $dates = $objEventList->buildRecurList($arrEvents);

                //print_r($dates) ;
                $_eventscntr = 0;
                $_monthcntr = 0;

                //колво евентов в датес
                foreach($dates as $_date => &$_hash){
                    foreach($_hash as $_time => &$_id_id){
                        $_eventscntr += count($_id_id);
                    }
                }
               // $Data['Data']['events_futered_display_number'] = 100;
                while ($_eventscntr < $Data['Data']['events_futered_display_number'] && $_monthcntr < 12)
                {
                    $_monthcntr ++;

                    $_periodStartDateM = clone $periodStartDateM;
                    $_periodStartEndM = clone $periodStartEndM;

                    $_periodStartDateM->add($_monthcntr, Zend_Date::MONTH);
                    $_periodStartDateM->sub(7, Zend_Date::DAY);

                    if ($_periodStartDateM->getTimestamp() < $periodStartDate->getTimestamp()){$_periodStartDateM = clone $periodStartDate;}

                   // print($_periodStartDateM->toString('yyyy-MM-ddTHHmmss'));
                    $_periodStartEndM->add($_monthcntr, Zend_Date::MONTH);
                    $_periodStartEndM->add(7, Zend_Date::DAY);

                    $objEventList->setPeriodDtstart($_periodStartDateM->toString('yyyy-MM-ddTHHmmss'));
                    $objEventList->setPeriodDtend($_periodStartEndM->toString('yyyy-MM-ddTHHmmss'));
                    $dates2 = $objEventList->buildRecurList($arrEvents);
                  //  print_r($dates2) ;
                    $dates = array_merge($dates, $dates2);

                    $_eventscntr = 0;
                    //колво евентов в датес
                    foreach($dates as $_date => &$_hash){
                        foreach($_hash as $_time => &$_id_id){
                            $_eventscntr += count($_id_id);
                        }
                    }
                }

                //print_r($dates);
                //===================================================================================
                //добавление объектов в массив
                $eventsList = array();
                $_cntr = 0;
                $eventsDates = array();
                $eventsDatesAtt = array();
                foreach($dates as $_date => &$_hash){
                    foreach($_hash as $_time => &$_id_id){
                        foreach ($_id_id as &$_info){
                            if (count($eventsList) < $Data['Data']['events_futered_display_number']){

                                $_nEvent = new Warecorp_ICal_Event($_info['id']);
                                $_nEvent->setTimezone($currentTimezone);
                                $eventsList[] = $_nEvent;
                                $eventsDates[] = substr($_date, 5, 2).'/'.substr($_date, 8, 2).'/'.substr($_date, 0, 4);
                                if($_nEvent->isAllDay()) {
                                    $eventsDatesAtt[] = $_date;
                                } else {
                                    $eventsDatesAtt[] = $_date.'T'.str_replace(':','',$_time);
                                }
                            }
                            //foreach($arrEvents as &$_event){
                            //    if (count($eventsList) < $Data['Data']['events_futered_display_number'] && $_event->getId() == $_info['uid']){
                            //        $eventsList[] = $_event;
                            //        $eventsDates[] = substr($_date, 5, 2).'/'.substr($_date, 8, 2).'/'.substr($_date, 0, 4);
                            //        $eventsDatesAtt[] = $_date.'T'.str_replace(':','',$_time);
                             //   }
                           // }
                        }
                    }
                }

                $this->view->eventsList = $eventsList;
                $this->view->eventsDates = $eventsDates;
                $this->view->eventsDatesAtt = $eventsDatesAtt;
            }
            //=======================================================================================
            // Manually select an events to dsiplay
            //=======================================================================================
            if ($Data['Data']['event_display_style'] == 2) {

                $objEvents = new Warecorp_ICal_Event_List_Standard();
                $objEvents->setTimezone($currentTimezone);
                $objEvents->setOwnerIdFilter($this->currentUser->getId());
                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);

                // PRIVACY etc----------------------------------------------------------
                if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
                //    $objEvents->setSharingFilter(array(0,1));
                //} else {
                    $objEvents->setSharingFilter(array(0));
                //}

                $objEvents->setCurrentEventFilter(true);
                $objEvents->setExpiredEventFilter(false);
                //--------------------------------------------------------------------

                $_eventsList = array();
                if (! empty($Data['Data']['events_threads'])) {
                    $_eventsList = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->_page->_user);
                }

                $eventsList = array();
                foreach ($Data['Data']['events_threads'] as &$_et_id) {
                    foreach ($_eventsList as &$_v) {
                        if ($_et_id == $_v->getId()) {
                            $eventsList[] = $_v;
                        }
                    }
                }
                $this->view->eventsList = $eventsList;

                if (! empty($editMode)) {
                    //list of events for SELECT in edit mode
                    $selectEventsList = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->_page->_user);
                    $this->view->selectEventsList = $selectEventsList;
                }
            }
            //=======================================================================================
            // Automatically rotate events on a list with calendar
            //=======================================================================================
            if ($Data['Data']['event_display_style'] == 3) {

                // CALENDAR =========================================================================
                $objRequest = $this->getRequest();

                $defaultTimezone = date_default_timezone_get();
                date_default_timezone_set($currentTimezone);
                $objDateNow = new Zend_Date();
                date_default_timezone_set($defaultTimezone);
                $objRequest->setParam('year', $objDateNow->toString('yyyy'));
                $objRequest->setParam('month', $objDateNow->toString('MM'));
                $objRequest->setParam('year', ( floor($objRequest->getParam('year')) < 1970 ) ? 1970 : floor($objRequest->getParam('year')) );
                $objRequest->setParam('year', ( floor($objRequest->getParam('year')) > 2038 ) ? 2038 : floor($objRequest->getParam('year')) );
                $objRequest->setParam('month', ( floor($objRequest->getParam('month')) < 1 ) ? 1 : floor($objRequest->getParam('month')) );
                $objRequest->setParam('month', ( floor($objRequest->getParam('month')) > 12 ) ? 12 : floor($objRequest->getParam('month')) );

                /**
                * Build dates
                */
                $objCurrDate = new Zend_Date(sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01T000000', Zend_Date::ISO_8601, 'en_US');

                $objPrevDate = clone $objCurrDate;
                $objPrevDate->sub(1, Zend_Date::MONTH);
                $objNextDate = clone $objCurrDate;
                $objNextDate->add(1, Zend_Date::MONTH);

                date_default_timezone_set($currentTimezone);
                $strPeriodStartDate =  sprintf('%04d', $objRequest->getParam('year')).'-'.sprintf('%02d', $objRequest->getParam('month')).'-01T000000';
                $periodStartDate = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                $periodStartEnd = clone $periodStartDate;
                $periodStartEnd->add(1, Zend_Date::MONTH);
                $objDateNow = new Zend_Date();
                $this->view->objDateNow = $objDateNow;

                Warecorp_ICal_Calendar_Cfg::setWkst('SU');
                $objYear = new Warecorp_ICal_Calendar_Year($objRequest->getParam('year'));
                $objYear->setShowMonths($objRequest->getParam('month'));
                $this->view->objYear = $objYear;


                $objEvents = new Warecorp_ICal_Event_List_Standard();
                $objEvents->setTimezone($currentTimezone);
                $objEvents->setOwnerIdFilter($this->currentUser->getId());
                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
                $objEvents->setShowCopyFilter(true);
                // privacy
                if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentUser, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
                //    $objEvents->setSharingFilter(array(0,1));
                //} else {
                    $objEvents->setSharingFilter(array(0));
                //}
                $objEvents->setCurrentEventFilter(true);
                $objEvents->setExpiredEventFilter(false);

                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($this->_page->_user);

                $objEventList = new Warecorp_ICal_Event_List();
                $objEventList->setTimeZone($this->_page->_user->getTimezone());

                // тест, чтобы показывались скрытые события
                $periodStartDate->sub(7, Zend_Date::DAY);
                $periodStartEnd->add(7, Zend_Date::DAY);

                $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                $dates = $objEventList->buildRecurList($arrEvents);

                //===================================================================================
                //Days List
                $objEventList3 = clone $objEventList;
                $objEventList3->setPeriodDtstart($objDateNow->toString('yyyy-MM-ddT000000'));
                //print $objDateNow->toString('yyyy-MM-ddTHHmmss');

                $objCurrDate3 = clone $objDateNow;
                //$objCurrDate3->setLocale('en_US');
                $daysList = array();

                for ($i = 0; $i < $Data['Data']['events_display_number']; $i ++) {
                    $daysList[$i]['m'] = $objCurrDate3->toString('MM');
                    $daysList[$i]['d'] = $objCurrDate3->toString('dd');
                    $daysList[$i]['y'] = $objCurrDate3->toString('yyyy');
                    $daysList[$i]['check'] = $objCurrDate3->toString('yyyy-MM-dd');
                    $daysList[$i]['date'] = ($i == 0) ? 'Today' : $objCurrDate3->toString('EEEE').' '.$objCurrDate3->toString(Warecorp_Date::DATE_MEDIUM);
                    $objCurrDate3->add(1, Zend_Date::DAY);
                }

                //print $objCurrDate3->toString('yyyy-MM-ddTHHmmss');

                $objEventList3->setPeriodDtend($objCurrDate3->toString('yyyy-MM-ddTHHmmss'));
                $dates2 = $objEventList3->buildRecurList($arrEvents);

                //добавление объектов в массив
                foreach($dates2 as $_date => &$_hash){
                    foreach($_hash as $_time => &$_id_id){
                        foreach ($_id_id as &$_info){

                         //   foreach($arrEvents as &$_event){
                         //       if ($_event->getId() == $_info['uid']){

                                    $_event = new Warecorp_ICal_Event($_info['id']);
                              /*      $_nEvent->setTimezone($currentTimezone);
                                    $eventsList[] = $_nEvent;
                                    $eventsDates[] = substr($_date, 5, 2).'/'.substr($_date, 8, 2).'/'.substr($_date, 0, 4);
                                    if($_nEvent->isAllDay()) {
                                        $eventsDatesAtt[] = $_date;
                                    } else {
                                        $eventsDatesAtt[] = $_date.'T'.str_replace(':','',$_time);
                                    }*/

                                    $_info['_event'] = $_event;//->getRootEvent();

                                    //---------------------
                                    //tooltip
                                    $_k = $_info['_event']->getId() . '_' . str_replace('-','_',$_date);

                                    $objCDate=$_info['_event']->convertTZ($_info['_event']->getDtstart(), $currentTimezone);
                                    //$objCDate->setLocale('en_US');
                                    if ($_info['_event']->isAllDay()){
                                        $_timestr= Warecorp::t('All day');
                                    }else {
                                        $_timestr='&#160;'.$objCDate->toString(Warecorp_Date::TIME_SHORT).' ';
                                        if ($_info['_event']->isTimezoneExists()){
                                            $_timestr.=$objCDate->get(Zend_Date::TIMEZONE);
                                        }
                                    }

                                    $tooltip_text = '
                                        <div>' . $_info['_event']->displayDate('dd.myevents.tooltip', $this->_page->_user, $currentTimezone) . '</div>
                                        <div><strong><u>' . htmlspecialchars($_info['_event']->getTitle()) . '</u></strong></div>
                                        <div>Organizer : ' . htmlspecialchars($_info['_event']->getCreator()->getLogin()) . '</div>
                                        <div>' . ( ($_info['_event']->getOwnerType() == 'group') ? ( 'Group event : '.htmlspecialchars($_info['_event']->getOwner()->getName()) ) : '' ) . '</div>
                                        <div>' . ( ($_info['_event']->getPrivacy()) ? 'Private Event' : 'Public Event' ) . '</div>
                                    ';
                                    $tooltip_text = str_replace(array("\r", "\n"), "", $tooltip_text);
                                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddMyEvents_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"250px"});';
                                    //----------------------
                        //        }
                        //    }
                        }
                    }
                }
                /**
                * Assign template vars
                */
                $this->view->arrDates = $dates;
                $this->view->objCurrDate = $objCurrDate;
                $this->view->objPrevDate = $objPrevDate;
                $this->view->objNextDate = $objNextDate;
                $this->view->daysList = $daysList;
                $this->view->calendar_data = $dates2;
            }
            //=======================================================================================
        }
        //-----------------------------
        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddMyEvents/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddMyEvents/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddMyEvents/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    // Disabled by Komarovski after review
    /*case 'ddProfileHeadline':

        $headline = $this->_page->_user->headline;
        if (empty ($headline))
        {
        $headline = 'Headline is empty';
        }
        $this->view->Content = $headline;
        $content = $this->view->getContents('content_objects/ddProfileHeadline/preview_mode.tpl');

        break;*/
    //-------------------------------------------------------------------------------------
    // Disabled by Komarovski after review
    /*case 'ddProfileIntroduction':

        $intro = $this->_page->_user->intro;
        if (empty ($intro))
        {
        $intro = 'Introduction is empty';
        }
        $this->view->Content = $intro;
        $content = $this->view->getContents('content_objects/ddProfileIntroduction/preview_mode.tpl');

        break;*/
    //-------------------------------------------------------------------------------------
    default:
        $content = Warecorp::t('Unknown Content Block');
        break;
    //-------------------------------------------------------------------------------------
}
if (empty($editMode)) {
    $script .= "ddContentObj.borderStyle = '" . (! empty($Data['Style']['borderStyle']) ? $Data['Style']['borderStyle'] : '') . "';";
    $script .= "ddContentObj.borderColor = '" . (! empty($Data['Style']['borderColor']) ? $Data['Style']['borderColor'] : '') . "';";
    $script .= "ddContentObj.backgroundColor = '" . (! empty($Data['Style']['backgroundColor']) ? $Data['Style']['backgroundColor'] : '') . "';";
}/* else {
    if ( empty($lightReload) &&
            $ContentType != 'ddContentBlock' &&
            $ContentType != 'ddScript' &&
            !($ContentType == 'ddMyLists' && !empty($Data['Data']['list_display_type']) ) ) {

        $theme = Warecorp_Theme::loadThemeFromDB($this->currentUser);
        $theme->prepareFonts();

        if (empty($Data['Style']['backgroundColor'])){
            if ($theme->fillColorTransparent){
                $_tBackgroundColor = 'transparent';
            }else{
                $_tBackgroundColor = $theme->fillColor;
            }
        }else{
            $_tBackgroundColor = $Data['Style']['backgroundColor'];
        }
        $script .= 'ddContentObj.mceEditorBC = "'. $_tBackgroundColor .'";';
        $_tTextColor = $theme->headlineTextColor;
        $_tFontStyle = $theme->headlineTextFontFamily;
        if ($theme->fillColorTransparent){
            $script .= 'ddContentObj.mceEditorBCD = "transparent";';
        }else{
            $script .= 'ddContentObj.mceEditorBCD = "'. $theme->fillColor .'";';
        }
        $script .= 'ddContentObj.mceEditorID = tinyMCEInit("tinyMCE_' . $CloneElID . '_H");';
        $script .= 'applyThemeToTinyMCE(ddContentObj.mceEditorID,"'.$_tBackgroundColor.'" ,"'.$_tTextColor.'" ,"'.$_tFontStyle.'");';

        $script .= 'document.getElementById("tinyMCE_' . $CloneElID . '_div_wait_H").style.display = "none";';
        $script .= 'document.getElementById("tinyMCE_' . $CloneElID . '_div_H").style.visibility = "visible";';
    }
}*/
$script .= "WarecorpDDblockApp.refreshStyles('$CloneElID');";

//height
$script .= "WarecorpDDblockApp.ddTargetSmoothing();";

//save after load
if ($saveAfterLoad) {
    $script .= "WarecorpDDblockApp.save();";
}

//display edit mode buttons
if (empty($editMode)) {
    $script .= "showViewModeButtons('$CloneElID');";
}
$objResponse->addAssign($ContentDivId, "innerHTML", $content);
$objResponse->addScript($script);
