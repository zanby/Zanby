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
 * @package Warecorp_Theme
 * @copyright  Copyright (c) 2008
 * @author Alexander Komarovski
 */


class BaseWarecorp_Theme
{
    public $fillColor = '#FFFFFF';
    public $fillColorTransparent = 1;
    
    public $headlineTextColor = '#333333';      
    public $headlineTextFontFamily = 2;
    
    public $bodyTextColor = '#000000';      
    public $bodyTextFontFamily = 2;       
    
    public $commentColor = '#666666';
    public $commentFontFamily = 2;
    
    public $headerColor = '#FF6600';
    public $headerFontFamily = 2;
    
    public $linkColor = '#004488'; 
    
    public $outlineColor = '#CFCFCF'; 
    public $outlineStyle = 'solid';
    
    public $backgroundColor = '#FFFFFF'; 
    public $backgroundImage = '';
    public $backgroundTile = 0;
    public $backgroundUrl = '';
    
    public $applyChangesToSavedLayout = 1;
    
    public $fontFamilies = array(
        '1' => '\"Times New Roman\", Arial, sans-serif',    
        '2' => 'Helvetica, Arial, sans-serif',
        '3' => 'Tahoma, Arial, sans-serif',
        '4' => 'Verdana, Arial, sans-serif',
        '5' => '\"Lucida Console\", Arial',
    );
    
    /**
    * 
    */
 	public function __construct() {
		
        
       /**/
	}
    
    public function prepareFonts() {
        $this->headlineTextFontFamily = $this->fontFamilies[$this->headlineTextFontFamily];       
        $this->bodyTextFontFamily = $this->fontFamilies[$this->bodyTextFontFamily];       
        $this->commentFontFamily = $this->fontFamilies[$this->commentFontFamily];
        $this->headerFontFamily = $this->fontFamilies[$this->headerFontFamily];
    }
    
    
     /**
     * Load DDPages theme_css data from database
     *
     * @param integer $user_id 
     * @param integer $entity_type_id
     * 
     */
    public static function loadThemeFromDB ($entity)
    {
        if (! ($entity instanceof Warecorp_User) && ! ($entity instanceof Warecorp_Group_Base)) {
            return false;
        }
        $db = Zend_Registry::get("DB");
        $entity_id = $entity->getId();
        $entity_type_id = $entity->EntityTypeId;
        $select = $db->select()->from('zanby_dd__pages', 'theme_css')->where('entity_id = ?', $entity_id)->where('entity_type_id = ?', $entity_type_id);
        $themeString = $db->fetchOne($select);
        //if (! preg_match('/^[1-5]\s[1-5]\s[1-5]\s[1-5]\s[1-7][0-9]?\s[1-7][0-9]?\s[1-7][0-9]?\s[1-7][0-9]?\s[1-7][0-9]?\s[1-7][0-9]?\s[1-7][0-9]?$/', trim($themeString))) {
        //    $themeString = '1 2 2 2 59 1 33 61 41 3 3';
        //}
        //print   $themeString;
        if (!empty($themeString) && $themeString != "a:0:{}" && (substr($themeString,0,1)=='o' || substr($themeString,0,1)=='O') ){
            
            $themeObject = unserialize($themeString);

             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->fillColor)){
                  $themeObject->fillColor = '#FFFFFF';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->headlineTextColor)){
                  $themeObject->headlineTextColor = '#333333';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->bodyTextColor)){
                  $themeObject->bodyTextColor = '#000000';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->commentColor)){
                  $themeObject->commentColor = '#666666';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->headerColor)){
                  $themeObject->headerColor = '#FF6600';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->linkColor)){
                  $themeObject->linkColor = '#004488';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->outlineColor)){
                  $themeObject->outlineColor = '#CFCFCF';
             }
             if (!preg_match('/^#?([0-9a-fA-F]{3}){1,2}$/', $themeObject->backgroundColor)){
                  $themeObject->backgroundColor = '#FFFFFF';
             }            
            //print_r($themeObject) ; 
            return $themeObject;
        }
        //print_r($themeObject);
        
        return new Warecorp_Theme();
    }
    
     /**
     * Save css to DB
     *
     * @param unknown_type $user_id
     * @param unknown_type $entity_type_id
     * @param unknown_type $css
     */
    public function saveThemeToDB ($entity, $clear = false)
    {
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
