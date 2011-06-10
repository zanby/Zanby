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
 * @package Warecorp_CO_Theme_Item
 * @copyright  Copyright (c) 2009
 * @author Alexander Komarovski
 */


class BaseWarecorp_CO_Theme_Item {
    public $fillColor;
    public $fillColorTransparent;

    public $headlineTextColor;
    public $headlineTextFontFamily;

    public $bodyTextColor;
    public $bodyTextFontFamily;

    public $commentColor;
    public $commentFontFamily;

    public $headerColor;
    public $headerFontFamily;

    public $linkColor;

    public $outlineColor;
    public $outlineStyle;

    public $backgroundColor;
    public $backgroundImage;
    public $backgroundTile;
    public $backgroundUrl;

    public $applyChangesToSavedLayout ;

    public $fontFamilies = array(
    '1' => '\"Times New Roman\", Arial, sans-serif',
    '2' => 'Helvetica, Arial, sans-serif',
    '3' => 'Tahoma, Arial, sans-serif',
    '4' => 'Verdana, Arial, sans-serif',
    '5' => '\"Lucida Console\", Arial',
    );

    private $defaults = null;

    /**
     *
     */
    public function __construct() {

        $cfgCOTheme = Warecorp_Config_Loader::getInstance()->getAppConfig('COLT/cfg.theme.xml')->{'default_theme'};

        $this->defaults = $cfgCOTheme;


        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->fillColor)) {
            $this->fillColor = $this->defaults->fill_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->headlineTextColor)) {
            $this->headlineTextColor = $this->defaults->headline_text_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->bodyTextColor)) {
            $this->bodyTextColor = $this->defaults->body_texy_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->commentColor)) {
            $this->commentColor = $this->defaults->comment_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->headerColor)) {
            $this->headerColor = $this->defaults->header_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->linkColor)) {
            $this->linkColor = $this->defaults->link_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->outlineColor)) {
            $this->outlineColor = $this->defaults->outline_color;
        }
        if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $this->backgroundColor)) {
            $this->backgroundColor = $this->defaults->background_color;
        }


        if (!isset($this->fillColorTransparent)) {
            $this->fillColorTransparent = $this->defaults->fill_color_transparent;
        }
        if (!$this->isValidFontFamilyId($this->headlineTextFontFamily)) {
            $this->headlineTextFontFamily = $this->defaults->headline_text_font_family;
        }
        if (!$this->isValidFontFamilyId($this->bodyTextFontFamily)) {
            $this->bodyTextFontFamily = $this->defaults->body_text_font_family;
        }
        if (!$this->isValidFontFamilyId($this->commentFontFamily)) {
            $this->commentFontFamily = $this->defaults->comment_font_family;
        }
        if (!$this->isValidFontFamilyId($this->headerFontFamily)) {
            $this->headerFontFamily = $this->defaults->header_font_family;
        }
        if (!isset($this->outlineStyle)) {
            $this->outlineStyle = $this->defaults->outline_style;
        }
        if (!isset($this->backgroundImage)) {
            $this->backgroundImage = $this->defaults->background_image;
        }
        if (!isset($this->backgroundTile)) {
            $this->backgroundTile = $this->defaults->background_tile;
        }
        if (!isset($this->backgroundUrl)) {
            $this->backgroundUrl = $this->defaults->background_url;
        }
        if (!isset($this->applyChangesToSavedLayout)) {
            $this->applyChangesToSavedLayout = $this->defaults->apply_changes_to_saved_layout;
        }

    }

    public function prepareFonts() {
        $this->headlineTextFontFamily = $this->fontFamilies[$this->headlineTextFontFamily];
        $this->bodyTextFontFamily = $this->fontFamilies[$this->bodyTextFontFamily];
        $this->commentFontFamily = $this->fontFamilies[$this->commentFontFamily];
        $this->headerFontFamily = $this->fontFamilies[$this->headerFontFamily];
    }

    public function isValidFontFamilyId($value = null) {
        if (key_exists($value, $this->fontFamilies)) {
            return true;
        }
        return false;
    }

    public static function loadThemeFromDB ($entity) {//return new Warecorp_CO_Theme_Item();
        if (! ($entity instanceof Warecorp_User) && ! ($entity instanceof Warecorp_Group_Base)) {
            return false;
        }
        $db = Zend_Registry::get("DB");
        $entity_id = $entity->getId();
        $entity_type_id = $entity->EntityTypeId;
        $select = $db->select()->from('zanby_dd__pages', 'theme_css')->where('entity_id = ?', $entity_id)->where('entity_type_id = ?', $entity_type_id);
        $themeString = $db->fetchOne($select);
        
        if (!empty($themeString) && $themeString != "a:0:{}" && (substr($themeString,0,1)=='o' || substr($themeString,0,1)=='O') ) {

            $themeObject = unserialize($themeString);
            $themeObject->__construct();

            return $themeObject;
        }
        return new Warecorp_CO_Theme_Item();
    }

    public static function getSWFUploadTheme() {
        $cfgCOTheme = Warecorp_Config_Loader::getInstance()->getAppConfig('COLT/cfg.theme.xml')->{'swf_upload'};
        return $cfgCOTheme->toArray();
    }

    public function saveThemeToDB ($entity, $clear = false) {
        $themeString = serialize($this);
        $entity_id = $entity->getId();
        $entity_type_id = $entity->EntityTypeId;
        $db = Zend_Registry::get("DB");
        $select = $db->select()->from('zanby_dd__pages', 'id')->where('entity_id = ? AND entity_type_id=' . $entity_type_id, $entity_id);
        $result = $db->fetchCol($select);
        if (empty($result)) {
            $db->insert('zanby_dd__pages', array(
                'data' => '' ,
                'theme_css' => $themeString ,
                'entity_type_id' => $entity_type_id ,
                'entity_id' => $entity_id));
        } else {
            $db->update('zanby_dd__pages', array(
                'theme_css' => $themeString), $db->quoteInto('entity_id = ? AND entity_type_id=' . $entity_type_id, $entity_id));
            if ($clear) Warecorp_DDPages::resetBlocksStyles($entity);
        }
    }
}
