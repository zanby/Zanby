<?php
Warecorp::addTranslation('/modules/groups/contentblocks/action.getblockcontent.php.xml');

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

$defHeadline = '<strong>'.Warecorp::t('This is the default headline').'</strong>';
$smarty_vars = array();
//print $ContentType;die;
switch ($ContentType) {
    //-------------------------------------------------------------------------------------
    case 'ddRoundInfo':
        $script .= "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.displayType = '" . (isset($Data['Data']['display_type']) ? $Data['Data']['display_type'] : 0) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";

        $this->view->Round = Warecorp_Round_Item::getCurrentRound($this->currentGroup->getId());

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/'.$ContentType.'/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/'.$ContentType.'/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/'.$ContentType.'/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddRoundEvents':
        $script .= "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.displayType = '" . (isset($Data['Data']['display_type']) ? $Data['Data']['display_type'] : 0) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";

        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.open = '" . (isset($Data['Data']['open']) ? $Data['Data']['open'] : 1) . "';";
        $script .= "ddContentObj.byinvitation = '" . (isset($Data['Data']['byinvitation']) ? $Data['Data']['byinvitation'] : 1) . "';";
        $script .= "ddContentObj.full = '" . (isset($Data['Data']['full']) ? $Data['Data']['full'] : 0) . "';";
        $script .= "ddContentObj.past = '" . (isset($Data['Data']['past']) ? $Data['Data']['past'] : 0) . "';";
        if (! isset($Data['Data']) || count($Data['Data'])<4) {
            $Data['Data'] = array();
            $Data['Data']['display_type'] = 0;
            $Data['Data']['open'] = 1;
            $Data['Data']['byinvitation'] = 1;
            $Data['Data']['full'] = 0;
            $Data['Data']['past'] = 0;
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->assign($Data['Data']);

        $round = Warecorp_Round_Item::getCurrentRound($this->currentGroup->getId());

        $this->view->Round = $round;

        $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';
        /**
         * Initialization global objects that is used in script
         */
        $AccessManager = Warecorp_ICal_AccessManager_Factory::create();
        $lstEventsObj = new Warecorp_ICal_Event_List();
        $lstEventsObj->setTimezone($currentTimezone);
        $tz = date_default_timezone_get();
        date_default_timezone_set($currentTimezone);
        $objNowDate = new Zend_Date();
        date_default_timezone_set($tz);

        /**
         * Find events that belog to main group
         * $arrEvents will contains all this events
         */
        $objEvents = new Warecorp_ICal_Event_List_Standard();
        $objEvents->setTimezone($currentTimezone);
        $objEvents->setOwnerIdFilter($this->currentGroup->getId());
        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
        $objEvents->setWithVenueOnly( true );
        // privacy
        if ( $AccessManager->canViewPublicEvents($this->currentGroup, $this->_page->_user) && $AccessManager->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(0,1));
        } elseif ( $AccessManager->canViewPublicEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(0));
        } elseif ( $AccessManager->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setPrivacyFilter(array(1));
        } else {
            $objEvents->setPrivacyFilter(null);
        }
        // sharing
        if ( $AccessManager->canViewSharedEvents($this->currentGroup, $this->_page->_user) ) {
            $objEvents->setSharingFilter(array(0,1));
        } else {
            $objEvents->setSharingFilter(array(0));
        }
        if ($round->getRoundId()) {
            $objEvents->setFilterPartOfRound($round->getRoundId());
        }else{
            $objEvents->setFilterPartOfNonRound(true);
        }
        
        $objEvents->setCurrentEventFilter(true);
        $objEvents->setExpiredEventFilter(true);


        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
        
        $arrEventIds = array();
        foreach ($arrEvents as $id=>$v) {
            $event = new Warecorp_ICal_Event($id);

            $strFirstDate = $lstEventsObj->findFirstEventDate($event, $objNowDate);
            if ( null !== $strFirstDate ) $event->setDtstart($strFirstDate);

            $endDate = $event->getDtend();
            $endDate->setTimezone($currentTimezone);

            
            $type = 'past';
            if ($endDate->isLater($objNowDate)) {
                $attendee = $event->getAttendee();
                if ($event->getMaxRsvp() > 0 && $event->getMaxRsvp() <= $attendee->setAnswerFilter('YES')->getCount()) {
                    $type='full';
                }else{
                    $invitation = $event->getInvite();
                    if ($invitation->getIsAnybodyJoin() || $invitation->getAllowGuestToInvite()) {
                        $type = 'open';
                    }else {
                        $type = 'byinvitation';
                    }
                }
            }
            if ($Data['Data'][$type] == 0) continue;
            $arrEventIds[$event->getId()]=$type;
        }

        //  Save events location to cache to use it on map
        $mapCache = md5(uniqid(mt_rand(), true));
        $cache = Warecorp_Cache::getFileCache();
        $cache->save($arrEventIds, $mapCache, array(), 60*60*10);
        $this->view->mapCache = $mapCache;

        if (!empty($previewMode)) {
            $content = $this->view->getContents('content_objects/'.$ContentType.'/preview_mode_' . $blockColumn . '.tpl');
        }else if (empty($lightReload)) {
            if ($editMode > 0) {
                $content = $this->view->getContents('content_objects/'.$ContentType.'/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/'.$ContentType.'/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/'.$ContentType.'/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddMogulus':

    if (!isset($Data['Data']['channel'])) {
        $coSettings = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks_settings.xml');
        $defaultChannel = $coSettings->ddMogulus->defaultChannel;

        $channel = (isset($defaultChannel)) ? $defaultChannel : '';
    }

     $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
     $script .= "ddContentObj.headlineAbsent = true;";
     $script .= "ddContentObj.channel = '" . (isset($Data['Data']['channel']) ? $Data['Data']['channel'] : $channel) . "';";
     $script .= "ddContentObj.startOnInit = '" . (isset($Data['Data']['startOnInit']) ? $Data['Data']['startOnInit'] : 0) . "';";

     if (! isset($Data['Data'])) {
        $Data['Data'] = array();
        $Data['Data']['channel'] = $channel;
        $Data['Data']['startOnInit'] = 0;
     }

     $this->view->cloneId = $CloneElID;
     $this->view->assign($Data['Data']);

     if (! empty($editMode)) {
         $content = $this->view->getContents('content_objects/ddMogulus/compose_mode_edit_' . $blockColumn . '.tpl');
     } else {
         /*$_width = (($blockColumn == 'wide')?407:157);
         $_height = (($blockColumn == 'wide')?407:157);

         $script .= "document.getElementById('light_".$CloneElID."').innerHTML='';";
         $script .= 'document.oldWriteFunction = document.write;';
         $script .= "document.write = function(str){document.getElementById('light_".$CloneElID."').innerHTML += str;};";
         $script .= "tmpScript= document.createElement('script');";
         $script .= "tmpScript.src = 'http://www.mogulus.com/scripts/playerv2.js?channel=".$Data['Data']['channel']."&layout=playerEmbedDefault&backgroundColor=0xeff4f9&backgroundAlpha=1&backgroundGradientStrength=0&chromeColor=0x000000&headerBarGlossEnabled=true&controlBarGlossEnabled=true&chatInputGlossEnabled=false&uiWhite=true&uiAlpha=0.5&uiSelectedAlpha=1&dropShadowEnabled=true&dropShadowHorizontalDistance=10&dropShadowVerticalDistance=10&paddingLeft=1&paddingRight=3&paddingTop=6&paddingBottom=1&cornerRadius=3&backToDirectoryURL=null&bannerURL=null&bannerText=null&bannerWidth=320&bannerHeight=50&showViewers=false&embedEnabled=true&chatEnabled=false&onDemandEnabled=true&programGuideEnabled=false&fullScreenEnabled=true&reportAbuseEnabled=false&gridEnabled=false&initialIsOn=".(empty($Data['Data']['startOnInit'])?'false':'true')."&initialIsMute=false&initialVolume=4&width=".$_width."&height=".$_height."&wmode=window';";
         $script .= "tmpScript.onload = function(){document.write = document.oldWriteFunction;};";
         $script .= "document.getElementById('light_".$CloneElID."').appendChild(tmpScript);";
         */
        // $content = $this->view->getContents('content_objects/ddMogulus/preview_mode_' . $blockColumn . '.tpl');
         $content = $this->view->getContents('content_objects/ddMogulus/compose_mode_view_' . $blockColumn . '.tpl');
    }

    break;
    //-------------------------------------------------------------------------------------
    case 'ddIframe':
     $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
     $script .= "ddContentObj.headlineAbsent = true;";
     $script .= "ddContentObj.altSrc = '" . (isset($Data['Data']['alt_src']) ? $Data['Data']['alt_src'] : 'f5d439c0b3') . "';";

     if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['alt_src'] = 'f5d439c0b3';
     } elseif (!$Data['Data']['alt_src']) {
         $Data['Data']['alt_src'] = 'f5d439c0b3';
     }

     $this->view->cloneId = $CloneElID;
     $this->view->assign($Data['Data']);

     if (! empty($editMode)) {
         $content = $this->view->getContents('content_objects/ddIframe/compose_mode_edit_' . $blockColumn . '.tpl');
     } else {
         $content = $this->view->getContents('content_objects/ddIframe/preview_mode_' . $blockColumn . '.tpl');
     }

    break;
    //-------------------------------------------------------------------------------------
    case 'ddScript':

     if (! isset($Data['Data'])) {
        $Data['Data'] = array();
        $Data['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
        $Data['Data']['custom_height'] = 0;
     }

     if (empty($Data['Data']['custom_height'])) $Data['Data']['custom_height'] = 0;

     if ( empty($Data['Data']['jscontent']) || empty($editMode)) {

         $filename = SCRIPTING_UPLOAD_PATH."/".md5('group').md5($this->currentGroup->getId()).$Data['Data']['unique_code'].'.dat';
         if (file_exists($filename) && filesize($filename)>0) {
             $handle = fopen($filename, "a+");
             $contents = fread($handle, filesize($filename));
             fclose($handle);
             $fileurl = SCRIPTING_UPLOAD_URL."/".md5('group').md5($this->currentGroup->getId()).$Data['Data']['unique_code'].'.html';
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
    case 'ddFamilyWidgetMap':
    case 'ddGroupWidgetMap':

        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.defaultDisplayType = '" . (isset($Data['Data']['defaultDisplayType']) ? $Data['Data']['defaultDisplayType'] : 0) . "';";
        $script .= "ddContentObj.displayRange = '" . (isset($Data['Data']['displayRange']) ? $Data['Data']['displayRange'] : 1) . "';";
        $script .= "ddContentObj.eventsDisplayType = '" . (isset($Data['Data']['eventsDisplayType']) ? $Data['Data']['eventsDisplayType'] : 0) . "';";
        $script .= "ddContentObj.eventToDisplayId = '" . (isset($Data['Data']['eventToDisplayId']) ? $Data['Data']['eventToDisplayId'] : 0) . "';";
        $script .= "ddContentObj.groupContext = '" .  $this->currentGroup->getId() . "';";
        $script .= "ddContentObj.headlineAbsent = true;";

        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['defaultDisplayType'] = 0;
            $Data['Data']['displayRange'] = 1;
            $Data['Data']['eventsDisplayType'] = 0;
            $Data['Data']['eventToDisplayId'] = 0;
        }

        $resultString = '';
        if ($Data['Data']['defaultDisplayType'] == 1) {$resultString .= '&defaultDisplayType=1';}
        if ($Data['Data']['displayRange'] == 1) {$resultString .= '&displayRange=1';}
        if ($Data['Data']['eventsDisplayType'] == 1) {
            $resultString .= '&eventsDisplayType=1';
            if ($Data['Data']['eventToDisplayId']) {
                $resultString .= '&eventToDisplayId='.$Data['Data']['eventToDisplayId'];
            }
        }
        $resultString .= '&groupContext='.$this->currentGroup->getId();
        $resultString .= '&r='.rand();
                if ($blockColumn == 'wide') {
                    $_width=440;
                } elseif ($blockColumn == 'narrow') {
                    $_width=240;
                }
        //@TODO change hardcoded url
        //&country=United%States&zoom=3
        $resultString = BASE_URL.'/widget.js?wtype=map&wdtype=iniframe&needDistrictLayer=1&width='.$_width.'&height=300&kmlControlInternalId=getKMLLink'.$resultString;

        $script .= "if (document.getElementById('wMap_iframe_$CloneElID')) {document.getElementById('wMap_iframe_$CloneElID').src = \"".$resultString."\"};";


        $this->view->assign($Data['Data']);

        $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set($currentTimezone);
        $nowDate = new Zend_Date();
        date_default_timezone_set($defaultTimeZone);

        $ndaList = new Warecorp_Nda_List;

        if (true || isset($fromCurrenToFuture) && $fromCurrenToFuture) {
            $ndaList->addWhere('ceni.nda_edate > ?', $nowDate->toString("yyyy-MM-dd 00:00:00"))
                    ->setOrder("ceni.nda_sdate, ceni.nda_name");
        } else {
            $ndaList->addWhere('(ceni.nda_sdate <= ?', $nowDate->toString("yyyy-MM-dd 00:00:00"))
                    ->addWhere('ceni.nda_edate >= ?', $nowDate->toString("yyyy-MM-dd 00:00:00"))
                    ->addWhereOr('ceni.nda_sdate <= ?', $nowDate->toString("yyyy-MM-dd 23:59:59"))
                    ->addWhere('ceni.nda_edate >= ?)', $nowDate->toString("yyyy-MM-dd 23:59:59"))
                    ->setOrder("ceni.nda_sdate, ceni.nda_name");
        }

        $list = $ndaList->setStatusFilter(Warecorp_Nda_Enum_Status::PUBLISH)->getList();

        $this->view->ndaList  = $list;
        $this->view->ndaCount = $ndaList->getCount();

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/'.$ContentType.'/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupWidgetMap/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupWidgetMap/light_block_' . $blockColumn . '.tpl');
        }

        break;

    //-------------------------------------------------------------------------------------
    case 'ddFamilyMap':

        if (!isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
            $Data['Data']['headline'] = '<strong>Headline</strong>';
            $Data['Data']['show_districts'] = true;
            $Data['Data']['show_precincts'] = false;
            $Data['Data']['districts_default_layer'] = true;
            $Data['Data']['area']['type'] = 'default';
            $Data['Data']['area']['country_id'] = 1;
            $Data["Data"]["area"]["around"]     = '';
            $Data["Data"]["area"]["radio_code"] = 'zip';
            $Data["Data"]["area"]["zip"]        = '';
            $Data["Data"]["area"]["latitude"]   = '';
            $Data["Data"]["area"]["longitude"]  = '';
        }
        $script = '';

        $children = $this->currentGroup->getGroups()->setTypes(array('simple','family'))->getList();

        if ($blockColumn == 'wide') {
            $width=340;
            $height=340;
            $additionalControls = array('GLargeMapControl');
        } elseif ($blockColumn == 'narrow') {
            $width=183;
            $height=183;
        }

        /**
         *  Create countries list
         */
        $location = new Warecorp_Location();
        $countries = $location->getCountriesListAssoc(true);
        unset($countries[0]);
        $this->view->countries = $countries;


        $filebase = "/".md5('family_map').md5($this->currentGroup->getId()).$Data['Data']['unique_code'].'.html';
        $filename = SCRIPTING_UPLOAD_PATH.$filebase;
        $fileurl = SCRIPTING_UPLOAD_URL.$filebase;


            $handle = fopen($filename, "w+");
            $this->view->children = $children;
            $this->view->width = $width;
            $this->view->height = $height;

            if ($Data['Data']['show_districts'] && $Data['Data']['districts_default_layer'])
                $this->view->mapType = 'G_MAP_OVERLAY';
            else
                $this->view->mapType = '';

            if ($Data['Data']['show_districts'])
                $this->view->needDistrictLayer = 1;

            if ($Data['Data']['area']['country_id'] && $Data['Data']['area']['type']=='default')
                $this->view->countryName =$countries[$Data['Data']['area']['country_id']];

            if ($Data["Data"]["area"]["around"]) {
                $zoom = Z1SKY_GMap_Utils::getZoomForCO($width,$height,$Data["Data"]["area"]["around"]);
            }

            if ($Data['Data']['area']['type']=='custom'
                && $Data["Data"]["area"]["radio_code"] == 'lat'
                && $Data["Data"]["area"]["latitude"]
                && $Data["Data"]["area"]["longitude"]
                && $Data["Data"]["area"]["around"]){
                $this->view->latitude =$Data["Data"]["area"]["latitude"];
                $this->view->longitude =$Data["Data"]["area"]["longitude"];
                $this->view->zoom =$zoom;
            }

            if ($Data['Data']['area']['type']=='custom'
                && $Data["Data"]["area"]["radio_code"] == 'zip'
                && $Data["Data"]["area"]["around"]
                && $Data["Data"]["area"]["zip"]) {
                $this->view->zip =$Data["Data"]["area"]["zip"];
                $this->view->zoom =$zoom;
            }

            if (isset($additionalControls)) {
                $this->view->additionalControls =$additionalControls;
            }

            $this->view->gmapKey =Z1SKY_GMap_Utils::getCOGMapKey();

            fwrite($handle, $this->view->getContents('content_objects/ddFamilyMap/html_content.tpl'));
            fclose($handle);
       if (empty($editMode) || !empty($lightReload)) {
       } else {
            $script .= 'initZipAutocomplete();';
       }

       //


    /*
    $script .= "tmpScript= document.createElement('script');";
    $script .= "tmpScript.innerHTML = 'alert(\'one\');';";
    $script .= "document.getElementById('light_".$CloneElID."').appendChild(tmpScript);";
    */

    /**
    *
    */


        $script .= "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.uniqueCode = '".$Data['Data']['unique_code']."';";
        $script .= "ddContentObj.showPrecincts = '".(boolean)$Data['Data']['show_precincts']."';";
        $script .= "ddContentObj.showDistricts = '".(boolean)$Data['Data']['show_districts']."';";
        $script .= "ddContentObj.districtsDefaultLayer = '".(boolean)$Data['Data']['districts_default_layer']."';";
        $script .= "ddContentObj.areaType = '".$Data['Data']['area']['type']."';";
        $script .= "ddContentObj.areaCountryId = '".$Data['Data']['area']['country_id']."';";
        $script .= "ddContentObj.areaAround = '".$Data['Data']['area']['around']."';";
        $script .= "ddContentObj.areaRadioCode = '".$Data['Data']['area']['radio_code']."';";
        $script .= "ddContentObj.zip = '".$Data['Data']['area']['zip']."';";
        $script .= "ddContentObj.latitude = '".$Data['Data']['area']['latitude']."';";
        $script .= "ddContentObj.longitude = '".$Data['Data']['area']['longitude']."';";
        $script .= "ddContentObj.altSrc = '';";
        $script .= "ddContentObj.headlineAbsent = true;";

       // $script .= 'YAHOO.util.Event.onDOMReady(initZipAutocomplete);';

        $this->view->fileurl = $fileurl;
        $this->view->cloneId = $CloneElID;
        $this->view->showDistricts = $Data['Data']['show_districts'];
        $this->view->showPrecincts = $Data['Data']['show_precincts'];
        $this->view->districtsDefaultLayer =$Data['Data']['districts_default_layer'];
        $this->view->areaType = $Data['Data']['area']['type'];
        $this->view->areaCountryId = $Data['Data']['area']['country_id'];
        $this->view->areaAround = $Data['Data']['area']['around'];
        $this->view->areaRadioCode = $Data['Data']['area']['radio_code'];
        $this->view->zip = $Data['Data']['area']['zip'];
        $this->view->latitude = $Data['Data']['area']['latitude'];
        $this->view->longitude = $Data['Data']['area']['longitude'];


        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddFamilyMap/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddFamilyMap/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddFamilyMap/light_block_' . $blockColumn . '.tpl');
        }


        break;


    //-------------------------------------------------------------------------------------
    case 'ddElectedOfficial':
            $regionalFlag = $this->currentGroup->getCongressionalDistrict();
            $state = null;
            $district = null;

            if ( $regionalFlag !== null && $regionalFlag != '')
            {
                $state = substr($regionalFlag, 0, 2);
                $district = substr($regionalFlag, 2);

            $theme = Zend_Registry::get("AppTheme");
            $legislators = Z1SKY_Location_District::getSunlabLegislators($state, $district,$theme);
            $this->view->legislators = $legislators;
            $this->view->headline = 'Elected Officials';

            $script .= "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
           // $script .= "ddContentObj.headlineAbsent = true;";


            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddElectedOfficial/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddElectedOfficial/compose_mode_view_' . $blockColumn . '.tpl');
            }
        }

        break;
    case 'ddGroupMap':

        if (!isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
            $Data['Data']['headline'] = '<strong>'.Warecorp::t('Headline').'</strong>';
            $Data['Data']['show_districts'] = true;
            $Data['Data']['show_precincts'] = false;
            $Data['Data']['districts_default_layer'] = true;
            $Data['Data']['area']['type'] = 'default';
            $Data['Data']['area']['country_id'] = 1;
            $Data["Data"]["area"]["around"]     = '';
            $Data["Data"]["area"]["radio_code"] = 'zip';
            $Data["Data"]["area"]["zip"]        = '';
            $Data["Data"]["area"]["latitude"]   = '';
            $Data["Data"]["area"]["longitude"]  = '';
        }
        $script = '';

        $customMarkers = array();

        $cfgGmap = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.gmap.xml');
        if ($cfgGmap) {
            if ($cfgGmap->icons->groupMap) {
                $groupMapIcon = $cfgGmap->icons->groupMap;
            }
        }

        if (isset($groupMapIcon)) {
            $customMarker = array('itemIds' => array($this->currentGroup->getId()),
                                   'markerImg' => $groupMapIcon);
            $customMarkers[] = $customMarker;
        }

        if ($customMarkers) {
            $this->view->customMarkers = $customMarkers;
        }

 /*
        $groupListObj = new Warecorp_Group_List();
        $allGroups = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
        //                 ->setListSize(50)
                           //->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC)
                           ->getList();
        $children = $allGroups;
 */

        $groupSearch = new Z1SKY_Group_Search();
        $groupSearch->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);

        $s['district']  = Z1SKY_Location_District::getDistrictsByZipCode($this->currentGroup->getZipCode());
        $s['country']   = 1;
        $state = Warecorp_Location_City::create($this->currentGroup->getCityId())->getState();
        $s['state']     = $state->id;

        $maxMinCoordinates = Z1SKY_Location_District::getCoordinates($s['district'],$state->code);

        if ($maxMinCoordinates) {
            $this->view->maxMinCoordinates = $maxMinCoordinates;
        }

        $groups = $groupSearch->searchByCriterions($s);

        foreach ($groups as &$group) {
           $group = new Warecorp_Group_Simple('id', $group);
        }

        $children = $groups;


        if ($blockColumn == 'wide') {
            $width=340;
            $height=340;
            $additionalControls = array('GLargeMapControl');
        } elseif ($blockColumn == 'narrow') {
            $width=183;
            $height=183;
        }

        /**
         *  Create countries list
         */
        $location = new Warecorp_Location();
        $countries = $location->getCountriesListAssoc(true);
        unset($countries[0]);
        $this->view->countries = $countries;


        $filebase = "/".md5('group_map').md5($this->currentGroup->getId()).$Data['Data']['unique_code'].'.html';
        $filename = SCRIPTING_UPLOAD_PATH.$filebase;
        $fileurl = SCRIPTING_UPLOAD_URL.$filebase;


        $handle = fopen($filename, "w+");
        $this->view->children = $children;
        $this->view->width = $width;
        $this->view->height = $height;

        if ($Data['Data']['show_districts'] && $Data['Data']['districts_default_layer'])
            $this->view->mapType = 'G_MAP_OVERLAY';
        else
            $this->view->mapType = '';

        if ($Data['Data']['show_districts'])
            $this->view->needDistrictLayer = 1;

        if ($Data['Data']['area']['country_id'] && $Data['Data']['area']['type']=='default')
            $this->view->countryName =$countries[$Data['Data']['area']['country_id']];

        if ($Data["Data"]["area"]["around"]) {
            $zoom = Z1SKY_GMap_Utils::getZoomForCO($width,$height,$Data["Data"]["area"]["around"]);
        }

        if ($Data['Data']['area']['type']=='custom'
            && $Data["Data"]["area"]["radio_code"] == 'lat'
            && $Data["Data"]["area"]["latitude"]
            && $Data["Data"]["area"]["longitude"]
            && $Data["Data"]["area"]["around"]){
            $this->view->latitude =$Data["Data"]["area"]["latitude"];
            $this->view->longitude =$Data["Data"]["area"]["longitude"];
            $this->view->zoom =$zoom;
        }

        if ($Data['Data']['area']['type']=='custom'
            && $Data["Data"]["area"]["radio_code"] == 'zip'
            && $Data["Data"]["area"]["around"]
            && $Data["Data"]["area"]["zip"]) {
            $this->view->zip =$Data["Data"]["area"]["zip"];
            $this->view->zoom =$zoom;
        }

        if (isset($additionalControls)) {
            $this->view->additionalControls =$additionalControls;
        }

        $this->view->gmapKey =Z1SKY_GMap_Utils::getCOGMapKey();

        fwrite($handle, $this->view->getContents('content_objects/ddGroupMap/html_content.tpl'));
        fclose($handle);

        if (empty($editMode) || !empty($lightReload)) {
        } else {
            $script .= 'initZipAutocomplete();';
        }



        $script .= "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.uniqueCode = '".$Data['Data']['unique_code']."';";
        $script .= "ddContentObj.showPrecincts = '".(boolean)$Data['Data']['show_precincts']."';";
        $script .= "ddContentObj.showDistricts = '".(boolean)$Data['Data']['show_districts']."';";
        $script .= "ddContentObj.districtsDefaultLayer = '".(boolean)$Data['Data']['districts_default_layer']."';";
        $script .= "ddContentObj.areaType = '".$Data['Data']['area']['type']."';";
        $script .= "ddContentObj.areaCountryId = '".$Data['Data']['area']['country_id']."';";
        $script .= "ddContentObj.areaAround = '".$Data['Data']['area']['around']."';";
        $script .= "ddContentObj.areaRadioCode = '".$Data['Data']['area']['radio_code']."';";
        $script .= "ddContentObj.zip = '".$Data['Data']['area']['zip']."';";
        $script .= "ddContentObj.latitude = '".$Data['Data']['area']['latitude']."';";
        $script .= "ddContentObj.longitude = '".$Data['Data']['area']['longitude']."';";
        $script .= "ddContentObj.altSrc = '';";
        $script .= "ddContentObj.headlineAbsent = true;";

       // $script .= 'YAHOO.util.Event.onDOMReady(initZipAutocomplete);';

        $this->view->fileurl = $fileurl;
        $this->view->cloneId = $CloneElID;
        $this->view->showDistricts = $Data['Data']['show_districts'];
        $this->view->showPrecincts = $Data['Data']['show_precincts'];
        $this->view->districtsDefaultLayer =$Data['Data']['districts_default_layer'];
        $this->view->areaType = $Data['Data']['area']['type'];
        $this->view->areaCountryId = $Data['Data']['area']['country_id'];
        $this->view->areaAround = $Data['Data']['area']['around'];
        $this->view->areaRadioCode = $Data['Data']['area']['radio_code'];
        $this->view->zip = $Data['Data']['area']['zip'];
        $this->view->latitude = $Data['Data']['area']['latitude'];
        $this->view->longitude = $Data['Data']['area']['longitude'];


        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupMap/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupMap/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupMap/light_block_' . $blockColumn . '.tpl');
        }


        break;

    //-------------------------------------------------------------------------------------
    case 'ddGroupMembers':
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
        $this->view->cloneId = $CloneElID;
        $this->view->assign($Data['Data']);
        if ($Data['Data']['default_index_sort'] == 1) {
            $gml = $this->currentGroup->getMembers()->setOrder('zgm.status, zgm.creation_date DESC');

            $this->view->gml = $gml;

            $members = $gml->getList();
            //This block have been commented by Komarovski because this functionality doesn't use in product templates. Actually it also have commented in old z1sky templates
            // TODO: need to implement using factory
            //if (HTTP_CONTEXT == 'z1sky') {
            //    foreach ($members as $id => $m) {
            //        if (!Z1SKY_Group_AccessManager::cohostDeletable($this->currentGroup,$m)) {
            //            unset($members[$id]);
            //        }
            //    }
            //}

            $countriesList = array();
            $result = array();
            foreach ($members as &$member) {
                if (! isset($result[$member->getCity()->getState()->getCountry()->name]) || count($result[$member->getCity()->getState()->getCountry()->name]) < $Data['Data']['display_number_in_each_region']) {
                    $result[$member->getCity()->getState()->getCountry()->name][] = $member;
                    $countriesList[$member->getCity()->getState()->getCountry()->id] = $member->getCity()->getState()->getCountry()->name;
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
            $this->view->membersSortedByCountry = $result;
            foreach ($result as &$_country) {
                foreach ($_country as &$_v) {
                    $isHCOAdd = '';
                     if ($gml->isHost($_v)) $isHCOAdd.='&nbsp;'.Warecorp::t('HOST');
                     if ($gml->isCoHost($_v)) $isHCOAdd.='&nbsp;'.Warecorp::t('CO-HOST');

                    $created = new Zend_Date($_v->getRegisterDate(), Zend_Date::ISO_8601, 'en');
                    $created->setTimezone($this->_page->_user->getTimezone());
                    $tooltip_text = '<b>' . $_v->getLogin() .$isHCOAdd. '</b><br>' . $_v->getCity()->name . '&nbsp;' . $_v->getCity()->getState()->name . '<br>'.Warecorp::t('Member since').' '. $created->toString('MMM d, hh:mm a zz');
                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_v->getId() . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_v->getId() . '", {hidedelay:100, context:"gmembers_' . $CloneElID . '_' . $_v->getId() . '", text:"' . $tooltip_text . ' ", width:"220px"});';
                }
            }
        } else {
            $members = $this->currentGroup->getMembers()->setListSize($Data['Data']['display_number_in_each_region'])->setCurrentPage(1)->setOrder('zgm.status, zgm.creation_date DESC');
            $gml = $members->getList();

            //This block have been commented by Komarovski because this functionality doesn't use in product templates. Actually it also have commented in old z1sky templates
            // TODO: need to implement using factory
            //if (HTTP_CONTEXT == 'z1sky') {
            //
            //    $notDeletable = array();
            //    foreach ($gml as $m) {
            //        if (!Z1SKY_Group_AccessManager::cohostDeletable($this->currentGroup,$m)) {
            //            $notDeletable[] = $m->getId();
            //        }
            //    }
            //    $this->view->notDeletable = $notDeletable;
                //$smarty_vars['notDeletable'] = $notDeletable;
            //}


            foreach ( $gml as $_k => &$_v) {

                $isHCOAdd = '';
                if ($members->isHost($_v)) $isHCOAdd.='&nbsp;'.Warecorp::t('HOST');
                if ($members->isCoHost($_v)) $isHCOAdd.='&nbsp;'.Warecorp::t('CO-HOST');

                $created = new Zend_Date($_v->getRegisterDate(), Zend_Date::ISO_8601, 'en');
                $created->setTimezone($this->_page->_user->getTimezone());
                $tooltip_text = '<b>' . $_v->getLogin() .$isHCOAdd. '</b><br>' . $_v->getCity()->name . '&nbsp;' . $_v->getCity()->getState()->name . '<br>'.Warecorp::t('Member since').' '. $created->toString('MMM d, hh:mm a zz');
                $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"gmembers_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"220px"});';
            }
        }
        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupMembers/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupMembers/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupMembers/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddFamilyPeople':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.displayType = '" . (isset($Data['Data']['display_type']) ? $Data['Data']['display_type'] : 0) . "';";
        $script .= "ddContentObj.defaultIndexSort = '" . (isset($Data['Data']['default_index_sort']) ? $Data['Data']['default_index_sort'] : 2) . "';";
        $script .= "ddContentObj.entityToDisplay = '" . (isset($Data['Data']['entity_to_display']) ? $Data['Data']['entity_to_display'] : 1) . "';";
        $script .= "ddContentObj.displayNumberInEachRegion = '" . (isset($Data['Data']['display_number_in_each_region']) ? $Data['Data']['display_number_in_each_region'] : 3) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['display_type'] = 0;
            $Data['Data']['default_index_sort'] = 2;
            $Data['Data']['entity_to_display'] = 1;
            $Data['Data']['display_number_in_each_region'] = 3;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['hide'] = array();
        }
        $this->view->cloneId = $CloneElID;
        $this->view->assign($Data['Data']);

        if ($Data['Data']['default_index_sort'] == 1) {

            if (intval($Data['Data']['entity_to_display']) === 2) {

                $gml = $this->currentGroup->getGroups()->setTypes('simple');
            } else {
                //echo "begin cnt - asdasdasdasda";
                $gml = $this->currentGroup->getMembers();//->setOrder('zgm.status, zgm.creation_date DESC');
            }

            $this->view->gml = $gml;

            $members = $gml->getList();
            //unset($gml);
            $countriesList = array();
            $result = array();
            //echo "begin cnt - ".sizeof($members)."\n";
            $ind = 0;
            foreach ($members as &$member) {
                $ind++;
                //echo "cnt - ".$ind." - <br/>";
                if (! isset($result[$member->getCity()->getState()->getCountry()->name]) || count($result[$member->getCity()->getState()->getCountry()->name]) < $Data['Data']['display_number_in_each_region']) {
                    $result[$member->getCity()->getState()->getCountry()->name][] = $member;
                    $countriesList[$member->getCity()->getState()->getCountry()->id] = $member->getCity()->getState()->getCountry()->name;

                }

                //ob_flush();
            }
            unset($members);
              //echo "begin ";  exit();

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
            $this->view->membersSortedByCountry = $result;
            foreach ($result as &$_country) {
                foreach ($_country as &$_v) {
                    $tooltip_text = '';
                    if (intval($Data['Data']['entity_to_display']) === 1) {
                         $isHCOAdd = '';
                         if ($gml->isHost($_v)) $isHCOAdd.='&nbsp;HOST';
                         if ($gml->isCoHost($_v)) $isHCOAdd.='&nbsp;CO-HOST';

                        $created = new Zend_Date($_v->getRegisterDate(), Zend_Date::ISO_8601, 'en');
                        $created->setTimezone($this->_page->_user->getTimezone());
                        $tooltip_text = '<b>' . $_v->getLogin() .$isHCOAdd. '</b><br>' . htmlentities($_v->getCity()->name) . '&nbsp;' . htmlentities($_v->getCity()->getState()->name) . '<br>'.Warecorp::t('Member since').' ' . $created->toString('MMM d, hh:mm a zz');
                        $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_v->getId() . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_v->getId() . '", {hidedelay:100, context:"gmembers_' . $CloneElID . '_' . $_v->getId() . '", text:"' . $tooltip_text . ' ", width:"220px"});';
                    } else {
                        $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
                        $created->setTimezone($this->_page->_user->getTimezone());
                        $tooltip_text = '<b>' . $_v->getName().'</b><br>' . htmlentities($_v->getCity()->name) . '&nbsp;' . htmlentities($_v->getCity()->getState()->name) . '<br>'.Warecorp::t('Created').' ' . $created->toString('MMM d, hh:mm a zz');
                        $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_v->getId() . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_v->getId() . '", {hidedelay:100, context:"gmembers_' . $CloneElID . '_' . $_v->getId() . '", text:"' . $tooltip_text . ' ", width:"220px"});';
                    }
                }
            }




        } else {
            if (intval($Data['Data']['entity_to_display']) === 2) {
                $members = $this->currentGroup->getGroups()->setTypes('simple')->setListSize($Data['Data']['display_number_in_each_region'])->setCurrentPage(1)->setOrder('creation_date DESC');
            } else {
                $members = $this->currentGroup->getMembers()->setListSize($Data['Data']['display_number_in_each_region'])->setCurrentPage(1);//->setOrder('zgm.status, zgm.creation_date DESC');
            }
            //$members = $this->currentGroup->getMembers()->setListSize($Data['Data']['display_number_in_each_region'])->setCurrentPage(1);//->setOrder('zgm.status, zgm.creation_date DESC');
            $gml = $members->getList();
            foreach ( $gml as $_k => &$_v) {

                if (intval($Data['Data']['entity_to_display']) === 1) {
                         $isHCOAdd = '';
                        if ($members->isHost($_v)) $isHCOAdd.='&nbsp;HOST';
                        if ($members->isCoHost($_v)) $isHCOAdd.='&nbsp;CO-HOST';

                        $created = new Zend_Date($_v->getRegisterDate(), Zend_Date::ISO_8601, 'en');
                        $created->setTimezone($this->_page->_user->getTimezone());
                        $tooltip_text = '<b>' . $_v->getLogin() .$isHCOAdd. '</b><br>' . htmlentities($_v->getCity()->name) . '&nbsp;' . htmlentities($_v->getCity()->getState()->name) . '<br>'.Warecorp::t('Member since').' ' . $created->toString('MMM d, hh:mm a zz');
                        $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"gmembers_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"220px"});';
                } else {
                    $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
                    $created->setTimezone($this->_page->_user->getTimezone());
                    $tooltip_text = '<b>' . $_v->getName().'</b><br>' . htmlentities($_v->getCity()->name) . '&nbsp;' . htmlentities($_v->getCity()->getState()->name) . '<br>'.Warecorp::t('Created').' ' . $created->toString('MMM d, hh:mm a zz');
                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"gmembers_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"220px"});';
                }
            }
        }



        $groupMode = (intval($Data['Data']['entity_to_display']) === 1)?'':'_gmode';
        $this->view->gmode = $groupMode;

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddFamilyPeople/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddFamilyPeople/compose_mode_view_' . $blockColumn.$groupMode. '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddFamilyPeople/light_block_' . $blockColumn .$groupMode. '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddFamilyMemberIndex':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.displayType = '" . (isset($Data['Data']['display_type']) ? $Data['Data']['display_type'] : 0) . "';";
        $script .= "ddContentObj.defaultIndexSort = '" . (isset($Data['Data']['default_index_sort']) ? $Data['Data']['default_index_sort'] : 1) . "';";
        $script .= "ddContentObj.displayNumberInEachRegion = '" . (isset($Data['Data']['display_number_in_each_region']) ? $Data['Data']['display_number_in_each_region'] : 3) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['display_type'] = 0;
            $Data['Data']['default_index_sort'] = 1;
            $Data['Data']['display_number_in_each_region'] = 3;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['hide'] = array();
        }
        $this->view->cloneId = $CloneElID;
        $this->view->assign($Data['Data']);
        $h = Warecorp_Group_Hierarchy_Factory::create();
        $h->setGroupId($this->currentGroup->getId());
        $h->setGroupDisplay(empty($Data['Data']['default_index_sort']) ? 1 : 0);
        $r = $h->getHierarchyList();
        $curr_hid = (sizeof($r) != 0 ? $r[0]->getId() : null);
        if ($curr_hid !== null)
            $h->loadById($curr_hid);
        $tree = $h->getHierarchyTree();
        $this->view->globalCategories = Warecorp_Group_Hierarchy::prepareTreeToPreview($h, $tree);
        $this->view->tree = $tree;
        $this->view->hierarchyList = $r;
        $this->view->curr_hid = $curr_hid;
        $this->view->current_hierarchy = $h;

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddFamilyMemberIndex/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddFamilyMemberIndex/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddFamilyMemberIndex/light_block_' . $blockColumn . '.tpl');
        }


        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupAvatar':
    case 'ddFamilyAvatar':
        $_tmp = $this->currentGroup->getAvatar()->getId();
        $avatar_id = (! empty($_tmp)) ? $avatar_id = $_tmp : 0;
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        /* @author Alexander Komarovski
        *  Added ddFamilyAvatar type to separate content blocks titles in template editor.
        *  Following code will change type of content object for existing blocks in coordination with group type.
        *  Block will change their title permanently only after saving (any changes in layout or any content block)
        */
        if ($this->currentGroup->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) {
            $script .= "ddContentObj.contentType = 'ddFamilyAvatar';";
        }
        /**/
        $script .= "ddContentObj.avatarId = " . $avatar_id . ";";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->cloneId = $CloneElID;
        $this->view->currentAvatar = $this->currentGroup->getAvatar();
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupAvatar/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupAvatar/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupAvatar/light_block_' . $blockColumn . '.tpl');
        }


        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupFamilyIcons':
        $avatar_id = (isset($Data['Data']['avatarId']) ? intval($Data['Data']['avatarId']) : 0);
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.avatarId = " . $avatar_id . ";";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->cloneId = $CloneElID;

        if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)) {
            $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);
            if (in_array($currentBrandPhoto->getGroupId(),  $this->currentGroup->getFamilyGroups()->returnAsAssoc(true)->setAssocValue('family_id')->getList()) || $avatar_id = 0) {
                $this->view->currentAvatar = $currentBrandPhoto;
            } else {
                $this->view->currentAvatar = new Warecorp_Group_BrandPhoto_Item();
            }
        } else {
            $this->view->currentAvatar = new Warecorp_Group_BrandPhoto_Item();
        }
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupFamilyIcons/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupFamilyIcons/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupFamilyIcons/light_block_' . $blockColumn . '.tpl');
        }


        break;
    //-------------------------------------------------------------------------------------
    case 'ddFamilyIcons':
        $avatar_id = (isset($Data['Data']['avatarId']) ? intval($Data['Data']['avatarId']) : 0);
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.avatarId = " . $avatar_id . ";";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['headline'] = $defHeadline;
        }
        $this->view->cloneId = $CloneElID;
        if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)) {
            $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);
            if ($currentBrandPhoto->getGroupId() == $this->currentGroup->getId() || $avatar_id = 0) {
                $this->view->currentAvatar = $currentBrandPhoto;
            }
        } else {
            $this->view->currentAvatar = new Warecorp_Group_BrandPhoto_Item();
        }
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddFamilyIcons/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddFamilyIcons/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddFamilyIcons/light_block_' . $blockColumn . '.tpl');
        }


        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupImage':
        $avatar_id = (isset($Data['Data']['avatarId']) ? intval($Data['Data']['avatarId']) : 0);
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.avatarId = " . $avatar_id . ";";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['avatarId'] = 0;
            $Data['Data']['headline'] = $defHeadline;
        }
        if (Warecorp_Photo_Standard::isPhotoExists($avatar_id)) {
            $currentImage = Warecorp_Photo_Factory::loadById($avatar_id);
            if (! Warecorp_Photo_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentGroup, $this->_page->_user)) {
                $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentGroup);
            }
        } else {
            $currentImage = Warecorp_Photo_Factory::createByOwner($this->currentGroup);
        }
        $this->view->cloneId = $CloneElID;
        $this->view->currentImage = $currentImage;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupImage/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupImage/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupImage/light_block_' . $blockColumn . '.tpl');
        }


        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupHeadline':

        $headline = $this->currentGroup->getHeadline();
        if (empty($headline)) {
            $headline = '';
        }
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
        }
        if (! isset($Data['Data']['Content'])){
            $Data['Data']['Content'] = $headline;
        }
    //-------------------------------------------------------------------------------------
    case 'ddGroupDescription':

        $intro = $this->currentGroup->getDescription();
        if (empty($intro)) {
            $intro = '';
        }

        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
        }
        if (! isset($Data['Data']['Content'])){
            $Data['Data']['Content'] = $intro;
        }

     //-------------------------------------------------------------------------------------
        $ContentType = 'ddContentBlock';
        case 'ddContentBlock':
        $content = (isset($Data['Data']['Content']) ? $Data['Data']['Content'] : '<p align="center">'.Warecorp::t('Click Edit button to change this text').'</p>');
        $content = str_replace("'","\'",$content);
       // print $content;
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
            //var_dump($Data);
            $this->view->assign($Data['Data']);
        }
        //var_dump($Data);
        $this->view->cloneId = $CloneElID;
        if (! empty($editMode)) {
            $content = $this->view->getContents('content_objects/ddContentBlock/compose_mode_edit_' . $blockColumn . '.tpl');

            $script .= 'ddContentObj.mceEditorID = tinyMCEInit("tinyMCE_' . $CloneElID . '");';

            $theme = Warecorp_Theme::loadThemeFromDB($this->currentGroup);
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
    case 'ddFamilyDiscussions':
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
                if (!isset($_v[0]) || !isset($_v[1])) continue;
                $script .= "ddContentObj.discussions_threads[" . $_k . "] = new Array();";
                //discussion
                $script .= "ddContentObj.discussions_threads[" . $_k . "][0] = " . $_v[0] . ";";
                //topic
                $script .= "ddContentObj.discussions_threads[" . $_k . "][1] = " . $_v[1] . ";";
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
        $discussions = $discussionList->findByGroupId($this->currentGroup->getId());
        //
        $templatePrefix = 'Group';
        if ($this->currentGroup->getGroupType() == 'family') {
            $templatePrefix = 'Family';
            $groupsList = $this->currentGroup->getGroups()->setTypes(array('simple'))->getList();
            $gropNames[$this->currentGroup->getId()] = $this->currentGroup->getName();
            foreach ($groupsList as &$_v) {
                $tmpList = $discussionList->findByGroupId($_v->getId());
                foreach ($tmpList as &$_vv) {
                    // $_vv->setGroup(new Warecorp_Group_Standard($_vv->getGroupId()));
                    $discussions[] = $_vv;
                }
            }
            $gropNames = array();
            foreach ($discussions as &$_discussion) {
                $_group = Warecorp_Group_Factory::loadById($_discussion->getGroupId());

                if ($_group->isPrivate() ) {
                    // skip private groups from list
                    continue;
                }
                //not empty discussions
                if ($_discussion->hasTopics())
                    $gropNames[$_discussion->getGroupId()] = $_group->getName();
            }
            $this->view->groupNames = $gropNames;
        }
        $this->view->discussionList = $discussions;
        $this->view->assign($Data['Data']);
        $this->view->cloneId = $CloneElID;
        //if tabs present
        if (! empty($Data['Data']['discussionsShowThreadsNumber']) && (! empty($Data['Data']['discussionsDisplayMostActive']) || ! empty($Data['Data']['discussionsDisplayMostRecent']))) {
            $topicsList = new Warecorp_DiscussionServer_TopicList();
            $this->view->topicsList = $topicsList;

            $fGroupsList = array($this->currentGroup->getId());

            if ($this->currentGroup->getGroupType() == 'family') {
                //$fGroupsList = array();
                foreach ($this->currentGroup->getGroups()->returnAsAssoc(true)->setAssocValue('zgi.id')->getList() as $_v) {
                    $fGroupsList[] = $_v;
                }
            }
            $this->view->fGroupsList = $fGroupsList;
        }
        //
        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/dd' . $templatePrefix . 'Discussions/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/dd' . $templatePrefix . 'Discussions/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/dd' . $templatePrefix . 'Discussions/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddFamilyTopVideos':
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.topvideos_display_most_active = '" . (isset($Data['Data']['topvideosDisplayMostActive']) ? $Data['Data']['topvideosDisplayMostActive'] : 1) . "';";
        $script .= "ddContentObj.topvideos_display_most_recent = '" . (isset($Data['Data']['topvideosDisplayMostRecent']) ? $Data['Data']['topvideosDisplayMostRecent'] : 1) . "';";
        $script .= "ddContentObj.topvideos_display_most_upped = '" . (isset($Data['Data']['topvideosDisplayMostUpped']) ? $Data['Data']['topvideosDisplayMostUpped'] : 1) . "';";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.topvideos_show_threads_number = '" . (isset($Data['Data']['topvideosShowThreadsNumber']) ? $Data['Data']['topvideosShowThreadsNumber'] : 0) . "';";

        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['topvideosDisplayMostActive'] = 1;
            $Data['Data']['topvideosDisplayMostRecent'] = 1;
            $Data['Data']['topvideosDisplayMostUpped'] = 1;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['topvideosShowThreadsNumber'] = 0;
        }

        if (! empty($Data['Data']['topvideosShowThreadsNumber']) && (! empty($Data['Data']['topvideosDisplayMostActive']) || ! empty($Data['Data']['topvideosDisplayMostUpped']) || ! empty($Data['Data']['topvideosDisplayMostRecent']))) {

            $topvideosList = Warecorp_Video_List_Factory::loadByOwner($this->currentGroup);

            if (! empty($Data['Data']['topvideosDisplayMostRecent'])) {
                $this->view->mostRecentVideos = $topvideosList->setOrder('tbl.creation_date DESC')->setListSize(intval($Data['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList();
            }
            if (! empty($Data['Data']['topvideosDisplayMostActive'])) {
                $this->view->mostActiveVideos = $topvideosList->returnInMostActiveOrder()->setListSize(intval($Data['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList();
            }

            if (! empty($Data['Data']['topvideosDisplayMostUpped'])) {
                $this->view->mostUppedVideos = $topvideosList->returnInMostUppedOrder()->setListSize(intval($Data['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList();
            }
        }

        $this->view->assign($Data['Data']);
        $this->view->cloneId = $CloneElID;

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddFamilyTopVideos/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddFamilyTopVideos/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddFamilyTopVideos/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupDocuments':
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
            $lvars = array();
            $documents_ids_tpl = array();
            $documents_ids_tpl = $Data['Data']['items'];
            $lvars['documents_ids'] = $documents_ids_tpl;
            $lvars['headline'] = (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline);
            $lvars['documents_objects'] = array();
            foreach ($lvars['documents_ids'] as $_k => &$_v) {
                //$tooltip_text = Warecorp::t("Click to select document");
                //$script .= 'ddContentObj.documents[' . $_k . '] = ' . $_v . ';';
                if (! empty($_v) && Warecorp_Document_Item::isDocumentExists($_v)) {

                    $tooltip_text = Warecorp::t("Click to select document");
                    $script .= 'ddContentObj.documents[' . $_k . '] = ' . $_v . ';';


                    $_doc = new Warecorp_Document_Item($_v);
                    if (Warecorp_Document_AccessManager_Factory::create()->canViewDocument($_doc, $_doc->getOwner(), $this->_page->_user)) {
                        $lvars['documents_objects'][$_k] = $_doc;
                        $string = $lvars['documents_objects'][$_k]->getDescription();
                        $length = 50;
                        $etc = '...';
                        if (strlen($string) > $length) {
                            $length -= strlen($etc);
                            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
                            $string = substr($string, 0, $length) . $etc;
                        }
                        $tooltip_text = $lvars['documents_objects'][$_k]->getOriginalName() . '<br>' . $lvars['documents_objects'][$_k]->getFileSize() . ' | ' . $lvars['documents_objects'][$_k]->getFileExt() . '<br />' . $string . '<br /><br />'.Warecorp::t('Created by').' <a href=\"' . $lvars['documents_objects'][$_k]->getCreator()->getUserPath('profile') . '/\">' . $lvars['documents_objects'][$_k]->getCreator()->getLogin() . '</a> '.Warecorp::t('on').' ' . $lvars['documents_objects'][$_k]->getCreationDate();
                    } else {
                        unset($lvars['documents_ids'][$_k]);
                    }

                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:500, context:"document_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' "});';

                } else {
                    if (empty($editMode)) {
                        unset ($lvars['documents_ids'][$_k]);
                    } else {
                        $tooltip_text = Warecorp::t("Click to select document");
                        $script .= 'ddContentObj.documents[' . $_k . '] = ' . $_v . ';';
                        $lvars['documents_ids'][$_k] = 0;
                        $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:500, context:"document_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' "});';
                    }
                }
                //$script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:500, context:"document_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' "});';
            }
            $this->view->assign($lvars);
            $this->view->currentUser = $this->_page->_user;
            $list = new Warecorp_List_List($this->_page->_user);
            $this->view->listsList = $list;
        }
        $this->view->cloneId = $CloneElID;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }
         if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupDocuments/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $this->view->disable_click = true;
                $content = $this->view->getContents('content_objects/ddGroupDocuments/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupDocuments/light_block_' . $blockColumn . '.tpl');
        }

        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupLists':
    case 'ddFamilyLists':
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
        $this->view->aSort = array(
            1 => 'rankdesc' ,
            2 => 'createdesc' ,
            3 => 'createasc'
        );
        $this->view->listsCategories = $listsCategories;
        $this->view->displayCategories = $displayCategories;
        $this->view->cloneId = $CloneElID;
        $this->view->assign($Data['Data']);
        $list = new Warecorp_List_List($this->currentGroup);
        $this->view->listsList = $list;

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupLists/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupLists/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupLists/light_block_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupPhotos':
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
            $galleries = $this->currentGroup->getGalleries()->setPrivacy(array(0,1))->setSharingMode('both')->getList();
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
                        //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                        $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                        foreach ($thumbs as &$_tv) {
                            $thumbnails[] = $_tv;
                        }
                    }
                    //opt
                } else {
                    $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::createByOwner($this->currentGroup);
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
            $tooltip_text = '<b>' . $_v->getTitle() . '</b><br>' . $_v->getDescription() . '<br>'.Warecorp::t('Shared by').' ' . $_v->getCreator()->getLogin() . ' '.Warecorp::t('on').' ' . $created->toString('MMM d, hh:mm a zz');
            $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddGroupPhotos_' . $CloneElID . '_' . $_k . '", text:"' . smarty_modifier_escape($tooltip_text, 'javascript') . ' ", width:"250px"});';
        }
        $this->view->thumbnails = $thumbnails;
        if (empty($Data['Data']['gallery_show_as_icons'])) {
            foreach ($gallery_hash as $_k => &$_v) {
                if ( $_v ) {
                    $created = new Zend_Date($_v->getCreateDate(), Zend_Date::ISO_8601, 'en');
                    $created->setTimezone($this->_page->_user->getTimezone());
                    $tooltip_text = '<b>' . $_v->getTitle() . '</b><br>' . $_v->getDescription() . '<br>'.Warecorp::t('Shared by').' ' . $_v->getCreator()->getLogin() . ' '.Warecorp::t('on').' ' . $created->toString('MMM d, hh:mm a zz');
                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddGroupPhotosG_' . $CloneElID . '_' . $_k . '", text:"' . smarty_modifier_escape($tooltip_text, 'javascript') . ' ", width:"250px"});';
                }
            }
        }
        $this->view->gallery_hash = $gallery_hash;
        $this->view->cloneId = $CloneElID;
        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }

        if (empty($lightReload)) {
            if (! empty($editMode)) {
                $content = $this->view->getContents('content_objects/ddGroupPhotos/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupPhotos/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupPhotos/light_block_' . $blockColumn . '.tpl');
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
        $this->view->cloneId = $CloneElID;


        if (empty($editMode)) {
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
	                            $record["content"] = urldecode($record["content"]);
	                            $pattern = "/\<img[^\>]*\>/i";
	                            $record["content"] = preg_replace($pattern, '', $record["content"]);

	                            $pattern = "/\<object.*object\>/ims";
	                            $record["content"] = preg_replace($pattern, '', $record["content"]);
	                        }

	                        //Zend_Registry::set("_temporaryCORSSWidth", 0);
	                        //Zend_Registry::set("_temporaryCORSSCol", $content_item['template_type']);

	                        if (is_string($record["content"])) {
	                            $pattern = "/(\<object)([^\>]*)(width=\")([0-9]*)(\")([^\>]*)(\>)/i";
	                            $record["content"] = preg_replace_callback($pattern, 'Warecorp_CO_Content::prcw', $record["content"]);
	                            $pattern = "/(\<object)([^\>]*)(height=\")([0-9]*)(\")([^\>]*)(\>)/i";
	                            $record["content"] = preg_replace_callback($pattern, 'Warecorp_CO_Content::prch', $record["content"]);
	                        	$rss_hash[] = $record;
	                    	}
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

    //-------------------------------------------------------------------------------------
    case 'ddFamilyVideoContentBlock':
        $video_id = (isset($Data['Data']['videoId']) ? intval($Data['Data']['videoId']) : 0);
        $content = (isset($Data['Data']['Content']) ? $Data['Data']['Content'] : '<p align="center">'.Warecorp::t('Click Edit button to change this text').'</p>');
        $script = "var ddContentObj = WarecorpDDblockApp.getObjByID('$CloneElID');";
        $script .= "ddContentObj.headline = '" . (isset($Data['Data']['headline']) ? $Data['Data']['headline'] : $defHeadline) . "';";
        $script .= "ddContentObj.innerText = '" . $content . "';";
        $script .= "ddContentObj.videoId = " . $video_id . ";";
        $script .= "tinyMCE.CloneElID = '" . $CloneElID . "';";
        $script .= "ddContentObj.headlineAbsent = true;";
        if (! isset($Data['Data'])) {
            $Data['Data'] = array();
            $Data['Data']['innerText'] = $content;
            $Data['Data']['Content'] = $content;
            $Data['Data']['headline'] = $defHeadline;
            $Data['Data']['videoId'] = 0;
        }

        if (Warecorp_Video_Standard::isVideoExists($video_id)) {
            $currentImage = Warecorp_Video_Factory::loadById($video_id);
            if (! Warecorp_Video_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentGroup, $this->_page->_user)) {
                $currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup);
            }
        } else {
            $currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup);
        }

        $this->view->video = $currentImage;

        if (isset($Data['Data'])) {
            $this->view->assign($Data['Data']);
        }
        $this->view->cloneId = $CloneElID;



        if (! empty($editMode)) {
            $content = $this->view->getContents('content_objects/ddFamilyVideoContentBlock/compose_mode_edit_' . $blockColumn . '.tpl');

       //     $script .= 'ddContentObj.mceEditorID2 = tinyMCEInit("tinyMCE_' . $CloneElID . '");';

            $theme = Warecorp_Theme::loadThemeFromDB($this->currentGroup);
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
            $content = $this->view->getContents('content_objects/ddFamilyVideoContentBlock/compose_mode_view_' . $blockColumn . '.tpl');
        }
        break;
    //-------------------------------------------------------------------------------------
    case 'ddGroupEvents':
    case 'ddFamilyEvents':
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
        if (! empty($Data['Data']['events_threads']) && $Data['Data']['events_threads']!=='undefined') {
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


                $_glist = array();
                if ($this->currentGroup->getGroupType() == 'family') {
                    $_glist = $this->currentGroup->getGroups()->returnAsAssoc(true)->setTypes(array('simple', 'family'))->getList();
                }
                $_glist[$this->currentGroup->getId()] = $this->currentGroup->getName();
                $objEvents->setOwnerIdFilter(array_keys($_glist));




                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                $objEvents->setShowCopyFilter(true);
                // privacy
                if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
                //    $objEvents->setSharingFilter(array(0,1));
                //} else {
                    $objEvents->setSharingFilter(array(0,1));
                //}
                //$objEvents->setExpiredEventFilter(false);

                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();

                $objEventList = new Warecorp_ICal_Event_List();
                $objEventList->setTimezone($currentTimezone);

                //$periodStartDate->sub(7, Zend_Date::DAY);
                $periodStartDateM->sub(7, Zend_Date::DAY);
                $periodStartEnd->add(7, Zend_Date::DAY);

             // print $periodStartDate->toString('yyyy-MM-ddTHHmmss');

                $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                $dates = $objEventList->buildRecurList($arrEvents);


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

                    $_periodStartEndM->add($_monthcntr, Zend_Date::MONTH);
                    $_periodStartEndM->add(7, Zend_Date::DAY);

                    $objEventList->setPeriodDtstart($_periodStartDateM->toString('yyyy-MM-ddTHHmmss'));
                    $objEventList->setPeriodDtend($_periodStartEndM->toString('yyyy-MM-ddTHHmmss'));
                    $dates2 = $objEventList->buildRecurList($arrEvents);

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
                            //        $eventsList[] = $_event->getRootEvent();
                            //        $eventsDates[] = substr($_date, 5, 2).'/'.substr($_date, 8, 2).'/'.substr($_date, 0, 4);
                            //        $eventsDatesAtt[] = $_date.'T'.str_replace(':','',$_time);
                            //    }
                            //}
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

                $_glist = array();
                if ($this->currentGroup->getGroupType() == 'family') {
                    $_glist = $this->currentGroup->getGroups()->returnAsAssoc(true)->setTypes(array('simple', 'family'))->getList();
                }
                $_glist[$this->currentGroup->getId()] = $this->currentGroup->getName();
                $objEvents->setOwnerIdFilter(array_keys($_glist));

                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);

                // PRIVACY etc----------------------------------------------------------
                if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
                //    $objEvents->setSharingFilter(array(0,1));
                //} else {
                    $objEvents->setSharingFilter(array(0,1));
                //}

                $objEvents->setCurrentEventFilter(true);
                $objEvents->setExpiredEventFilter(false);
                //--------------------------------------------------------------------


                $_eventsList = array();
                if (! empty($Data['Data']['events_threads'])) {
                    $_eventsList = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
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
                    $selectEventsList = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
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

                $_glist = array();
                if ($this->currentGroup->getGroupType() == 'family') {
                    $_glist = $this->currentGroup->getGroups()->returnAsAssoc(true)->setTypes(array('simple', 'family'))->getList();
                }
                $_glist[$this->currentGroup->getId()] = $this->currentGroup->getName();
                $objEvents->setOwnerIdFilter(array_keys($_glist));

                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                $objEvents->setShowCopyFilter(true);
                // privacy
                if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($this->currentGroup, $this->_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($this->currentUser, $this->_page->_user) ) {
                //    $objEvents->setSharingFilter(array(0,1));
                //} else {
                    $objEvents->setSharingFilter(array(0,1));
                //}
                $objEvents->setCurrentEventFilter(true);
                $objEvents->setExpiredEventFilter(false);

                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();

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

                           // foreach($arrEvents as &$_event){
                           //     if ($_event->getId() == $_info['uid']){
                           //         $_info['_event'] = $_event->getRootEvent();

                             $_event = new Warecorp_ICal_Event($_info['id']);
                             $_info['_event'] = $_event;

                                    //---------------------
                                    //tooltip
                                    $_k = $_info['_event']->getId() . '_' . str_replace('-','_',$_date);

                                    $objCDate=$_info['_event']->convertTZ($_info['_event']->getDtstart(), $currentTimezone);
                                    if ($_info['_event']->isAllDay()){
                                        $_timestr='All day';

                                    }else {
                                        $_timestr='&#160;'.$objCDate->toString(Warecorp_Date::TIME_SHORT).' '.$objCDate->get(Zend_Date::MERIDIEM).' ';
                                        if ($_info['_event']->isTimezoneExists()){
                                            $_timestr.=$objCDate->get(Zend_Date::TIMEZONE);
                                        }
                                    }

                                    $tooltip_text = $objCDate->toString(Warecorp_Date::DATE_FULL) . '<br />' . $_timestr . '<br /><strong><u>' . $_info['_event']->getTitle() . '</u></strong><br />'.Warecorp::t('Organizer').': ' . $_info['_event']->getCreator()->getLogin() . '<br />' . Warecorp::t('% event', array(($_info['_event']->getPrivacy() ? Warecorp::t('Private') : Warecorp::t('Public'))));
                                    $script .= 'YAHOO.example.container.ttdocs_' . $CloneElID . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $CloneElID . '_' . $_k . '", {hidedelay:100, context:"ddGroupEvents_' . $CloneElID . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"250px"});';
                                    //----------------------
                            //    }
                           // }

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
                $content = $this->view->getContents('content_objects/ddGroupEvents/compose_mode_edit_' . $blockColumn . '.tpl');
            } else {
                $content = $this->view->getContents('content_objects/ddGroupEvents/compose_mode_view_' . $blockColumn . '.tpl');
            }
        } else {
            $content = $this->view->getContents('content_objects/ddGroupEvents/light_block_' . $blockColumn . '.tpl');
        }


        break;
    //-------------------------------------------------------------------------------------
    default:
        $content = 'Unknown Content Block';
        break;
    //-------------------------------------------------------------------------------------
}
if (empty($editMode)) {
    $script .= "ddContentObj.borderStyle = '" . (! empty($Data['Style']['borderStyle']) ? $Data['Style']['borderStyle'] : '') . "';";
    $script .= "ddContentObj.borderColor = '" . (! empty($Data['Style']['borderColor']) ? $Data['Style']['borderColor'] : '') . "';";
    $script .= "ddContentObj.backgroundColor = '" . (! empty($Data['Style']['backgroundColor']) ? $Data['Style']['backgroundColor'] : '') . "';";
} /*else {
    if ( empty($lightReload) &&
        $ContentType != 'ddFamilyWidgetMap' &&
        $ContentType != 'ddGroupWidgetMap' &&
        $ContentType != 'ddGroupMap' &&
        $ContentType != 'ddFamilyMap' &&
        //$ContentType != 'ddGroupHeadline' &&
        $ContentType != 'ddIframe' &&
        $ContentType != 'ddScript' &&
        $ContentType != 'ddMogulus' &&
        $ContentType != 'ddFamilyVideoContentBlock' &&
        //$ContentType != 'ddGroupDescription' &&
        $ContentType != 'ddContentBlock' &&
            !($ContentType == 'ddFamilyLists' &&
                !empty($Data['Data']['list_display_type'])
            ) &&
            !($ContentType == 'ddGroupLists'
                && !empty($Data['Data']['list_display_type'])
             )
        ) {

        $theme = Warecorp_Theme::loadThemeFromDB($this->currentGroup);
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

        $_tTextColor = $theme->headlineTextColor;
        $_tFontStyle = $theme->headlineTextFontFamily;
        if ($theme->fillColorTransparent){
            $script .= 'ddContentObj.mceEditorBCD = "transparent";';
        }else{
            $script .= 'ddContentObj.mceEditorBCD = "'. $theme->fillColor .'";';
        }
        $script .= 'ddContentObj.mceEditorBC = "'. $_tBackgroundColor .'";';

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
    $script .= "WarecorpDDblockApp.save();"; //print $script;
}

//display edit mode buttons
if (empty($editMode)) {
    $script .= "showViewModeButtons('$CloneElID');";
}

$objResponse->addAssign($ContentDivId, "innerHTML", $content);
$objResponse->addScript($script);
