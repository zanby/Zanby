<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

/**
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_CO_Content
 * @copyright  Copyright (c) 2009
 * @author Alexander Komarovski
 */
class BaseWarecorp_CO_Content
{
    /**
     * Constructor
     *
     */
    public function __construct ()
    {}

    public static function prcw($matches)
    {
        $_nW = (Zend_Registry::get("_temporaryCORSSCol") == 'narrow')?145:390;

        Zend_Registry::set("_temporaryCORSSWidth", intval($matches[4]));
        $replacement = $matches[1].$matches[2].'width="'.$_nW.'"'.$matches[6].$matches[7];
        return $replacement;
    }
    public static function prch($matches)
    {
        $_nW = (Zend_Registry::get("_temporaryCORSSCol") == 'narrow')?145:390;

        $_oW = Zend_Registry::get("_temporaryCORSSWidth");
        if ($_oW == 0) $_oW=100;
        $_oH = intval($matches[4]);
        $_nH = intval($_oH*$_nW/$_oW)+20;
        $replacement = $matches[1].$matches[2].'height="'.$_nH.'"'.$matches[6].$matches[7];
        return $replacement;
    }






    public static function resetBlocksStyles($entity){

        $result = Warecorp_CO_Content::loadFromDB($entity->getId(), $entity->EntityTypeId);
        $data = unserialize($result);
        if (! empty($data)) {
            foreach ($data as $k=>&$v)
            {
              $v['Style']['backgroundColor']='';
              $v['Style']['borderColor']='';
              $v['Style']['borderStyle']='';
            }
           Warecorp_CO_Content::saveToDB($entity->getId(), $entity->EntityTypeId, $data);
        }
    }
    /**
     * CONTENT_OBJECTS - Load data from database
     *
     * @param integer $user_id
     * @param integer $entity_type_id
     * @author Alexander Komarovski
     */
    public static function loadFromDB ($user_id, $entity_type_id)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select()->from('zanby_dd__pages', 'data')->where('entity_id = ?', $user_id)->where('entity_type_id = ?', $entity_type_id);
        return $db->fetchOne($select);
    }

    /**
     * CONTENT_OBJECTS - Save to DB
     *
     * @param integer $user_id
     * @param integer $entity_type_id
     * @param array $items
     * @author Alexander Komarovski
     */
    public static function saveToDB ($entity_id, $entity_type_id, $items)
    {
        $db = Zend_Registry::get("DB");
        //var_dump($items);
        $data = serialize($items);
        $select = $db->select()->from('zanby_dd__pages', 'id')->where('entity_id = ? AND entity_type_id=' . $entity_type_id, $entity_id);
        $result = $db->fetchCol($select);
        if (empty($result)) {
            $db->insert('zanby_dd__pages', array(
                'data' => $data ,
                'entity_type_id' => $entity_type_id ,
                'entity_id' => $entity_id));
        } else {
            $db->update('zanby_dd__pages', array(
                'data' => $data), $db->quoteInto('entity_id = ? AND entity_type_id=' . $entity_type_id, $entity_id));
        }
    }

    /**
     * Sort function
     *
     * @param integer $a
     * @param integer $b
     * @return integer
     * @author Alexander Komarovski
     */
    public static function ddpages_sort_items ($a, $b)
    {
        $av = ! empty($a['positionVertical']) ? $a['positionVertical'] : 0;
        $bv = ! empty($b['positionVertical']) ? $b['positionVertical'] : 0;
        $a = ! empty($a['positionHorizontal']) ? $a['positionHorizontal'] : 0;
        $b = ! empty($b['positionHorizontal']) ? $b['positionHorizontal'] : 0;
        if ($a == $b) {
            if ($av !== $bv) {
                return ($av < $bv) ? - 1 : 1;
            }
            return 0;
        }
        return ($a < $b) ? - 1 : 1;
    }

    /**
     * Returns All HTML blocks for preview
     *
     * @param unknown_type $_page
     * @param unknown_type $gid
     * @param unknown_type $viewer
     */
    public static function getAllBlocksHTML (&$_page, &$entity, $viewer = null)
    {
        $content_array = "";
        $result = Warecorp_CO_Content::loadFromDB($entity->getId(), $entity->EntityTypeId);
        //print $result;die;
        //if (empty($result) || $result == "a:0:{}") {
        if (empty($result)) {
            switch ($entity->EntityTypeName) {
                case 'user':
                    $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'user'};
                    $result = $cfgCO->default_co_set;

                    $_headline = $entity->getHeadline();
                    if ( !empty( $_headline ) ) {
                        $_ta = unserialize( $result );
                        $_ta[2]['Data']['Content'] = '<div align="center"><strong>' . $_headline . '</strong></div>';
                        $result = serialize( $_ta );
                    }
                    $_intro = $entity->getIntro();
                    if ( !empty( $_intro ) ) {
                        $_ta = unserialize( $result );
                        $_ta[4]['Data']['Content'] = '<div align="center"><strong>' . $_intro . '</strong></div>';
                        $result = serialize( $_ta );
                    }
                    break;
                case 'group':

                	if ($entity->isCongressionalDistrict()) {
                        $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'district_group'};
                	} else {
                        $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'group'};
                	}
                    $result = $cfgCO->default_co_set;

                    if ($entity->getGroupType() == "family") {
                        $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'family'};
                        $result = $cfgCO->default_co_set;
                    } elseif (Warecorp::checkHttpContext('at') && $entity->getCategory() && $entity->getCategory()->id == 49) {
                        $result = $cfgCO->default_co_set_49;
                    }
                    break;
                default:
                    $result = 'a:0:{}';
                    break;
            }
        }
        $data = unserialize($result);
        if ($viewer) {
            $access = array();
            $access['photos']       = Warecorp_Photo_AccessManager::canViewGalleries($entity, $viewer);
            $access['lists']        = Warecorp_List_AccessManager_Factory::create()->canViewLists($entity, $viewer);
            $access['documents']    = Warecorp_Document_AccessManager_Factory::create()->canViewOwnerDocuments($entity, $entity, $viewer->getId());
            $access['events']       = Warecorp_ICal_AccessManager_Factory::create()->canViewEvents($entity, $viewer);
            $access['videos']       = Warecorp_Video_AccessManager::canViewGalleries($entity, $viewer);
            if ($entity->EntityTypeName == 'user') {
                $access['friends']  = Warecorp_User_AccessManager::canViewFriends($entity, $viewer);
                $access['tags']     = Warecorp_User_AccessManager::canViewTags($entity, $viewer);
                //need to tags be displayed or not in preview_mode_narrow.tpl or preview_mode_wide.tpl templates
                $_page->Template->assign('access', $access);
            }
        }
        if ( ! empty($data) ) {
            usort($data, "Warecorp_CO_Content::ddpages_sort_items");
            if (sizeof($data) != 0) {
                if ($viewer) {
                    foreach ($data as $item) {
                        switch ($item["ContentType"]) {
                            case 'ddMyPhotos':
                            case 'ddGroupPhotos':
                                if ($access['photos']) {
                                    Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                                }
                                break;
                            case 'ddMyLists':
                            case 'ddGroupLists':
                            case 'ddFamilyLists':
                                if ($access['lists']){
                                    Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                                }
                                break;
                            case 'ddMyDocuments':
                            case 'ddGroupDocuments':
                                if ($access['documents']) {
                                    Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                                }
                                break;
                            case 'ddMyEvents':
                            case 'ddGroupEvents':
                                if ($access['events']){
                                    Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                                }
                                break;
                            case 'ddMyVideos':
                            case 'ddFamilyTopVideos':
                                if ($access['videos']) {
                                    Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                                }
                                break;
                            case 'ddMyFriends':
                                if ($access['friends']) {
                                    Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                                }
                                break;
                            default :
                                Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                        }
                    }
                } else {
                    //for support old core functionality
                    foreach ($data as $item) {
                        Warecorp_CO_Content::getBlockHTML($_page, $content_array, $item, $entity);
                    }
                }
            }
        }
        if (!empty($content_array)){
            foreach ($content_array as $k=>&$v) $v['id'] = 'ddContentObject'.$k;
        }
        return $content_array;
    }

    /**
     * Returns HTML block for preview
     *
     * @param unknown_type $_page
     * @param unknown_type $html
     * @param unknown_type $item
     * @param unknown_type $gid
     * @return unknown
     */
    public static function getBlockHTML (&$_page, &$content_array, $item, $entity)
    {
        $_smarty = new Warecorp_View_Smarty();
        $_smarty->setTemplatesDir(TEMPLATES_DIR);
        $_smarty->setCompiledDir(APP_VAR_DIR.'/_compiled/site/');
        $_smarty->assign('user', $_page->_user);
        $dateObj = new Zend_Date();
        $dateObj->setTimezone($_page->_user->getTimezone());
        $_page->Template->assign('TIMEZONE', $dateObj->get(Zend_Date::TIMEZONE));
        $_smarty->assign('TIMEZONE', $dateObj->get(Zend_Date::TIMEZONE));
        $smarty_vars = array();
        $smarty_vars["BorderStyle"] = "";
        $content_item = array();


        $content_item['target'] = (! empty($item['positionHorizontal']) ? $item['positionHorizontal'] : 1);

        /* Komarovski @TODO put in correct place*/
	    /* CO, CO Themes, CO Layout*/
        $cfgCOLayout = Warecorp_Config_Loader::getInstance()->getAppConfig('COLT/cfg.layout.xml')->{'layout'};
		/**/

        if (empty($content_item['target'])) {
            $content_item['template_type'] = $cfgCOLayout->default_template_for_co;
        } else {
            $content_item['template_type'] = ($content_item['target'] == 1) ? $cfgCOLayout->target1_template_for_co : $cfgCOLayout->target2_template_for_co;
        }


        switch ($item["ContentType"]) {
            case 'ddRoundEvents':
                if (!Warecorp::checkHttpContext('zccf')) {
                    $smarty_vars['Content'] = '';break;
                }
                $round = Warecorp_Round_Item::getCurrentRound($entity->getId());
                $_page->Template->assign('Round', Warecorp_Round_Item::getCurrentRound($entity->getId()));
                if (! isset($item['Data']) || count($item['Data'])<4) {
                    $item['Data'] = array();
                    $item['Data']['display_type'] = 0;
                    $item['Data']['open'] = 1;
                    $item['Data']['byinvitation'] = 1;
                    $item['Data']['full'] = 0;
                    $item['Data']['past'] = 0;
                }
                $_page->Template->assign($item['Data']);

                $currentTimezone = ( null !== $_page->_user->getId() && null !== $_page->_user->getTimezone() ) ? $_page->_user->getTimezone() : 'UTC';
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
                $objEvents->setOwnerIdFilter($entity->getId());
                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                $objEvents->setWithVenueOnly( true );
                // privacy
                if ( $AccessManager->canViewPublicEvents($entity, $_page->_user) && $AccessManager->canViewPrivateEvents($entity, $_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0,1));
                } elseif ( $AccessManager->canViewPublicEvents($entity, $_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(0));
                } elseif ( $AccessManager->canViewPrivateEvents($entity, $_page->_user) ) {
                    $objEvents->setPrivacyFilter(array(1));
                } else {
                    $objEvents->setPrivacyFilter(null);
                }
                // sharing
                if ( $AccessManager->canViewSharedEvents($entity, $_page->_user) ) {
                    $objEvents->setSharingFilter(array(0,1));
                } else {
                    $objEvents->setSharingFilter(array(0));
                }

                $objEvents->setCurrentEventFilter(true);
                $objEvents->setExpiredEventFilter(true);
                if ($round->getRoundId()) {
                    $objEvents->setFilterPartOfRound($round->getRoundId());
                }else{
                    $objEvents->setFilterPartOfNonRound(true);
                }

                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();

                $arrEventIds = array();
                foreach ($arrEvents as $id=>$v) {
                    $event = new Warecorp_ICal_Event($id);

                    $strFirstDate = $lstEventsObj->findFirstEventDate($event, $objNowDate);
                    if ( null !== $strFirstDate ) $event->setDtstart($strFirstDate);


                    $type = 'past';

                    if ($event->getDtstart()->isLater($objNowDate)) { //Filter only NON PAST EVENTS
                        $attendee = $event->getAttendee();
                        if ($event->getMaxRsvp() > 0 && $event->getMaxRsvp() <= $attendee->setAnswerFilter('YES')->getCount()) {
                            $type='full';
                        }else{
                            $invitation = $event->getInvite();

                            if ($invitation->getIsAnybodyJoin() || $invitation->getAllowGuestToInvite()) {
                                $type = 'open';
                            }else{
                                $type = 'byinvitation';
                            }
                        }
                    }
                    if ($item['Data'][$type] == 0) continue;
                    $arrEventIds[$event->getId()]=$type;
                }

                //  Save events location to cache to use it on map
                $mapCache = md5(uniqid(mt_rand(), true));
                $cache = Warecorp_Cache::getFileCache();
                $cache->save($arrEventIds, $mapCache, array(), 60*60*10);
                $_page->Template->assign('mapCache',$mapCache);

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/'.$item["ContentType"].'/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;
            case 'ddRoundInfo':
                if (!Warecorp::checkHttpContext('zccf')) {
                    $smarty_vars['Content'] = '';break;
                }

                $_page->Template->assign('Round', Warecorp_Round_Item::getCurrentRound($entity->getId()));

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/'.$item["ContentType"].'/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;
             //-------------------------------------------------------------------------------------
            case 'ddMogulus':
                if (! isset($item['Data'])) {
                    $item['Data'] = array();

                    $coSettings = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks_settings.xml');
                    $defaultChannel = $coSettings->ddMogulus->defaultChannel;

                    $channel = (isset($defaultChannel)) ? $defaultChannel : '';

                    $item['Data']['channel'] = $channel;
                }

                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddMogulus/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;
             //-------------------------------------------------------------------------------------
            case 'ddIframe':
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['alt_src'] = 'f5d439c0b3';
                }

                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddIframe/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;
            //-------------------------------------------------------------------------------------
            case 'ddScript':

                 if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
                 }

                 $item['Data']['custom_height'] = (empty($item['Data']['custom_height'])?0:intval($item['Data']['custom_height']));

                 $filename = SCRIPTING_UPLOAD_PATH."/".md5($entity->EntityTypeName).md5($entity->getId()).$item['Data']['unique_code'].'.dat';
                 if (file_exists($filename) && filesize($filename)>0) {
                     $handle = fopen($filename, "a+");
                     $contents = fread($handle, filesize($filename));
                     fclose($handle);
                     $fileurl = SCRIPTING_UPLOAD_URL."/".md5($entity->EntityTypeName).md5($entity->getId()).$item['Data']['unique_code'].'.html';
                 } else {
                    $contents = '';
                    $fileurl = '';
                 }


                $_page->Template->assign('fileurl', $fileurl);
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddScript/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;

            //-------------------------------------------------------------------------------------
		    case 'ddGroupWidgetMap':
		    case 'ddFamilyWidgetMap':
		        $group = Warecorp_Group_Factory::loadById($entity->getId());

		        if (! isset($item['Data'])) {
		            $item['Data'] = array();
		            $item['Data']['defaultDisplayType'] = 0;
		            $item['Data']['displayRange'] = 1;
		            $item['Data']['eventsDisplayType'] = 0;
		            $item['Data']['eventToDisplayId'] = 0;
		        }

		        $resultString = '';
		        if ($item['Data']['defaultDisplayType'] == 1) {$resultString .= '&defaultDisplayType=events';}
		        if ($item['Data']['displayRange'] == 1) {$resultString .= '&displayRange=district';}
		        if ($item['Data']['eventsDisplayType'] == 1) {
		            $resultString .= '&eventsDisplayType=nda';
		            if ($item['Data']['eventToDisplayId']) {
		                $resultString .= '&eventToDisplayId='.$item['Data']['eventToDisplayId'];
		            }
		        }
		        $resultString .= '&groupContext='.$group->getId();
		        $resultString .= '&r='.rand();
		        if ($content_item['template_type'] == 'wide') {
	                $_width=440;
	            } elseif ($content_item['template_type'] == 'narrow') {
	                $_width=240;
	            }
		        //@TODO change hardcoded url
		        //&country=United%States&zoom=3
		        $resultString = BASE_URL.'/widget.js?wtype=map&wdtype=iniframe&needDistrictLayer=1&width='.$_width.'&height=300&kmlControlInternalId=getKMLLink'.$resultString;

		        $iframeSrc = $resultString;

		        $_page->Template->assign('iframeSrc', $iframeSrc);
		        $_page->Template->assign($item['Data']);
		        $_page->Template->assign('cloneId', $item['ID']);
		        $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupWidgetMap/preview_mode_' . $content_item['template_type'] . '.tpl');

		        break;


            //-------------------------------------------------------------------------------------
            case 'ddFamilyMap':


    /*
                if (!isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
                    $item['Data']['headline'] = $defHeadline;
                }

                $filebase = "/".md5('family_map').md5($entity->getId()).$item['Data']['unique_code'].'.html';
                $filename = SCRIPTING_UPLOAD_PATH.$filebase;
                $fileurl = SCRIPTING_UPLOAD_URL.$filebase;

                $_page->Template->assign('fileurl', $fileurl);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyMap/preview_mode_' . $content_item['template_type'] . '.tpl');
*/


    if (isset($item['Data'])) {
            		// сделать файл заново

            $Data = $item;
	        $children = $entity->getGroups()->setTypes(array('simple','family'))->getList();

	        $location = new Warecorp_Location();
	        $countries = $location->getCountriesListAssoc(true);
	        unset($countries[0]);

	        $filebase = "/".md5('family_map').md5($entity->getId()).$Data['Data']['unique_code'].'.html';
	        $filename = SCRIPTING_UPLOAD_PATH.$filebase;
	        $fileurl = SCRIPTING_UPLOAD_URL.$filebase;

	        if ($content_item['template_type'] == 'wide') {
	            $width=340;
	            $height=340;
	            $additionalControls = array('GLargeMapControl');
	        } elseif ($content_item['template_type'] == 'narrow') {
	            $width=183;
	            $height=183;
	        }
            $handle = fopen($filename, "w+");
            $_page->Template->assign('children', $children);
            $_page->Template->assign('width', $width);
            $_page->Template->assign('height', $height);

            if ($Data['Data']['show_districts'] && $Data['Data']['districts_default_layer'])
                $_page->Template->assign('mapType', 'G_MAP_OVERLAY');
            else
                $_page->Template->assign('mapType', '');

            if ($Data['Data']['show_districts'])
                $_page->Template->assign('needDistrictLayer', 1);

            if ($Data['Data']['area']['country_id'] && $Data['Data']['area']['type']=='default') {
                $_page->Template->assign('countryName',$countries[$Data['Data']['area']['country_id']]);
            }

            if ($Data["Data"]["area"]["around"]) {
                $zoom = Z1SKY_GMap_Utils::getZoomForCO($width,$height,$Data["Data"]["area"]["around"]);
            }

            if ($Data['Data']['area']['type']=='custom'
                && $Data["Data"]["area"]["radio_code"] == 'lat'
                && $Data["Data"]["area"]["latitude"]
                && $Data["Data"]["area"]["longitude"]
                && $Data["Data"]["area"]["around"]){
                $_page->Template->assign('latitude',$Data["Data"]["area"]["latitude"]);
                $_page->Template->assign('longitude',$Data["Data"]["area"]["longitude"]);
                $_page->Template->assign('zoom',$zoom);
            }

            if ($Data['Data']['area']['type']=='custom'
                && $Data["Data"]["area"]["radio_code"] == 'zip'
                && $Data["Data"]["area"]["around"]
                && $Data["Data"]["area"]["zip"]) {
                $_page->Template->assign('zip',$Data["Data"]["area"]["zip"]);
                $_page->Template->assign('zoom',$zoom);
            }

            if (isset($additionalControls)) {
                $_page->Template->assign('additionalControls',$additionalControls);
            }

            $_page->Template->assign('gmapKey',Z1SKY_GMap_Utils::getCOGMapKey());

            fwrite($handle, $_page->Template->getContents('content_objects/ddFamilyMap/html_content.tpl'));
            fclose($handle);

            $_page->Template->assign($item['Data']);
            $_page->Template->assign('fileurl', $fileurl);
            $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyMap/preview_mode_' . $content_item['template_type'] . '.tpl');

       } else {   // поднять старый файл
                if (!isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
                    $item['Data']['headline'] = $defHeadline;
                }

                $filebase = "/".md5('family_map').md5($entity->getId()).$item['Data']['unique_code'].'.html';
                $filename = SCRIPTING_UPLOAD_PATH.$filebase;
                $fileurl = SCRIPTING_UPLOAD_URL.$filebase;

                $_page->Template->assign('fileurl', $fileurl);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyMap/preview_mode_' . $content_item['template_type'] . '.tpl');

       }


                break;

            //-------------------------------------------------------------------------------------
            case 'ddElectedOfficial':
                $regionalFlag = $entity->getCongressionalDistrict();
                $state = null;
                $district = null;

                if ( $regionalFlag !== null && $regionalFlag != '')
                {
                    $state = substr($regionalFlag, 0, 2);
                    $district = substr($regionalFlag, 2);

                    $theme = Zend_Registry::get("AppTheme");
                    $legislators = Z1SKY_Location_District::getSunlabLegislators($state, $district, $theme);
                    $_page->Template->assign('legislators', $legislators);
                    $_page->Template->assign('headline', "Elected Officials");
                    $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddElectedOfficial/preview_mode_' . $content_item['template_type'] . '.tpl');
                }
            break;
            case 'ddGroupMap':

                if (isset($item['Data'])) {
                    // сделать файл заново

                    $Data = $item;
                  //  $children = array($entity);

                    $customMarkers = array();

                    $cfgGmap = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.gmap.xml');
                    if ($cfgGmap) {
                        if ($cfgGmap->icons->groupMap) {
                            $groupMapIcon = $cfgGmap->icons->groupMap;
                        }
                    }

                    if (isset($groupMapIcon)) {
                        $customMarker = array('itemIds' => array($entity->getId()),
                                               'markerImg' => $groupMapIcon);
                        $customMarkers[] = $customMarker;
                    }

                    if ($customMarkers) {
                        $_page->Template->assign('customMarkers', $customMarkers);
                    }

                    if ($customMarkers) {
                        $_page->Template->assign('customMarkers', $customMarkers);
                    }

                    $groupSearch = new Z1SKY_Group_Search();
                    $groupSearch->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);

                    $s['district']  = Z1SKY_Location_District::getDistrictsByZipCode($entity->getZipCode());
                    $s['country']   = 1;
                    $state = Warecorp_Location_City::create($entity->getCityId())->getState();
                    $s['state']     = $state->id;

                    if ($s['district']) {
                        $maxMinCoordinates = Z1SKY_Location_District::getCoordinates($s['district'],$state->code);

                        if ($maxMinCoordinates) {
                            $_page->Template->assign('maxMinCoordinates', $maxMinCoordinates);
                        }
                    }

                    $groups = $groupSearch->searchByCriterions($s);

                    foreach ($groups as &$group) {
                       $group = Warecorp_Group_Factory::loadById($group,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                    }

                    $children = $groups;




                    $location = new Warecorp_Location();
                    $countries = $location->getCountriesListAssoc(true);
                    unset($countries[0]);

                    $filebase = "/".md5('group_map').md5($entity->getId()).$Data['Data']['unique_code'].'.html';
                    $filename = SCRIPTING_UPLOAD_PATH.$filebase;
                    $fileurl = SCRIPTING_UPLOAD_URL.$filebase;

                    if ($content_item['template_type'] == 'wide') {
                        $width=340;
                        $height=340;
                        $additionalControls = array('GLargeMapControl');
                    } elseif ($content_item['template_type'] == 'narrow') {
                        $width=183;
                        $height=183;
                    }
                    $handle = fopen($filename, "w+");
                    $_page->Template->assign('children', $children);
                    $_page->Template->assign('width', $width);
                    $_page->Template->assign('height', $height);

                    if ($Data['Data']['show_districts'] && $Data['Data']['districts_default_layer'])
                        $_page->Template->assign('mapType', 'G_MAP_OVERLAY');
                    else
                        $_page->Template->assign('mapType', '');

                    if ($Data['Data']['show_districts'])
                        $_page->Template->assign('needDistrictLayer', 1);

                    if ($Data['Data']['area']['country_id'] && $Data['Data']['area']['type']=='default')
                        $_page->Template->assign('countryName',$countries[$Data['Data']['area']['country_id']]);
                        if ($Data['Data']['area']['country_id']!=1) {
                            $_page->Template->assign('noAutoPosition',1);
                        }

                    if ($Data["Data"]["area"]["around"]) {
                        $zoom = Z1SKY_GMap_Utils::getZoomForCO($width,$height,$Data["Data"]["area"]["around"]);
                    }

                    if ($Data['Data']['area']['type']=='custom') {
                        $_page->Template->assign('noAutoPosition',1);
                    }

                    if ($Data['Data']['area']['type']=='custom'
                        && $Data["Data"]["area"]["radio_code"] == 'lat'
                        && $Data["Data"]["area"]["latitude"]
                        && $Data["Data"]["area"]["longitude"]
                        && $Data["Data"]["area"]["around"]){
                        $_page->Template->assign('latitude',$Data["Data"]["area"]["latitude"]);
                        $_page->Template->assign('longitude',$Data["Data"]["area"]["longitude"]);
                        $_page->Template->assign('zoom',$zoom);
                    }

                    if ($Data['Data']['area']['type']=='custom'
                        && $Data["Data"]["area"]["radio_code"] == 'zip'
                        && $Data["Data"]["area"]["around"]
                        && $Data["Data"]["area"]["zip"]) {
                        $_page->Template->assign('zip',$Data["Data"]["area"]["zip"]);
                        $_page->Template->assign('zoom',$zoom);
                    }

                    if (isset($additionalControls)) {
                        $_page->Template->assign('additionalControls',$additionalControls);
                    }

                    $_page->Template->assign('gmapKey',Z1SKY_GMap_Utils::getCOGMapKey());

                    fwrite($handle, $_page->Template->getContents('content_objects/ddGroupMap/html_content.tpl'));
                    fclose($handle);

                    $_page->Template->assign($item['Data']);
                    $_page->Template->assign('fileurl', $fileurl);
                    $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupMap/preview_mode_' . $content_item['template_type'] . '.tpl');

               } else {   // поднять старый файл
                    if (!isset($item['Data'])) {
                        $item['Data'] = array();
                        $item['Data']['unique_code'] = Warecorp_Common_Functions :: getRandomString();
                        $item['Data']['headline'] = $defHeadline;
                    }

                    $filebase = "/".md5('group_map').md5($entity->getId()).$item['Data']['unique_code'].'.html';
                    $filename = SCRIPTING_UPLOAD_PATH.$filebase;
                    $fileurl = SCRIPTING_UPLOAD_URL.$filebase;

                    $_page->Template->assign('fileurl', $fileurl);
                    if (isset($item['Data'])) {
                        $_page->Template->assign($item['Data']);
                    }

                    $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupMap/preview_mode_' . $content_item['template_type'] . '.tpl');
               }


                break;
            //-------------------------------------------------------------------------------------
            case 'ddMyFriends':
                $userInfo = new Warecorp_User('id', $entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['display_type'] = 0;
                    $item['Data']['default_index_sort'] = 2;
                    $item['Data']['display_number_in_each_region'] = 3;
                    $item['Data']['headline'] = 'This is the default headline';
                } elseif ($item['Data']['default_index_sort'] == 1) {
                    $friends = $userInfo->getFriendsList()->setOrder('created DESC')->getList();
                    $result = array();
                    foreach ($friends as &$friend) {
                        if (! isset($result[$friend->getFriend()->getCity()->getState()->getCountry()->name]) || count($result[$friend->getFriend()->getCity()->getState()->getCountry()->name]) < $item['Data']['display_number_in_each_region']) {
                            $result[$friend->getFriend()->getCity()->getState()->getCountry()->name][] = $friend;
                        }
                    }
                    $_page->Template->assign('friendsSortedByCountry', $result);
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('currentUser', $userInfo);
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddMyFriends/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddMyLists':
                $userInfo = new Warecorp_User('id', $entity->getId());
                $displayCategories = array();
                if (! empty($item['Data']['list_categories_to_display'])) {
                    foreach ($item['Data']['list_categories_to_display'] as &$_v) {
                        $displayCategories[$_v] = 1;
                    }
                }
                $listsCategories = Warecorp_List_Item::getListTypesListAssoc();
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['list_categories_to_display'] = array();
                    $item['Data']['list_display_type'] = 0;
                    $item['Data']['list_default_index_sort'] = 0;
                    $item['Data']['list_display_number_in_each_category'] = 3;
                    $item['Data']['list_show_summaries'] = 1;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['list_to_display'] = 0;
                    $item['Data']['list_default_sort'] = 1;
                }
                if (! empty($item['Data']['list_to_display']) && ! empty($item['Data']['list_display_type'])) {
                    $_list = new Warecorp_List_Item($item['Data']['list_to_display']);
                    if (Warecorp_List_Item::isListShared($_list->getId(), 'user', $userInfo->getId()) || $_list->getIsPrivate()) {
                        if (Warecorp_List_AccessManager_Factory::create()->canViewPrivateLists($userInfo, $_page->_user)) {
                            $_smarty->assign('currentList', $_list);
                        }
                    } else {
                        $_smarty->assign('currentList', $_list);
                    }
                }
                $_smarty->assign('listsCategories', $listsCategories);
                $_smarty->assign('displayCategories', $displayCategories);
                $_smarty->assign('cloneId', $item['ID']);
                $_smarty->assign('currentUser', $userInfo);
                $_smarty->assign($item['Data']);
                $list = new Warecorp_List_List($userInfo);
                $_smarty->assign('listsList', $list);
                $smarty_vars['Content'] = $_smarty->getContents('content_objects/ddMyLists/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddGroupLists':
            case 'ddFamilyLists':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                $displayCategories = array();
                if (! empty($item['Data']['list_categories_to_display'])) {
                    foreach ($item['Data']['list_categories_to_display'] as &$_v) {
                        $displayCategories[$_v] = 1;
                    }
                }
                $listsCategories = Warecorp_List_Item::getListTypesListAssoc();
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['list_categories_to_display'] = array();
                    $item['Data']['list_display_type'] = 0;
                    $item['Data']['list_default_index_sort'] = 0;
                    $item['Data']['list_display_number_in_each_category'] = 3;
                    $item['Data']['list_show_summaries'] = 1;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['list_to_display'] = 0;
                    $item['Data']['list_default_sort'] = 1;
                }
                if (! empty($item['Data']['list_to_display']) && ! empty($item['Data']['list_display_type'])) {
                    $_list = new Warecorp_List_Item($item['Data']['list_to_display']);
                    if (Warecorp_List_Item::isListShared($_list->getId(), 'group', $group->getId()) || $_list->getIsPrivate()) {
                        if (Warecorp_List_AccessManager_Factory::create()->canViewPrivateLists($group, $_page->_user)) {
                            $_smarty->assign('currentList', $_list);
                        }
                    } else {
                        $_smarty->assign('currentList', $_list);
                    }
                }
                $_smarty->assign('listsCategories', $listsCategories);
                $_smarty->assign('displayCategories', $displayCategories);
                $_smarty->assign('cloneId', $item['ID']);
                $_smarty->assign('currentGroup', $group);
                $_smarty->assign($item['Data']);
                $list = new Warecorp_List_List($group);
                $_smarty->assign('listsList', $list);
                $smarty_vars['Content'] = $_smarty->getContents('content_objects/ddGroupLists/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddGroupMembers':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['display_type'] = 0;
                    $item['Data']['default_index_sort'] = 2;
                    $item['Data']['display_number_in_each_region'] = 3;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['hide'] = array();
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);
                if ($item['Data']['default_index_sort'] == 1) {
                    $gml =$group->getMembers()->setOrder('zgm.status, zgm.creation_date DESC');
                    $members = $gml->getList();

                    $countriesList = array();
                    $result = array();
                    foreach ($members as &$member) {
                        if (! isset($result[$member->getCity()->getState()->getCountry()->name]) || count($result[$member->getCity()->getState()->getCountry()->name]) < $item['Data']['display_number_in_each_region']) {
                            $result[$member->getCity()->getState()->getCountry()->name][] = $member;
                            $countriesList[$member->getCity()->getState()->getCountry()->id] = $member->getCity()->getState()->getCountry()->name;
                        }
                    }
                    $counter = 0;
                    foreach ($countriesList as $_k => &$_v) {
                        if (! empty($item['Data']['hide'][$_k])) {
                            $item['Data']['hide'][$_k] = 1;
                        } else {
                            $item['Data']['hide'][$_k] = 0;
                        }
                        $counter ++;
                    }
                    $_page->Template->assign('countriesList', $countriesList);
                    $_page->Template->assign('membersSortedByCountry', $result);
                    $_page->Template->assign('gml', $gml);
                }
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupMembers/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddFamilyVideoContentBlock':
                $group = Warecorp_Group_Factory::loadById($entity->getId());

                $video_id = (isset($item['Data']['videoId']) ? intval($item['Data']['videoId']) : 0);
                $content = (isset($item['Data']['Content']) ? $item['Data']['Content'] : '<p align="center">Click Edit button to change this text</p>');

                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['innerText'] = $content;
                    $item['Data']['Content'] = $content;
                    $item['Data']['headline'] = $defHeadline;
                    $item['Data']['videoId'] = 0;
                }

                if (Warecorp_Video_Standard::isVideoExists($video_id)) {
                    $currentImage = Warecorp_Video_Factory::loadById($video_id);
                    if (! Warecorp_Video_AccessManager::canViewGallery($currentImage->getGallery(), $group, $_page->_user)) {
                        $currentImage = Warecorp_Video_Factory::createByOwner($group);
                    }
                } else {
                    $currentImage = Warecorp_Video_Factory::createByOwner($group);
                }

                $_page->Template->assign('video', $currentImage);

                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyVideoContentBlock/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;
            //-------------------------------------------------------------------------------------
            case 'ddFamilyPeople':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['display_type'] = 0;
                    $item['Data']['default_index_sort'] = 2;
                    $item['Data']['entity_to_display'] = 1;
                    $item['Data']['display_number_in_each_region'] = 3;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['hide'] = array();
                }

                $cache_key = 'ddFamilyPeople_'.md5( Warecorp::r_implode('_', $item['Data']) ).'_'.$entity->getId();
                $cache       = Warecorp_Cache::getFileCache();
                if ( $data = $cache->load($cache_key) ) {
                    $smarty_vars['Content'] = $data;
                    break;
                }

                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);

                if ($item['Data']['default_index_sort'] == 1) {

                    if (intval($item['Data']['entity_to_display']) === 2) {
                        $gml = $group->getGroups()->setTypes('simple');
                    } else {
                        $gml = $group->getMembers();
                    }

                    $_page->Template->assign('gml', $gml);

                    $members = $gml->getList();
                    $countriesList = array();
                    $result = array();
                    foreach ($members as &$member) {
                        if (! isset($result[$member->getCity()->getState()->getCountry()->name]) || count($result[$member->getCity()->getState()->getCountry()->name]) < $item['Data']['display_number_in_each_region']) {
                            $result[$member->getCity()->getState()->getCountry()->name][] = $member;
                            $countriesList[$member->getCity()->getState()->getCountry()->id] = $member->getCity()->getState()->getCountry()->name;
                        }
                    }
                    $counter = 0;
                    foreach ($countriesList as $_k => &$_v) {
                        if (! empty($item['Data']['hide'][$_k])) {
                            $item['Data']['hide'][$_k] = 1;
                        } else {
                            $item['Data']['hide'][$_k] = 0;
                        }
                        $counter ++;
                    }
                    $_page->Template->assign('countriesList', $countriesList);
                    $_page->Template->assign('membersSortedByCountry', $result);

                }

                $groupMode = (intval($item['Data']['entity_to_display']) === 1)?'':'_gmode';
                $_page->Template->assign('gmode', $groupMode);

                $data = $_page->Template->getContents('content_objects/ddFamilyPeople/preview_mode_' . $content_item['template_type'] .$groupMode. '.tpl');
                $smarty_vars['Content'] = $data;
                $cache->save($data, $cache_key, array(), 300);

                break;
            //-------------------------------------------------------------------------------------
            case 'ddFamilyTopVideos':
            $group = Warecorp_Group_Factory::loadById($entity->getId());

            if (! isset($item['Data'])) {
                $item['Data'] = array();
                $item['Data']['topvideosDisplayMostActive'] = 1;
                $item['Data']['topvideosDisplayMostRecent'] = 1;
                $item['Data']['topvideosDisplayMostUpped'] = 1;
                $item['Data']['headline'] = 'This is the default headline';
                $item['Data']['topvideosShowThreadsNumber'] = 0;
            }

            if (! empty($item['Data']['topvideosShowThreadsNumber']) && (! empty($item['Data']['topvideosDisplayMostActive']) || ! empty($item['Data']['topvideosDisplayMostRecent'])  || ! empty($item['Data']['topvideosDisplayMostUpped']) )) {

                $topvideosList = Warecorp_Video_List_Factory::loadByOwner($group);
                if (! empty($item['Data']['topvideosDisplayMostRecent'])) {
                    $_page->Template->assign('mostRecentVideos', $topvideosList->setOrder('tbl.creation_date DESC')->setListSize(intval($item['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList());
                }
                if (! empty($item['Data']['topvideosDisplayMostActive'])) {
                    $_page->Template->assign('mostActiveVideos', $topvideosList->returnInMostActiveOrder()->setListSize(intval($item['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList());
                }

                if (! empty($item['Data']['topvideosDisplayMostUpped'])) {
                    $_page->Template->assign('mostUppedVideos', $topvideosList->returnInMostActiveOrder(false)->returnInMostUppedOrder(true)->setListSize(intval($item['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList());
                }
            }

            $_page->Template->assign('cloneId', $item['ID']);
            $_page->Template->assign($item['Data']);

            $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyTopVideos/preview_mode_' . $content_item['template_type']. '.tpl');

            break;
            //-------------------------------------------------------------------------------------
            case 'ddFamilyMemberIndex':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['display_type'] = 0;
                    $item['Data']['default_index_sort'] = 1;
                    $item['Data']['display_number_in_each_region'] = 3;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['hide'] = array();
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);


                $h = Warecorp_Group_Hierarchy_Factory::create();
                $h->setGroupId($group->getId());
                $h->addSystemHierarchy();

                $h->setGroupDisplay(empty($item['Data']['default_index_sort']) ? 1 : 0);
                $r = $h->getHierarchyList();
                $curr_hid = (sizeof($r) != 0 ? $r[0]->getId() : null);
                if ($curr_hid !== null) {
                    $h->loadById($curr_hid);
                }
                $tree = $h->getHierarchyTree();


                $_page->Template->assign('globalCategories', Warecorp_Group_Hierarchy::prepareTreeToPreview($h, $tree));
                $_page->Template->assign('tree', $tree);
                $_page->Template->assign("hierarchyList", $r);
                $_page->Template->assign('curr_hid', $curr_hid);
                $_page->Template->assign('current_hierarchy', $h);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyMemberIndex/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // -------------------------------------------------------------------------------------
            case 'ddGroupAvatar':
            case 'ddFamilyAvatar':
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                $_page->Template->assign('currentAvatar', $group->getAvatar());
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupAvatar/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddFamilyIcons':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                $avatar_id = (isset($item['Data']['avatarId']) ? intval($item['Data']['avatarId']) : 0);
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['headline'] = 'This is the default headline';
                }
                if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)) {
                    $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);
                    if ($currentBrandPhoto->getGroupId() == $group->getId() || $avatar_id = 0) {
                        $_page->Template->assign('currentAvatar', $currentBrandPhoto);
                    }
                } else {
                    $_page->Template->assign('currentAvatar', new Warecorp_Group_BrandPhoto_Item());
                }
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $_page->Template->assign('currentUser', $_page->_user);
                $_page->Template->assign('currentGroup', $group);
                $_page->Template->assign('cloneId', $item['ID']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddFamilyIcons/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddGroupFamilyIcons':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                $avatar_id = (isset($item['Data']['avatarId']) ? intval($item['Data']['avatarId']) : 0);

                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['headline'] = $defHeadline;
                }

                if (Warecorp_Group_BrandPhoto_Item::isPhotoExists($avatar_id)) {
                    $currentBrandPhoto = new Warecorp_Group_BrandPhoto_Item($avatar_id);

                    $fGroup = Warecorp_Group_Factory::loadById($currentBrandPhoto->getGroupId());

                    if ( ($fGroup->getMembers()->isMemberExistsAndApproved($_page->_user->getId()) && in_array($fGroup->getId(),  $group->getFamilyGroups()->returnAsAssoc(true)->setAssocValue('family_id')->getList()) )    ||
                    ($group->getMembers()->isMemberExistsAndApproved($_page->_user->getId()) && in_array($currentBrandPhoto->getGroupId(),  $group->getFamilyGroups()->returnAsAssoc(true)->setAssocValue('family_id')->getList()) ) ||
                    $avatar_id = 0) {
                        $_page->Template->assign('currentAvatar', $currentBrandPhoto);
                    } else {
                        $_page->Template->assign('currentAvatar', new Warecorp_Group_BrandPhoto_Item() );
                    }
                } else {
                    $_page->Template->assign('currentAvatar', new Warecorp_Group_BrandPhoto_Item() );
                }



                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $_page->Template->assign('currentUser', $_page->_user);
                $_page->Template->assign('currentGroup', $group);
                $_page->Template->assign('cloneId', $item['ID']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupFamilyIcons/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddGroupDocuments':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                $script = '';
                $lvars = array();
                $documents_ids_tpl = array();
                $documents_ids_tpl = $item['Data']['items'];
                $lvars['documents_ids'] = $documents_ids_tpl;
                $lvars['headline'] = $item['Data']['headline'];
                $lvars['documents_objects'] = array();
                foreach ($lvars['documents_ids'] as $_k => &$_v) {
                    if (! empty($_v) && Warecorp_Document_Item::isDocumentExists($_v)) {
                        $_doc = new Warecorp_Document_Item($_v);
                        if (Warecorp_Document_AccessManager_Factory::create()->canViewDocument($_doc, $_doc->getOwner(), $_page->_user)) {
                            $lvars['documents_objects'][$_k] = $_doc;
                        } else {
                            unset($lvars['documents_ids'][$_k]);
                        }
                    } else {
                        $lvars['documents_ids'][$_k] = 0;
                    }
                }
                $_page->Template->assign($lvars);
                $_page->Template->assign('currentUser', $_page->_user);
                $_page->Template->assign('currentGroup', $group);
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupDocuments/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddFamilyDiscussions':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                $discussions = array();
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['discussionsThreads'] = array();
                    $item['Data']['discussionsShowThreadSummaries'] = 0;
                    $item['Data']['discussionsShowThreadSummaries2'] = 0;
                    $item['Data']['discussionsDisplayMostActive'] = 1;
                    $item['Data']['discussionsDisplayMostRecent'] = 1;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['discussionsShowThreadsNumber'] = 0;
                }



                $discussions = array();
                $discussionList = new Warecorp_DiscussionServer_DiscussionList();
                $discussions = $discussionList->findByGroupId($group->getId());
                //
                $templatePrefix = 'Group';
                if ($group->getGroupType() == 'family') {
                    $templatePrefix = 'Family';
                    $groupsList = $group->getGroups()->setTypes(array('simple'))->getList();
                    $gropNames = array();
                    $gropNames[$group->getId()] = $group->getName();
                    foreach ($groupsList as &$_g) {
                        $dlist = $discussionList->findByGroupId($_g->getId());
                        foreach ($dlist as &$d) {
                            $discussions[] = $d;

                            if ($_g->isPrivate() || ! $d->hasTopics()) continue;
                            
                            $gropNames[$d->getGroupId()] = $_g->getName();
                        }
                    }
                    $_page->Template->assign('groupNames',$gropNames);
                }
                $_page->Template->assign('discussionList',$discussions);
                $_page->Template->assign($item['Data']);
                $_page->Template->assign('cloneId',$item['ID']);
                //if tabs present
                if (! empty($item['Data']['discussionsShowThreadsNumber']) && (! empty($item['Data']['discussionsDisplayMostActive']) || ! empty($item['Data']['discussionsDisplayMostRecent']))) {
                    $topicsList = new Warecorp_DiscussionServer_TopicList();
                    $_page->Template->assign('topicsList',$topicsList);

                    $fGroupsList = array($group->getId());

                    if ($group->getGroupType() == 'family') {
                        //$fGroupsList = array();
                        foreach ($group->getGroups()->returnAsAssoc(true)->setAssocValue('zgi.id')->getList() as $_v) {
                            $fGroupsList[] = $_v;
                        }
                    }
                    $_page->Template->assign('fGroupsList',$fGroupsList);
                }

                $_page->Template->assign('AccessManager', new Warecorp_Group_AccessManager());
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/dd' . $templatePrefix . 'Discussions/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddMyDiscussions':
                $userInfo = new Warecorp_User('id', $entity->getId());
                $discussions = array();
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['discussionsThreads'] = array();
                    $item['Data']['discussionsShowThreadSummaries'] = 0;
                    $item['Data']['discussionsShowThreadSummaries2'] = 0;
                    $item['Data']['discussionsDisplayMostActive'] = 1;
                    $item['Data']['discussionsDisplayMostRecent'] = 1;
                    $item['Data']['headline'] = 'This is the default headline';
                    $item['Data']['discussionsShowThreadsNumber'] = 0;
                }
                $discussionList = new Warecorp_DiscussionServer_DiscussionList();
                $groupsList = $userInfo->getGroups()->getList();
                foreach ($groupsList as &$_group) {
                    $tmpList = $discussionList->findByGroupId($_group->getId());
                    foreach ($tmpList as &$_vv) {
                        if (Warecorp_DiscussionServer_AccessManager_Factory::create()->canViewGroupDiscussions($_group, $_page->_user->getId())) {
                            $discussions[] = $_vv->setGroup($_group);
                        }

                    }
                }

                //if tabs present
                if (! empty($item['Data']['discussionsShowThreadsNumber']) && (! empty($item['Data']['discussionsDisplayMostActive']) || ! empty($item['Data']['discussionsDisplayMostRecent']))) {
                    $topicsList = new Warecorp_DiscussionServer_TopicList();
                    $_page->Template->assign('topicsList', $topicsList);
                    $fGroupsList = array();
                    $fGroupsList = array_keys($userInfo->getGroups()->returnAsAssoc(true)->getList());
                    $_page->Template->assign('fGroupsList', $fGroupsList);
                }
                $_page->Template->assign('gFactory', new Warecorp_Group_Factory());
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('discussionList', $discussions);
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddMyDiscussions/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddImage':
                $userInfo = new Warecorp_User('id', $entity->getId());
                $avatar_id = (isset($item['Data']['avatarId']) ? intval($item['Data']['avatarId']) : 0);
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['avatarId'] = 0;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                if (Warecorp_Photo_Standard::isPhotoExists($avatar_id)) {
                    $currentImage = Warecorp_Photo_Factory::loadById($avatar_id);
                    if (! Warecorp_Photo_AccessManager::canViewGallery($currentImage->getGallery(), $userInfo, $_page->_user)) {
                        $currentImage = Warecorp_Photo_Factory::createByOwner($userInfo);
                    }
                } else {
                    $currentImage = Warecorp_Photo_Factory::createByOwner($userInfo);
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('currentImage', $currentImage);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddImage/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // -------------------------------------------------------------------------------------
            case 'ddPicture':
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $userInfo = new Warecorp_User('id', $entity->getId());
                $userInfo->loadDefaultAvatar();
                $_page->Template->assign('currentAvatar', $userInfo->getAvatar());
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddPicture/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // + -------------------------------------------------------------------------------------
            case 'ddMyPhotos':
                $userInfo = new Warecorp_User('id', $entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['gallery_count'] = 1;
                    $item['Data']['gallery_type'] = 1;
                    $item['Data']['gallery_show_thumbnails_count'] = 20;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $gallery_hash = array();
                $thumbnails = array();
                // TIMED
                if ($item['Data']['gallery_type'] == 1) {
                    $galleries = $userInfo->getGalleries()->setPrivacy(0)->setSharingMode('both')->getList();
                    $_gall_num = 0;
                    for ($i = 0; $i < $item['Data']['gallery_count']; $i ++) {
                        if (empty($galleries)) {
                            $gallery_hash[$i] = NULL; //Warecorp_Photo_Gallery_Factory::createByOwner($userInfo);
                        } else if ($_gall_num > count($galleries) - 1) {
                            $gallery_hash[$i] = NULL; //Warecorp_Photo_Gallery_Factory::createByOwner($userInfo);
                        } else {
                            $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::loadById($galleries[$_gall_num]->getId());
                            $_gall_num ++;
                            //opt
                            if (!empty($item['Data']['gallery_show_as_icons'])){
                                //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                                $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                                foreach ($thumbs as &$_tv) {
                                    $thumbnails[] = $_tv;
                                }
                            }
                            //opt
                        }
                    }
                } else { //MANUAL
                    for ($i = 0; $i < $item['Data']['gallery_count']; $i ++) {
                        if (! empty($item['Data']['galleries'][$i]) && Warecorp_Photo_Gallery_Abstract::isGalleryExists($item['Data']['galleries'][$i])) {
                            $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::loadById($item['Data']['galleries'][$i]);
                            //opt
                            if (!empty($item['Data']['gallery_show_as_icons'])){
                                //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                                $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                                foreach ($thumbs as &$_tv) {
                                    $thumbnails[] = $_tv;
                                }
                            }
                            //opt
                        } else {
                            $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::createByOwner($userInfo);
                        }
                    }
                }
                // If show as icons selected - we make shuffled array with limit
                if (! empty($item['Data']['gallery_show_thumbnails_count'])) {
                    shuffle($thumbnails);
                    while (count($thumbnails) > $item['Data']['gallery_show_thumbnails_count']) {
                        $_tmp = array_pop($thumbnails);
                    }
                }
                foreach ($thumbnails as $_k => &$_v) {
                    $_v = Warecorp_Photo_Factory :: loadById($_v);
                }
                $_page->Template->assign('gallery_hash', $gallery_hash);
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('thumbnails', $thumbnails);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddMyPhotos/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // + -------------------------------------------------------------------------------------
            case 'ddMyVideos':
                $userInfo = new Warecorp_User('id', $entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['gallery_count'] = 1;
                    $item['Data']['gallery_type'] = 1;
                    $item['Data']['gallery_show_thumbnails_count'] = 20;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $gallery_hash = array();
                $thumbnails = array();
                // TIMED
                if ($item['Data']['gallery_type'] == 1) {
                    $galleries = $userInfo->getVideoGalleries()->setPrivacy(0)->setSharingMode('own')->getList();
                    $_gall_num = 0;
                    for ($i = 0; $i < $item['Data']['gallery_count']; $i ++) {
                        if (empty($galleries)) {
                            $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::createByOwner($userInfo);
                            ;
                        } else {
                            if ($_gall_num > count($galleries) - 1) {
                                $_gall_num = 0;
                            }
                            $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::loadById($galleries[$_gall_num]->getId());
                            $_gall_num ++;
                            //opt
                            if (!empty($item['Data']['gallery_show_as_icons'])){
                                //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                                $thumbs = $gallery_hash[$i]->getVideos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                                foreach ($thumbs as &$_tv) {
                                    $thumbnails[] = $_tv;
                                }
                            }
                            //opt
                        }
                    }
                } else { //MANUAL
                    for ($i = 0; $i < $item['Data']['gallery_count']; $i ++) {
                        if (! empty($item['Data']['galleries'][$i]) && Warecorp_Video_Gallery_Abstract::isGalleryExists($item['Data']['galleries'][$i])) {
                            $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::loadById($item['Data']['galleries'][$i]);
                            //opt
                            if (!empty($item['Data']['gallery_show_as_icons'])){
                                //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                                $thumbs = $gallery_hash[$i]->getVideos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                                foreach ($thumbs as &$_tv) {
                                    $thumbnails[] = $_tv;
                                }
                            }
                            //opt
                        } else {
                            $gallery_hash[$i] = Warecorp_Video_Gallery_Factory::createByOwner($userInfo);
                        }
                    }
                }
                // If show as icons selected - we make shuffled array with limit
                if (! empty($item['Data']['gallery_show_thumbnails_count'])) {
                    shuffle($thumbnails);
                    while (count($thumbnails) > $item['Data']['gallery_show_thumbnails_count']) {
                        $_tmp = array_pop($thumbnails);
                    }
                }
                foreach ($thumbnails as $_k => &$_v) {
                    $_v = Warecorp_Video_Factory :: loadById($_v);
                }
                $_page->Template->assign('gallery_hash', $gallery_hash);
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('thumbnails', $thumbnails);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddMyVideos/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // + -------------------------------------------------------------------------------------
            case 'ddProfileDetails':
                $userInfo = new Warecorp_User('id', $entity->getId());
                $userInfo->setForceDbTags();
                $_page->Template->assign('userInfo', $userInfo);

                if (empty($item['Data'])) {
                    $item['Data'] = array(
                        'hide' => array());
                    $item['Data']['hide'][0] = 0;
                    $item['Data']['hide'][1] = ($userInfo->getIsBirthdayPrivate() ? 1 : 0);
                    $item['Data']['hide'][2] = ($userInfo->getIsGenderPrivate() ? 1 : 0);
                    $item['Data']['hide'][3] = 0;
                    $item['Data']['hide'][4] = 0;
                    $item['Data']['hide'][5] = 0;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddProfileDetails/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // + -------------------------------------------------------------------------------------
            case 'ddProfileDetailsAT':
                $userInfo = new Warecorp_User('id', $entity->getId());
                $tags = $userInfo->getTagsList();
                foreach ($tags as &$_tag) {
                    $_tag->name = $_tag->getPreparedTagName();
                }
                $_page->Template->assign('userInfo', $userInfo);
                $_page->Template->assign('tags', $tags);
                if (empty($item['Data'])) {
                    $item['Data'] = array(
                        'hide' => array());
                    $item['Data']['hide'][0] = 0;
                    $item['Data']['hide'][1] = ($userInfo->getIsBirthdayPrivate() ? 1 : 0);
                    $item['Data']['hide'][2] = ($userInfo->getIsGenderPrivate() ? 1 : 0);
                    $item['Data']['hide'][3] = 0;
                    $item['Data']['hide'][4] = 0;
                    $item['Data']['hide'][5] = 0;
                    $item['Data']['hide'][6] = 0;
                    $item['Data']['hide'][7] = 0;
                    $item['Data']['hide'][8] = 0;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $_page->Template->assign($item['Data']);
                $_page->Template->assign('userAffiliation', AT_User_Enum_UserAffiliation::getList());
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddProfileDetailsAT/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddMyVideoContentBlock':
                $userInfo = new Warecorp_User('id', $entity->getId());

                $video_id = (isset($item['Data']['videoId']) ? intval($item['Data']['videoId']) : 0);
                $content = (isset($item['Data']['Content']) ? $item['Data']['Content'] : '<p align="center">Click Edit button to change this text</p>');

                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['innerText'] = $content;
                    $item['Data']['Content'] = $content;
                    $item['Data']['headline'] = $defHeadline;
                    $item['Data']['videoId'] = 0;
                }

                if (Warecorp_Video_Standard::isVideoExists($video_id)) {
                    $currentImage = Warecorp_Video_Factory::loadById($video_id);
                    if (! Warecorp_Video_AccessManager::canViewGallery($currentImage->getGallery(), $userInfo, $_page->_user)) {
                        $currentImage = Warecorp_Video_Factory::createByOwner($userInfo);
                    }
                } else {
                    $currentImage = Warecorp_Video_Factory::createByOwner($userInfo);
                }

                $_page->Template->assign('video', $currentImage);

                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);

                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddMyVideoContentBlock/preview_mode_' . $content_item['template_type'] . '.tpl');

                break;
            // + -------------------------------------------------------------------------------------
            case 'ddMyDocuments':
                $userInfo = new Warecorp_User('id', $entity->getId());
                $_page->Template->assign('userInfo', $userInfo);
                $script = '';
                $lvars = array();
                $documents_ids_tpl = array();
                $documents_ids_tpl = $item['Data']['items'];
                $lvars['documents_ids'] = $documents_ids_tpl;
                $lvars['headline'] = $item['Data']['headline'];
                $lvars['documents_objects'] = array();
                foreach ($lvars['documents_ids'] as $_k => &$_v) {
                    if (! empty($_v) && Warecorp_Document_Item::isDocumentExists($_v)) {
                        $_doc = new Warecorp_Document_Item($_v);
                        if (($_doc->getOwnerId() == $userInfo->getId()) && ($_doc->getOwnertype() == 'user') && Warecorp_Document_AccessManager_Factory::create()->canViewDocument($_doc, $userInfo, $_page->_user)) {
                            $lvars['documents_objects'][$_k] = $_doc;
                        } else {
                            unset($lvars['documents_ids'][$_k]);
                        }
                    } else {
                        $lvars['documents_ids'][$_k] = 0;
                    }
                }
                $_page->Template->assign($lvars);
                $_page->Template->assign('currentUser', $userInfo);
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign($item['Data']);
                $smarty_vars['Content'] = $_page->Template->getContents("content_objects/ddMyDocuments/preview_mode_" . $content_item['template_type'] . ".tpl");
                break;
            // + -------------------------------------------------------------------------------------
            case 'ddGroupPhotos':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['gallery_count'] = 1;
                    $item['Data']['gallery_type'] = 1;
                    $item['Data']['gallery_show_thumbnails_count'] = 20;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $gallery_hash = array();
                $thumbnails = array();
                // TIMED
                if ($item['Data']['gallery_type'] == 1) {
                    //$_sharingMode = Warecorp_Photo_AccessManager::canViewPrivateGalleries($group, $_page->_user) ? 'both' : 'own';

                    if (Warecorp_Photo_AccessManager::canViewPrivateGalleries($group, $_page->_user)){
                        $galleries = $group->getGalleries()->setPrivacy(array(0,1))->setSharingMode('both')->getList();
                    } else {
                        $galleries = $group->getGalleries()->setPrivacy(0)->setSharingMode('both')->getList();
                    }




                    $_gall_num = 0;
                    for ($i = 0; $i < $item['Data']['gallery_count']; $i ++) {
                        if (empty($galleries)) {
                            $gallery_hash[$i] = NULL; //Warecorp_Photo_Gallery_Factory::createByOwner($group);
                        } else if ($_gall_num > count($galleries) - 1) {
                            $gallery_hash[$i] = NULL;
                        } else {
                            $gallery_hash[$i] = $galleries[$_gall_num];
                            $_gall_num ++;
                            //opt
                            if (!empty($item['Data']['gallery_show_as_icons'])){
                                //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                                $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                                foreach ($thumbs as &$_tv) {
                                    $thumbnails[] = $_tv;
                                }
                            }
                            //opt
                        }
                    }
                } else { //MANUAL

                    for ($i = 0; $i < $item['Data']['gallery_count']; $i ++) {
                        if (! empty($item['Data']['galleries'][$i]) && Warecorp_Photo_Gallery_Abstract::isGalleryExists($item['Data']['galleries'][$i])) {
                            $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::loadById($item['Data']['galleries'][$i]);
                             //if ($gallery_hash[$i]->getId() == 80) print_r($gallery_hash[$i]);

                           // print ($gallery_hash[$i]->getId().'-'.intval($gallery_hash[$i]->getPrivate()).'___');
                            if (! Warecorp_Photo_AccessManager::canViewPrivateGalleries($group, $_page->_user) && ($gallery_hash[$i]->isShared($group) || $gallery_hash[$i]->getPrivate())) {
                                $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::createByOwner($group);
                            }
                            //opt
                            if (!empty($item['Data']['gallery_show_as_icons'])){
                                //$thumbs = $gallery_hash[$i]->getPhotos()->getList();
                                $thumbs = $gallery_hash[$i]->getPhotos()->returnAsAssoc(true)->setAssocValue('id')->getList();
                                foreach ($thumbs as &$_tv) {
                                    $thumbnails[] = $_tv;
                                }
                            }
                            //opt
                        } else {
                            $gallery_hash[$i] = Warecorp_Photo_Gallery_Factory::createByOwner($group);
                        }
                    }
                }
                // If show as icons selected - we make shuffled array with limit
                if (! empty($item['Data']['gallery_show_thumbnails_count'])) {
                    shuffle($thumbnails);
                    while (count($thumbnails) > $item['Data']['gallery_show_thumbnails_count']) {
                        $_tmp = array_pop($thumbnails);
                    }
                }
                foreach ($thumbnails as $_k => &$_v) {
                    $_v = Warecorp_Photo_Factory :: loadById($_v);
                }
                $_page->Template->assign('gallery_hash', $gallery_hash);
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('thumbnails', $thumbnails);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupPhotos/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // -------------------------------------------------------------------------------------
            case 'ddMyGroups':
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['headline'] = 'This is the default headline';
                }
                if ( !isset($item['Data']['not_new_groups']) ) {
                    $item['Data']['not_new_groups'] = array();
                    $item['Data']['auto_disp_family'] = 0;
                    $item['Data']['auto_disp_simple'] = 0;
                }
                $userInfo = new Warecorp_User('id', $entity->getId());

                $auto_disp_simple = false;
                $auto_disp_family = false;

                if ( Warecorp::checkHttpContext('zccr') ) {
                    $groupsList = $userInfo->getGroups()->setTypes('simple')->getList();
                    foreach ($groupsList as $_k => &$_v) {
                        if ( $_v->getGroupUID() === IMPLEMENTATION_GROUP_UID ) {
                            unset($groupsList[$_k]);
                            continue;  // this is unique group
                        }
                        if ( !in_array($_v->getId(), $item['Data']['not_new_groups']) ) {
                            $auto_disp_simple = true;
                            continue;
                        }
                    }
                    $_page->Template->assign('groupsList', $groupsList);
                    $_page->Template->assign('cloneId', $item['ID']);
                    $_page->Template->assign($item['Data']);
                    $_page->Template->assign('family_groups_empty', TRUE);
                    $_page->Template->assign('groups_empty', ((empty($item['Data']['unhide']) || count($item['Data']['unhide']) == 0) && !$auto_disp_simple));
                } else {
                    $groupsList = $userInfo->getGroups()->setTypes('simple')->getList();
                    $familyGroupsList = $userInfo->getGroups()->setTypes('family')->getList();
                    if ( $familyGroupsList && $item['Data']['auto_disp_family'] ) {
                        foreach ( $familyGroupsList as &$family ) {
                            if ( !in_array($family->getId(), $item['Data']['not_new_groups']) ) {
                                $auto_disp_family = true;
                                break;
                            }
                        }
                    }
                    if ( $groupsList && $item['Data']['auto_disp_simple'] ) {
                        foreach ( $groupsList as &$group ) {
                            if ( !in_array($group->getId(), $item['Data']['not_new_groups']) ) {
                                $auto_disp_simple = true;
                                break;
                            }
                        }
                    }
                    $_page->Template->assign('groupsList', $groupsList);
                    $_page->Template->assign('familyGroupsList', $familyGroupsList);
                    $_page->Template->assign('cloneId', $item['ID']);
                    $_page->Template->assign($item['Data']);
                    $_page->Template->assign('family_groups_empty', ((empty($item['Data']['family_unhide']) || count($item['Data']['family_unhide']) == 0) && !$auto_disp_family));
                    $_page->Template->assign('groups_empty', ((empty($item['Data']['unhide']) || count($item['Data']['unhide']) == 0) && !$auto_disp_simple));
                }
                $smarty_vars['Content'] = $_page->Template->getContents("content_objects/ddMyGroups/preview_mode_" . $content_item['template_type'] . ".tpl");
                break;
            //-------------------------------------------------------------------------------------
            case 'ddRSSFeed':

            if (! isset($item['Data'])) {
               $item['Data'] = array();
               $item['Data']['headline'] = 'This is the default headline';
            }

            $cache_key = 'RSSCo_'.md5(join('_',$item['Data']));
            $cache       = Warecorp_Cache::getFileCache();
            if ( $data = $cache->load($cache_key) ) {
                $smarty_vars['Content'] = $data;
                break;
            }

            $item['Data']['rss_url'] = str_replace(array('feed://','feed:http'), array('http://','http'), $item['Data']['rss_url']);
            $smarty_vars["title"] = empty($item['Data']['rss_title']) ? '' : $item['Data']['rss_title'];


            try {
            	$feed = Warecorp_Feed_Reader::import($item['Data']['rss_url']);
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

                    Zend_Registry::set("_temporaryCORSSWidth", 0);
                    Zend_Registry::set("_temporaryCORSSCol", $content_item['template_type']);

                    if (is_string($record["content"])) {
                        $pattern = "/(\<object)([^\>]*)(width=\")([0-9]*)(\")([^\>]*)(\>)/i";
                        $record["content"] = preg_replace_callback($pattern, 'Warecorp_CO_Content::prcw', $record["content"]);
                        $pattern = "/(\<object)([^\>]*)(height=\")([0-9]*)(\")([^\>]*)(\>)/i";
                        $record["content"] = preg_replace_callback($pattern, 'Warecorp_CO_Content::prch', $record["content"]);
                    }
                    $rss_hash[] = $record;
                }
             	$smarty_vars["rss_hash"] = $rss_hash;
             }

             $_smarty->assign($item['Data']);
             $_smarty->assign($smarty_vars);
             $data = $_smarty->getContents('content_objects/ddRSSFeed/preview_mode_' . $content_item['template_type'] . '.tpl');
             $smarty_vars['Content'] = $data;
             $cfgLifetime = Warecorp_Config_Loader::getInstance()->getAppConfig('cache/cfg.index.xml')->{'lifetime'};
             $cache->save($data, $cache_key, array(), $cfgLifetime->feeds);
             break;

            //-------------------------------------------------------------------------------------
            case 'ddMyEvents':
                $userInfo = new Warecorp_User('id', $entity->getId());
                if (empty($item['Data']['events_threads'])) {
                    $item['Data']['events_threads'][0] = 0;
                }
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['event_display_style']            = 1;
                    $item['Data']['events_futered_display_number']  = 1;
                    $item['Data']['events_display_number']          = 3;
                    $item['Data']['events_show_summaries']          = 0;
                    $item['Data']['events_show_calendar']           = 1;
                    $item['Data']['events_show_venues']             = 0;
                    $item['Data']['headline']                       = 'This is the default headline';
                }
                $_page->Template->assign($item['Data']);

                $currentTimezone = ( null !== $_page->_user->getId() && null !== $_page->_user->getTimezone() ) ? $_page->_user->getTimezone() : 'UTC';
                $_page->Template->assign('currentTimezone', $currentTimezone);

                //---------------------------------
                if (! empty($item['Data']['event_display_style'])) {
                    //=======================================================================================
                    //Automatically rotate featured events
                    //=======================================================================================
                    if ($item['Data']['event_display_style'] == 1) {
                        /**
                         * Create $objDateNow in current timezone
                         */
                        $defaultTimezone = date_default_timezone_get();
                        date_default_timezone_set($currentTimezone);
                        $objDateNow = new Zend_Date();
                        date_default_timezone_set($defaultTimezone);
                        /**
                         * Valudate year, month
                         */
                        $_p_year = $objDateNow->toString('yyyy');
                        $_p_month = $objDateNow->toString('MM');
                        $_p_year = ( floor($_p_year) < 1970 ) ? 1970 : floor($_p_year);
                        $_p_year = ( floor($_p_year) > 2038 ) ? 2038 : floor($_p_year);
                        $_p_month = ( floor($_p_month) < 1 ) ? 1 : floor($_p_month);
                        $_p_month = ( floor($_p_month) > 12 ) ? 12 : floor($_p_month);
                        /**
                        * Build dates master array
                        * 1. create start and end dates for looked period
                        */
                        date_default_timezone_set($currentTimezone);
                        $strPeriodStartDate =  sprintf('%04d', $_p_year).'-'.sprintf('%02d', $_p_month).'-01T000000';
                        $periodStartDateM = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                        $periodStartDate = clone $objDateNow;
                        //$periodStartDate->setHour(0)->setMinute(0)->setSecond(0);
                        //new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                        $periodStartEnd = clone $periodStartDateM;
                        $periodStartEnd->add(1, Zend_Date::MONTH);
                        $periodStartEndM = clone $periodStartDateM;
                        $periodStartEndM->add(1, Zend_Date::MONTH);
                        // add 7 days to display hidden events
                        $periodStartDateM->sub(7, Zend_Date::DAY);
                        $periodStartEnd->add(7, Zend_Date::DAY);
                        /**
                        * Build dates master array
                        * 2. create list of events that can be presents on page - $arrEvents
                        */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $objEvents->setOwnerIdFilter($userInfo->getId());
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
                        $objEvents->setShowCopyFilter(true);
                        // privacy
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($userInfo, $_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0,1));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(1));
                        } else {
                            $objEvents->setPrivacyFilter(null);
                        }
                        // sharing
                        //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($userInfo, $_page->_user) ) {
                        //    $objEvents->setSharingFilter(array(0,1));
                        //} else {
                            $objEvents->setSharingFilter(array(0));
                        //}
                        //$objEvents->setExpiredEventFilter(false);

                        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($userInfo);
                        /**
                        * Build dates master array
                        * 3. create master array by period
                        */
                        $objEventList = new Warecorp_ICal_Event_List();
                        $objEventList->setTimezone($currentTimezone);
                        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                        $dates = $objEventList->buildRecurList($arrEvents);
                        /**
                         * Build dates master array
                         * 4. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                         */
                        if ( !$_page->_user || null === $_page->_user->getId() ) {
                            if ( sizeof($dates) != 0 ) {
                                $arrDatesAnonymos = array();
                                foreach ( $dates as $dateKey => $times ) {
                                    foreach ( $times as $timeKey => $events ) {
                                        foreach ( $events as $idKey => $eventInfo ) {
                                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                        }
                                    }
                                }
                                ksort($arrDatesAnonymos);
                                $dates = $arrDatesAnonymos;
                            }
                        }
                        /**
                         * Find total events count in $dates
                         */
                        $_eventscntr = $_monthcntr = 0;
                        foreach($dates as $_date => &$_hash){
                            foreach($_hash as $_time => &$_id_id){
                                $_eventscntr += count($_id_id);
                            }
                        }
                        //$item['Data']['events_futered_display_number'] = 100;
                        while ($_eventscntr < $item['Data']['events_futered_display_number'] && $_monthcntr < 12)
                        {
                            $_monthcntr ++;
                            /**
                            * Build dates master array
                            * 1. create start and end dates for looked period
                            */
                            $_periodStartDateM = clone $periodStartDateM;
                            $_periodStartEndM = clone $periodStartEndM;
                            $_periodStartDateM->add($_monthcntr, Zend_Date::MONTH);
                            $_periodStartDateM->sub(7, Zend_Date::DAY);
                            if ($_periodStartDateM->getTimestamp() < $periodStartDate->getTimestamp()){$_periodStartDateM = clone $periodStartDate;}
                            $_periodStartEndM->add($_monthcntr, Zend_Date::MONTH);
                            $_periodStartEndM->add(7, Zend_Date::DAY);
                            /**
                            * Build dates master array
                            * 2. create master array by period
                            */
                            $objEventList->setPeriodDtstart($_periodStartDateM->toString('yyyy-MM-ddTHHmmss'));
                            $objEventList->setPeriodDtend($_periodStartEndM->toString('yyyy-MM-ddTHHmmss'));
                            $dates2 = $objEventList->buildRecurList($arrEvents);
                            /**
                             * Build dates master array
                             * 3. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                             */
                            if ( !$_page->_user || null === $_page->_user->getId() ) {
                                if ( sizeof($dates2) != 0 ) {
                                    $arrDatesAnonymos = array();
                                    foreach ( $dates2 as $dateKey => $times ) {
                                        foreach ( $times as $timeKey => $events ) {
                                            foreach ( $events as $idKey => $eventInfo ) {
                                                $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                            }
                                        }
                                    }
                                    ksort($arrDatesAnonymos);
                                    $dates2 = $arrDatesAnonymos;
                                }
                            }
                            $dates = array_merge($dates, $dates2);
                            /**
                             * Find total events count in $dates
                             */
                            $_eventscntr = 0;
                            foreach($dates as $_date => &$_hash){
                                foreach($_hash as $_time => &$_id_id){
                                    $_eventscntr += count($_id_id);
                                }
                            }
                        }
                        /**
                         * create event object whith fixed date & create tooltip for event
                         */
                        $eventsList = array();
                        $_cntr = 0;
                        $eventsDates = array();
                        $eventsDatesAtt = array();
                        foreach($dates as $_date => &$_hash){
                            foreach($_hash as $_time => &$_id_id){
                                foreach ($_id_id as &$_info){
                                    if (count($eventsList) < $item['Data']['events_futered_display_number']){
                                        $_nEvent = $objEventList->createEvent($_info, $currentTimezone);
                                        $eventsList[] = $_nEvent;
                                        /*
                                        $_nEvent = new Warecorp_ICal_Event($_info['id']);
                                        $_nEvent->setTimezone($currentTimezone);
                                        $eventsList[] = $_nEvent;
                                        $eventsDates[] = substr($_date, 5, 2).'/'.substr($_date, 8, 2).'/'.substr($_date, 0, 4);
                                        if($_nEvent->isAllDay()) {
                                            $eventsDatesAtt[] = $_date;
                                        } else {
                                            $eventsDatesAtt[] = $_date.'T'.str_replace(':','',$_time);
                                        }
                                        */
                                    }
                                }
                            }
                        }
                        $_page->Template->assign('eventsList', $eventsList);
                        $_page->Template->assign('eventsDates', $eventsDates);
                        $_page->Template->assign('eventsDatesAtt', $eventsDatesAtt);
                    }
                    //=======================================================================================
                    // Manually select an events to dsiplay
                    //=======================================================================================
                    if ($item['Data']['event_display_style'] == 2) {
                        /**
                        * Build dates master array
                        * 1. create list of events that can be presents on page - $arrEvents
                        */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $objEvents->setOwnerIdFilter($userInfo->getId());
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
                        // PRIVACY etc----------------------------------------------------------
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($userInfo, $_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0,1));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(1));
                        } else {
                            $objEvents->setPrivacyFilter(null);
                        }
                        // sharing
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($userInfo, $_page->_user) ) {
                            $objEvents->setSharingFilter(array(0,1));
                        } else {
                            $objEvents->setSharingFilter(array(0));
                        }
                        $objEvents->setCurrentEventFilter(true);
                        $objEvents->setExpiredEventFilter(false);
                        //--------------------------------------------------------------------

                        $_eventsList = array();
                        if (! empty($item['Data']['events_threads'])) {
                            $_eventsList = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($userInfo);
                        }
                        $eventsList = array();
                        /**
                         * Initialization global objects that is used in script
                         */
                        $lstEventsObj = new Warecorp_ICal_Event_List();
                        $lstEventsObj->setTimezone($currentTimezone);
                        $tz = date_default_timezone_get();
                        date_default_timezone_set($currentTimezone);
                        $objNowDate = new Zend_Date();
                        date_default_timezone_set($tz);

                        foreach ($item['Data']['events_threads'] as &$_et_id) {
                            foreach ($_eventsList as &$_v) {
                                if ($_et_id == $_v->getId()) {
                                    /**
                                     * Find the event first date
                                     */
                                    $strFirstDate = $lstEventsObj->findFirstEventDate($_v, $objNowDate);
                                    if ( null !== $strFirstDate ) {
                                        $_v->setTimezone($currentTimezone);
                                        $_v->setDtstart($strFirstDate);
                                    }
                                    $eventsList[] = $_v;
                                }
                            }
                        }
                        unset($lstEventsObj);
                        unset($objNowDate);
                        $_page->Template->assign('eventsList', $eventsList);

                    }
                    //=======================================================================================
                    // Automatically rotate events on a list with calendar
                    //=======================================================================================
                    if ($item['Data']['event_display_style'] == 3) {
                        // CALENDAR =========================================================================
                        /**
                         * Create $objDateNow in current timezone
                         */
                        $defaultTimezone = date_default_timezone_get();
                        date_default_timezone_set($currentTimezone);
                        $objDateNow = new Zend_Date();
                        date_default_timezone_set($defaultTimezone);
                        /**
                         * Valudate year, month
                         */
                        $_p_year    = $objDateNow->toString('yyyy');
                        $_p_month   = $objDateNow->toString('MM');
                        $_p_year    = ( floor($_p_year) < 1970 )    ? 1970  : floor($_p_year);
                        $_p_year    = ( floor($_p_year) > 2038 )    ? 2038  : floor($_p_year);
                        $_p_month   = ( floor($_p_month) < 1 )      ? 1     : floor($_p_month);
                        $_p_month   = ( floor($_p_month) > 12 )     ? 12    : floor($_p_month);
                        /**
                         * build prev and next dates to display links in calendar table block
                         * << Prev ... Next >>
                         */
                        $objCurrDate = new Zend_Date(sprintf('%04d', $_p_year).'-'.sprintf('%02d', $_p_month).'-01T000000', Zend_Date::ISO_8601, 'en_US');
                        $objPrevDate = clone $objCurrDate;
                        $objPrevDate->sub(1, Zend_Date::MONTH);
                        $objNextDate = clone $objCurrDate;
                        $objNextDate->add(1, Zend_Date::MONTH);
                        /**
                         * create object to render calendar table block
                         */
                        Warecorp_ICal_Calendar_Cfg::setWkst('SU');
                        $objYear = new Warecorp_ICal_Calendar_Year($_p_year);
                        $objYear->setShowMonths($_p_month);
                        $_page->Template->assign('objYear', $objYear);
                        /**
                        * Build dates master array
                        * 1. create                * 1. create start and end dates for looked period
                        */
                        date_default_timezone_set($currentTimezone);
                        $strPeriodStartDate =  sprintf('%04d', $_p_year).'-'.sprintf('%02d', $_p_month).'-01T000000';
                        $periodStartDate = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                        $periodStartEnd = clone $periodStartDate;
                        $periodStartEnd->add(1, Zend_Date::MONTH);
                        $objDateNow = new Zend_Date();
                        // add 7 days to display hidden events
                        $periodStartDate->sub(7, Zend_Date::DAY);
                        $periodStartEnd->add(7, Zend_Date::DAY);
                        $_page->Template->assign('objDateNow', $objDateNow);
                        /**
                        * Build dates master array
                        * 2. create list of events that can be presents on page - $arrEvents
                        */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $objEvents->setOwnerIdFilter($userInfo->getId());
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);
                        $objEvents->setShowCopyFilter(true);
                        // privacy
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($userInfo, $_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0,1));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($userInfo, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(1));
                        } else {
                            $objEvents->setPrivacyFilter(null);
                        }
                        // sharing
                        //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($userInfo, $_page->_user) ) {
                        //    $objEvents->setSharingFilter(array(0,1));
                        //} else {
                            $objEvents->setSharingFilter(array(0));
                        //}
                        $objEvents->setCurrentEventFilter(true);
                        $objEvents->setExpiredEventFilter(false);

                        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getListByUser($userInfo);
                        /**
                        * Build dates master array
                        * 3. create master array by period
                        */
                        $objEventList = new Warecorp_ICal_Event_List();
                        $objEventList->setTimeZone($_page->_user->getTimezone());
                        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                        $dates = $objEventList->buildRecurList($arrEvents);
                        /**
                         * Build dates master array
                         * 4. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                         */
                        if ( !$_page->_user || null === $_page->_user->getId() ) {
                            if ( sizeof($dates) != 0 ) {
                                $arrDatesAnonymos = array();
                                foreach ( $dates as $dateKey => $times ) {
                                    foreach ( $times as $timeKey => $events ) {
                                        foreach ( $events as $idKey => $eventInfo ) {
                                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                        }
                                    }
                                }
                                ksort($arrDatesAnonymos);
                                $dates = $arrDatesAnonymos;
                            }
                        }

                        // EVENTS LIST FOR NEXT N DAYS ======================================================

                        /**
                         * create label for days that will be displayed
                         * count of days depends on user settings
                         * @var $daysList
                         * @var $objCurrDate3 - in end of will contain last date for events list block
                         */
                        $objCurrDate3 = clone $objDateNow;
                        //$objCurrDate3->setLocale('en_US');
                        $daysList = array();
                        for ($i = 0; $i < $item['Data']['events_display_number']; $i ++) {
                            $daysList[$i]['m'] = $objCurrDate3->toString('MM');
                            $daysList[$i]['d'] = $objCurrDate3->toString('dd');
                            $daysList[$i]['y'] = $objCurrDate3->toString('yyyy');
                            $daysList[$i]['check'] = $objCurrDate3->toString('yyyy-MM-dd');
                            $daysList[$i]['date'] = ($i == 0) ? 'Today' : $objCurrDate3->toString('EEEE MM/dd/yyyy');
                            $objCurrDate3->add(1, Zend_Date::DAY);
                        }
                        /**
                        * Build dates master array for event list block
                        * 1. create master array by period
                        */
                        $objEventList3 = clone $objEventList;
                        $objEventList3->setPeriodDtstart($objDateNow->toString('yyyy-MM-ddT000000'));
                        $objEventList3->setPeriodDtend($objCurrDate3->toString('yyyy-MM-ddTHHmmss'));
                        $dates2 = $objEventList3->buildRecurList($arrEvents);
                        /**
                         * Build dates master array
                         * 2. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                         */
                        if ( !$_page->_user || null === $_page->_user->getId() ) {
                            if ( sizeof($dates2) != 0 ) {
                                $arrDatesAnonymos = array();
                                foreach ( $dates2 as $dateKey => $times ) {
                                    foreach ( $times as $timeKey => $events ) {
                                        foreach ( $events as $idKey => $eventInfo ) {
                                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                        }
                                    }
                                }
                                ksort($arrDatesAnonymos);
                                $dates2 = $arrDatesAnonymos;
                            }
                        }
                        /**
                         * create event object whith fixed date & create tooltip for event
                         */
                        $tooltips = '';
                        foreach( $dates2 as $_date => &$_hash ) {
                            foreach( $_hash as $_time => &$_id_id ) {
                                foreach ( $_id_id as &$_info ) {
                                    /**
                                     * create event object and correct its date start and end
                                     */
                                    $_info['_event'] = $objEventList3->createEvent($_info, $currentTimezone);
                                    /**
                                     * create tooltip for event
                                     * @var $_k - tooltip key
                                     */
                                    $_k = $_info['_event']->getId() . '_' . str_replace('-','_',$_date);
                                    $tooltip_text = '
                                        <div>' . $_info['_event']->displayDate('dd.myevents.tooltip', $_page->_user, $currentTimezone) . '</div>
                                        <div><strong><u>' . htmlspecialchars($_info['_event']->getTitle()) . '</u></strong></div>
                                        <div>Organizer : ' . htmlspecialchars($_info['_event']->getCreator()->getLogin()) . '</div>
                                        <div>' . ( ($_info['_event']->getOwnerType() == 'group') ? ( 'Group event : '.htmlspecialchars($_info['_event']->getOwner()->getName()) ) : '' ) . '</div>
                                        <div>' . ( ($_info['_event']->getPrivacy()) ? 'Private Event' : 'Public Event' ) . '</div>
                                    ';
                                    $tooltip_text = str_replace(array("\r", "\n"), "", $tooltip_text);
                                    $tooltips .= 'YAHOO.example.container.ttdocs_' . $item['ID'] . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $item['ID'] . '_' . $_k . '", {hidedelay:100, context:"ddMyEvents_' . $item['ID'] . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"250px"});';
                                }
                            }
                        }
                        /**
                        * Assign template vars
                        */
                        $_page->Template->assign('arrDates', $dates);
                        $_page->Template->assign('objCurrDate', $objCurrDate);
                        $_page->Template->assign('objPrevDate', $objPrevDate);
                        $_page->Template->assign('objNextDate', $objNextDate);
                        $_page->Template->assign('daysList', $daysList);
                        $_page->Template->assign('calendar_data', $dates2);
                        $_page->Template->assign('tooltips', $tooltips);
                    }
                    //=======================================================================================
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $smarty_vars["Content"] = $_page->Template->getContents('content_objects/ddMyEvents/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            //-------------------------------------------------------------------------------------
            case 'ddGroupEvents':
            case 'ddFamilyEvents':
                $group = Warecorp_Group_Factory::loadById($entity->getId());
                if (empty($item['Data']['events_threads'])) {
                    $item['Data']['events_threads'][0] = 0;
                }
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['event_display_style'] = 1;
                    $item['Data']['events_futered_display_number'] = 1;
                    $item['Data']['events_display_number'] = 3;
                    $item['Data']['events_show_summaries'] = 0;
                    $item['Data']['events_show_calendar'] = 1;
                    $item['Data']['events_show_venues'] = 0;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                $_page->Template->assign($item['Data']);

                $currentTimezone = ( null !== $_page->_user->getId() && null !== $_page->_user->getTimezone() ) ? $_page->_user->getTimezone() : 'UTC';
                $_page->Template->assign('currentTimezone', $currentTimezone);

                //---------------------------------
                if (! empty($item['Data']['event_display_style'])) {
                    //=======================================================================================
                    //Automatically rotate featured events
                    //=======================================================================================
                    if ($item['Data']['event_display_style'] == 1) {
                        /**
                         * Create $objDateNow in current timezone
                         */
                        $defaultTimezone = date_default_timezone_get();
                        date_default_timezone_set($currentTimezone);
                        $objDateNow = new Zend_Date();
                        date_default_timezone_set($defaultTimezone);
                        /**
                         * Valudate year, month
                         */
                        $_p_year = $objDateNow->toString('yyyy');
                        $_p_month = $objDateNow->toString('MM');
                        $_p_year = ( floor($_p_year) < 1970 ) ? 1970 : floor($_p_year);
                        $_p_year = ( floor($_p_year) > 2038 ) ? 2038 : floor($_p_year);
                        $_p_month = ( floor($_p_month) < 1 ) ? 1 : floor($_p_month);
                        $_p_month = ( floor($_p_month) > 12 ) ? 12 : floor($_p_month);
                        /**
                        * Build dates master array
                        * 1. create start and end dates for looked period
                        */
                        date_default_timezone_set($currentTimezone);
                        $strPeriodStartDate =  sprintf('%04d', $_p_year).'-'.sprintf('%02d', $_p_month).'-01T000000';
                        $periodStartDateM = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                        $periodStartDate = clone $objDateNow;
                        //$periodStartDate->setHour(0)->setMinute(0)->setSecond(0);
                        //new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                        $periodStartEnd = clone $periodStartDateM;
                        $periodStartEnd->add(1, Zend_Date::MONTH);
                        $periodStartEndM = clone $periodStartDateM;
                        $periodStartEndM->add(1, Zend_Date::MONTH);
                        // add 7 days to display hidden events
                        $periodStartDateM->sub(7, Zend_Date::DAY);
                        $periodStartEnd->add(7, Zend_Date::DAY);
                        /**
                        * Build dates master array
                        * 2. create list of events that can be presents on page - $arrEvents
                        */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $_glist = array();
                        if ($group->getGroupType() == 'family') {
                            $_glist = $group->getGroups()->returnAsAssoc(true)->setTypes(array('simple', 'family'))->getList();
                        }
                        $_glist[$group->getId()] = $group->getName();
                        $objEvents->setOwnerIdFilter(array_keys($_glist));
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                        $objEvents->setShowCopyFilter(true);
                        // privacy
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($group, $_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0,1));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(1));
                        } else {
                            $objEvents->setPrivacyFilter(null);
                        }
                        // sharing
                        //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($userInfo, $_page->_user) ) {
                        //    $objEvents->setSharingFilter(array(0,1));
                        //} else {
                            $objEvents->setSharingFilter(array(0));
                        //}
                        //$objEvents->setExpiredEventFilter(false);

                        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                        /**
                        * Build dates master array
                        * 3. create master array by period
                        */
                        $objEventList = new Warecorp_ICal_Event_List();
                        $objEventList->setTimezone($currentTimezone);
                        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                        $dates = $objEventList->buildRecurList($arrEvents);
                        /**
                         * Build dates master array
                         * 4. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                         */
                        if ( !$_page->_user || null === $_page->_user->getId() ) {
                            if ( sizeof($dates) != 0 ) {
                                $arrDatesAnonymos = array();
                                foreach ( $dates as $dateKey => $times ) {
                                    foreach ( $times as $timeKey => $events ) {
                                        foreach ( $events as $idKey => $eventInfo ) {
                                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                        }
                                    }
                                }
                                ksort($arrDatesAnonymos);
                                $dates = $arrDatesAnonymos;
                            }
                        }
                        /**
                         * Find total events count in $dates
                         */
                        $_eventscntr = $_monthcntr = 0;
                        foreach($dates as $_date => &$_hash){
                            foreach($_hash as $_time => &$_id_id){
                                $_eventscntr += count($_id_id);
                            }
                        }
                        while ($_eventscntr < $item['Data']['events_futered_display_number'] && $_monthcntr < 12)
                        {
                            $_monthcntr ++;
                            /**
                            * Build dates master array
                            * 1. create start and end dates for looked period
                            */
                            $_periodStartDateM = clone $periodStartDateM;
                            $_periodStartEndM = clone $periodStartEndM;
                            $_periodStartDateM->add($_monthcntr, Zend_Date::MONTH);
                            $_periodStartDateM->sub(7, Zend_Date::DAY);
                            if ($_periodStartDateM->getTimestamp() < $periodStartDate->getTimestamp()){$_periodStartDateM = clone $periodStartDate;}
                            $_periodStartEndM->add($_monthcntr, Zend_Date::MONTH);
                            $_periodStartEndM->add(7, Zend_Date::DAY);
                            /**
                            * Build dates master array
                            * 2. create master array by period
                            */
                            $objEventList->setPeriodDtstart($_periodStartDateM->toString('yyyy-MM-ddTHHmmss'));
                            $objEventList->setPeriodDtend($_periodStartEndM->toString('yyyy-MM-ddTHHmmss'));
                            $dates2 = $objEventList->buildRecurList($arrEvents);
                            /**
                             * Build dates master array
                             * 3. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                             */
                            if ( !$_page->_user || null === $_page->_user->getId() ) {
                                if ( sizeof($dates2) != 0 ) {
                                    $arrDatesAnonymos = array();
                                    foreach ( $dates2 as $dateKey => $times ) {
                                        foreach ( $times as $timeKey => $events ) {
                                            foreach ( $events as $idKey => $eventInfo ) {
                                                $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                            }
                                        }
                                    }
                                    ksort($arrDatesAnonymos);
                                    $dates2 = $arrDatesAnonymos;
                                }
                            }
                            $dates = array_merge($dates, $dates2);
                            /**
                             * Find total events count in $dates
                             */
                            $_eventscntr = 0;
                            foreach($dates as $_date => &$_hash){
                                foreach($_hash as $_time => &$_id_id){
                                    $_eventscntr += count($_id_id);
                                }
                            }
                        }
                        /**
                         * create event object whith fixed date & create tooltip for event
                         */
                        $eventsList = array();
                        $_cntr = 0;
                        $eventsDates = array();
                        $eventsDatesAtt = array();
                        foreach($dates as $_date => &$_hash){
                            foreach($_hash as $_time => &$_id_id){
                                foreach ($_id_id as &$_info){
                                    if (count($eventsList) < $item['Data']['events_futered_display_number']){
                                        $_nEvent = $objEventList->createEvent($_info, $currentTimezone);
                                        $eventsList[] = $_nEvent;
                                        /*
                                        $_nEvent = new Warecorp_ICal_Event($_info['id']);
                                        $_nEvent->setTimezone($currentTimezone);
                                        $eventsList[] = $_nEvent;
                                        $eventsDates[] = substr($_date, 5, 2).'/'.substr($_date, 8, 2).'/'.substr($_date, 0, 4);
                                        if($_nEvent->isAllDay()) {
                                            $eventsDatesAtt[] = $_date;
                                        } else {
                                            $eventsDatesAtt[] = $_date.'T'.str_replace(':','',$_time);
                                        }
                                        */
                                   }
                                }
                            }
                        }
                        $_page->Template->assign('eventsList', $eventsList);
                        $_page->Template->assign('eventsDates', $eventsDates);
                        $_page->Template->assign('eventsDatesAtt', $eventsDatesAtt);
                    }
                    //=======================================================================================
                    // Manually select an events to dsiplay
                    //=======================================================================================
                    if ($item['Data']['event_display_style'] == 2) {
                        /**
                        * Build dates master array
                        * 1. create list of events that can be presents on page - $arrEvents
                        */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $_glist = array();
                        if ($group->getGroupType() == 'family') {
                            $_glist = $group->getGroups()->returnAsAssoc(true)->setTypes(array('simple', 'family'))->getList();
                        }
                        $_glist[$group->getId()] = $group->getName();
                        $objEvents->setOwnerIdFilter(array_keys($_glist));
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                        // PRIVACY etc----------------------------------------------------------
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($group, $_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0,1));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(1));
                        } else {
                            $objEvents->setPrivacyFilter(null);
                        }
                        // sharing
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($group, $_page->_user) ) {
                            $objEvents->setSharingFilter(array(0,1));
                        } else {
                            $objEvents->setSharingFilter(array(0));
                        }
                        $objEvents->setCurrentEventFilter(true);
                        $objEvents->setExpiredEventFilter(false);
                        //--------------------------------------------------------------------

                        $_eventsList = array();
                        if (! empty($item['Data']['events_threads'])) {
                            $_eventsList = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                        }
                        $eventsList = array();
                        /**
                         * Initialization global objects that is used in script
                         */
                        $lstEventsObj = new Warecorp_ICal_Event_List();
                        $lstEventsObj->setTimezone($currentTimezone);
                        $tz = date_default_timezone_get();
                        date_default_timezone_set($currentTimezone);
                        $objNowDate = new Zend_Date();
                        date_default_timezone_set($tz);

                        foreach ($item['Data']['events_threads'] as &$_et_id) {
                            foreach ($_eventsList as &$_v) {
                                if ($_et_id == $_v->getId()) {
                                    /**
                                     * Find the event first date
                                     */
                                    $strFirstDate = $lstEventsObj->findFirstEventDate($_v, $objNowDate);
                                    if ( null !== $strFirstDate ) {
                                        $_v->setTimezone($currentTimezone);
                                        $_v->setDtstart($strFirstDate);
                                    }
                                    $eventsList[] = $_v;
                                }
                            }
                        }
                        $_page->Template->assign('eventsList', $eventsList);
                    }
                    //=======================================================================================
                    // Automatically rotate events on a list with calendar
                    //=======================================================================================
                    if ($item['Data']['event_display_style'] == 3) {
                        // CALENDAR =========================================================================
                        /**
                         * Create $objDateNow in current timezone
                         */
                        $defaultTimezone = date_default_timezone_get();
                        date_default_timezone_set($currentTimezone);
                        $objDateNow = new Zend_Date();
                        date_default_timezone_set($defaultTimezone);
                        /**
                         * Valudate year, month
                         */
                        $_p_year = $objDateNow->toString('yyyy');
                        $_p_month = $objDateNow->toString('MM');
                        $_p_year = ( floor($_p_year) < 1970 ) ? 1970 : floor($_p_year);
                        $_p_year = ( floor($_p_year) > 2038 ) ? 2038 : floor($_p_year);
                        $_p_month = ( floor($_p_month) < 1 ) ? 1 : floor($_p_month);
                        $_p_month = ( floor($_p_month) > 12 ) ? 12 : floor($_p_month);
                        /**
                         * build prev and next dates to display links in calendar table block
                         * << Prev ... Next >>
                         */
                        $objCurrDate = new Zend_Date(sprintf('%04d', $_p_year).'-'.sprintf('%02d', $_p_month).'-01T000000', Zend_Date::ISO_8601, 'en_US');
                        $objPrevDate = clone $objCurrDate;
                        $objPrevDate->sub(1, Zend_Date::MONTH);
                        $objNextDate = clone $objCurrDate;
                        $objNextDate->add(1, Zend_Date::MONTH);
                        /**
                         * create object to render calendar table block
                         */
                        Warecorp_ICal_Calendar_Cfg::setWkst('SU');
                        $objYear = new Warecorp_ICal_Calendar_Year($_p_year);
                        $objYear->setShowMonths($_p_month);
                        $_page->Template->assign('objYear', $objYear);
                        /**
                        * Build dates master array
                        * 1. create start and end dates for looked period
                        */
                        date_default_timezone_set($currentTimezone);
                        $strPeriodStartDate =  sprintf('%04d', $_p_year).'-'.sprintf('%02d', $_p_month).'-01T000000';
                        $periodStartDate = new Zend_Date($strPeriodStartDate, Zend_Date::ISO_8601);
                        $periodStartEnd = clone $periodStartDate;
                        $periodStartEnd->add(1, Zend_Date::MONTH);
                        $objDateNow = new Zend_Date();
                        // add 7 days to display hidden events
                        $periodStartDate->sub(7, Zend_Date::DAY);
                        $periodStartEnd->add(7, Zend_Date::DAY);
                        $_page->Template->assign('objDateNow', $objDateNow);
                        /**
                        * Build dates master array
                        * 2. create list of events that can be presents on page - $arrEvents
                        */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $_glist = array();
                        if ($group->getGroupType() == 'family') {
                            $_glist = $group->getGroups()->returnAsAssoc(true)->setTypes(array('simple', 'family'))->getList();
                        }
                        $_glist[$group->getId()] = $group->getName();
                        $objEvents->setOwnerIdFilter(array_keys($_glist));
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                        $objEvents->setShowCopyFilter(true);
                        // privacy
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($group, $_page->_user) && Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0,1));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPublicEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(0));
                        } elseif ( Warecorp_ICal_AccessManager_Factory::create()->canViewPrivateEvents($group, $_page->_user) ) {
                            $objEvents->setPrivacyFilter(array(1));
                        } else {
                            $objEvents->setPrivacyFilter(null);
                        }
                        $objEvents->setCurrentEventFilter(true);
                        $objEvents->setExpiredEventFilter(false);
                        // sharing
                        //if ( Warecorp_ICal_AccessManager_Factory::create()->canViewSharedEvents($userInfo, $_page->_user) ) {
                        //    $objEvents->setSharingFilter(array(0,1));
                        //} else {
                            $objEvents->setSharingFilter(array(0));
                        //}

                        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                        /**
                        * Build dates master array
                        * 3. create master array by period
                        */
                        $objEventList = new Warecorp_ICal_Event_List();
                        $objEventList->setTimeZone($_page->_user->getTimezone());
                        $objEventList->setPeriodDtstart($periodStartDate->toString('yyyy-MM-ddTHHmmss'));
                        $objEventList->setPeriodDtend($periodStartEnd->toString('yyyy-MM-ddTHHmmss'));
                        $dates = $objEventList->buildRecurList($arrEvents);
                        /**
                         * Build dates master array
                         * 4. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                         */
                        if ( !$_page->_user || null === $_page->_user->getId() ) {
                            if ( sizeof($dates) != 0 ) {
                                $arrDatesAnonymos = array();
                                foreach ( $dates as $dateKey => $times ) {
                                    foreach ( $times as $timeKey => $events ) {
                                        foreach ( $events as $idKey => $eventInfo ) {
                                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                        }
                                    }
                                }
                                ksort($arrDatesAnonymos);
                                $dates = $arrDatesAnonymos;
                            }
                        }

                        // EVENTS LIST FOR NEXT N DAYS ======================================================

                        /**
                         * create label for days that will be displayed
                         * count of days depends on user settings
                         * @var $daysList
                         * @var $objCurrDate3 - in end of will contain last date for events list block
                         */
                        $objCurrDate3 = clone $objDateNow;
                        //$objCurrDate3->setLocale('en_US');
                        $daysList = array();
                        for ($i = 0; $i < $item['Data']['events_display_number']; $i ++) {
                            $daysList[$i]['m'] = $objCurrDate3->toString('MM');
                            $daysList[$i]['d'] = $objCurrDate3->toString('dd');
                            $daysList[$i]['y'] = $objCurrDate3->toString('yyyy');
                            $daysList[$i]['check'] = $objCurrDate3->toString('yyyy-MM-dd');
                            $daysList[$i]['date'] = ($i == 0) ? 'Today' : $objCurrDate3->toString('EEEE MM/dd/yyyy');
                            $objCurrDate3->add(1, Zend_Date::DAY);
                        }
                        /**
                        * Build dates master array for event list block
                        * 1. create master array by period
                        */
                        $objEventList3 = clone $objEventList;
                        $objEventList3->setPeriodDtstart($objDateNow->toString('yyyy-MM-ddT000000'));
                        $objEventList3->setPeriodDtend($objCurrDate3->toString('yyyy-MM-ddTHHmmss'));
                        $dates2 = $objEventList3->buildRecurList($arrEvents);
                        /**
                         * Build dates master array
                         * 2. if user is anonymous user all dates should be converted to event original timezone from $currentTimezone
                         */
                        if ( !$_page->_user || null === $_page->_user->getId() ) {
                            if ( sizeof($dates2) != 0 ) {
                                $arrDatesAnonymos = array();
                                foreach ( $dates2 as $dateKey => $times ) {
                                    foreach ( $times as $timeKey => $events ) {
                                        foreach ( $events as $idKey => $eventInfo ) {
                                            $arrDatesAnonymos[$eventInfo['original']['key_date']][$eventInfo['original']['key_time']][$eventInfo['original']['key_id']] = $eventInfo;
                                        }
                                    }
                                }
                                ksort($arrDatesAnonymos);
                                $dates2 = $arrDatesAnonymos;
                            }
                        }
                        /**
                         * create event object whith fixed date & create tooltip for event
                         */
                        $tooltips = '';
                        foreach($dates2 as $_date => &$_hash){
                            foreach($_hash as $_time => &$_id_id){
                                foreach ($_id_id as &$_info){
                                    /**
                                     * create event object and correct its date start and end
                                     */
                                    $_info['_event'] = $objEventList3->createEvent($_info, $currentTimezone);
                                    /**
                                     * create tooltip for event
                                     * @var $_k - tooltip key
                                     */
                                    $_k = $_info['_event']->getId() . '_' . str_replace('-','_',$_date);
                                    $tooltip_text = '
                                        <div>' . $_info['_event']->displayDate('dd.myevents.tooltip', $_page->_user, $currentTimezone) . '</div>
                                        <div><strong><u>' . htmlspecialchars($_info['_event']->getTitle()) . '</u></strong></div>
                                        <div>Organizer : ' . htmlspecialchars($_info['_event']->getCreator()->getLogin()) . '</div>
                                        <div>' . ( ($_info['_event']->getOwnerType() == 'group') ? ( 'Group event : '.htmlspecialchars($_info['_event']->getOwner()->getName()) ) : '' ) . '</div>
                                        <div>' . ( ($_info['_event']->getPrivacy()) ? 'Private Event' : 'Public Event' ) . '</div>
                                    ';
                                    $tooltip_text = str_replace(array("\r", "\n"), "", $tooltip_text);
                                    $tooltips .= 'YAHOO.example.container.ttdocs_' . $item['ID'] . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $item['ID'] . '_' . $_k . '", {hidedelay:100, context:"ddGroupEvents_' . $item['ID'] . '_' . $_k . '", text:"' . $tooltip_text . ' ", width:"250px"});';
                                }
                            }
                        }
                        /**
                        * Assign template vars
                        */
                        $_page->Template->assign('arrDates', $dates);
                        $_page->Template->assign('objCurrDate', $objCurrDate);
                        $_page->Template->assign('objPrevDate', $objPrevDate);
                        $_page->Template->assign('objNextDate', $objNextDate);
                        $_page->Template->assign('daysList', $daysList);
                        $_page->Template->assign('calendar_data', $dates2);
                        $_page->Template->assign('tooltips', $tooltips);

                    }
                    //=======================================================================================
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $smarty_vars["Content"] = $_page->Template->getContents('content_objects/ddGroupEvents/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // + -------------------------------------------------------------------------------------
            // + -------------------------------------------------------------------------------------
            case 'ddGroupHeadline':
                $groupInfo = Warecorp_Group_Factory::loadById($entity->getId());
                $headline = $groupInfo->getHeadline();

                if (empty($headline)) {
                    $headline = '';
                }

                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                }
                if (!isset($item['Data']['Content'])) {
                    $item['Data']['Content'] = $headline;
                }

            case 'ddGroupDescription':
                $groupInfo = Warecorp_Group_Factory::loadById($entity->getId());
                $description = $groupInfo->getDescription();

                if (empty($description)) {
                    $description = '';
                }

                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                }
                if (!isset($item['Data']['Content'])) {
                    $item['Data']['Content'] = nl2br($description);
                }

            case 'ddContentBlock':
                $_page->Template->assign($item["Data"]);
                $smarty_vars["Content"] = $_page->Template->getContents('content_objects/ddContentBlock/preview_mode.tpl');
                break;

            //-------------------------------------------------------------------------------------
            case 'ddGroupImage':
                $groupInfo = Warecorp_Group_Factory::loadById($entity->getId());
                $avatar_id = (isset($item['Data']['avatarId']) ? intval($item['Data']['avatarId']) : 0);
                if (! isset($item['Data'])) {
                    $item['Data'] = array();
                    $item['Data']['avatarId'] = 0;
                    $item['Data']['headline'] = 'This is the default headline';
                }
                if (Warecorp_Photo_Standard::isPhotoExists($avatar_id)) {
                    $currentImage = Warecorp_Photo_Factory::loadById($avatar_id);
                    if (! Warecorp_Photo_AccessManager::canViewGallery($currentImage->getGallery(), $groupInfo, $_page->_user)) {
                        $currentImage = Warecorp_Photo_Factory::createByOwner($groupInfo);
                    }
                } else {
                    $currentImage = Warecorp_Photo_Factory::createByOwner($groupInfo);
                }
                $_page->Template->assign('cloneId', $item['ID']);
                $_page->Template->assign('currentImage', $currentImage);
                if (isset($item['Data'])) {
                    $_page->Template->assign($item['Data']);
                }
                $smarty_vars['Content'] = $_page->Template->getContents('content_objects/ddGroupImage/preview_mode_' . $content_item['template_type'] . '.tpl');
                break;
            // + -------------------------------------------------------------------------------------
            default:
                $smarty_vars["Content"] = $_page->Template->getContents('content_objects/ddUnknown/preview_mode.tpl');
                break;
            // -------------------------------------------------------------------------------------
        }
        $smarty_vars["item"] = $item;
        $_page->Template->assign($smarty_vars);
        $content_item['Style'] = empty($item['Style']) ? array() : $item['Style'];
        $content_item['content'] = $_page->Template->getContents('content_objects/common_view_mode.tpl');
        $content_array[] = $content_item;
        return $content_array;
    }
    /**
     * Imported from old engine
     *
     * @param unknown_type $Code
     * @param unknown_type $Var
     * @param unknown_type $arr
     */
    public static function phpArr2jsArr (&$Code, $Var, $arr)
    {
        $delim = "";
        //$delim = "\n";
        if (sizeof($arr) != 0) {
            foreach ($arr as $item_name => $item_value) {
                if (is_array($item_value)) {
                    $Code .= "" . $Var . "[\"" . $item_name . "\"] = new Array();" . $delim;
                    Warecorp_CO_Content::phpArr2jsArr($Code, "" . $Var . "[\"" . $item_name . "\"]", $item_value);
                } elseif (is_numeric($item_value)) {
                    $Code .= "" . $Var . "[\"" . $item_name . "\"] = " . $item_value . ";" . $delim;
                } elseif (is_string($item_value)) {
                    $Code .= "" . $Var . "[\"" . $item_name . "\"] = \"" . trim(str_replace("\r", "", str_replace("\n", "", str_replace('"', '\"', str_replace("\\", "\\\\", $item_value))))) . "\";" . $delim;
                }
            }
        }
    }
    /**
     * Imported from old engine
     *
     * @return unknown
     */
    public static function profilesAPI_register_code ()
    {
        return md5('code' . rand(100, 1000) * rand(100, 1000) * rand(100, 1000));
    }
}
