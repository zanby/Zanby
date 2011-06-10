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
 * @copyright  Copyright (c) 2006
 */
class BaseWarecorp_Common_Page
{
    private $_title;        // the title of current page
    private $_keywords;     // keywords
    private $_description;  // site description
    private $_geo_position; //
    private $_geo_placename;//
    private $_geo_region;   //
    private $_feed;         // feed
    private $_style;        // style
    public  $Locale;
    public  $Template;      // Template object
    public  $Config;
    public  $Access;
    public  $Xajax;
    public  $needXajaxInit;
    public  $hideLeftMenu;
    public  $hideBreadcrumb;
    public  $breadcrumb;
    private $ajaxAlert = false;     //  show ajax alert or not
    private $ajaxAlertProperty;
    private $ajaxWindow = false;
    private $ajaxWindowProperty;

    public function __construct()
    {
        require_once(ENGINE_DIR.'/Xajax/xajax.inc.php');
        require_once(ENGINE_DIR.'/Xajax/xajaxResponse.inc.php');
        require_once(ENGINE_DIR.'/Xajax/xajaxPopup.php');
        //$this->Template       = new Warecorp_View_Smarty();
        $this->hideLeftMenu   = false;
        $this->hideBreadcrumb = false;
        //$this->Template->layout = "main.tpl";
        $this->Config   = new Warecorp_Common_DBConfig();
        $this->Xajax    = new xajax();
        $this->needXajaxInit = false;
        $this->Locale   = Zend_Registry::get('Zend_Locale');
        //$this->Template->assign('CURRENT_PAGE', $_SERVER['REQUEST_URI']);
        //$this->Template->assign('Access', $this->Access);
        //$this->setKeywords("group, club, event, association, campaign, school, community, gathering, meeting, meet, mingle, get-together, reunion");
        //$this->setDescription(SITE_NAME_AS_STRING." is a community of groups that are active online and offline, enabled by the richest set of free community organization and lifestyle management tools on the web. The ".SITE_NAME_AS_STRING." suite of tools allow groups to scale naturally from small gatherings to large businesses with enterprise organizational needs.");
    }

    public function setTemplate( & $templateEngine )
    {
        $this->Template = $templateEngine;

        $this->Template->assign('CURRENT_PAGE', $_SERVER['REQUEST_URI']);
        $this->Template->assign('Access', $this->Access);
        $this->setKeywords("group, club, event, association, campaign, school, community, gathering, meeting, meet, mingle, get-together, reunion");
        $this->setDescription(SITE_NAME_AS_STRING." is a community of groups that are active online and offline, enabled by the richest set of free community organization and lifestyle management tools on the web. The ".SITE_NAME_AS_STRING." suite of tools allow groups to scale naturally from small gatherings to large businesses with enterprise organizational needs.");
    }

    public function hideLeftMenu(){
        $this->hideLeftMenu = true;
        $this->Template->setLayout("main_wide.tpl");
    }

    public function hideBreadcrumb(){
        $this->hideBreadcrumb = true;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
        $this->Template->assign('TITLE', $this->getTitle());
    }

    public function getTitle()
    {
        return SITE_NAME_AS_STRING . " :: " . $this->_title;
    }

    public function setKeywords($keywords)
    {
        $this->_keywords = $keywords;
        $this->Template->assign('KEYWORDS', $this->getKeywords());
    }

    public function getKeywords()
    {
        return $this->_keywords;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
        $this->Template->assign('DESCRIPTION', $this->getDescription());
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setGeoPosition($value)
    {
        $this->_geo_position = $value;
        $this->Template->assign('GEOPOSITION', $this->getGeoPosition());
    }

    public function getGeoPosition()
    {
        return $this->_geo_position;
    }
    public function setGeoPlacename($value)
    {
        $this->_geo_placename = $value;
        $this->Template->assign('GEOPLACENAME', $this->getGeoPlacename());
    }

    public function getGeoPlacename()
    {
        return $this->_geo_placename;
    }
    public function setGeoRegion($value)
    {
        $this->_geo_region = $value;
        $this->Template->assign('GEOREGION', $this->getGeoRegion());
    }

    public function getGeoRegion()
    {
        return $this->_geo_region;
    }

    public function setFeed($feed_link)
    {
        $this->_feed = $feed_link;
        $this->Template->assign('FEED', $this->getFeed());
    }

    public function getFeed()
    {
        return $this->_feed;
    }

    public function setStyle($style_name)
    {
        $this->_style = $style_name;
        $this->Template->assign('STYLE', $this->_style);
    }

    public function getStyle()
    {
        return $this->_style;
    }

    public function initAjax()
    {
        if ( $this->needXajaxInit == true ) $this->Xajax->processRequests();
        $this->Template->assign('XajaxJavascript', $this->Xajax->getJavascript("", JS_URL."/xajax.js"));
        $this->Xajax->registerUriFunction("closePopup", "/ajax/closePopup/");
    }

    /**
     * show ajax alert
     * @param string $content
     * @param $property
     * @return void
     * @author Artem Sukharev
     */
    public function showAjaxAlert($content, $property = null)
    {
    	$this->ajaxAlert = true;
    	if ( $property === null ) $property = new stdClass();
    	$property->content = $content;
    	$this->ajaxAlertProperty = $property;
    }
    
    public function showAjaxWindow($content, $property = null)
    {
    	$this->ajaxWindow = true;
    	if ( $property === null ) $property = new stdClass();
    	$property->content = $content;
    	$this->ajaxWindowProperty = $property;    
    }

    public function getAjaxWindowJsCode()
    {
    	if ( $this->ajaxWindow ) {
            $json = Zend_Json::encode($this->ajaxWindowProperty);
    	    $code = "YAHOO.util.Event.onDOMReady(MainApplication.showAjaxMessageFromPhp, null, ".$json.");";
    	    return $code;
    	}
    	return null;
    }
    
    /**
     * return js code for ajax alert
     * @author Artem Sukharev
     */
    public function getAjaxAlertJsCode()
    {
    	if ( $this->ajaxAlert ) {
            $json = Zend_Json::encode($this->ajaxAlertProperty);
    	    $code = "YAHOO.util.Event.onDOMReady(MainApplication.showAjaxAlertFromPhp, null, ".$json.");";
    	    return $code;
    	}
    	return null;
    }
    
    /**
     * return true if need show ajax alert
     * @author Artem Sukharev
     */
    public function issetAjaxAlert()
    {
    	return (boolean) $this->ajaxAlert;
    }
    
    /**
     * get ajaxAlertProperty
     * @author Artem Sukharev
     */
    public function getAjaxAlertProperty()
    {
    	return $this->ajaxAlertProperty;
    }
    
    public function getAjaxWindowProperty()
    {
    	return $this->ajaxWindowProperty;
    }    
}
